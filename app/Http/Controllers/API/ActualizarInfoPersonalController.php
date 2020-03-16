<?php

namespace App\Http\Controllers\API;

use App\Ciudad;
use App\Egresado;
use App\Http\Controllers\Controller;
use App\Localizacion;
use App\Discapacidad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ActualizarInfoPersonalController extends Controller
{
    //Metodo que permite actualizar la información personal de un egresado, por si mismo
    public function update(Request $request, $idEgresado)
    {
        $egresado = Egresado::where('id_aut_egresado', $idEgresado)->first();

        //Obteniendo discapacidades del formulario
        $discapacidades = $request->get('discapacidades');
         //Asociando listado de discapacidades de egresado
         
        $otraDiscapacidad = $request->get('otra_discapacidad');
        
        
        $egresado->nombres = $request->get('nombres');
        $egresado->apellidos = $request->get('apellidos');
        $egresado->grupo_etnico = $request->get('grupoEtnico');
        $egresado->estado_civil = $request->get('estadoCivil');
        $egresado->identificacion = $request->get('identificacion');
        $egresado->genero = $request->get('genero');
        $egresado->correo = $request->get('correo');
        $egresado->correo_alternativo = $request->get('correoAlternativo');
        $egresado->telefono_fijo = $request->get('telefonoFijo');
        //return response()->json( $request,400);
        $egresado->celular = $request->get('celular');

        $egresado->ciudadNacimiento()->dissociate();
        $egresado->ciudadNacimiento()->associate(Ciudad::where('id_aut_ciudad', $request->get('idCiudadNacimiento'))->first());

        // get lugar_residencia data
        $residencia = $egresado->lugarResidencia()->first(); //Localizacion::find($egresado->id_lugar_nacimiento);
        $residencia->ciudad()->dissociate();

        $residencia->direccion = $request->get('direccionResidencia');
        //$residencia->barrio = $request->get('barrio');
        $residencia->ciudad()->associate(Ciudad::where('id_aut_ciudad', $request->get('idCiudadResidencia'))->first());

        //$egresado->num_hijos = $request->get('num_hijos');
        //
        $egresado = $this->_updateInformation($egresado, $residencia,$discapacidades,$otraDiscapacidad);

        return $this->success($egresado);
    }

    private function _updateInformation(Egresado $egresado, $localizacion,$discapacidades,$otraDiscapacidad)
    {
        return DB::transaction(function () use ($egresado, $localizacion,$discapacidades,$otraDiscapacidad) {
            
            //Asociando listado de discapacidades de egresado
            $egresado->discapacidades()->detach();
            foreach ($discapacidades as $discapacidad) {

                $egresado->discapacidades()->attach($discapacidad);
               
            }

            //Agregando nuevas discapacidades de egresado
            if(!empty($otraDiscapacidad)){
                $egresado->discapacidades()->attach($otraDiscapacidad['id_discapacidad'],[$otraDiscapacidad['descripcion']]);
            }
            //Guardar nueva localización de egresado
            $localizacion->save();
            $egresado->lugarResidencia()->associate($localizacion);
            $egresado->save();
//            return response()->json($egresado,400);
        });
    }
}
