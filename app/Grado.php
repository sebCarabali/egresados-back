<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Grado extends Model
{
    protected $table = 'grados';
    protected $primaryKey = 'id_aut_grado';
    protected $guarded = ['id_aut_grado'];

    public function programa() {
        return $this->belongsTo('App\Programa', 'id_programa');
    }
}
