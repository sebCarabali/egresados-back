<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Facultad;
use Exception;
use Illuminate\Support\Facades\DB;

class FacultadController extends Controller
{
    public function getAll()
    {
        return response()->json(Facultad::all(), 200);       
    }

    public function getBySede($idSede) {
        try {
            $facultades = DB::table('facultades')
                ->join('programas', function($join) {
                    $join->on('programas.id_facultad', '=', 'facultades.id_aut_facultad');
                })->select('facultades.nombre', 'facultades.id_aut_facultad')
                ->distinct()->get();
            return response()->json($facultades, 200);
        } catch(Exception $e) {
            return response()->json([
                'error' => 'Error cargando las facultades por sede',
                'detailed_error' => $e
            ], 400);    
        }
    }
}
