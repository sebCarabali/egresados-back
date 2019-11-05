<?php

namespace App;

use App\Http\Resources\SubSectorResource;
use Illuminate\Database\Eloquent\Model;

class Sector extends Model
{
    protected $table = "sectores";
    protected $primaryKey = "id_aut_sector";
    public $timestamps = false;
    protected $fillable = ['nombre'];


    public function subSectores()
    {
        return $this->hasMany(SubSector::class, 'id_sectores', 'id_aut_sector');
    }

}
