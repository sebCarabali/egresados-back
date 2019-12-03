<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Egresado;
use Carbon\Carbon;

class CarnetizacionController extends Controller
{
    //Obtiene todas las solicitudes de carnetizacion de los egresados
    public function getAll(){
        $users = Egresado::join("carnetizacion","egresados.id_aut_egresado","=","carnetizacion.id_aut_carnetizacion")
            ->where('carnetizacion.estado_solicitud','=',"Solicitado")
            ->select('egresados.nombres', 'egresados.apellidos', 'egresados.correo', 'egresados.identificacion','carnetizacion.fecha_solicitud')
            ->get();
    }

    // Actualiza el Administrador la fecha de respuesta y el estado a "Solicitado" a "respondido" de carnet por egresados
    public function updateAdmin($idCarnetizacion){
        $carnetizacion= Carnetizacion::where("estado_solicitud","=","Solicitado")
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
    *haya completado el registro y se encuentre en estado Activo.
    */
    public function validarCarnetizacion($correo){
        $estados_carnetizacion = Egresado::where("correo","=",$correo)
        ->select("estado_completar", "estado")->get();
        return response()->json($estados_carnetizacion);
    }
}
