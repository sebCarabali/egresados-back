<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Departamento extends Model
{
    protected $table = 'departamentos';
    protected $primaryKey = 'id_aut_dep';
    protected $guarded = ['id_aut_dep'];
    public $timestamps = false;

    public function ciudades()
    {
      return $this->hasMany('App\Ciudad', 'id_departamento', 'id_aut_dep');
    }

    public function pais()
    {
      return $this->belongsTo('App\Pais', 'id_pais_fk');
    }
}
