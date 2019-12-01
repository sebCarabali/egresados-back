<?php

namespace App\Http\Controllers;

use App\Departamento;
use App\Ciudad;
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

    public function getAllCitiesWithDeparments($idPais)
    {
      $departamentos = Departamento::where('id_pais_fk', $idPais)->get();

      $departamentos->load('ciudades');

      foreach ($departamentos as $departamento) {
        unset($departamento['id_pais_fk']);

        $departamento['id_departamento'] = $departamento['id_aut_dep'];
        unset($departamento['id_aut_dep']);
        foreach ($departamento->ciudades as $ciudad) {
          $ciudad['id_ciudad'] = $ciudad['id_aut_ciudad'];
          unset($ciudad['id_aut_ciudad']);

          unset($ciudad['id_departamento']);
        }
      }
      // $ciudades = Ciudad::where('');

      return response()->json($departamentos, 200);
      // return response()->json($ciudades, 200);
    }
}
