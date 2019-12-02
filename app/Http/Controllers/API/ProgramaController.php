<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProgramaResource;
use App\Programa;
use Exception;
use Illuminate\Support\Facades\DB;

class ProgramaController extends Controller
{
    public function getByFacultad($idFacultad)
    {
        $programas = DB::table('programas')
                ->where('id_facultad', $idFacultad)->get();
        return response()->json($programas, 200);
    }
    public function getByNivelPrograma($idNivelPrograma)
    {
        return ProgramaResource::collection(Programa::whereIdNivelprogra($idNivelPrograma)->get());
    }

    public function getAll()
    {
        return ProgramaResource::collection(Programa::all());
    }

    public function getBySedeAndFacultadAndNivelEstudio($idSede, $idFacultad, $idNivelEstudio) {
        try {
            $programas = DB::table('programas')
                ->where('id_sede', $idSede)
                ->where('id_facultad', $idFacultad)
                ->where('id_nivelestudio', $idNivelEstudio)
                ->get();
            return response()->json($programas, 200);
        } catch(Exception $e) {
            return response()->json(['error' => 'Error obteniendo programas por sede y facultad'], 400);
        }
        
    }
}
