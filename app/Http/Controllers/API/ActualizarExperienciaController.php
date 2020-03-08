<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Experiencia;

class ActualizarExperienciaController extends Controller
{
    //
    public function update(Request $request,$idExperiencia){
        $experiencia=Experiencia::find($idExperiencia);
        return DB::transaction(function () use ($experiencia, $request) {
            $experiencia->fecha_fin=$request->get('fecha_fin');
            $experiencia->save();
        });
    }
}