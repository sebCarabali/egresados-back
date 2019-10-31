<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sector extends Model
{
    protected $table = "ofertas.sectores";

    protected $primaryKey = "id_aut_sectore";

    public function empresas()
    {
        return $this->belongsToMany('App\Empresa', 'empresas_sectores', 'id_sector', 'id_empresa');
    }
}
