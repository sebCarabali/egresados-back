<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Titulo extends Model
{
    
    protected $table = "titulo";
    protected $primaryKey = "id_aut_titulo";
    public $timestamps = false;  

    public function programas() {
        return $this->belognsTo('App\Programa',  'id_programa', 'id_aut_programa');
    }
}
