<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class ContactoHV extends Model
{

    use Notifiable;

    protected $table = 'recepcion_hv';
    protected $primaryKey = 'id_aut_recepcionhv';
    public $timestamps = false;

    protected $fillable = [
        "correo",
        "nombres",
        "apellidos",
        "telefono_movil"
    ];

    public function routeNotificationForMail()
    {
        return $this->correo;
    }
    public function oferta()
    {
        return $this->belongsTo(Oferta::class, 'id_oferta', 'id_aut_oferta');
    }
}
