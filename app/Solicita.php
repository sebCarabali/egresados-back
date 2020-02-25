<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Solicita extends Model
{
    //
    protected $table = 'solicita';
    
    public function carnetizacion()
    {
        return $this->belongsTo('App\Carnetizacion', 'id_carnetizacion');
    }
}
