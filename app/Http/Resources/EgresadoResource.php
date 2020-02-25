<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class EgresadoResource extends Resource
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
            "idEgresado" => $this->id_aut_egresado,
            "nombres" => $this->nombres,
            "apellidos" => $this->apellidos,
            "correo" => $this->correo,
            "celular" => $this->celular,
            "nivelEducativo"=> $this->nivelEducativo->nombre,
            "estado" => $this->pivot->estado
            
        ];
        // return parent::toArray($request);
    }
}
