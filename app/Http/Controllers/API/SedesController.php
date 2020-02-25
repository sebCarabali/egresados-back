<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Sede;

class SedesController extends Controller
{
    public function getAll() {
        $sedes = Sede::all();
        return response()->json($sedes, 200);
    }
}
