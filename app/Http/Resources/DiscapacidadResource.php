<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class DiscapacidadResource extends Resource
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
            "idDiscapacidad" => $this->id_aut_discapacidades,
            "Nombre" => $this->nombre
        ];
    }
}
