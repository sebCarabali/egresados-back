<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Resources;
use \Illuminate\Http\Resources\Json\Resource;

/**
 * Description of EgresadoAdminResource
 *
 * @author sebastian
 */
class EgresadoAdminResource extends Resource {
    public function toArray($request) {
        return [
            'id' => $this->id_aut_egresado,
            'nombres' => $this->nombres,
            'apellidos' => $this->apellidos,
            'identificacion' => $this->identificacion,
            'estado' => $this->estado,
            'grupoEtnico' => $this->grupo_etnico,
            'genero' => $this->genero,
            'correo' => $this->correo,
            'correoAlternativo' => $this->correo_alternativo,
            'estadoCivil' => $this->estado_civil,
            'celular' => $this->celular,
            'grados' => GradosResource::collection(\App\Grado::where('id_egresado', $this->id_aut_egresado)->get()),
            'referenciasPersonales' => 'Load referencias',
            'trabajoActual' => 'Load trabajo actual',
            'telefonoFijo' => $this->telefono_fijo,
            'lugarNacimiento' => new CiudadResource($this->ciudadNacimiento()->first()),
            'lugarResidencia' => new LocalizacionResource($this->lugarResidencia()->first())
        ];
    }
}
