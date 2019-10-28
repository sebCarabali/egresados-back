<?php

namespace App\Http\Controllers;

use App\Pais;
use Illuminate\Http\Request;

class PaisController extends Controller
{
    public function getAllCountries()
    {
        $response = Pais::all() ?: null;
        return response()->json($response);
    }
    public function getAllDepartments($pais)
    {

        $response = Pais::find($pais);
        return response()->json(($response ? $response->departamentos: null));
    }
}
