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

    public function nacimiento()
    {
        return $this->belongsTo('App\Nacimiento', 'id_nacimiento_fk');
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

    public function referidos() {
      return $this->belognsToMany('App\Referido', 'referidos_egresados', 'id_egresados', 'id_referidos');       
    }

    public function experiencia() {
      return $this->hasMany('App\Experiencia', 'id_egresado');       
    }
}
