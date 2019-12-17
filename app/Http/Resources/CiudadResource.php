<?php

namespace App\Http\Resources;

use \Illuminate\Http\Resources\Json\Resource;

class CiudadResource extends Resource {

    public function toArray($request) {
        return [
            'id' => $this->id_aut_ciudad,
            'nombre' => $this->nombre,
            'departamento' => new DepartamentoResource($this->departamento()->first())
        ];
    }

}
