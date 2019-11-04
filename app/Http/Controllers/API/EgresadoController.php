<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Egresado;
use App\Nacimiento;
use App\Ciudad;
use App\Localizacion;
use App\Experiencia;
use App\Programa;
use App\NivelEstudio;

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

    public function fullInfo($idEgresado, Request $request){
        // Código de error por defecto
        $code = 400;
        $data = null;

        //Actualizacion numero de hijos
        $this->validate($request,['num_hijos'=>'required']);
        $egresado = Egresado::find($idEgresado);
        $egresado->num_hijos=$request->get('num_hijos');
        $egresado->ha_trabajado=$request->get('ha_trabajado');
        $egresado->trabaja_actualmente=$request->get('trabaja_actualmente');
        $egresado->save();


        return response()->json($egresado,202);
  

        // Obteniendo informacion de los referidos
        //Referido Madre
        $referido = new Referido();
        $referido->id_egresado = $request->get('identificacion');
        $referido->nombres = $request->get('nombres');
        $referido->apellidos = $request->get('apellidos');
        $referido->niveles_estudio()->associate(NivelEstudio::whereId($request->get('id_nivel_educativo'))->firstOrFail());
        $referido->telefono_movil = $request->get('telefono');
        $referido->correo = $request->get('correo');
        $referido->parentesco = $request->get('parentesco');
        $referido->niveles_estudio()->associate(Programa::whereId($request->get('id_aut_programa'))->firstOrFail());
        $referido->es_egresado = $request->get('es_egresado');
        
        //Obteniendo datos laborales
        $experiencia = new Experiencia();
        $experiencia->egresados()->associate(Egresado::whereId($request->get('id_egresado'))->firstOrFail());
        $experiencia->cargos()->associate(Cargo::whereId($request->get('id_cargo'))->firstOrFail());
        $experiencia->nombre_jefe = $request->get('nombre_jefe');
        $experiencia->telefono_jefe = $request->get('telefono_jefe');
        $experiencia->correo_jefe = $request->get('correo_jefe');
        $experiencia->nombre_empresa = $request->get('nombre_empresa');
        $experiencia->dir_empresa = $request->get('dir_empresa');
        $experiencia->tel_trabajo = $request->get('tel_trabajo');
        $experiencia->rango_salario = $request->get('rango_salario');
        $experiencia->tipo_contrato = $request->get('tipo_contrato');
        $experiencia->trabajo_en_su_area = $request->get('trabajo_en_su_area');
        $experiencia->sector = $request->get('sector');

        
/*
        //Obtener informacion del referido
        $referido = new Referido();
        $referido->id_egresado = $request->get('identificacion');
        $referido->nombres = $request->get('nombres');
        $referido->apellidos = $request->get('apellidos');
        $referido->telefono_movil = $request->get('telefono');
        $referido->correo = int($request->get('correo'));
        $referido->parentesco = int($request->get('parentesco'));
        $referido->es_egresado = int($request->get('es_egresado'));

        
        $nacimiento->ciudad()->associate(Cuidad::whereId($request->get('id_ciudad_nacimiento'))->firstOrFail());*/
        
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
     * @param  int  $ids
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        
    }
}
