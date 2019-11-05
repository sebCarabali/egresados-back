<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CategoriaCargo extends Model
{
    protected $table = 'categorias_cargos';
    protected $guarded = ['id_aut_cate_cargo'];
    protected $primaryKey = 'id_aut_cate_cargo';
    public $timestamps = false;
    

    protected $fillable = [
        'nombre'
    ];

    public function cargos()
    {
        return $this->hasMany('App\Cargo', 'id_categoria', 'id_aut_cate_cargo');
    }
}
