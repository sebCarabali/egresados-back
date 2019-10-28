<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Localizacion extends Model
{
    protected $table = 'localizacion';
    protected $fillable = ['codigo_postal', 'direccion', 'barrio', 'id_ciudad'];
    public $timestamps = false;

    protected $primaryKey = 'id_aut_localizacion';

    public function ciudad() {
      return $this->belongsTo('App\Ciudad', 'id_ciudad');
    }

    public function egresados() {
      return $this->hasMany('App\Egresado');
    }

    public function falcultades() {
      return $this->hasMany('App\Facultad');
    }

    public function representanteEmpresa()
    {
        return $this->hasMany(\App\RepresentanteEmpresa, 'id_direccion', 'id');
        // return $this->hasMany(\App\RepresentanteEmpresa, 'id_direccion', 'id_aut_localizacion');
    }

    public function empresas()
    {
        // return $this->hasMany(\App\Empresa, 'id_direccion', 'id');
        return $this->hasMany(\App\Empresa, 'id_direccion', 'id_aut_localizacion');
    }
}
