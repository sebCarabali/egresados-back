<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NivelEstudio extends Model
{
    protected $table = 'niveles_estudio';
    protected $primaryKey = 'id_aut_estudio';
    public $timestamps = false;

 public function referidos()
 {
     return $this->hasMany('App\Referido', 'id_nivel_estudio');
 }
}
