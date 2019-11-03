<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Programa extends Model
{
    protected $table = 'programas';
    protected $guarded = ['id_aut_programa'];
    protected $primaryKey = 'id_aut_programa';
    public $timestamps = false;

    public function egresados() {
        return $this->belognsToMany('App\Egresado', 'grados', 'id_programa', 'id_estudiante');
                /*->withPivot('tipo', 'mension_honor', 'titulo_especial', 'comentarios', 'fecha_graduacion',
                            'docente_influencia');*/
    }

    public function facultad() {
        return $this->belognsTo('App\Facultad', 'id_facultad');
    }
}
