<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class TituloResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id_aut_titulo,
            'nombre' => $this->nombre,
        ];
    }
}
