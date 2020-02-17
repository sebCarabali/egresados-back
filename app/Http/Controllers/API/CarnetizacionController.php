<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Egresado;
use App\Carnetizacion;
use Carbon\Carbon;

class CarnetizacionController extends Controller
{
    //Obtiene todas las solicitudes de carnetizacion de los egresados (ADMINISTRADOR)
    public function getAll(){
        $solicitudesPendientes = Carnetizacion::where('carnetizacion.estado_solicitud','=',"PENDIENTE")->get();
        return response()->json($solicitudesPendientes,200);
    }

    // Actualiza el Administrador la fecha de respuesta y el estado a "Solicitado" a "respondido" de carnet por egresados(ADMINISTRADOR)
    public function updateAdmin($idSolicitud,$estado){
        $nuevoEstado="";
        if($estado){
            $nuevoEstado="RESPONDIDO";
        }else {
            $nuevoEstado="RECHAZADO";
        }

        $fecha= Carbon::now();
        $fecha=$fecha->format('yy-m-d');

        $solicitud = Carnetizacion::where("estado_solicitud","=","PENDIENTE")
        ->where("id_aut_carnetizacion","=",$idSolicitud)->update(['estado_solicitud'=>$nuevoEstado],['fecha_respuesta'=>$fecha]);
    }

    
    // Confirmacion de respuesta del Administrador para el egresado, se modifica el estado de solicitud RESPONDIDO -> RECIBIDO
    public function updateEgresado($idEgresado){
        $carnetizacion = Carnetizacion::where("carnetizacion.estado_solicitud","=","RESPONDIDO")
        ->where("carnetizacion.id_egresado","=",$idEgresado)
        ->update(["carnetizacion.estado_solicitud"=>"RECIBIDO"]);
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
        $solicitud_pendiente = DB::table('carnetizacion')
        ->where('carnetizacion.id_egresado',$idEgresado)
        ->select('carnetizacion.estado_solicitud')->first();

        return response()->json($solicitud_pendiente, 400);
    }

    

    //Metodo que permite hacer una solicitud de egresado
    public function solicitarCarnet($idEgresado){

        $fecha= Carbon::now();
        $fecha=$fecha->format('yy-m-d');

 
        $egresado = Egresado::find($idEgresado);
        $nuevaSolicitudCarnet = new Carnetizacion();
        $nuevaSolicitudCarnet->estado_solicitud="PENDIENTE";
        $nuevaSolicitudCarnet->fecha_solicitud=$fecha;        
        $nuevaSolicitudCarnet->egresados()->associate($egresado);
        $nuevaSolicitudCarnet->save();
        
    }

    public function cancelarSolicitud($idEgresado){
        $solicitud = Carnetizacion::where('id_egresado',$idEgresado)
        ->where('estado_solicitud',"PENDIENTE")->update(['estado_solicitud'=>"CANCELADO"]);
    }
}
