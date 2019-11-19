<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Requests\GuardarInformacionBasicaRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Egresado;
use App\Ciudad;
use App\User;
use App\Role as Rol;
use App\Localizacion;
use App\Experiencia;
use App\Programa;
use App\NivelEstudio;
use App\Referido;
use App\Cargo;
use Validator;
use Excel;
use App\Imports\EgresadosImport;

class EgresadoController extends Controller
{

    /**
     * Store egresados basic info.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function guardarInformacionBasica(GuardarInformacionBasicaRequest $request)
    {
        // verificar si ya hay datos registrados.
        $hasDataRegistered = $this->_tieneDatosRegistrados($request->get('identificacion'));
        if ($hasDataRegistered) {
            // cambiar estado egresado y completar información básica
            $egresado = Egresado::where('identificacion', $request->get('identificacion'))->first();
            $egresado = $this->_completarInformacionBasica($egresado, $request);
        } else {
            // crear un nuevo registro de egresado con el estado EGRESADO_NO_LOGUEADO
            $egresado = $this->_crearInformacionBasica($request);
        }
        return response()->json($egresado, 201);
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
        // TODO: Verificar estados.
        $egresado->estado = 'EN_ESPERA';
        $egresado->ha_trabajado = false;
        $egresado->trabaja_actualmente = false;


        // 
        // get lugar_residencia data
        $localizacion = new Localizacion();
        $localizacion->codigo_postal = $request->get('codigo_postal');
        $localizacion->direccion = $request->get('direccion');
        $localizacion->ciudad()->associate(Ciudad::where('id_aut_ciudad', $request->get('id_lugar_residencia'))->first());

        // get grado info
        $grado = array(
            'id_programa' => $request->get('id_programa'),
            'mension_honor' => $request->get('mension_honor'),
            'titulo_especial' => $request->get('titulo_especial'),
            'fecha_grado' => date('m/d/Y', strtotime($request->get('fecha_grado'))),
            'anio_graduacion' => $request->get('anio_graduacion')
        );

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
        $egresado->nivelEducativo()->associate(NivelEstudio::find($request->get('id_nivel_educativo'))->first());
        //$egresado->discapacidad = $request->get('discapacidad');
        $egresado->telefono_fijo = $request->get('telefono_fijo');
        $egresado->celular = $request->get('celular');
        $egresado->estado_civil = $request->get('estado_civil');
        $egresado->genero = $request->get('genero');
        //TODO: Cambio de estados
        $egresado->estado = 'ACTIVO_LOGUEADO';
        // get lugar_residencia data
        $localizacion = new Localizacion();
        $localizacion->codigo_postal = $request->get('codigo_postal');
        $localizacion->direccion = $request->get('direccion');
        $localizacion->ciudad()->associate(Ciudad::where('id_aut_ciudad', $request->get('id_lugar_residencia'))->first());
        // get grado info
        $grado = array(
            'id_programa' => $request->get('id_programa'),
            'mension_honor' => $request->get('mension_honor'),
            'titulo_especial' => $request->get('titulo_especial'),
            'fecha_grado' => date('m/d/Y', strtotime($request->get('fecha_grado'))),
            'anio_graduacion' => $request->get('anio_graduacion')
        );
        $discapacidad = $request->get('discapacidad');
        return $this->_guardarInformacionBasica($egresado, $localizacion, $grado, $discapacidad);
    }

    private function _crearUsuario($egresado)
    {
        $user = new User([
            'email' => $egresado->correo,
            'codigo_verificacion' => $this->_generarCodigoConfirmacion()
        ]);
        $user->rol()->associate(Rol::where('nombre', 'User')->first());
        $user->save();
        return $user;
    }

    /**
     * Genera un código de confirmación único(basado en UUID v4) para el usuario.
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
        //
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
            $egresado->save();
            $egresado->programas()->attach($grado['id_programa'], [
                //'tipo' => $grado->tipo,
                'mencion_honor' => array_key_exists('mension_honor', $grado) ? $grado['mension_honor'] : 'No',
                'titulo_especial' => array_key_exists('titulo_especial', $grado) ? $grado['titulo_especial'] : '',
                //'comentarios' => array_key_exists('comentarios', $grado) ? $grado['comentarios'] : '',
                'fecha_graduacion' => $grado['fecha_grado'],
                //'docente_influencia' => array_key_exists('docente_influencia', $grado) ? $grado['docente_influencia'] : '',
                'anio_graduacion' => $grado['anio_graduacion']
            ]);
            foreach ($discapacidad as $d) {
                $egresado->discapacidades()->attach($d);
            }
            $this->_enviarMensajeActivacion($usuario);
            return $egresado;
        });
    }

    //Metodo que permite almacenar una lista de Referidos de un egresado
    private function guardarinformacionReferido(array $referidos, $idEgresado){
        return DB::transaction(function () use($referidos, $idEgresado){
            foreach($referidos as $ref) {
                $referido = new Referido();
                $referido->nombres = $ref['nombres'];
                $referido->parentesco = $ref['parentesco'];
                $referido->es_egresado = $ref['es_egresado'];
                $referido->niveles_estudio()->associate(NivelEstudio::where('id_aut_estudio',$ref['id_nivel_educativo'])->firstOrFail());
                $referido->programa()->associate(Programa::where('id_aut_programa', $ref['id_aut_programa'])->firstOrFail());
                $referido->telefono_movil = $ref['telefono_movil'];
                $referido->correo = $ref['correo'];
            
                $referido->save();
                $referido->egresados()->attach($idEgresado);
            }
        });
    }


    //Metodo que permite almacenar una lista de experiencias de un egresado
    public function guardarInfoExperiencia(array $experiencias, $idEgresado){
        return DB::transaction(function () use($experiencias,$idEgresado){
           
            //Informacion experiencias pasada
            foreach($experiencias as $exp) {
                 //Informacion experiencias actual
                $experiencia = new Experiencia();
                $experiencia->tipo_trabajo = $exp['tipo_trabajo'];
                $experiencia->nombre_empresa = $exp['nombre_empresa'];
                $experiencia->ciudad()->associate(Ciudad::where('id_aut_ciudad',$exp['id_ciudad'])->firstOrFail());
                $experiencia->dir_empresa = $exp['dir_empresa'];
                $experiencia->tel_trabajo=$exp['tel_trabajo'];
                $experiencia->cargos()->associate(Cargo::where('id_aut_cargos',$exp['id_cargo'])->firstOrFail());
                $experiencia->rango_salario=$exp['rango_salario'];
                $experiencia->fecha_inicio=$exp['fecha_inicio'];
                $experiencia->fecha_fin=$exp['fecha_fin'];
                $experiencia->tipo_contrato=$exp['tipo_contrato'];
                $experiencia->sector=$exp['sector'];
                $experiencia->trabajo_en_su_area=$exp['trabajo_en_su_area'];
                
                $experiencia->save();
            }
        });
    }
    //Carga los atributos de un egresado especifico para actualizar
    
    public function edit($idEgresado){
        

        $expedicion = DB::table('egresados')
            ->join('ciudades', 'egresados.id_lugar_expedicion', '=', 'ciudades.id_aut_ciudad')
            ->join('departamentos', 'departamentos.id_aut_departamento', '=', 'ciudades.id_departamento')
            ->join('pais', 'pais.id_aut_pais', '=', 'departamentos.id_aut_dep')
            ->select('paises.nombre', 'departamentos.nombre', 'ciudades.nombre')
            ->get();
        
        $residencia = DB::table('egresados')
            ->join('localizacion', 'egresado.id_lugar_residencia', '=', 'localizacion.id_aut_localizacion')
            ->join('ciudades', 'localizacion.id_ciudad', '=', 'ciudades.id_aut_ciudad')
            ->join('departamentos', 'departamentos.id_aut_departamento', '=', 'ciudades.id_departamento')
            ->join('pais', 'pais.id_aut_pais', '=', 'departamentos.id_aut_dep')
            ->select('localizacion.barrio','localizacion.direccion','localizacion.codigo_postal','paises.nombre', 'departamentos.nombre', 'ciudades.nombre')
            ->get();        
        
        $nivelEstudio=DB::table('egresados')
            ->join('niveles_estudio', 'egresados.id_nivel_educativo', '=', 'niveles_estudio.id_aut_estudio')
            ->select('niveles_estudio.nombre')
            ->get();

        $nacimiento = DB::table('egresados')
            ->join('ciudades', 'egresados.id_lugar_expedicion', '=', 'ciudades.id_aut_ciudad')
            ->join('departamentos', 'departamentos.id_aut_departamento', '=', 'ciudades.id_departamento')
            ->join('pais', 'pais.id_aut_pais', '=', 'departamentos.id_aut_dep')
            ->select('paises.nombre', 'departamentos.nombre', 'ciudades.nombre')
            ->get();

        return response()->json();
    }

    public function update(Request $request,$idEgresado){
        $egresado=Egresado::find($idEgresado);
        
        //Obteniendo discapacidades del formulario
        $discapacidades=$request->get('discapacidades');
            
        $nuevaDiscapacidad=new Discapacidad();
        $nuevaDiscapacidad=$request->get('otra_discapacidad');

        $egresado->estado_civil=$request->get('estado_civil');
        $egresado->celular=$request->get('celular');
        
        // get lugar_residencia data
        $localizacion = new Localizacion();
        $localizacion->codigo_postal = $request->get('codigo_postal');
        $localizacion->direccion = $request->get('direccion');
        $localizacion->barrio=$request->get('barrio');
        $localizacion->ciudad()->associate(Ciudad::where('id_aut_ciudad', $request->get('id_lugar_residencia'))->first());

        //get nivel de estudio
        $egresado->niveles_estudio()->associate(NivelEstudio::where('id_aut_estudio',$idEgresado)->firstOrFail());
        $egresado->correo=$request->get('correo');
        $egresado->correo_alternativo=$request->get('correo_alternativo');
        $egresado->telefono_fijo=$request->get('telefono_fijo');
        $egresado->num_hijos=$request->get('num_hijos');
        
        //Obtener lista de referidos para modificación
        $referidosUpdate=$request->get('referidos');     
        
        //Obtener lista de experiencias laboral para modificación

        $experienciasUpdate=$request->get('experiencias');
        _updateInformation($egresado,$discapacidades,$nuevaDiscapacidad,$localizacion,$referidosUpdate,$experienciasUpdate);
    }

    //Permite asocias las incapacidades seleccionadas al egresado
    private function _updateInformation(Egresado $egresado,array $discapacidades,$nuevaDiscapacidad,$localizacion,$referidosUpdate,$experienciasUpdate){
        return DB::transaction(function() use($egresado,$discapacidades,$nuevaDiscapacidad,$localizacion,$referidosUpdate,$experienciasUpdate){

            //Asociando listado de discapacidades de egresado
            foreach($discapacidades as $discapacidad){
                $egresado->discapacidad()->attach($discapacida['id_discapacidad']);
            }

            //Agregando nuevas discapacidades de egresado
            $nuevaDiscapacidad->save();
            $nuevaDiscapacidad->egresado()->attach($egresado->get('id_aut_egresado'));
            
            //Guardar nueva localización de egresado
            $localizacion->save();
            $egresado->lugarResidencia()->associate($localizacion);

            //Desvincular listado de referidos anteriores del egresado.
            $referidosAnteriores=Referido::first();
            $referidosAnteriores->egresados();
            foreach($referidosAnteriores as $ref){
                $ref->egresado()->deattach($egresado('id_aut_egresado'));
            }
            
            
            //Asociando listado  de referidos
            foreach($referidosUpdate as $ref){
                $referido = new Referido();
                $referido->nombres = $ref['nombres'];
                $referido->parentesco = $ref['parentesco'];
                $referido->es_egresado = $ref['es_egresado'];
                $referido->niveles_estudio()->associate(NivelEstudio::where('id_aut_estudio',$ref['id_nivel_educativo'])->firstOrFail());
                $referido->programa()->associate(Programa::where('id_aut_programa', $ref['id_aut_programa'])->firstOrFail());
                $referido->telefono_movil = $ref['telefono_movil'];
                $referido->correo = $ref['correo'];
            
                $referido->save();
                $egresado->referido()->attach($referido);
            }

            //Asociando nuevas experiencias para un egresado
            $this->guardarInfoExperiencia($experiencias,$idEgresado);
        });        
    }

    //Recupera numero de hijos y estado civil de un egresado
    public function getStateAndChildren($idEgresado){
        $egresado=DB::table('egresados')->where('id_aut_egresdo',$idEgresado)->value('num_hijos','num_hijos');
        return $egresado;
    }
    
    //Recupera niveles de estudio

    public function getAllLevelStudy()
    {
        $levels=BD::table('niveles_estudio')->get();
        return response()->json($levels, 205);
    }

    public function fullInfo(Request $request, $idEgresado){
        
        //Actualizacion numero de hijos
        $egresado = Egresado::find($idEgresado);
        $egresado->num_hijos=$request->get('num_hijos');
        $egresado->ha_trabajado=$request->get('ha_trabajado');

        $egresado->trabaja_actualmente=$request->get('trabaja_actualmente');
        $egresado->save();

        

        
        // Obteniendo informacion de los referidos de un egresado
        $referidos = $request->get('referidos');
        $this->guardarinformacionReferido($referidos,$idEgresado);

        // Obteniendo informacion de las experiencias de un egresado
        $experiencias = $request->get('experiencias');
        $this->guardarInfoExperiencia($experiencias,$idEgresado);

        return response()->json($egresado, 202);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $correo = $usuario->email;
        $data = [
            'codigo' => $usuario->codigo_verificacion
        ];
        Mail::send('mail.confirmation', $data, function ($message) use ($correo, $data) {
            $message->from('sebastiancc@unicauca.edu.co', 'Egresados');
            $message->to($correo)->subject('Nuevo usuario');
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
        $egresados = $import->egresados;
        return $egresados;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $ids

     * Contraste la información de los egresados registrados en la base de datos,
     * con los de el excel proporcionado por la secretaria general.
     * 
     * @param \Illuminate\Http\Request  $req

     * @return \Illuminate\Http\Response
     */
    public function validateExcel(Request $request)
    {
        $file = $request->file('fileInput');
        $input = [
            'file' => $file,
            'extension' => strtolower($file->getClientOriginalExtension())
        ];
        $rules = [
            'file' => 'required',
            'extension' => 'required|in:xlsx,xsl,csv,ods'
        ];
        $messages = [
            'file.required' => 'Es necesario pasar un archivo.',
            'extension.required' => 'Debe ser un archivo con extension.',
            'extension.in' => 'Debe se un arvhivo excel válido.'
        ];
        $validator = Validator::make($input, $rules, $messages);

        /* if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }*/
        $egresadosEnExcel = $this->getCollection($file);
        $aceptados = $this->_procesarExcel($egresadosEnExcel);
        return response()->json(['aceptados' => $aceptados], 200, [], JSON_UNESCAPED_UNICODE);
    }

    private function _procesarExcel($egresados)
    {
        $resultado = array();
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
