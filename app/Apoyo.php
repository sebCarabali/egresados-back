<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Apoyo extends Model
{
    use Notifiable;

    public $timestamps = false;
    protected $table = 'apoyos';
    protected $primaryKey = 'id_aut_apoyo';
    protected $guarded = ['id_aut_apoyo'];

    public function routeNotificationForMail()
    {
        return $this->correo;
    }

    public function servicios()
    {
        return $this->belongsToMany('App\Servicio', 'acceso', 'id_apoyo', 'id_servicio');
    }

    public function usuario()
    {
        return $this->belongsTo('App\User', 'id_user');
    }
}
