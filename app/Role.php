<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'rol';

    // public function users()
    // {
    //     return $this->hasMany('App\User');
    // }
    public function users()
    {
        return $this->hasMany(User::class, 'id_rol', 'id_aut_rol');
    }
}
