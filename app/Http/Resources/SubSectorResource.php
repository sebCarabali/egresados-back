<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class SubSectorResource extends Resource
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
            "idSector" => $this->id_aut_sub_sector,
            "Nombre" => $this->nombre
        ];
    }
}
