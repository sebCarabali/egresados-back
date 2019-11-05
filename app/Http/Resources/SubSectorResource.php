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
            "idSubSector" => $this->id_aut_sub_sector,
            "Nombre" => $this->nombre,
            "idSector" => $this->id_sectores
        ];
    }
}
