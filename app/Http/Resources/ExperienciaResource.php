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
            'id' => $this->id_aut_experiencia,
            'cargo' => new CargoResource($this->cargos()->first()),
            'nombreEmpresa' => $this->nombre_empresa,
            'sector' => $this->sector,
            'fechaInicio' => $this->fecha_inicio,
            'tipoContrato' => $this->tipo_contrato,
            'ciudad' => new CiudadResource($this->ciudad()->first()),
            'direccionEmpresa' => $this->dir_empresa,
            'rangoSalario' => $this->rango_salario,
            'telefonoTrabajo' => $this->tel_trabajo,
            'trabajaEnSuArea' => $this->trabajo_en_su_area
        ];
    }

}
