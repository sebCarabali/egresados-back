<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class ReferidoResource extends Resource
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
            'id' => $this->id_aut_referido,
            'nombres' => $this->nombres,
            'correo' => $this->correo,
            'parentesco' => $this->parentesco,
            'telefonoMovil' => $this->telefono_movil,
            'esEgresado' => $this->es_egresado,
            'programa' => new ProgramaResource($this->programa()->first())
        ];
    }
}
