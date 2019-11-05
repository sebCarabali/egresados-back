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
        return $this->belongsTo('App\Localizacion', 'id_direccion', 'id_aut_localizacion');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'id_user', 'id');
    }

    public function ofertas()
    {
        return $this->hasMany('App\Oferta', 'id_empresa', 'id_aut_empresa');
    }

    public function subSectores()
    {
        return $this->belongsToMany('App\SubSector', 'empresas_sectores', 'id_empresa', 'id_sub_sector');
    }

    public function representante()
    {
        return $this->hasOne('App\RepresentanteEmpresa', 'id_empresa', 'id_aut_empresa');
    }

    public function administrador()
    {
        return $this->hasOne('App\AdministradorEmpresa', 'id_empresa', 'id_aut_empresa');
    }
}
