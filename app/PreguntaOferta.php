<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PreguntaOferta extends Model
{
    protected $table = 'preguntas_oferta';
    protected $primaryKey = 'id_aut_pregunta';
    public $timestamps = false;

    protected $fillable = [
        'pregunta'
    ];
    public function oferta()
    {
        return $this->hasOne(Oferta::class, 'id_contrato', 'id_aut_contrato');
    }
}
