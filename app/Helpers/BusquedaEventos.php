<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Helpers;

use App\Eventos;
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
        $eventos = (new Eventos)->newQuery();

        if ($filters->has('fecha')) {
            $eventos->where('fecha_inicio', $filters->get('fecha'));
        }

        if ($filters->has('lugar')) {
            $eventos->where('lugar', 'like', '%' . $filters->get('lugar') . '%');
        }
        
        return $eventos->get();
    }

}
