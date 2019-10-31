<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    protected $table = 'empresas';
    public $timestamps = false;
    protected $primaryKey = 'id_aut_empresa';
    protected $guarded = ['id_aut_empresa'];


    public function direccion()
    {
        // return $this->belongsTo(\App\Localizacion::class, 'id_direccion', 'id');
        return $this->belongsTo(\App\Localizacion::class, 'id_direccion', 'id_aut_localizacion');
    }

    public function user()
    {
        return $this->belongsTo(\App\User::class, 'id_user', 'id');
        // return $this->belongsTo(\App\User::class, 'id_aut_user', 'id_aut_user');
    }

    public function ofertas()
    {
        return $this->hasMany(\App\Oferta::class, 'id_empresa', 'id_aut_empresa');
    }

    public function sectores()
    {
        return $this->belongsToMany('App\Sector', 'empresas_sectores', 'id_empresa', 'id_sector');
    }

    public function representante()
    {
        return $this->hasMany(\App\RepresentanteEmpresa::class, 'id_empresa', 'id_aut_empresa');
    }

    public function administrador()
    {
        return $this->hasMany(\App\AdministradorEmpresa::class, 'id_empresa', 'id_aut_empresa');
    }
}
