<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

use App\User;
use App\Role;
use App\AdministradorEmpresa;
use App\Empresa;
use Exception;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $credentials['activo'] = true;
        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 400);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        // Verificación extra para rol Empresa
        if ($this->isEmpresa($request->email) && !$this->verificarEmpresaActiva($request->email)) {
            return response()->json(['error' => 'invalid_credentials'], 400);
        }
        return $this->responseWithToken($token);
    }

    private function responseWithToken($token)
    {
        return response()->json([
            'access_token' => $token
        ], 200);
    }

    private function isEmpresa($email)
    {
        // Verifica si el usuario a partir de su email posee el rol de 'Empresa'
        $aux = false;
        try {
            $user = User::where('email', $email)->first();
            $rol = Role::where('id_aut_rol', $user->id_rol)->first()->nombre;

            if ($rol == 'Empresa') {
                $aux = true;
            }
        } catch (Exception $e) {
            $aux = false;
        }
        return $aux;
    }

    private function verificarEmpresaActiva($email)
    {
        // Verificar si una empresa está activa para poder iniciar sesión
        $estado = false;
        try {
            $idUser = User::where('email', $email)->first()->id_aut_user;
            $idEmpresa = AdministradorEmpresa::where('id_aut_user', $idUser)->first()->id_empresa;

            $empresa = Empresa::find($idEmpresa);

            if ($empresa->estado == 'Activo') {
                $estado = true;
            }
        } catch (Exception $e) {
            $estado = false;
        }

        return $estado;
    }
}
