<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cargo extends Model
{
    protected $table = 'cargos';
    protected $primaryKey = 'id_aut_cargos';
    public $timestamps = false;

    protected $fillable = [
        'nombre', 'id_categoria', 'estado'
    ];

    public function categoria()
    {
        return $this->belongsTo('App\CategoriaCargo', 'id_categoria', 'id_aut_cate_crago');
    }

    public function administradores()
    {
        return $this->hasMany('App\AdministradorEmpresa', 'id_cargo', 'id_aut_cargos');
    }
}
