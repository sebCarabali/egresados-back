<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

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
        return $this->responseWithToken($token);
    }

    private function responseWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'user_email' => auth()->user()->email,
            'user_rol' => auth()->user()->rol()->first()->nombre
        ], 200);
    }
}
