<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\User;
use App\Helpers\JwtAuth;

class UserController extends Controller
{

    public function activarCuenta(Request $request, $codigo)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|confirmed|min:8',
            'password_confirmation' => 'required|same:password'
        ], [
            'password.required' => 'Debe ingresar un password',
            'password_confirmation.same' => 'Debe ingresar el mismo password.',
            'password.min' => 'El password debe tener 8 o más caracteres.',
            'password_confirmation.required' => 'Debe ingresar la confirmación del password.'
        ]);

        if($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        // Obtener usuario con el código de confirmación
        $usuario = User::where('codigo_verificacion', $codigo)->first();
        if($usuario == null) {
            return response()->json([
                "mensaje" => "No se ha encontrado un usuario con código " + $codigo,
                "status" => false
            ], 400);
        }

        $usuario->activo = true;
        $usuario->password = Hash::make($request->get('password'));
        $usuario->save();
        return response()->json(true, 200);
    }

    public function esUsuarioActivoPorCodigo($codigo) {
        $usuario = User::where('codigo_verificacion', $codigo)->first();
        $status = 200;
        $res = true;
        if(!$usuario) {
            $res = false;
            $status = 400;
        } else {
            $res = $usuario->activo;
        }
        return response()->json($res, $status);
    }

    public function esUsuarioActivo($email)
    {
        $activo = false;
        $usuario = User::where('email', $email)->first();
        if($usuario) {
            $activo = boolval($usuario->activo);
        }
        return response()->json($activo, 200);
    }
}
