<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Idioma extends Model
{
    protected $table = "idiomas";
    protected $primaryKey = "id_aut_idioma";

    public function ofertas() {
        return $this->belongsToMany(Oferta::class, 'ofertas_idiomas')
        ->withPivot(['id_oferta', 'id_idioma', 'nivel_escritura', 'nivel_lectura', 'nivel_conversacion'])
        ->withTimestamps();
    }
}
