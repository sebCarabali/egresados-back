<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProgramaResource;
use App\Programa;
use Exception;
use Illuminate\Support\Facades\DB;

class ProgramaController extends Controller {

    public function getByFacultad($idFacultad) {
        $programas = DB::table('programas')
                        ->where('id_facultad', $idFacultad)->get();
        return response()->json($programas, 200);
    }
    
    public function getByNivelPrograma($idNivelPrograma)
    {
        return ProgramaResource::collection(Programa::whereIdNivelestudio($idNivelPrograma)->get());
    }

    public function getAll() {
        return ProgramaResource::collection(Programa::all());
    }

    public function getBySedeAndFacultadAndNivelEstudio($idSede, $idFacultad, $idNivelEstudio) {
        try {
            $idProgramas = DB::table('sede_programas')
                    ->select('id_programa')
                    ->where('id_sede', $idSede)->get()->toArray();

            $machetazo = array();
            foreach($idProgramas as $id){
                array_push($machetazo, $id->id_programa);
            }
            //$idProgramas=array_flatten($idProgramas);
            
                $programas = DB::table('programas')
                    ->whereIn('id_aut_programa',  $machetazo )
                    ->where('id_facultad', $idFacultad)
                    ->where('id_nivelestudio', $idNivelEstudio)->get();
                    return response()->json($programas, 200);
/*
            $programas = DB::table('programas')
                ->where('id_sede', $idSede)
                ->where('id_facultad', $idFacultad)
                ->where('id_nivelestudio', $idNivelEstudio)
                ->get();*/
            return response()->json($programas, 200);
        } catch(Exception $e) {
            return response()->json(['error'=>$e], 400);
        }
        /* try {
          $programas = DB::table('programas')
          ->where('id_sede', $idSede)
          ->where('id_facultad', $idFacultad)
          ->where('id_nivelestudio', $idNivelEstudio)
          ->get();
          return response()->json($programas, 200);
          } catch(Exception $e) {
          return response()->json(['error' => 'Error obteniendo programas por sede y facultad'], 400);
          } */
    }

}
