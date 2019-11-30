<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Discapacidad extends Model
{
    protected $table = "discapacidades";
    protected $primaryKey = "id_aut_discapacidades";
    public $timestamps = false;
    
    public function egresados() {
        return $this->belongsToMany('App\Egresado', 'egresados_discapacidades', 'id_discapacidad','id_egresados');
    }

    
}
