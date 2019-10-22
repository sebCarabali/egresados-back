<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NivelEducativo extends Model
{
    public $timestamps = false;
    protected $table = "niveles_estudio";
    protected $fillable = ['nombre'];

    public function egresados()
    {
        return $this->hasMany('App\Egresado', 'id_nivel_educativo', 'id');
    }
}
