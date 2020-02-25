<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OfertaSoftware extends Model
{
    protected $table = 'ofertas_software';
    protected $primaryKey = 'id_software';
    public $timestamps = false;

    protected $fillable = [
        'id_oferta', 'nombre', 'nivel'
    ];

    public function oferta()
    {
        return $this->belongsTo(Oferta::class, 'id_oferta' , 'id_aut_oferta');
    }
}
