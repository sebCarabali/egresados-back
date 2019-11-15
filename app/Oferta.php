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

    public function contrato()
    {
        return $this->belongsTo('App\Contrato', 'id_contrato', 'id_aut_contrato');
    }

    public function cargo()
    {
        return $this->belongsTo('App\Cargo', 'id_cargo', 'id_aut_cargos');
    }

    public function empresa()
    {
        return $this->belongsTo('App\Empresa', 'id_empresa', 'id_aut_empresa');
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
        return $this->belongsToMany(Ciudad::class, 'ubicacion_oferta','id_oferta', 'id_ciudad');
    }

    public function idiomas()
    {
        return $this->belongsToMany(Idioma::class, 'ofertas_idiomas')
            ->withPivot(['id_oferta', 'id_idioma', 'nivel_escritura', 'nivel_lectura', 'nivel_conversacion'])
            ->withTimestamps();
    }

    public function salario()
    {
        return $this->belongsTo(Salario::class, 'id_forma_pago', 'id_aut_salario');
    }


}
