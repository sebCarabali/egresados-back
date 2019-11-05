<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Egresado extends Model
{
    protected $table = 'egresados';
    //protected $guraded = ['id'];
    protected $primaryKey = 'id_aut_egresado';
    //protected $fillable = ['nombres'];


    public $timestamps = false;

    public function ciudadNacimiento()
    {
      return $this->belongsTo('App\Ciudad', 'id_lugar_nacimiento');
    }

    public function nivelEducativo()
    {
      return $this->belongsTo('App\NivelEstudio', 'id_nivel_educativo');
    }

    public function lugarResidencia(){
      return $this->belongsTo('App\Localizacion', 'id_lugar_residencia');
    }

    public function lugarExpedicion() {
      return $this->belongsTo('App\Ciudad', 'id_lugar_expedicion');
    }
    /*
    public function user()
    {
        return $this->belongsTo('App\User', 'foreign_key', 'other_key');
    }*/

    public function programas() {
      return $this->belongsToMany('App\Programa', 'grados', 'id_estudiante', 'id_programa');
        /*->withPivot('tipo', 'mension_honor', 'titulo_especial', 'comentarios', 'fecha_graduacion',
                    'docente_influencia');*/
    }

    public function referidos() {
      return $this->belognsToMany('App\Referido', 'referidos_egresados', 'id_egresados', 'id_referidos');       
    }

    public function experiencia() {
      return $this->hasMany('App\Experiencia', 'id_egresado');       
    }
}

