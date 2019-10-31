<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Requests\GuardarInformacionBasicaRequest;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Egresado;
use App\Nacimiento;
use App\Ciudad;
use App\Localizacion;
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
        $egresado->fecha_nacimiento = $request->get('fecha_nacimiento');
        $egresado->ciudadNacimiento()->associate(Cuidad::whereId($request->get('id_ciudad_nacimiento'))->firstOrFail());
        $egresado->nivelEstudio()->associate(NivelEstudio::find($request->get('id_nivel_educativo')));
        $egresado->discapacidad = $request->get('discapacidad');
        $egresado->telefono = $request->get('telefono');
        $egresado->estado_civil = $request->get('estado_civil');
        $egresado->genero = $request->get('genero');
        $egresado->identificacion = $request->get('identificacion');
        $egresado->nombres = $request->get('nombres');
        $egresado->apellidos = $request->get('apellidos');
        $egresado->genero = $request->get('genero');
        $egresado->num_hijos = int($request->get('num_hijos'));
        // get lugar_residencia data
        $localizacion = new Localizacion();
        $localizacion->codigo_postal = $request->get('codigo_postal');
        $localizacion->direccion = $request->get('direccion');
        $localizacion->barrio = $request->get('barrio');
        $localizacion->ciudad()->associate(Cuidad::whereId($request->get('id_ciudad_residencia'))->firstOrFail());
        // get grado info
        $grado = $request->get('grado');
        $this->_guardarInformacionBasica($egresado, $localizacion, $nacimiento, $grado);
    }

    private function _completarInformacionBasica(Egresado $egresado, Request $request)
    {
        $egresado->num_hijos = $request->get('num_hijos');
        $egresado->correo = $request->get('correo');
        $egresado->correo_alternativo = $request->get('correo_alternativo');
        $egresado->grupo_etnico = $request->get('grupo_etnico');
        $egresado->fecha_nacimiento = $request->get('fecha_nacimiento');
        $egresado->ciudadNacimiento()->associate(Cuidad::whereId($request->get('id_ciudad_nacimiento'))->firstOrFail());
        $egresado->nivelEstudio()->associate(NivelEstudio::find($request->get('id_nivel_educativo')));
        $egresado->discapacidad = $request->get('discapacidad');
        $egresado->telefono = $request->get('telefono');
        $egresado->estado_civil = $request->get('estado_civil');
        $egresado->genero = $request->get('genero');
        //TODO: Cambio de estados
        //$egresado->estado = 'PENDIENTE';
        // get lugar_residencia data
        $localizacion = new Localizacion();
        $localizacion->codigo_postal = $request->get('codigo_postal');
        $localizacion->direccion = $request->get('direccion');
        $localizacion->barrio = $request->get('barrio');
        $localizacion->ciudad()->associate(Cuidad::whereId($request->get('id_ciudad_residencia'))->firstOrFail());
        // get grado info
        $grado = $request->get('grado');
        $this->_guardarInformacionBasica($egresado, $localizacion, $nacimiento, $grado);
    }

    private function _guardarInformacionBasica(Egresado $egresado, Localizaion $localizacion,
             Nacimiento $nacimiento, Grado $grado)
    {
        // save all data and response egresados object in json format
        return DB::transaction(function () use ($nacimiento, $egresado, $localizacion, $grado) {
            $nacimiento->save();
            $egresado->nacimiento()->associate($nacimiento);
            $localizacion->save();
            $egresado->lugarResidencia()->associate($localizacion);
            $egresado->save();
            $egresado->programas()->attach($grado->id_programa, [
                'tipo' => $grado->tipo,
                'mension_honor' => $grado->mension_honor,
                'titulo_especial' => $grado->titulo_especial,
                'comentarios' => $grado->comentarios,
                'fecha_graduacion' => $grado->fecha_graduacion,
                'docente_influencia' => $grado->docente_influencia
            ]);
            //$usuario = $this->_crearUsuario($egresado);
            //$this->_enviarMensajeActivacion($usuario);
            return $egresado;
        });
    }

    private function _crearUsuario($egresado)    {
        return User::create([
            'email' => $egresado->correo,
            'id_rol' => Rol::where('nombre', '=', 'ADMIN')->get()->id,
            'codigo_confirmacion' => Hash::make($egresado->correo)
        ]);
    }

    private function _enviarMensajeActivacion(User $usuario)
    {
        $correo = $usuario->email;
        Mail::send('mail.confirmation', ['codigo' => $usuario->codigo_confirmacion], 
                function ($message) use ($correo){
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
        return array_pop($egresados);
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
        $procesador = new ProcesadorExcel($egresadosEnExcel);

        /*if(count($errors) > 0) {
            return response()->json($validator->errors(), 422);
        }*/

        return response()->json(['msg' => 'Archivo verificado correctamente', 'aceptados' => []], 200);
    }

    class ProcesadorExcel {
        public $egresados;
        public $activosLogueados;
        public $activosNoLogueados;
        public $rechazados;

        public function __construct($egresados) {
            $this->egresados = $egresados;
            $this->activosLogueados = [];
            $this->activosNoLogueados = [];
            $this->rechazados = [];
        }

        public function proccess(DB $db)
        {
            foreach($this->egresados as $egresado) {
                // ver si el egresado está en la base de datos.
                $egresadoBd = Egresado::where('identificacion', $egresado->identificacion)->get();
                // TODO: Verificar los estados.
                if($egresadoBd /*&& $egresadoBd->estado == 'PENDIENTE'*/) {
                    // si está cambiar su estado y agregar a la lista de activosLogueados
                    // TODO: Cambio de estaods.
                    //$egresadoBd->estado = 'ACTIVO_LOGUEADO';
                    $egresadoBd->save();
                    array_push($this->activosLogueados, $egresadoBd);
                } else {
                    // sino registrar en la base de datos con el estado activosNoLogueados
                    // TODO: Extraer datos del excel de secretaria, ¿ Cúales datos extraer ?
                }
                // ¿Qué hacer con los rechazados.?
            }
        }
    }
}
