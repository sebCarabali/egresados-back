<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CategoriaCargo extends Model
{
    protected $table = 'categoria';

    public $timestamps = false;

    protected $fillable = [
        'id_aut_categoria', 'nombre'
    ];

    public function cargos()
    {
        return $this->hasMany(\App\Cargo::class, 'id_categoria', 'id');
    }
}
