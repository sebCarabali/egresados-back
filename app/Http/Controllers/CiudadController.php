<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CiudadController extends Controller
{
    public function getByDepartamento($idDepartamento)
    {
        $ciudades = DB::table('ciudades')->
                where('id_departamento', $idDepartamento)->get();
        return response()->json($ciudades, 200);
    }
}
