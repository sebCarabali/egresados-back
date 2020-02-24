<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Referido;

class refPersonalesController extends Controller
{
    //
    public function update(Request $request,$idEgresado){
        $referidos = $request->get('referidos');
        //$eliminarRferidos = $request->get('eliminarReferidos');


        foreach($referidos as $referido){
            if($referido['id_aut_referido']!=null){
                Referido::updateOrCreate(
                    ['id_aut_referido'=>$referido['id_aut_referido']],
                    ['nombres'=>$referido['nombres'],
                    'id_nivel_educativo'=>$referido['id_nivel_educativo'],
                    'telefono_movil'=>$referido['telefono_movil'],
                    'correo'=>$referido['correo'],
                    'parentesco'=>$referido['parentesco'],
                    'id_aut_programa'=>$referido['id_aut_programa'],
                    'es_egresado'=>$referido['es_egresado']]
                );
            }else{
                
            }
        }
        /*foreach (){
            App\Flight::destroy(1);
        }*/
        

        /*$referidosActuales = DB::table('referidos')
        ->join('referidos_egresados','referidos.id_aut_referido','referidos_egresados.id_referido')
        ->where('regeridos_egresados.id_egresados',$idEgresado)->get();*/

    }

}
