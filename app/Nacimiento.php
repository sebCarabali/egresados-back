<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Nacimiento extends Model
{
    protected $table = 'nacimiento';
    protected $guarded = ['id_lug_nac'];
    public $timestamps = false;
    
    public function egresados()
    {
      return $this->hasMany('App\Egresado', 'id_nacimiento_fk');  
    }

    public function ciudad()
    {
        return $this->belongsTo('App\Ciudad', 'id_ciudad_fk');
    }
}
