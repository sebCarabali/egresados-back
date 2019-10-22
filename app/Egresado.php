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
        return $this->belongsTo('App\Nacimiento', 'id_nacimiento_fk');
    }

    public function lugarResidencia(){
      return $this->belongsTo('App\Localizacion', 'id_luagr_residencia');
    }

    public function lugarExpedicion() {
      return $this->belongsTo('App\Ciudad', 'id_lugar_expedicion');
    }

    public function roles()
    {
        return $this->belongsToMany('App\Role', 'role_user_table', 'user_id', 'role_id');
    }

    public function programas() {
      return $this->belongsToMany('App\Programa', 'grados', 'id_estudiante', 'id_programa');
        /*->withPivot('tipo', 'mension_honor', 'titulo_especial', 'comentarios', 'fecha_graduacion',
                    'docente_influencia');*/
    }

    public function nivelEducativo() {
      return $this->belongsTo('App\NivelEducativo', 'id_nivel_educativo');
    }
}
