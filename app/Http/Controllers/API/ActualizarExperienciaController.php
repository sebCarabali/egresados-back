<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ActualizarExperienciaController extends Controller
{
    //
    public function update(Request $request,$idEgresado){
        foreach($request as $referido){
            $referencia = Experiencia::updateOrCreate(
                ['id_aut_egresado'=>$request->get('id')],
                ['nombre'=>$request->get('nombre')],
                ['id_nivel_educativo'=>$request->get('id_nivel_educativo')],
                ['telefono_movil'=>$request->get('telefono_movil')],
                ['correo'=>$request->get('correo')],
                ['parentesco'=>$request->get('parentesco')],
                ['id_aut_programa'=>$request->get('id_aut_programa')],
                ['es_egresado'=>$request->get('es_egresado')]
            );
        }    
    }
}
