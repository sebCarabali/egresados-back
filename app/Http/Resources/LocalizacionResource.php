<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Resources;

use \Illuminate\Http\Resources\Json\Resource;
/**
 * Description of LocalizacionResource
 *
 * @author sebastian
 */
class LocalizacionResource extends Resource {
    //put your code here
    public function toArray($request) {
        return [
            'id' => $this->id_aut_localizacion,
            'ciudad' => new CiudadResource($this->ciudad()->first()),
            'direccion' => $this->direccion
        ];
    }
}
