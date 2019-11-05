<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Salario extends Model
{
    protected $table = 'salarios';
    protected $primaryKey = 'id_aut_salario';
    public $timestamps = false;

    protected $fillable = [
        "minimo", "maximo", "forma_pago"
    ];
    public function oferta()
    {
        return $this->hasOne('App\Oferta', 'id_contrato', 'id_aut_contrato');
    }
}
