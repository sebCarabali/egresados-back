<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class EventosResource extends Resource
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
            'id' => $this->id_aut_evento,
            'nombre' => $this->nombre,
            'fechaInicio' => $this->fecha_inicio,
            'fechaFin' => $this->fecha_fin,
            'lugar' => $this->lugar,
            'descripcion' => $this->descripcion,
            'cupos' => $this->cupos,
            'dirigidoA' => $this->a_quien_va_dirigida,
            'imagePath' => storage_path('storage/eventos/' . $this->imagen)
        ];
    }
}
