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
}
