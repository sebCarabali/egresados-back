<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class ConfirmacionCorreo extends Model
{
    use Notifiable;

    public $timestamps = false;
    protected $table = 'confirmacion_correo';
    protected $guarded = ['id_aut_confirmacion_correo'];
    protected $primaryKey = 'id_aut_confirmacion_correo';

    public function routeNotificationForMail()
    {
        return $this->nuevo_correo;
    }

    public function apoyo()
    {
        return $this->belongsTo(Apoyo::class, 'id_apoyos_fk');
    }
}
