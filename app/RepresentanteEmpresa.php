<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RepresentanteEmpresa extends Model
{
    //
    protected $table = 'representante_empresa';
    protected $primaryKey = 'id_aut_administrador_empresa'; // Corregir
    public $timestamps = false;

    public function empresa()
    {
        return $this->belongsTo(\App\Empresa::class, 'id_empresa', 'id_aut_empresa');
        // return $this->belongsTo(\App\Empresa::class, 'id_empresa', 'id_aut_empresa');
    }

}
