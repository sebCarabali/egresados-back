<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class CarnetizaciónResource extends Resource
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
            'estado'=>$this->estado,
            'estado_completar'=>$this->estado_completar,
            'estado_solicitud'=>$this->estado_solicitud,
        ];
    }

}