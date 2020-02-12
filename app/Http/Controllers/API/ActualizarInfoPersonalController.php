<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ActualizarInfoPersonalController extends Controller
{
    //Metodo que permite actualizar la información personal de un egresado, por si mismo
    public function update(Request $request, $email){
        //$egresado = Egresado::find($idEgresado);
        $idEgresado = DB::table('egresados')
                ->where('correo', $email);

        //Obteniendo discapacidades del formulario
        $discapacidades = $request->get('discapacidades');

        $nuevaDiscapacidad = new Discapacidad();
        $nuevaDiscapacidad = $request->get('otra_discapacidad');
        
        $egresado->estado_civil = $request->get('estado_civil');
        $egresado->correo = $request->get('correo');
        $egresado->correo_alternativo = $request->get('correo_alternativo');
        
        $egresado->celular = $request->get('celular');
        $egresado->telefono_fijo = $request->get('telefono_fijo');


        // get lugar_residencia data
        $localizacion = new Localizacion();
        //$localizacion->codigo_postal = $request->get('codigo_postal');
        $localizacion->direccion = $request->get('direccion');
        $localizacion->barrio = $request->get('barrio');
        $localizacion->ciudad()->associate(Ciudad::where('id_aut_ciudad', $request->get('id_lugar_residencia'))->first());

        //$egresado->num_hijos = $request->get('num_hijos');

        //Obtener lista de referidos para modificación
        $referidosUpdate = $request->get('referidos');
        
        //Obtener lista de experiencias laboral para modificación

        $experienciasUpdate = $request->get('experiencias');
        _updateInformation($egresado, $discapacidades, $nuevaDiscapacidad, $localizacion, $referidosUpdate, $experienciasUpdate);
    }
}
