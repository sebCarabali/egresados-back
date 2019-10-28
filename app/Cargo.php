<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cargo extends Model
{
    protected $table = 'ofertas.cargos';

    protected $primaryKey = 'id_aut_cargo';

    public $timestamps = false;

    protected $fillable = [
        'nombre', 'id_categoria'
    ];

    public function categoria()
    {
        return $this->belongsTo(\App\CategoriaCargo::class, 'id_categoria', 'id_aut_cate_crago');
    }

    public function representantesEmpresas()
    {
        return $this->hasMany(\App\RepresentanteEmpresa::class, 'id_cargo', 'id');
    }
}
