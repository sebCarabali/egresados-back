<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Egresado;

class CarnetizacionController extends Controller
{
    //Obtiene todas las solicitudes de carnetizacion de los egresados (ADMINISTRADOR)
    public function getAll(){
        $SolicitudesPendientes = Egresado::join("solicita","egresados.id_aut_egresado","=","solicita.id_egresado")
            ->join("carnetizacion","solicita.id_carnetizacion","=","carnetizacion.id_aut_carnetizacion")
            ->where('carnetizacion.estado_solicitud','=',"Solicitado")
            ->select('egresados.nombres', 'egresados.apellidos', 'egresados.correo', 'egresados.identificacion','solicita.fecha_solicitud')
            ->get();
        return response()->json($SolicitudesPendientes,200);
    }

    // Actualiza el Administrador la fecha de respuesta y el estado a "Solicitado" a "respondido" de carnet por egresados(ADMINISTRADOR)
    public function updateAdmin($idCarnetizacion){
        $carnetizacion= Carnetizacion::where("estado_solicitud","=","SOLICITADO")
        ->where("id_aut_carnetizacion","=",$idCarnetizacion)
        ->update(["estado_solicitud"=>"Respondido"],["fecha_respuesta"=>Carbon::now()->toTimeString()]);
    }

    
    // Confirmacion de respuesta del Administrado por el egresado, se modifica el estado de solicitud
    public function updateEgresado($idEgresado){
        $carnetizacion= Egresado::join("solicita","egresado.id_aut_egresado","=","solicita.id_egresado")
        ->join("carnetizacion","solicita.id_carnetizacion","=","carnetizacion.id_aut_carnetizacion")
        ->where("carnetizacion.estado_solicitud","=","Respondido")
        ->where("solicita.id_egresado","=",$idEgresado)
        ->update(["carnetizacion.estado_solicitud"=>"Recibido"]);
    }

    /*
    *Retorna el estado del egresado, para validar que este
    *se encuentre en estado logeado.
    */

    public function validarEstadoEgresado($idEgresado){
   
        $estados_egresado = Egresado::where('id_aut_egresado',$idEgresado)
        ->select("estado")->first();
        $estadoBol=false;

        if($estados_egresado->estado=='PENDIENTE' || $estados_egresado->estado=='ACTIVO NO LOGUEADO'){
            $estadoBol=false;
            return response()->json($estadoBol,200);
        }else if($estados_egresado->estado=='ACTIVO LOGUEADO' ){
            $estadoBol=true;
            return response()->json($estadoBol,200);
        }
        return response()->json('ERROR AL CARGAR ESTADO DE EGRESADO',400);
    }

    /*
    *Retorna el estado del egresado, para validar que este
    *se encuentre en estado logeado.
    */

    public function validarCompletarInfo($idEgresado){
        $estado_complete = Egresado::where('id_aut_egresado',$idEgresado)
        ->select("estado_completar")->first();        
        return response()->json($estado_complete,200);
    }

    public function validarSolicitudesEgresado($idEgresado){
        //return response()->json($idEgresado, 400);
        $solicitud_pendiente = DB::table('carnetizacion')
        ->where('carnetizacion.id_egresado',$idEgresado)
        ->select('carnetizacion.estado_solicitud')->first();
       
        return response()->json($solicitud_pendiente, 200);
        
    }
}
