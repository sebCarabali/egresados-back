<?php

namespace App\Http\Controllers;

use App\Http\Resources\IdiomaResource;
use App\Idioma;
use Illuminate\Http\Request;

class IdiomaController extends Controller
{
    public function getAll()
    {
        return IdiomaResource::collection(Idioma::all());
    }
}
