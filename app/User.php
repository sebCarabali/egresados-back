<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{

    protected $table = 'ofertas.users';

    public $timestamps = false;

    protected $fillable = [
        'id', 'email', 'password', 'id_rol', 'first_name', 'last_name'
    ];

    protected $hidden = [
        'password'
    ];

    // Relación con la tabla roles
    public function role()
    {
        return $this->belongsTo('App\Role', 'id_rol');
    }
}
