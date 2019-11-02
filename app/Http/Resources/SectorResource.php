<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class SectorResource extends Resource
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
            "Nombre" => $this->nombre,
            "subSectores" => SubSectorResource::collection($this->subSectores)
        ];
    }
}
