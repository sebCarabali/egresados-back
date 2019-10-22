<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RepresentanteEmpresa extends Model
{
    //
    protected $table = 'representante_empresa';

    public $timestamps = false;

    protected $fillable = [
        'id_aut_representante',
        'nombres',
        'apellidos',
        'id_cargo',
        'telefono',
        'telefono_movil',
        'correo_corporativo',
        'id_direccion',
        'id_empresa'
    ];

    public function direccion()
    {
        return $this->belongsTo(\App\Localizacion::class, 'id_direccion', 'id');
    }

    public function empresa()
    {
        return $this->belongsTo(\App\Empresa::class, 'id_empresa', 'id');
        // return $this->belongsTo(\App\Empresa::class, 'id_empresa', 'id_aut_empresa');
    }

    public function cargo()
    {
        return $this->belongsTo(\App\Cargo::class, 'id_cargo', 'id');
        // return $this->belongsTo(\App\Cargo::class, 'id_cargo', 'id_aut_cargo');
    }
    
}