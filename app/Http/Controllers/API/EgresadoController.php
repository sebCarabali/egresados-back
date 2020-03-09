<?php

namespace App\Http\Controllers\API;

use App\Cargo;
use App\Ciudad;
//use App\Http\Requests\CompletarInformacionRequest;
use App\Egresado;
use App\Experiencia;
use App\Grado;
use App\Http\Controllers\Controller;
use App\Http\Requests\GuardarInformacionBasicaRequest;
use App\Http\Resources\EgresadoAdminResource;
use App\Imports\EgresadosImport;
use App\Localizacion;
use App\NivelEstudio;
use App\Programa;
use App\Referido;
use App\Repository\EgresadoRepositoryInterface;
use App\Role as Rol;
use App\User;
use Excel;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Validator;

class EgresadoController extends Controller
{
    private const ACTIVO_NO_LOGUEADO = 'ACTIVO NO LOGUADO';
    private const PENDIENTE = 'PENDIENTE';
    private const ACTIVO_LOGUEADO = 'ACTIVO LOGUEADO';
    private $repository;

    public function __construct(EgresadoRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Store egresados basic info.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function guardarInformacionBasica(GuardarInformacionBasicaRequest $request)
    {
        // verificar si ya hay datos registrados.
        $hasDataRegistered = $this->_tieneDatosRegistrados($request->get('identificacion'));
        if ($hasDataRegistered) {
            // cambiar estado egresado y completar información básica
            $egresado = Egresado::where('identificacion', $request->get('identificacion'))->first();

            if (strcmp(mb_strtoupper($egresado->estado), self::ACTIVO_NO_LOGUEADO)) {
                $egresado = $this->_completarInformacionBasica($egresado, $request);
            }
        } else {
            // crear un nuevo registro de egresado con el estado PENDIENTE
            $egresado = $this->_crearInformacionBasica($request);
        }

        return response()->json($egresado, 201);
    }

    //Metodo que permite almacenar una lista de experiencias de un egresado
    public function guardarInfoExperiencia(array $experiencias, $idEgresado)
    {
        //Informacion experiencias pasada

        foreach ($experiencias as $exp) {
            //Informacion experiencias actual
            $experiencia = new Experiencia();
            $experiencia->nombre_empresa = $exp['nombre_empresa'];
            $experiencia->ciudad()->associate(Ciudad::where('id_aut_ciudad', $exp['id_ciudad'])->first());

            $experiencia->dir_empresa = $exp['dir_empresa'];
            $experiencia->tel_trabajo = $exp['tel_trabajo'];
            $experiencia->rango_salario = $exp['rango_salario'];
            $experiencia->fecha_inicio = $exp['fecha_inicio'];
            //$experiencia->fecha_fin = $exp['fecha_fin'];
            $experiencia->tipo_contrato = $exp['tipo_contrato'];
            $experiencia->sector = $exp['sector'];
            $experiencia->trabajo_en_su_area = $exp['trabajo_en_su_area'];
            $experiencia->categoria = $exp['categoria'];

            $cargo = new Cargo();
            $cargo->nombre = $exp['cargo_nombre'];
            $cargo->estado = false;
            $cargo->save();

            try {
                $experiencia->cargos()->associate($cargo);
                $experiencia->egresados()->associate(Egresado::where('id_aut_egresado', $idEgresado)->first());
                $experiencia->save();
            } catch (Exception $e) {
                return response()->json(['error' => $e], 400);
            }

            return response()->json($experiencias, 202);
        }
    }

    public function getEgresadoEmail($email)
    {
        try {
            $idEgresado = DB::table('egresados')
                ->where('correo', $email)
                ->select('id_aut_egresado')->first();

            return response()->json($idEgresado, 200);
        } catch (Exception $e) {
            return response()->json(['error' => $e], 400);
        }
    }

    //Carga la informacion de un egresado para mostrar en Ver Perfil
    public function verPerfil($email)
    {
        $idEgresado = DB::table('egresados')
            ->where('correo', $email)
            ->select('id_aut_egresado')->first();

        $egresado = Egresado::find($idEgresado->id_aut_egresado);

        return $this->success(new EgresadoAdminResource($egresado));
    }

    //Metodo que permite actualzar la información del egresado por parte de un egresado
    public function actualizaEgresado(Request $request, $idEgresado)
    {
        /* $egresado = Egresado::find($idEgresado);
         Información personal→
         Experiencia laboral
         Referencia personal
         Grado*/
    }

    //Metodo que permite actualizar informacion de un egresado por parte del administrador
    public function update(Request $request, $idEgresado)
    {
        $egresado = Egresado::find($idEgresado);

        //Obteniendo discapacidades del formulario
        $discapacidades = $request->get('discapacidades');

        $nuevaDiscapacidad = new Discapacidad();
        $nuevaDiscapacidad = $request->get('otra_discapacidad');

        $egresado->estado_civil = $request->get('estado_civil');
        $egresado->celular = $request->get('celular');

        // get lugar_residencia data
        $localizacion = new Localizacion();
        $localizacion->codigo_postal = $request->get('codigo_postal');
        $localizacion->direccion = $request->get('direccion');
        $localizacion->barrio = $request->get('barrio');
        $localizacion->ciudad()->associate(Ciudad::where('id_aut_ciudad', $request->get('id_lugar_residencia'))->first());

        //get nivel de estudio
        $egresado->niveles_estudio()->associate(NivelEstudio::where('id_aut_estudio', $idEgresado)->firstOrFail());
        $egresado->correo = $request->get('correo');
        $egresado->correo_alternativo = $request->get('correo_alternativo');
        $egresado->telefono_fijo = $request->get('telefono_fijo');
        $egresado->num_hijos = $request->get('num_hijos');

        //Obtener lista de referidos para modificación
        $referidosUpdate = $request->get('referidos');

        //Obtener lista de experiencias laboral para modificación

        $experienciasUpdate = $request->get('experiencias');
        _updateInformation($egresado, $discapacidades, $nuevaDiscapacidad, $localizacion, $referidosUpdate, $experienciasUpdate);
    }

    //Recupera numero de hijos y estado civil de un egresado
    public function getStateAndChildren($idEgresado)
    {
        return DB::table('egresados')->where('id_aut_egresdo', $idEgresado)->value('num_hijos', 'num_hijos');
    }

    //Recupera niveles de estudio

    public function getAllLevelStudy()
    {
        $levels = BD::table('niveles_estudio')->get();

        return response()->json($levels, 205);
    }

    public function guardarGradoSimultaneo($gradoSimultaneo, $idEgresado)
    {
        try {
            $gradoPendiente = Grado::where('id_egresado', '=', $idEgresado)
                ->where('estado', '=', 'PENDIENTE')
                ->select('fecha_graduacion', 'anio_graduacion')->first();

            $grado = [
                'id_programa' => $gradoSimultaneo['id_aut_programa'],
                'mension_honor' => $gradoSimultaneo['mencion_honor'],
                'titulo_especial' => $gradoSimultaneo['titulo_especial'],
                'fecha_grado' => date('m/d/Y', strtotime($gradoPendiente['fecha_grado'])),
                'anio_graduacion' => $gradoPendiente['anio_graduacion'],
                'estado' => 'PENDIENTE',
            ];

            $egresado = Egresado::find($idEgresado);

            $egresado->programas()->attach($gradoSimultaneo['id_aut_programa'], [
                'mencion_honor' => array_key_exists('mencion_honor', $grado) ? $grado['mencion_honor'] : 'NO',
                'titulo_especial' => array_key_exists('titulo_especial', $grado) ? $grado['titulo_especial'] : '',
                'fecha_graduacion' => $grado['fecha_grado'],
                'anio_graduacion' => $grado['anio_graduacion'],
                'estado' => $grado['estado'],
            ]);

            $idGradoRegistrado = DB::table('egresados')
                ->join('grados', 'egresados.id_aut_egresado', '=', 'grados.id_egresado')
                ->join('programas', 'grados.id_programa', '=', 'programas.id_aut_programa')
                ->where('egresados.id_aut_egresado', $idEgresado)
                ->where('grados.estado', 'PENDIENTE')
                ->where('grados.id_programa', '=', $gradoSimultaneo['id_aut_programa'])
                ->select('grados.id_aut_grado')->max('grados.id_aut_grado');

            $grado = Grado::find($idGradoRegistrado);

            $this->guardarComentario($gradoSimultaneo['comentarios'], $grado);
        } catch (Exception $e) {
            return response()->json(['error al momento de guardar grados simultaneo'], 400);
        }
    }

    public function guardarComentario($comentarios, $grado)
    {
        try {
            foreach ($comentarios as $comentario) {
                $grado->tipoObservacion()->attach($comentario['id_aut_comentario'], ['respuesta' => $comentario['respuesta']]);
            }
        } catch (Exception $e) {
            return response()->json(['Error al guardar comentarios' => $e], 400);
        }
    }

    //Metodo para completar la informacion de un egresdao
    //Se agrega: Regeridos
    //           Experiencias
    //           Datos personales especificos
    //CompletarInformacionRequest

    public function getvalidaCompletar($idEgresado)
    {
        //estado completar
        //cant hijo
        //grado del que se graduo

        $datosCompletar = Egresado::join('grados', 'egresados.id_aut_egresado', 'grados.id_egresado')
            ->where('egresados.id_aut_egresado', $idEgresado)
            ->where('grados.estado', 'PENDIENTE')
            ->select('egresados.estado_completar', 'egresados.num_hijos', 'grados.id_programa')->first();

        return response()->json($datosCompletar, 200);
    }

    public function fullInfo($idEgresado, Request $request)
    {
        //return response()->json($idEgresado,400);
        return DB::transaction(function () use ($request, $idEgresado) {
            try {
                $egresado = Egresado::find($idEgresado);

                $egresado->ha_trabajado = $request->get('ha_trabajado');
                $egresado->trabaja_actualmente = $request->get('trabaja_actualmente');
                $egresado->estado_completar = true;
                $egresado->save();

                // Obteniendo informacion de los referidos de un egresado
                $referidos = $request->get('referidos');

                if ($referidos && count($referidos) > 0) {
                    //return response()->json($referidos,400);
                    $this->guardarReferido($referidos, $idEgresado);
                }

                // Obteniendo informacion de las experiencias de un egresado
                $expActual = $request->get('expActual');

                //Se obtienen los comentarios del  grado ya registrado en el preregistro
                $comentariosGradoRegistrado = $request->get('comentarios');
                //return response()->json($request->get('comentarios'),400);
                /*
                         * GRADO SIMULTANEO
                         * Este caso se presenta cuando un graduando se
                         * gradua de DOS carreras diferentes al tiempo
                         */

                // Se obtine la informacion de un grado simultaneo
                $gradoSimultaneo = $request->get('gradoAdicional');
                //return response()->json($gradoSimultaneo, 400);

                if ($expActual && count($expActual) > 0) {
                    $this->guardarInfoExperiencia($expActual, $idEgresado);
                }

                if ($comentariosGradoRegistrado && count($comentariosGradoRegistrado)) {
                    $grado = Grado::where('id_egresado', '=', $idEgresado)
                        ->where('grados.estado', 'PENDIENTE')->first();

                    //return response()->json($idEgresado,400);
                    $this->guardarComentario($comentariosGradoRegistrado, $grado);
                }

                if ($request->get('otroGrado')) {
                    $val = $this->guardarGradoSimultaneo($gradoSimultaneo, $idEgresado);
                    //return response()->json($idGradoRegistrado, 200);
                            /* INSERT INTO ofertas.grados
                              ( id_aut_grado, tipo_grado, mencion_honor, titulo_especial, anio_graduacion, fecha_graduacion, id_programa, id_egresado, estado, observacion)
                              VALUES ( 2, '', '', '', '2019', '09/09/2019', 2, 10, 'PENDIENTE', '' );
                            */
                }
            } catch (Exception $e) {
                return response()->json($e->all(), 400);
            }

            return response()->json('TERMINANDO COPLENTAR REGISTRO', 200);
        });
    }

    /**
     * Store a newly created resource in storage.
     * INSERT INTO ofertas.grados.
     * VALUES ( 2, '', '', '', '2019', '09/09/2019', 2, 10, 'PENDIENTE', '' );.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $correo = $usuario->email;
        $data = [
            'codigo' => $usuario->codigo_verificacion,
        ];
        Mail::send('mail.confirmation', $data, function ($message) use ($correo, $data) {
            $message->from('sebastiancc@unicauca.edu.co', 'Egresados');
            $message->to($correo)->subject('Nuevo usuario');
        });
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int                      $ids
     *                                      Contraste la información de los egresados registrados en la base de datos,
     *                                      con los de el excel proporcionado por la secretaria general
     * @param \Illuminate\Http\Request $req
     *
     * @return \Illuminate\Http\Response
     */
    public function validateExcel(Request $request)
    {
        $file = $request->file('fileInput');
        $input = [
            'file' => $file,
            'extension' => strtolower($file->getClientOriginalExtension()),
        ];
        $rules = [
            'file' => 'required',
            'extension' => 'required|in:xlsx,xsl,csv,ods',
        ];
        $messages = [
            'file.required' => 'Es necesario pasar un archivo.',
            'extension.required' => 'Debe ser un archivo con extension.',
            'extension.in' => 'Debe se un arvhivo excel válido.',
        ];
        $validator = Validator::make($input, $rules, $messages);

        /* if ($validator->fails()) {
          return response()->json($validator->errors(), 422);
          } */
        $egresadosEnExcel = $this->getCollection($file);
        $aceptados = $this->_procesarExcel($egresadosEnExcel);

        return response()->json($aceptados, 200, [], JSON_UNESCAPED_UNICODE);
    }

    private function _crearInformacionBasica(Request $request)
    {
        // get egresados data
        $egresado = new Egresado();
        $egresado->num_hijos = $request->get('num_hijos');
        $egresado->correo = $request->get('correo');
        $egresado->correo_alternativo = $request->get('correo_alternativo');
        $egresado->grupo_etnico = $request->get('grupo_etnico');
        $egresado->fecha_nacimiento = date('m/d/Y', strtotime($request->get('fecha_nacimiento')));

        $egresado->lugarExpedicion()->associate(Ciudad::where('id_aut_ciudad', $request->get('id_lugar_expedicion'))->first());
        $egresado->ciudadNacimiento()->associate(Ciudad::where('id_aut_ciudad', $request->get('id_lugar_nacimiento'))->first());
        $egresado->nivelEducativo()->associate(NivelEstudio::where('id_aut_estudio', 1)->first());
        //$egresado->discapacidad = $request->get('discapacidad');
        $egresado->telefono_fijo = $request->get('telefono_fijo');
        $egresado->celular = $request->get('celular');
        $egresado->estado_civil = $request->get('estado_civil');
        $egresado->genero = $request->get('genero');
        $egresado->identificacion = $request->get('identificacion');
        $egresado->nombres = $request->get('nombres');
        $egresado->apellidos = $request->get('apellidos');
        $egresado->genero = $request->get('genero');
        $egresado->num_hijos = $request->get('num_hijos');
        $egresado->estado = 'PENDIENTE';
        $egresado->estado_completar = false;
        $egresado->ha_trabajado = false;
        $egresado->trabaja_actualmente = false;

        //
        // get lugar_residencia data
        $localizacion = new Localizacion();
        $localizacion->codigo_postal = $request->get('codigo_postal');
        $localizacion->direccion = $request->get('direccion');
        $localizacion->ciudad()->associate(Ciudad::where('id_aut_ciudad', $request->get('id_lugar_residencia'))->first());

        // get grado info
        $grado = [
            'id_programa' => $request->get('id_programa'),
            'mension_honor' => $request->get('mension_honor'),
            'titulo_especial' => $request->get('titulo_especial'),
            'fecha_grado' => date('m/d/Y', strtotime($request->get('fecha_grado'))),
            'anio_graduacion' => $request->get('anio_graduacion'),
        ];

        $discapacidad = $request->get('discapacidad');

        return $this->_guardarInformacionBasica($egresado, $localizacion, $grado, $discapacidad);
    }

    private function _completarInformacionBasica(Egresado $egresado, Request $request)
    {
        $egresado->num_hijos = $request->get('num_hijos');
        $egresado->correo = $request->get('correo');
        $egresado->correo_alternativo = $request->get('correo_alternativo');
        $egresado->grupo_etnico = $request->get('grupo_etnico');

        $egresado->fecha_nacimiento = date('m/d/Y', strtotime($request->get('fecha_nacimiento')));
        $egresado->lugarExpedicion()->associate(Ciudad::where('id_aut_ciudad', $request->get('id_lugar_expedicion'))->first());
        $egresado->ciudadNacimiento()->associate(Ciudad::where('id_aut_ciudad', $request->get('id_lugar_nacimiento'))->first());
        //$egresado->nivelEducativo()->associate(NivelEstudio::find($request->get('id_nivel_educativo'))->first());
        //$egresado->discapacidad = $request->get('discapacidad');
        $egresado->telefono_fijo = $request->get('telefono_fijo');
        $egresado->celular = $request->get('celular');
        $egresado->estado_civil = $request->get('estado_civil');
        $egresado->genero = $request->get('genero');

        $egresado->estado = self::ACTIVO_LOGUEADO;

        // TODO: grados egresado
        $grado = $this->repository->getGradoByPrograma($egresado->id_aut_egresado, $request->get('id_programa'));
        if ($grado) { // ya se ha registrado el grado del egresado
            // Actualizar info grado
            $grado->mencion_honor = '' == $request->get('mencion_honor') ? 'No' : $request->get('mencion_honor');
            $grado->titulo_especial = $request->get('titulo_especial');
            $grado->fecha_graduacion = date('m/d/Y', strtotime($request->get('fecha_grado')));
            $grado->anio_graduacion = $request->get('anio_graduacion');
            $grado->save();
            $grado = [];
        } else { // crear nuevo grado
            $grado = [
                'id_programa' => $request->get('id_programa'),
                'mencion_honor' => $request->get('mension_honor'),
                'titulo_especial' => $request->get('titulo_especial'),
                'fecha_grado' => date('m/d/Y', strtotime($request->get('fecha_grado'))),
                'anio_graduacion' => $request->get('anio_graduacion'),
            ];
        }

        // get lugar_residencia data
        $localizacion = new Localizacion();
        $localizacion->codigo_postal = $request->get('codigo_postal');
        $localizacion->direccion = $request->get('direccion');
        $localizacion->ciudad()->associate(Ciudad::where('id_aut_ciudad', $request->get('id_lugar_residencia'))->first());
        // get grado info

        $discapacidad = $request->get('discapacidad');

        return $this->_guardarInformacionBasica($egresado, $localizacion, $grado, $discapacidad);
    }

    private function _crearUsuario($egresado)
    {
        $user = new User([
            'email' => $egresado->correo,
            'codigo_verificacion' => $this->_generarCodigoConfirmacion(),
        ]);
        $user->rol()->associate(Rol::where(DB::raw('upper(nombre)'), 'EGRESADO')->first());
        $user->save();

        return $user;
    }

    /**
     * Genera un código de confirmación único(basado en UUID v4) para el usuario.
     *
     * @param mixed $length
     */
    /* private function _generarCodigoConfirmacion() {
      return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
      // 32 bits for "time_low"
      mt_rand(0, 0xffff), mt_rand(0, 0xffff),
      // 16 bits for "time_mid"
      mt_rand(0, 0xffff),
      // 16 bits for "time_hi_and_version",
      // four most significant bits holds version number 4
      mt_rand(0, 0x0fff) | 0x4000,
      // 16 bits, 8 bits for "clk_seq_hi_res",
      // 8 bits for "clk_seq_low",
      // two most significant bits holds zero and one for variant DCE1.1
      mt_rand(0, 0x3fff) | 0x8000,
      // 48 bits for "node"
      mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
      );
      } */

    private function _generarCodigoConfirmacion($length = 16)
    {
        $alfabeto = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        $yaExiste = false;
        do {
            $codigo = substr(str_shuffle($alfabeto), 0, $length);
            $yaExiste = $this->_codigoYaExisteEnBd($codigo);
        } while ($yaExiste);

        return $codigo;
    }

    private function _codigoYaExisteEnBd($codigo)
    {
        return User::where('codigo_verificacion', $codigo)->exists();
    }

    private function _enviarMensajeActivacion(User $usuario)
    {
        $correo = $usuario->email;
        Mail::send('mail.confirmation', ['codigo' => $usuario->codigo_verificacion], function ($message) use ($correo) {
            $message->from('sebastiancc@unicauca.edu.co', 'Egresados');
            $message->to($correo)->subject('Nuevo usuario');
        });
    }

    private function _guardarInformacionBasica(Egresado $egresado, Localizacion $localizacion, array $grado, array $discapacidad)
    {
        // save all data and response egresados object in json format
        return DB::transaction(function () use ($egresado, $localizacion, $grado, $discapacidad) {
            $localizacion->save();
            $usuario = $this->_crearUsuario($egresado);
            $egresado->user()->associate($usuario);
            $egresado->lugarResidencia()->associate($localizacion);
            //return response()->json($egresado,200);
            $egresado->save();
            if ($grado) {
                $egresado->programas()->attach($grado['id_programa'], [
                    //'tipo' => $grado->tipo,
                    'mencion_honor' => array_key_exists('mension_honor', $grado) ? $grado['mension_honor'] : 'No',
                    'titulo_especial' => array_key_exists('titulo_especial', $grado) ? $grado['titulo_especial'] : '',
                    //'comentarios' => array_key_exists('comentarios', $grado) ? $grado['comentarios'] : '',
                    'fecha_graduacion' => $grado['fecha_grado'],
                    //'docente_influencia' => array_key_exists('docente_influencia', $grado) ? $grado['docente_influencia'] : '',
                    'anio_graduacion' => $grado['anio_graduacion'],
                    'estado' => self::PENDIENTE,
                ]);
            }
            foreach ($discapacidad as $d) {
                $egresado->discapacidades()->attach($d);
            }
            $this->_enviarMensajeActivacion($usuario);

            return $egresado;
        });
    }

    //Metodo que permite almacenar una lista de Referidos de un egresado
    private function guardarReferido(array $referidos, $idEgresado)
    {
        foreach ($referidos as $ref) {
            try {
                if ($ref['es_egresado']) {
                    $referido = new Referido();
                    $referido->nombres = $ref['nombres'];
                    $referido->parentesco = $ref['parentesco'];
                    $referido->es_egresado = $ref['es_egresado'];
                    $referido->niveles_estudio()->associate(NivelEstudio::where('id_aut_estudio', $ref['id_nivel_educativo'])->firstOrFail());
                    $referido->programa()->associate(Programa::where('id_aut_programa', $ref['id_aut_programa'])->first());
                    $referido->telefono_movil = $ref['telefono_movil'];
                    $referido->correo = $ref['correo'];
                } else {
                    $referido = new Referido();
                    $referido->nombres = $ref['nombres'];
                    $referido->parentesco = $ref['parentesco'];
                    $referido->es_egresado = $ref['es_egresado'];
                    $referido->telefono_movil = $ref['telefono_movil'];
                    $referido->correo = $ref['correo'];
                }
                $referido->save();

                $referido->egresados()->attach($idEgresado);
            } catch (Exeption $e) {
                return respose()->json(['error' => 'Error agregando referido'], 400);
            }
        }
    }

    //Permite asocias las incapacidades seleccionadas al egresado
    private function _updateInformation(Egresado $egresado, array $discapacidades, $nuevaDiscapacidad, $localizacion, $referidosUpdate, $experienciasUpdate)
    {
        return DB::transaction(function () use ($egresado, $discapacidades, $nuevaDiscapacidad, $localizacion, $referidosUpdate, $experienciasUpdate) {
            //Asociando listado de discapacidades de egresado
            foreach ($discapacidades as $discapacidad) {
                $egresado->discapacidad()->attach($discapacida['id_discapacidad']);
            }

            //Agregando nuevas discapacidades de egresado
            $nuevaDiscapacidad->save();
            $nuevaDiscapacidad->egresado()->attach($egresado->get('id_aut_egresado'));

            //Guardar nueva localización de egresado
            $localizacion->save();
            $egresado->lugarResidencia()->associate($localizacion);

            //Desvincular listado de referidos anteriores del egresado.
            $referidosAnteriores = Referido::first();
            $referidosAnteriores->egresados();
            foreach ($referidosAnteriores as $ref) {
                $ref->egresado()->deattach($egresado('id_aut_egresado'));
            }

            //Asociando listado  de referidos
            foreach ($referidosUpdate as $ref) {
                $referido = new Referido();
                $referido->nombres = $ref['nombres'];
                $referido->parentesco = $ref['parentesco'];
                $referido->es_egresado = $ref['es_egresado'];
                $referido->niveles_estudio()->associate(NivelEstudio::where('id_aut_estudio', $ref['id_nivel_educativo'])->firstOrFail());
                $referido->programa()->associate(Programa::where('id_aut_programa', $ref['id_aut_programa'])->firstOrFail());
                $referido->telefono_movil = $ref['telefono_movil'];
                $referido->correo = $ref['correo'];
                $referido->save();
                $egresado->referido()->attach($referido);
            }

            //Asociando nuevas experiencias para un egresado
            $this->guardarInfoExperiencia($experiencias, $idEgresado);
        });
    }

    private function _tieneDatosRegistrados($identificacion)
    {
        return Egresado::where('identificacion', $identificacion)->exists();
    }

    private function getCollection($file)
    {
        $import = new EgresadosImport();
        Excel::import($import, $file);

        return $import->egresados;
    }

    private function _procesarExcel($egresados)
    {
        $resultado = [];
        $fisrtRow = true;
        // para todos los egresados de excel.
        foreach ($egresados as $e) {
            if (!$fisrtRow) {
                // Si el egresado ya ha realizado el pre-registro, cambiar estado de EN_ESPERA a ACTIVO LOGUEADO.
                if (!empty($e['identificacion'])) {
                    $egresadoYaRegistrado = Egresado::where('identificacion', $e['identificacion'])
                        ->where('estado', '=', 'EN_ESPERA')->first();
                    if ($egresadoYaRegistrado) {
                        // cambiar estado.
                        $egresadoYaRegistrado->estado = 'ACTIVO_LOGUEADO';
                        $egresadoYaRegistrado->save();
                        //$e['fecha_grado'] = date_format($e['fecha_grado'], 'm/d/Y');
                        array_push($resultado, $e);
                    }
                }
            }
            $fisrtRow = false;
        }

        return $resultado;
    }
}
