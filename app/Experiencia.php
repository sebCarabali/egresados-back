<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Experiencia extends Model
{
    protected $table = 'experiencia';
    protected $primaryKey = 'id_experiencia';
    protected $fillable = ['nombre_jefe','telefono_jefe','correo_jefe','nombre_empresa','dir_empresa','tel_trabajo','rango_salario','tipo_contrato','trabajo_en_su_area','sector'];
    public $timestamps = false;

    public function egresados() {
        return $this->belongTo('App\Egresado');
    }
    public function cargos() {
        return $this->belongTo('App\Cargo');
    }
}
