<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Apoyo extends Model
{
    protected $table = 'apoyos';
    protected $primaryKey = 'id_aut_apoyo';
    protected $guarded = ['id_aut_apoyo'];
    public $timestamps = false;

    public function servicios()
    {
        return $this->belongsToMany('App\Servicio', 'acceso', 'id_apoyo', 'id_servicio');
    }

    public function usuario() {
        return $this->belongsTo('App\User', 'id_user');
    }
}
