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
}
