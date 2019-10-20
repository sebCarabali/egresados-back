<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pais extends Model
{
    protected $table = 'ofertas.pais';
    protected $fillable = ['nombre'];
    public $timestamps = false;

    public function departamentos() {
      return $this->hasMany('App\Departamento');
    }
}
