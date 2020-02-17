<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Carnetizacion extends Model
{
    protected $table = 'carnetizacion';
    protected $primaryKey = 'id_aut_carnetizacion';
    protected $fillable = ['nombres'];
    public $timestamps = false;


    public function egresados()
    {
        return $this->belongsTo('App\Egresado','id_egresado');
    }
}
