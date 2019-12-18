<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class GradosResource extends Resource {

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request) {
        //return dd($this);
        return [
            'id' => $this->id_aut_grado,
            'mencion' => $this->mencion_honor,
            'estado' => $this->estado,
            'fechaGrado' => $this->fecha_graduacion,
            'programa' => new ProgramaResource($this->programa()->first())
        ];
    }

}
