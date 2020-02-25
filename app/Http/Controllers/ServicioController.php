<?php

namespace App\Http\Controllers;

use App\Http\Resources\ServicioResource;
use App\Servicio;
use Illuminate\Http\Request;

class ServicioController extends Controller
{
    public function getAll() {
        return ServicioResource::collection(Servicio::all());
    }
}
