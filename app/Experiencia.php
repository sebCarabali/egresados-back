<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Experiencia extends Model
{
    protected $table = 'experiencia';
    protected $primaryKey = 'id_aut_exp';
    protected $fillable = ['nombre_empresa','dir_empresa','tel_trabajo','rango_salario','tipo_contrato','trabajo_en_su_area','sector'];
    public $timestamps = false;

    public function egresados() {
        return $this->belongsTo('App\Egresado','id_egresado');
    }
    public function cargos() {
        return $this->belongsTo('App\Cargo','id_cargo');
    }

    public function ciudad(){
        return $this->belongsTo('App\Ciudad', 'id_ciudad', 'id_aut_ciudad');
    }
}
