<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Referido;
use App\Egresado;
use App\NivelEstudio;
use App\Programa;


class refPersonalesController extends Controller
{
    //
    public function update(Request $request,$idEgresado){
        $referidos = $request->get('referidos');
        //$eliminarRferidos = $request->get('eliminarReferidos');

        
        foreach($referidos as $referido){
            if($referido['tipoActualizacion']=='1'){ //Condición que permite identificar la actualización de un referido.
                $referidoAntiguo = Referido::find($referido['id_aut_referido']);
                /*$egresado = NivelEstudio::find($referido['id_nivel_educativo']);
                $programa = Programa::find($referido['id_aut_programa']);*/

                //return response()->json($referidoAntiguo->id_nivel_educativo,400);
                $referidoAntiguo->niveles_estudio()->dissociate();
                $referidoAntiguo->programa()->dissociate();
                
                
                $referidoAntiguo->nombres=$referido['nombres'];
                $referidoAntiguo->niveles_estudio()->associate(NivelEstudio::where('id_aut_estudio',$referido['id_nivel_educativo'])->firstOrFail());
                $referidoAntiguo->telefono_movil=$referido['telefono_movil'];
                $referidoAntiguo->correo=$referido['correo'];
                $referidoAntiguo->parentesco=$referido['parentesco'];
                $referidoAntiguo->programa()->associate(Programa::where('id_aut_programa', $referido['id_aut_programa'])->firstOrFail());
                $referidoAntiguo->es_egresado=$referido['es_egresado'];
                $referidoAntiguo->save();

            }else if($referido['tipoActualizacion']=='2'){//Condición que permite identificar si se crea un referido.
                $referidoNuevo= new Referido();
                $referidoNuevo->nombres=$referido['nombres'];
                $referidoNuevo->niveles_estudio()->associate(NivelEstudio::where('id_aut_estudio',$referido['id_nivel_educativo'])->firstOrFail());
                $referidoNuevo->telefono_movil=$referido['telefono_movil'];
                $referidoNuevo->correo=$referido['correo'];
                $referidoNuevo->parentesco=$referido['parentesco'];
                $referidoNuevo->programa()->associate(Programa::where('id_aut_programa', $referido['id_aut_programa'])->firstOrFail());
                $referidoNuevo->es_egresado=$referido['es_egresado'];
                $referidoNuevo->save();
                $referidoNuevo->egresados()->attach($idEgresado);
            }else if($referido['tipoActualizacion']=='3'){//Condición para eliminar egresado.
                $referidoDelete=Referido::find($referido['id_aut_referido']);
                $referidoDelete->egresados()->detach();
                Referido::destroy($referido['id_aut_referido']);
            }
        }
    }
}
