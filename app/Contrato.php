<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contrato extends Model
{
    protected $table = 'contratos';
    protected $primaryKey = 'id_aut_contrato';
    public $timestamps = false;

    protected $fillable = [
        'duracion', 'tipo_contrato', 'jornada_laboral', 'horario',
        'mostrar_salario', 'comentarios_salario'
    ];

    public function oferta()
    {
        return $this->belongsTo(Oferta::class, 'id_oferta', 'id_aut_oferta');
    }

}
