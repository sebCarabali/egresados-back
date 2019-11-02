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

    public function ofertas()
    {
        return $this->hasMany('App\Oferta', 'id_contrato', 'id_aut_contrato');
    }
}
