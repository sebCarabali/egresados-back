<?php

namespace App\Http\Controllers;

use App\Cargo;
use App\Http\Resources\CargoResource;
use Illuminate\Http\Request;

class CargoController extends Controller
{
    public function getAll()
    {
        return CargoResource::collection(Cargo::whereEstado(true)->get());
    }
}
