<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;
use \Illuminate\Support\Facades\DB;

class ComentaResource extends Resource {

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request) {
        return [
            'respuesta' => $this->respuesta,
            'pregunta' => $this->getPregunta()
        ];
    }

    private function getPregunta() {
        return DB::table('tipo_de_observacion')
                ->where('id_aut_comentario', $this->id_comentario)->first()->pregunta;
    }

}
