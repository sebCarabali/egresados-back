<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    protected $table = 'empresas';

    public $timestamps = false;


    // protected $primaryKey = 'id_aut_empresa';

    protected $fillable = [
        'nit',
        'nombre',
        'razon_social',
        'anio_creacion',
        'numero_empleados',
        'ingresos',
        'sitio_web',
        'id_direccion',
        'estado',
        'fecha_registro',
        'fecha_activacion',
        'total_publicaciones',
        'limite_publicaciones',
        'num_publicaciones_actuales'
    ];

    public function representante()
    {
        return $this->hasOne(\App\RepresentanteEmpresa::class, 'id_empresa', 'id');
        // return $this->hasOne(\App\RepresentanteEmpresa::class, 'id_empresa', 'id_aut_empresa');
    }

    public function direccion()
    {
        return $this->belongsTo(\App\Localizacion::class, 'id_direccion', 'id');
        // return $this->belongsTo(\App\Localizacion::class, 'id_direccion', 'id_aut_localizacion');
    }

    public function user()
    {
        return $this->belongsTo(\App\User::class, 'id_user', 'id');
        // return $this->belongsTo(\App\User::class, 'id_aut_user', 'id_aut_user');
    }
}
