<?php

namespace App\Http\Controllers;

use App\Departamento;
use Illuminate\Http\Request;

class DepartamentoController extends Controller
{
    public function getAllDepartments()
    {
        $response = Departamento::all() ?: null;
        return response($response);
    }

    public function getAllCitiesDepartment($dep)
    {
        $response = \App\Departamento::find($dep) ?: null;
        if ($response) {
            return response()->json($response->ciudades, 200);
        }
        return response()->json($response, 200);
    }
}
