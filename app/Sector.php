<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sector extends Model
{
    protected $table = "sectores";
    protected $primaryKey = "id_aut_sector";
    public $timestamps = false;

    public function subSectores()
    {
        return $this->belongsTo('App\SubSector', 'id_sectores', 'id_aut_sector');
    }
}
