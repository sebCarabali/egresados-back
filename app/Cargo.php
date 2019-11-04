<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cargo extends Model
{
    protected $table = 'cargos';

    public $timestamps = false;

    protected $fillable = [
        'id','nombre', 'id_categoria'
    ];

    public function categoria()
    {
        return $this->belongsTo(\App\CategoriaCargo::class, 'id_categoria', 'id');
    }

    public function representantesEmpresas()
    {
        return $this->hasMany(\App\RepresentanteEmpresa::class, 'id_cargo', 'id');
    }

    public function experiencia(){
        return $this->hasMany('App\Experiencia', 'id_cargo');
    }
}
