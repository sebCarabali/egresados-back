<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Egresado extends Model
{
    protected $table = 'egresados';
    //protected $guraded = ['id'];
    protected $primaryKey = 'id_aut_egresado';
    //protected $fillable = ['nombres'];
    protected $guarded = ['id_aut_egresado'];

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
    
    public function user()
    {
        return $this->belongsTo('App\User', 'id_aut_user', 'id_aut_user');
    }

    public function programas() {
      return $this->belongsToMany('App\Programa', 'grados', 'id_egresado', 'id_programa');
    }

  public function discapacidades() {
    return $this->belongsToMany('App\Discapacidad', 'egresados_discapacidades', 'id_egresado', 'id_discapacidad');
  }

  public function referidos()
  {
    return $this->belongsToMany('App\Referido', 'referidos_egresados', 'id_egresados', 'id_referidos');
  }

    public function experiencia() {
      return $this->hasMany('App\Experiencia', 'id_egresado');       
    }

    public function discapacidad(){
      return $this->hasMany('App\Discapacidad','egresados_discapacidades','id_egresado','id_discapacidad');
    }
    

  public function postulaciones()
  {
    return $this->belongsToMany(Oferta::class, 'postulaciones', 'id_aut_egresado', 'id_aut_oferta')
      ->withPivot(['fecha_postulacion', 'fecha_revision_empresa', 'estado']);
  }


  public function carnetizaciones()
  {
    return $this->belongsToMany('App\Carnetizacion', 'solicita', 'id_egresados', 'id_carnetizacion');
  }

}

  

