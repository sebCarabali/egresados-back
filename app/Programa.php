<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Programa extends Model
{
    public $timestamps = false;
    protected $table = 'programas';
    protected $guarded = ['id_aut_programa'];
    protected $primaryKey = 'id_aut_programa';

    public function egresados()
    {
        return $this->belognsToMany('App\Egresado', 'grados', 'id_programa', 'id_estudiante');
        /*->withPivot('tipo', 'mension_honor', 'titulo_especial', 'comentarios', 'fecha_graduacion',
                    'docente_influencia');*/
    }

    public function sede()
    {
        return $this->belongsTo('App\Sede', 'id_sede', 'id_aut_sede');
    }

    public function facultad()
    {
        return $this->belongsTo('App\Facultad', 'id_facultad', 'id_aut_facultad');
    }

    public function referidos()
    {
        return $this->hasMany('App\Referido', 'id_aut_referido');
    }

    public function nivelEstudio()
    {
        return $this->belongsTo('App\NivelEstudio', 'id_nivelestudio', 'id_aut_estudio');
    }

    public function ofertas()
    {
        return $this->belongsToMany('App\Oferta', 'programas_ofertas', 'id_programa', 'id_oferta');
    }
}
