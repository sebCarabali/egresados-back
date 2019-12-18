<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\TipoObservacion;

class TipoObservacionController extends Controller
{
    //
    public function getCuestionario(){
        return response()->json(TipoObservacion::all(), 200);       
    }
    
    public function getObservaciones()
    {
        return $this->success(\App\Http\Resources\TipoObservacionResource::collection(TipoObservacion::all()));
    }
}
