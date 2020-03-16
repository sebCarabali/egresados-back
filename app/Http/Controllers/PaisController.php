<?php

namespace App\Http\Controllers;

use App\Http\Resources\PaisResource;
use App\Pais;

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

        return response()->json(($response ? $response->departamentos : null));
    }

    public function getAll()
    {
        return response()->json(Pais::all(), 200);
    }

    public function findAll()
    {
        return $this->success(PaisResource::collection(Pais::all()));
    }
}
