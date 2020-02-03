<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Resources;
use \Illuminate\Http\Resources\Json\Resource;
/**
 * Description of DepartamentoResource
 *
 * @author sebastian
 */
class DepartamentoResource extends Resource {
    public function toArray($request) {
        return [
           'id' => $this->id_aut_dep,
            'nombre' => $this->nombre,
            'pais' => new PaisResource($this->pais()->first())
        ];
    }
}
