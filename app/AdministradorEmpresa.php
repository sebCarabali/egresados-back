<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdministradorEmpresa extends Model
{
    protected $table = 'administrador_empresa';
    protected $primaryKey = 'id_aut_administrador_empresa';
    public $timestamps = false;

    public function direccion()
    {
        return $this->belongsTo('App\Localizacion', 'id_direccion', 'id_aut_localizacion');
    }

    public function empresa()
    {
        return $this->belongsTo('App\Empresa', 'id_empresa', 'id_aut_empresa');
    }

    public function cargo()
    {
        return $this->belongsTo('App\Cargo', 'id_cargo', 'id_aut_cargo');
    }

    public function user()
    {
        return $this->hasOne('App\User', 'id_aut_user', 'id_aut_user');
    }
}
