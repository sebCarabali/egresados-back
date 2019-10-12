<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{

    protected $table = "empresas";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nit', 'nombre', 'razon_social', 'anio_creacion', 'numero_empleados', 'ingresos',
        'sitio_web', 'id_direccion', 'estado', 'fecha_registro', 'fecha_activacion',
         'total_publicaciones', 'limite_publicaciones', 'num_publicaciones_actuales'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];
}
