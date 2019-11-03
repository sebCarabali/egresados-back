<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{

    protected $table = 'users';
    protected $primaryKey = 'id_aut_user';
    public $timestamps = false;

    protected $fillable = [
        'email', 'password', 'id_rol', 'first_name', 'last_name', 'codigo_verificacion'
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
