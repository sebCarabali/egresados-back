<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Referido extends Model
{
    protected $table = 'referidos';
    protected $primaryKey = 'id_aut_referido';
    protected $fillable = ['nombres','apellidos','telefono_movil','correo','parentesco','es_egresado'];
    public $timestamps = false;

    public function egresados() {
        return $this->belongsToMany('App\Egresado', 'referidos_egresados', 'id_referidos','id_egresados');
    }

    public function niveles_estudio() {
        return $this->belongsTo('App\NivelEstudio','id_nivel_educativo');
    }
    
    public function programa(){
        return $this->belongsTo('App\Programa','id_aut_programa');
    }
}
