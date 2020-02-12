<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AreaConocimiento extends Model
{
    protected $table = 'areas_conocimiento';
    protected $primaryKey = 'id_aut_areaconocimiento';
    public $timestamps = false;

    public function ofertas()
    {
        return $this->belongsToMany('App\Oferta', 'ofertas_areascon', 'id_areaconocimiento', 'id_aut_oferta');
    }

}
