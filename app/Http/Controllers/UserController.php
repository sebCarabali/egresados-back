<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use App\User;
use App\Helpers\JwtAuth;

class UserController extends Controller
{

    public function login(Request $request)
    {
        $jwtAuth = new \JwtAuth();

        // Recibir datos por POST
        $json = $request->input('json', null);
        $params = json_decode($json);
        $params_array = json_decode($json, true);

        // Validar esos datos
        $validate = \Validator::make($params_array, [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Codigo de error por defecto
        $code = 400;

        if ($validate->fails()) {
            //Validación fallida
            $signup = null;

        } else {
            // Cifrar password
            $pwd = hash('sha256', $params->password);

            // Devolver token o datos
            if (!empty($params->getToken)) {
                $signup = $jwtAuth->signup($params->email, $pwd, true);
            } else {
                $signup = $jwtAuth->signup($params->email, $pwd);
            }

            // Probar si se autentico de forma correcta
            if (!is_null($signup)) {
                $code = 200;
            }
        }

        return response()->json($signup, $code);
    }

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
        if(!$usuario) {
            return response()->json(false, 400);
        }

        $usuario->activado = true;
        $usuario->save();
        return response()->json(true, 200);
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
