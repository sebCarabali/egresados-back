<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class SolicitudCarnetResource extends Resource
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
            'fechaSolicitud' => $this->fecha_solicitud,
            'fechaRespuesta' => $this->fecha_respuesta,
            'estadoSolicitud' => $this->estado_solicitud,
        ];
    }
}
