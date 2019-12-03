<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cargo extends Model
{
    protected $table = 'cargos';
    protected $primaryKey = 'id_aut_cargos';
    public $timestamps = false;
    // public $incrementing = false;

    protected $fillable = [
        'nombre', 'id_categoria', 'estado'
    ];

    public function administradores()
    {
        return $this->hasMany('App\AdministradorEmpresa', 'id_cargo', 'id_aut_cargos');
    }

    public function experiencia(){
        return $this->hasMany('App\Experiencia', 'id_cargo');
    }
}
