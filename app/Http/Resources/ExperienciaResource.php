<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class ExperienciaResource extends Resource {

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request) {
        return [
            'id_experiencia' => $this->id_aut_experiencia,
            'cargo' => new CargoResource($this->cargos()->first()),
            'nombreEmpresa' => $this->nombre_empresa,
            'sector' => $this->sector
        ];
    }

}
