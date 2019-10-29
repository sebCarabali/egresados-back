<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{

    protected $table = 'users';

    public $timestamps = false;

    protected $fillable = [
        'id', 'email', 'password', 'id_rol', 'first_name', 'last_name'
    ];

    protected $hidden = [
        'password'
    ];

    // Relación con la tabla roles
    public function rol()
    {
        return $this->belongsTo('App\Role', 'id_rol');
    }
}
