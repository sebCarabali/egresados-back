<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RepresentanteEmpresa extends Model
{
    //
    protected $table = 'representante_empresa';
    protected $primaryKey = 'id_aut_representante_empresa';
    public $timestamps = false;

    public function empresa()
    {
        return $this->belongsTo('App\Empresa', 'id_empresa', 'id_aut_empresa');
    }

}
