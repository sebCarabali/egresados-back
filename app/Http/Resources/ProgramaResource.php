<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class ProgramaResource extends Resource
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
            "idPrograma" => $this->id_aut_programa,
            "Nombre" => $this->nombre,
            'sede' => $this->sede()->first(),
            'facultad' => $this->facultad()->first(),
            'titulo' => DB::table('titulo')-select('nombre')->where('id_aut_titulo', $this->id_titulo)->first()
        ];
    }
}
