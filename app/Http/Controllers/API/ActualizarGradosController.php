<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Grado;

class ActualizarGradosController extends Controller
{
    // 
    public function getGrados($idEgresado){
        $listaGrados = Grado::join('programas','grados.id_programa','programas.id_aut_programa')
        ->where('grados.id_egresado',$idEgresado)
        ->select('grados.fecha_graduacion','grados.anio_graduacion','programas.nombre','grados.mencion_honor','grados.estado')->get();
        return response()->json($listaGrados,200);
    }
}
