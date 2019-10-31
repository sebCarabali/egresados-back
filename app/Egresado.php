<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Egresado extends Model
{
    protected $table = 'egresados';
    protected $guraded = ['id'];
    public $timestamps = false;

    public function ciudadNacimiento()
    {
      return $this->belognsTo('App\Ciudad', 'id_ciudad_nacimiento');
    }

    public function nivelEducativo()
    {
      return $this->belognsTo('App\NivelEstudio', 'id_nivel_educativo');
    }

    public function lugarResidencia(){
      return $this->belongsTo('App\Localizacion', 'id_lugar_residencia');
    }

    public function lugarExpedicion() {
      return $this->belongsTo('App\Ciudad', 'id_lugar_expedicion');
    }

    public function programas() {
      return $this->belognsToMany('App\Programa', 'grados', 'id_estudiante', 'id_programa');
        /*->withPivot('tipo', 'mension_honor', 'titulo_especial', 'comentarios', 'fecha_graduacion',
                    'docente_influencia');*/
    }
}
