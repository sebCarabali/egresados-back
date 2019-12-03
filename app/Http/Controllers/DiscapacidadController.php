<?php

namespace App\Http\Controllers;

use App\Discapacidad;
use App\Http\Resources\DiscapacidadResource;
use Illuminate\Http\Request;

class DiscapacidadController extends Controller
{
    public function getAll()
    {
        return DiscapacidadResource::collection(Discapacidad::all()); 
    }
}
