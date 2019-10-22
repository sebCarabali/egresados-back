<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Egresado;
use Illuminate\Support\Facades\DB;
use App\Nacimiento;
use App\Ciudad;
use App\NivelEducativo;
use App\Localizacion;
use App\Http\Requests\StoreEgresados;

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
        $egresado->num_hijos = $request->get('num_hijos');
        $egresado->correo = $request->get('correo');
        $egresado->correo_alternativo = $request->get('correo_alternativo');
        $egresado->estado_civil = 0;
        $egresado->ha_trabajado = 0;
        $egresado->trabaja_actualmente = 0;
        
        // set lugar de expedición.
        $egresado->lugarExpedicion()->associate(Ciudad::find($request->get('id_lugar_expedicion')));
        // get lugar_nacimiento data
        $nacimiento = new Nacimiento();
        $nacimiento->fecha_nacimiento = $request->get('fecha_nacimiento');
        $nacimiento->ciudad()->associate(Ciudad::find($request->get('id_ciudad_nacimiento')));

        // set nivel educativo
        $egresado->nivelEducativo()->associate(NivelEducativo::find($request->get('id_nivel_educativo')));
        
        // get lugar_residencia data
        $localizacion = new Localizacion();
        $localizacion->codigo_postal = $request->get('codigo_postal');
        $localizacion->direcccion = $request->get('direccion');
        $localizacion->barrio = $request->get('barrio');
        $localizacion->ciudad()->associate(Ciudad::find($request->get('id_ciudad_residencia'))->firstOrFail());
        // get grados data
        
        $egresado = DB::transaction(function () use ($nacimiento, $egresado, $localizacion, $request) {
            // save all data and response egresados object in json format
            $nacimiento->save();
            $egresado->nacimiento()->associate($nacimiento);
            $localizacion->save();
            $egresado->lugarResidencia()->associate($localizacion);
            $egresado->save();
            // save grados
            $grado = [
                'tipo' => $request->get('tipo'),
                'mension_honor' => $request->get('mension_honor'),
                'titulo_especial' => $request->get('titulo_especial'),
                'comentarios' => $request->get('comentarios'),
                'fecha_graduacion' => $request->get('fecha_graduacion')
                //'docente_influencia' => $request->get('docente_influencia')
            ];
            $egresado->programas()->attach($request->get('id_programa'), $grado);
            
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
}
