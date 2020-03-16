<?php

namespace App\Http\Controllers;

use App\Apoyo;
use App\Http\Resources\ServicioResource;
use App\Servicio;
use Illuminate\Support\Facades\DB;

class ServicioController extends Controller
{
    public function getAll()
    {
        return ServicioResource::collection(Servicio::all());
    }

    public function getServiciosApoyo($email)
    {
        $apoyo = Apoyo::where('correo', $email)->first();
        $idAcceso = DB::table('acceso')->where('id_apoyo', $apoyo->id_aut_apoyo)->pluck('id_servicio')->toArray();
        $servicios = Servicio::whereIn('id_aut_servicio', $idAcceso)->pluck('nombre')->toArray();

        return response()->json($servicios);
    }
}
