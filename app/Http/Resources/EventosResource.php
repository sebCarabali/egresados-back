<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class EventosResource extends Resource
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
            'id' => $this->id_aut_evento,
            'nombre' => $this->nombre,
            'fechaInicio' => $this->getDate($this->fecha_inicio),
            'fechaFin' => $this->getDate($this->fecha_fin),
            'lugar' => $this->lugar,
            'descripcion' => $this->descripcion,
            'cupos' => $this->cupos,
            'dirigidoA' => $this->a_quien_va_dirigida,
            'imagePath' => $this->imagen,
            'horaInicio' => substr($this->hora_inicio, 0, 5),
            'horaFin' => substr($this->hora_fin, 0, 5),
        ];
    }

    private function getDate($date)
    {
        $dateArray = explode('-', $date);

        return $dateArray[2].'/'.$dateArray[1].'/'.$dateArray[0];
    }
}
