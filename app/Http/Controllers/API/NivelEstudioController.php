<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\NivelEstudio;

class NivelEstudioController extends Controller
{
    public function getAll()
    {
        return response()->json(NivelEstudio::all(), 200);
    }

    public function getAllU()
    {
        return response()->json(NivelEstudio::where('pertenece_u',true)->get(), 200);
    }
}
