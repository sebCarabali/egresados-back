<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Helpers;


use Illuminate\Http\Request;
use \App\Programa;
use Illuminate\Support\Facades\DB;
/**
 * Description of BusquedaEgresados
 *
 * @author sebastian
 */
class BusquedaEgresados {

    public static function aplicarFiltros(Request $request) {
        $egresados = \App\Egresado::query();

        if ($request->has('cedula')) {
            $egresados = $egresados->where('identificacion', 'like', "%$request->get('cedula')%");
        }

        if ($request->has('nombreCompleto')) {
            $egresados = $egresados->where(DB::raw('concat(nombres, apellidos)'), 'like', "%$request->get('nombreCompleto')%");
        }

        if ($request->has('programa')) {
            $idEgresadosPorPrograma = Programa::select('grados.id_estudiante')
                    ->join('grados', function($join) {
                       $join->on('grados.id_programa', '=', 'programas.id_aut_programa');
                    })->where(DB::raw('upper(nombre)'), 'like', "%$request->get('programa')%")
                            ->values();
            $egresados = $egresados->whereIn('id_aut_egresado', $idEgresadosPorPrograma);
        }
        
        if($request->has('titulo')) {
            // De donde se saca el título.
        }
        
        if($request->has('estado')) {
            $egresados = $egresados->where('estado', $request->get('estado'));
        }

        return $egresados->get();
    }

}
