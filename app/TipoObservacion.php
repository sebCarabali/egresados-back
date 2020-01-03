<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoObservacion extends Model
{

    protected $table = "tipo_de_observacion";
    protected $primaryKey = "id_aut_comentario";
    public $timestamps = false;  

    public function grado() {
        return $this->belognsToMany('App\Grado', 'comenta', 'id_comentario', 'id_grado');
    }
    
}
