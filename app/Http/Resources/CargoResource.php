<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class CargoResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "idCargo" => $this->id_aut_cargos,
            "Nombre" => $this->nombre
        ];
    }
}
