<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ciudad extends Model
{
    protected $table = 'ofertas.ciudades';
    protected $primaryKey = 'id_aut_ciudad';
    protected $guarded = ['id'];
    public $timestamps = false;

    public function nacimientos() {
      return $this->hasMany('App\Nacimiento');
    }

    public function departamento()
    {
        return $this->belongsTo('App\Departamento', 'id_departamento');
    }
}
