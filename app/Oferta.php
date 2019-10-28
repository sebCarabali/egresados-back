<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Oferta extends Model
{
    protected $table = 'ofertas.ofertas';
    protected $primaryKey = 'id_aut_oferta';
    public $timestamps = false;

    protected $fillable = [
        'id_empresa', 'nombre', 'descripcion', 'id_cargo', 'id_contrato',
        'numero_vacantes', 'salario', 'experiencia', 'anios_experiencia',
        'fecha_publicacion', 'fecha_cierre', 'estado'
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
}
