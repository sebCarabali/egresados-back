<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Localizacion extends Model
{
    protected $table = 'ofertas.localizacion';
    protected $fillable = ['codigo_postal', 'direccion', 'barrio', 'id_ciudad'];
    public $timestamps = false;

    public function ciudad() {
      return $this->belongsTo('App\Ciudad', 'id_ciudad');
    }

    public function egresados() {
      return $this->hasMany('App\Egresado');
    }

    public function falcultades() {
      return $this->hasMany('App\Facultad');
    }
}
