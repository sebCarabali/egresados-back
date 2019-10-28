<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Facultad;

class FacultadController extends Controller
{
    public function getAll()
    {
        return response()->json(Facultad::all(), 200);       
    }
}
