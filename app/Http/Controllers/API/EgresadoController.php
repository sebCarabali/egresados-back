<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Requests\GuardarInformacionBasicaRequest;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use App\Egresado;
use App\Nacimiento;
use App\Ciudad;
use App\User;
use App\Role as Rol;
use App\Localizacion;
use App\NivelEstudio;
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
        if($hasDataRegistered) {
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
        $egresado->nivelEducativo()->associate(NivelEstudio::find($request->get('id_nivel_educativo'))->first());
        $egresado->discapacidad = $request->get('discapacidad');
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
        return $this->_guardarInformacionBasica($egresado, $localizacion, $grado);
    }

    private function _completarInformacionBasica(Egresado $egresado, Request $request)
    {
        $egresado->num_hijos = $request->get('num_hijos');
        $egresado->correo = $request->get('correo');
        $egresado->correo_alternativo = $request->get('correo_alternativo');
        $egresado->grupo_etnico = $request->get('grupo_etnico');
        $egresado->fecha_nacimiento = date('m/d/Y', strtotime($request->get('fecha_nacimiento')));
        $egresado->lugarExpedicion()->associate(Ciudad::where('id_aut_ciudad', $request->get('id_lugar_expedicion'))->first());
        $egresado->ciudadNacimiento()->associate(Ciudad::where('id_aut_ciudad',$request->get('id_lugar_nacimiento'))->first());
        $egresado->nivelEducativo()->associate(NivelEstudio::find($request->get('id_nivel_educativo'))->first());
        $egresado->discapacidad = $request->get('discapacidad');
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
        return $this->_guardarInformacionBasica($egresado, $localizacion, $grado);
    }

    private function _guardarInformacionBasica(Egresado $egresado, Localizacion $localizacion,
             array $grado)
    {
        // save all data and response egresados object in json format
        return DB::transaction(function () use ($egresado, $localizacion, $grado) {
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
            $this->_enviarMensajeActivacion($usuario);
            return $egresado;
        });
    }

    private function _crearUsuario($egresado)    {
        $user = new User([
            'email' => $egresado->correo,
            'codigo_verificacion' => Hash::make($egresado->correo)
        ]);
        $user->rol()->associate(Rol::where('nombre', 'User')->first());
        $user->save();
        return $user;
    }

    private function _enviarMensajeActivacion(User $usuario)
    {
        $correo = $usuario->email;
        $data = [
            'codigo' => $usuario->codigo_verificacion
        ];
        Mail::send('mail.confirmation', $data, 
                function ($message) use ($correo, $data){
            $message->from('sebastiancc@unicauca.edu.co', 'Egresados');
            $message->to($correo)->subject('Nuevo usuario');
        });
    }

    private function _tieneDatosRegistrados($identificacion)
    {
        return Egresado::where('identificacion', $identificacion)->exists();
    }

    private function getCollection($file){
        $import = new EgresadosImport();
        Excel::import($import, $file);
        $egresados = $import->egresados;
        return $egresados;
    }

    /**
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

        if($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $egresadosEnExcel = $this->getCollection($file);
        $aceptados = $this->_procesarExcel($egresadosEnExcel);
        /*if(count($errors) > 0) {
            return response()->json($validator->errors(), 422);
        }*/
        return response()->json($aceptados, 200);
    }

    private function _procesarExcel($egresados)
    {
        $resultado = array();
        $fisrtRow = true;
        // para todos los egresados de excel.
        foreach($egresados as $e) {
            if(!$fisrtRow) {
                // Si el egresado ya ha realizado el pre-registro, cambiar estado de EN_ESPERA a ACTIVO LOGUEADO.
                $egresadoYaRegistrado = Egresado::where('identificacion', $e['identificacion'])
                    ->where('estado', 'EN_ESPERA')->first();
                if($egresadoYaRegistrado) {
                    // cambiar estado.
                    $egresadoYaRegistrado->estado = 'ACTIVO_LOGUEADO';
                    $egresadoYaRegistrado->save();
                    //array_push($resultado, $e);
                }
                array_push($resultado, $e);
            }
            $fisrtRow = false;
        }
        return $resultado;
    }
}
