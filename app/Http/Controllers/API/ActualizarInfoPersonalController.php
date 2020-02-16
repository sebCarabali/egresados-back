<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Localizacion;
use App\Ciudad;
use App\Egresado;

class ActualizarInfoPersonalController extends Controller
{
    //Metodo que permite actualizar la información personal de un egresado, por si mismo
    public function update(Request $request, $idEgresado){

        $egresado = Egresado::where($idEgresado);
        

        //Obteniendo discapacidades del formulario
        //$discapacidades = $request->get('discapacidades');
        //$nuevaDiscapacidad = new Discapacidad();
        //$nuevaDiscapacidad = $request->get('otra_discapacidad');
        $egresado->$nombres = $request->get('nombres');
        $egresado->$apellidos = $request->get('apellidos');
        $egresado->$grupoEtnico = $request->get('grupoEtnico');
        $egresado->estado_civil = $request->get('estadoCivil');
        $egresado->$identificacion = $request->get('identificacion');
        $egresado->$genero = $request->get('genero');
        $egresado->correo = $request->get('correo');
        $egresado->correo_alternativo = $request->get('correoAlternativo');
        $egresado->telefono_fijo = $request->get('telefono_fijo');
        $egresado->celular = $request->get('celular');

        $egresado->ciudadNacimiento()->dissociate();
        $egresado->ciudadNacimiento()->associate(Ciudad::where('id_aut_ciudad', $request->get('idCiudadNacimiento'))->first());

      
        // get lugar_residencia data
        $residencia = Localizacion::find($egresado->id_lugar_nacimiento);
        $residencia->ciudad()->dissociate();

        $residencia->direccion = $request->get('direccionRecidencia');
        //$residencia->barrio = $request->get('barrio');
        $residencia->ciudad()->associate(Ciudad::where('id_aut_ciudad', $request->get('idCiudadResidencia'))->first());

        //$egresado->num_hijos = $request->get('num_hijos');

        _updateInformation($egresado,  $localizacion);
    }

    private function _updateInformation(Egresado $egresado,  $localizacion)
    {
        return DB::transaction(function () use ($egresado, $localizacion) {

            //Asociando listado de discapacidades de egresado
  /*          foreach ($discapacidades as $discapacidad) {
                $egresado->discapacidad()->attach($discapacida['id_discapacidad']);
            }

            //Agregando nuevas discapacidades de egresado
            $nuevaDiscapacidad->save();
            $nuevaDiscapacidad->egresado()->attach($egresado->get('id_aut_egresado'));
*/
            //Guardar nueva localización de egresado
            $localizacion->save();
            $egresado->lugarResidencia()->associate($localizacion);

        });
    }

}
