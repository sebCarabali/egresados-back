<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Departamento extends Model
{
    protected $table = 'departamentos';
    protected $guarded = ['id'];
    public $timestamps = false;

    public function ciudades() {
      return $this->hasMany('App\Ciudad','id_departamento', 'id');
    }

    public function pais() {
      return $this->belongsTo('App\Pais');
    }
}
