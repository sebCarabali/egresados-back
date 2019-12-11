<?php

namespace App\Helpers;

use App\Apoyo;
use App\Role;
use App\User;
// use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class CrearUsuario
{

    public function crearUsuario($email, $rol) {
        // throw new HttpResponseException(response()->json($email));
        $user = new User([
            'email' => $email,
            'codigo_verificacion' => $this->_generarCodigoConfirmacion()
        ]);
        $user->rol()->associate(Role::where(DB::raw('upper(nombre)'), strtoupper($rol))->firstOrFail());
        $user->save();
        $this->_enviarMensajeActivacion($user);
        return $user;
    }

    public function crearUsuarioApoyo(Apoyo $apoyo) {
        $user = new User([
            'email' => $apoyo->correo,
            'codigo_verificacion' => $this->_generarCodigoConfirmacion()
        ]);
        $user->rol()->associate(Role::where(DB::raw('upper(nombre)'), 'APOYO')->first());
        $user->save();
        $this->_enviarMensajeActivacion($user);
        return $user;
    }

    private function _generarCodigoConfirmacion($length = 16)
    {
        $alfabeto = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        $yaExiste = false;
        do {
            $codigo = substr(str_shuffle($alfabeto), 0, $length);
            $yaExiste = $this->_codigoYaExisteEnBd($codigo);
        } while ($yaExiste);
        return $codigo;
    }

    private function _codigoYaExisteEnBd($codigo)
    {
        return User::where('codigo_verificacion', $codigo)->exists();
    }

    private function _enviarMensajeActivacion(User $usuario)
    {
        $correo = $usuario->email;
        Mail::send('mail.confirmation', ['codigo' => $usuario->codigo_verificacion], function ($message) use ($correo) {
            $message->from('carloschapid@unicauca.edu.co', 'Egresados');
            $message->to($correo)->subject('Nuevo usuario');
        });
    }
}
