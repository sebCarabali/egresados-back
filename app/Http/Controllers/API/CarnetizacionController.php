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
        $solicitudesPendientes = Carnetizacion::join('egresados','egresados.id_aut_egresado','carnetizacion.id_egresado')
        ->where('carnetizacion.estado_solicitud','PENDIENTE')
        ->select('carnetizacion.id_aut_carnetizacion','egresados.nombres','egresados.apellidos','egresados.correo','egresados.identificacion','carnetizacion.fecha_solicitud')->get();
        return response()->json($solicitudesPendientes,200);
    }

    // Actualiza el Administrador la fecha de respuesta y el estado a "Solicitado" a "respondido" de carnet por egresados(ADMINISTRADOR)
    public function updateAdmin($idSolicitud,Request $request){
         
        $fecha= Carbon::now();
        $fecha=$fecha->format('yy/m/d');
        
        $solicitud = Carnetizacion::where("estado_solicitud","PENDIENTE")
        ->where("id_aut_carnetizacion",$idSolicitud)->update(['estado_solicitud'=>$request->get("estado"),'fecha_respuesta'=>$fecha]);
        
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
    
        $solicitud = Carnetizacion::where('id_egresado',$idEgresado)
        ->orderBy('id_aut_carnetizacion','desc')
        ->select('estado_solicitud')->first(); 

        return response()->json($solicitud,200);
    }


    //Metodo que permite cambiar el estado a una solicitud de carnetizacion por egresado
    public function updateEstadoSolicitudCarnet($idEgresado, Request $request){
        //return response()->json($request, 400);
        if($request->get('solicitud')=="PENDIENTE"){

            $fecha= Carbon::now();
            $fecha=$fecha->format('yy/m/d');
        
            $egresado = Egresado::find($idEgresado);
            $nuevaSolicitudCarnet = new Carnetizacion();
            $nuevaSolicitudCarnet->estado_solicitud="PENDIENTE";
            $nuevaSolicitudCarnet->fecha_solicitud=$fecha;        
            $nuevaSolicitudCarnet->egresados()->associate($egresado);
            $nuevaSolicitudCarnet->save();
            
        }else if($request->get('solicitud')=="CANCELADO"){
            $solicitud = Carnetizacion::where('id_egresado',$idEgresado)
            ->where('estado_solicitud',"PENDIENTE")->update(['estado_solicitud'=>"CANCELADO"]);
        }
        
        
    }
}
