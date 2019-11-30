<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Facultad extends Model
{
    protected $table = 'facultades';
    protected $fillable = ['nombre', 'acronimo', 'id_direccion'];
    public $timestamps = false;

    public function localizacion() {
      return $this->belongsTo('App\Localicacion', 'id_direccion');
    }

    public function programas() {
      return $this->hasMany('App\Programa');
    }
}
