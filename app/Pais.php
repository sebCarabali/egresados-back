<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pais extends Model
{
    protected $table = 'pais';
    protected $primaryKey = 'id_aut_pais';
    protected $fillable = ['nombre'];
    public $timestamps = false;

    public function departamentos() {
      return $this->hasMany('App\Departamento');
    }
}
