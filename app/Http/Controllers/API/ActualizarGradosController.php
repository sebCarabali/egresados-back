<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Grado;

class ActualizarGradosController extends Controller
{
    // Metodo que para obtener los informacion basica de los grados de un Egresado 
    public function getGrados($idEgresado){
        $listaGrados = Grado::join('programas','grados.id_programa','programas.id_aut_programa')
        ->where('grados.id_egresado',$idEgresado)
        ->select('grados.fecha_graduacion','grados.anio_graduacion','programas.nombre','grados.mencion_honor','grados.estado')->get();
        return response()->json($listaGrados,200);
    }

    public function update(Request $request, $idEgresado){
        $grado = Grado::join('programas','grados.id_programa','programas.id_aut_programa')
        ->where('grados.id_egresado',$idEgresado)->first();

        return $this->success(new GradosResource($grado));
    }
}
