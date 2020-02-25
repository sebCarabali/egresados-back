<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Resources;

use App\Carnetizacion;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\DB;

/**
 * Description of EgresadoAdminResource.
 *
 * @author sebastian
 */
class EgresadoAdminResource extends Resource
{
    public function toArray($request)
    {
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
            'referenciasPersonales' => $this->getReferidos(),
            'trabajosActuales' => $this->getTrabajoActual(),
            'telefonoFijo' => $this->telefono_fijo,
            'solicitudes' => $this->getSolicitudesCarnetizacion(),
            'lugarNacimiento' => new CiudadResource($this->ciudadNacimiento()->first()),
            'lugarResidencia' => new LocalizacionResource($this->lugarResidencia()->first()),
        ];
    }

    private function getReferidos()
    {
        return ReferidoResource::collection(\App\Referido::whereIn('id_aut_referido', DB::table('referidos_egresados')->where('id_egresados', $this->id_aut_egresado)->pluck('id_referidos')->toArray())->get());
    }

    private function getTrabajoActual()
    {
        return ExperienciaResource::collection(\App\Experiencia::where('id_egresado', $this->id_aut_egresado)
            ->whereNull('fecha_fin')->get());
    }

    private function getSolicitudesCarnetizacion()
    {
        return SolicitudCarnetResource::collection(Carnetizacion::where('id_egresado', $this->id_aut_egresado)->get());
    }
}
