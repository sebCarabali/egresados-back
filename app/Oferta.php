<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Oferta extends Model
{
    protected $table = 'ofertas';
    protected $primaryKey = 'id_aut_oferta';
    public $timestamps = false;

    protected $fillable = [
        'id_empresa', 'nombre', 'descripcion', 'id_cargo', 'id_contrato',
        'numero_vacantes', 'salario', 'experiencia', 'anios_experiencia',
        'fecha_publicacion', 'fecha_cierre', 'estado', 'estado_proceso'
    ];

    public function sector()
    {
        return $this->belongsTo('App\Sector', 'id_sector', 'id_aut_sector');
    }

    public function contrato()
    {
        return $this->hasOne('App\Contrato', 'id_oferta', 'id_aut_oferta');
    }

    public function cargo()
    {
        return $this->belongsTo('App\Cargo', 'id_cargo', 'id_aut_cargos');
    }

    public function empresa()
    {
        return $this->belongsTo('App\Empresa', 'id_empresa', 'id_aut_empresa');
    }

    public function nivelEstudio()
    {
        return $this->belongsTo('App\NivelEstudio', 'id_aut_nivestud', 'id_aut_estudio');
    }

    public function areasConocimiento()
    {
        return $this->belongsToMany('App\AreaConocimiento', 'ofertas_areascon', 'id_aut_oferta', 'id_areaconocimiento');
    }

    public function software()
    {
        return $this->hasMany(OfertaSoftware::class, 'id_oferta', 'id_aut_oferta');
    }

    public function ubicaciones()
    {
        return $this->belongsToMany(Ciudad::class, 'ubicacion_oferta', 'id_oferta', 'id_ciudad');
    }

    public function idiomas()
    {
        return $this->belongsToMany(Idioma::class, 'ofertas_idiomas', 'id_oferta', 'id_idioma')
            ->withPivot(['nivel_escritura', 'nivel_lectura', 'nivel_conversacion']);
    }

    public function programas()
    {
        return $this->belongsToMany(Programa::class,'programas_ofertas', 'id_oferta', 'id_programa');
    }

    public function salario()
    {
        return $this->belongsTo(Salario::class, 'id_forma_pago', 'id_aut_salario');
    }

    public function preguntas()
    {
        return $this->hasMany(PreguntaOferta::class, 'id_oferta', 'id_aut_oferta');
    }

    public function postulaciones()
    {
        return $this->belongsToMany(Egresado::class, 'postulaciones', 'id_aut_oferta', 'id_aut_egresado')
            ->withPivot(['fecha_postulacion', 'fecha_revision_empresa', 'estado']);
    }

    public function contacto_hv()
    {
        return $this->hasOne(ContactoHV::class, 'id_oferta', 'id_aut_oferta');
    }

    public function discapacidades() {
        return $this->belongsToMany(Discapacidad::class, 'ofertas_discapacidades', 'id_oferta','id_discapacidad');
    }

}
