<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class SectorSubsectoresResource extends Resource
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
            "idSector" => $this->id_aut_sector,
            "Nombre" => $this->nombre,
            "subSectores" => SubSectorResource::collection($this->subSectores)
        ];
    }
}
