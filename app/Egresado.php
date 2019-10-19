<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Egresado extends Model
{
    protected $table = 'egresados';
    protected $guraded = ['id'];
    public $timestamps = false;

    public function nacimiento()
    {
        return $this->belongsTo('App\Nacimiento');
    }

    public function lugarResidencia(){
      return $this->belongsTo('App\Localizacion');
    }

    public function lugarExpedicion() {
      return $this->belongsTo('App\Ciudad');
    }

    public function grados() {
      return $this->belognsToMany('App\Programa', 'grados', 'id_estudiante', 'id_programa')
        ->withPivot('tipo', 'mension_honor', 'titulo_especial', 'comentarios', 'fecha_graduacion',
                    'docente_influencia');
    }
}
