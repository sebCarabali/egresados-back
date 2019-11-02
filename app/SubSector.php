<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubSector extends Model
{
    protected $table = "sub_sectores";
    protected $primaryKey = "id_aut_sub_sector";
    public $timestamps = false;

    public function empresas()
    {
        return $this->belongsToMany('App\Empresa', 'empresas_sectores', 'id_sub_sector', 'id_empresa');
    }

    public function sector()
    {
        return $this->hasMany('App\Sector', 'id_sectores', 'id_aut_sub_sector');
    }
}
