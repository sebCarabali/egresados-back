<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Helpers;

use App\Evento;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

/**
 * Description of BusquedaEventos
 *
 * @author sebastian
 */
class BusquedaEventos {

    /**
     * Retorna una lista de eventos por fecha y lugar 
     * @param Request $filters
     */
    public static function apply(Request $filters) {
        //DB::enableQueryLog(); // Enable query log
        $eventos = Evento::query();
        
        if ($filters->has('fecha') && $filters->get('fecha') !== '') {
            $eventos = $eventos->where('fecha_inicio', date('Y-m-d', strtotime($filters->get('fecha'))));
            //$eventos = $eventos->where('fecha_fin', '>=', date('Y-m-d', strtotime($filters->get('fecha'))));
        }

        if ($filters->has('lugar')) {
            $lugarFilter = mb_strtoupper($filters->get('lugar'));
            $eventos = $eventos->where(DB::raw("upper(lugar)"), 'like', "%$lugarFilter%");
        }
        //$eventos->get();
        //return response()->json(['query' => dd(DB::getQueryLog())], 200);
        return $eventos->get();
    }

}
