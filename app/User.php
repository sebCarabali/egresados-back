<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'id_aut_user';
    public $timestamps = false;

    protected $fillable = [
        'email', 'password',  'first_name', 'last_name', 'codigo_verificacion'
    ];

    protected $hidden = [
        'password'
    ];

    // Relación con la tabla roles
    // public function rol()
    // {
    //     return $this->belongsTo('App\Role', 'id_rol');
    // }

    public function rol()
    {
        return $this->belongsTo(Role::class, 'id_rol', 'id_aut_rol');
    }

    public function administradores()
    {
        return $this->hasMany('App\AdministradorEmpresa', 'id_aut_user', 'id_aut_user');
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    
    public function getJWTCustomClaims()
    {
        return [
            'rol' => $this->rol()->first()->nombre,
            'email' => $this->email
        ];
    }
}
