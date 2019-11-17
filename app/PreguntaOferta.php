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
        return $this->belongsTo(Oferta::class, 'id_oferta', 'id_aut_oferta');
    }
}
