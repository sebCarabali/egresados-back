<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    protected $table = 'ofertas.empresas';

    public $timestamps = false;

    protected $fillable = [
        'id', 'nit', 'nombre', 'razon_social', 'anio_creacion', 'numero_empleados',
        'ingresos', 'sitio_web', 'id_direccion', 'estado', 'fecha_registro',
        'fecha_activacion', 'total_publicaciones', 'limite_publicaciones',
        'num_publicaciones_actuales'
    ];
}
