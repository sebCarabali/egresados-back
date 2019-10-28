<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pais extends Model
{
    protected $table = 'pais';
    protected $primaryKey = 'id_pais';
    protected $fillable = ['nombre'];
    public $timestamps = false;

    public function departamentos() {
      return $this->hasMany('App\Departamento', 'id_pais_fk', 'id_pais');
    }
}
