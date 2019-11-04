<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class IdiomaResource extends Resource
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
            "idIdioma" => $this->id_aut_idioma,
            "Nombre" => $this->nombre,
        ];
    }
}
