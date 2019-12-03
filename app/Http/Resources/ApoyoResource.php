<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class ApoyoResource extends Resource
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
            'id' => $this->id_aut_apoyo,
            'nombres' => $this->nombres,
            'apellidos' => $this->apellidos,
            'correo' => $this->correo,
            'nombreRol' => $this->nombre_rol,
            'correoSecundario' => $this->correo_secundario,
            'usuario' => new UserResource($this->usuario()->first()),
            'servicios' => ServicioResource::collection($this->servicios()->get())
        ];
    }
}
