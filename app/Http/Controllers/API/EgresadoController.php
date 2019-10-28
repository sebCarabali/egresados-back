<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
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
    public function storeBasicInfo(Request $request)
    {
        // get egresados data
        $egresado = new Egresado();
        $egresado->identificacion = $request->get('identificacion');
        $egresado->nombres = $request->get('nombres');
        $egresado->apellidos = $request->get('apellidos');
        $egresado->genero = $request->get('genero');
        $egresado->num_hijos = int($request->get('num_hijos'));
        // get lugar_nacimiento data
        $nacimiento = new Nacimiento();
        $nacimiento->fecha_nacimiento = $request->get('fecha_nacimiento');
        $nacimiento->ciudad()->associate(Cuidad::whereId($request->get('id_ciudad_nacimiento'))->firstOrFail());
        // get lugar_residencia data
        $localizacion = new Localizacion();
        $localizacion->codigo_postal = $request->get('codigo_postal');
        $localizacion->direccion = $request->get('direccion');
        $localizacion->barrio = $request->get('barrio');
        $localizacion->ciudad()->associate(Cuidad::whereId($request->get('id_ciudad_residencia'))->firstOrFail());
        // get grados data
        $egresado = DB::transaction(function () use ($nacimiento, $egresado, $localizacion, $request) {
            // save all data and response egresados object in json format
            $nacimiento->save();
            $egresado->nacimiento()->associate($nacimiento);
            $localizacion->save();
            $egresado->lugarResidencia()->associate($localizacion);
            $egresado->save();
            // save grados
            foreach ($request->get('grados') as $grado) {
                $egresado->programas()->attach($grado->id_programa, [
                    'tipo' => $grado->tipo,
                    'mension_honor' => $grado->mension_honor,
                    'titulo_especial' => $grado->titulo_especial,
                    'comentarios' => $grado->comentarios,
                    'fecha_graduacion' => $grado->fecha_graduacion,
                    'docente_influencia' => $grado->docente_influencia
                ]);
            }
            return $egresado;
        });
        
        return response()->json($egresado, 201);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        
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

        $aceptados = $this->getCollection($file);

        /*if(count($errors) > 0) {
            return response()->json($validator->errors(), 422);
        }*/

        return response()->json(['msg' => 'Archivo verificado correctamente', 'aceptados' => $aceptados], 200);
    }
}
