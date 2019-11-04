<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Referido extends Model
{
    protected $table = 'referido';
    protected $primaryKey = 'id_referido';
    protected $fillable = ['nombres','apellidos','telefono_movil','correo','parentesco','es_egresado'];
    public $timestamps = false;

    public function egresados() {
        return $this->belognsToMany('App\Egresado', 'referidos_egresados', 'id_referidos','id_egresados');
    }

    public function niveles_estudio() {
        return $this->belongsTo('App\NivelEstudio');
    }
    
    public function programa(){
        return $this->belongTo('App\Programa');
    }
}
