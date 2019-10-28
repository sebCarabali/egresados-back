<?php

namespace App\Http\Controllers;

use App\Departamento;
use Illuminate\Http\Request;

class DepartamentoController extends Controller
{
    public function getAllCitiesDepartment($dep)
    {
        $response = Departamento::find($dep);
        if ($response) {
            return response()->json($response->ciudades, 200);
        }
        return response()->json($response, 200);
    }
}
