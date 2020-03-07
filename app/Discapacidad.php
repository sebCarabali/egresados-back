<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Discapacidad extends Model
{
    protected $table = "discapacidades";
    protected $primaryKey = "id_aut_discapacidades";
    public $timestamps = false;
    
    public function egresados() {
        return $this->belongsToMany('App\Egresado', 'egresados_discapacidades', 'id_egresados','id_discapacidad');
    }
    public function ofertas() {
        return $this->belongsToMany(Oferta::class, 'ofertas_discapacidades', 'id_discapacidad','id_oferta');
    }    
}
