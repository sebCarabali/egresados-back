<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdministradorEmpresa extends Model
{
    protected $table = 'administrador_empresa';
    protected $primaryKey = 'id_aut_representante'; // Corregir
    public $timestamps = false;

    public function direccion()
    {
        // return $this->belongsTo(\App\Localizacion::class, 'id_direccion', 'id');
        return $this->belongsTo(\App\Localizacion::class, 'id_direccion', 'id_aut_localizacion');
    }

    public function empresa()
    {
        return $this->belongsTo(\App\Empresa::class, 'id_empresa', 'id_aut_empresa');
    }

    public function cargo()
    {
        return $this->belongsTo(\App\Cargo::class, 'id_cargo', 'id_aut_cargo');
    }

    public function user()
    {
        return $this->belongsTo(\App\User::class, 'id_aut_user', 'id_aut_user');
    }
}
