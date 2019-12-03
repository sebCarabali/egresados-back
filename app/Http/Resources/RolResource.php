<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class RolResource extends Resource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'idRol' => $this->id_aut_rol,
            'nombre' => $this->nombre
        ];
    }
}
