<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProgramaResource;
use App\Programa;
use Illuminate\Support\Facades\DB;

class ProgramaController extends Controller
{
    public function getByFacultad($idFacultad)
    {
        $programas = DB::table('programas')
                ->where('id_facultad', $idFacultad)->get();
        return response()->json($programas, 200);
    }

    public function getAll()
    {
        return ProgramaResource::collection(Programa::all());
    }
}
