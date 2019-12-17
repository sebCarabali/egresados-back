<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Grado extends Model {

    protected $table = 'grados';
    protected $primaryKey = 'id_aut_grado';
    protected $guarded = ['id_aut_grado'];

    public function programa() {
        return $this->belongsTo('App\Programa', 'id_programa');
    }

    public function tipoObservacion() {
        return $this->belongsToMany('App\TipoObservacion', 'comenta', 'id_grado', 'id_comentario');
    }

}
