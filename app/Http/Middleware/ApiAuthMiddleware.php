<?php

namespace App\Http\Middleware;

use Closure;

class ApiAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $rol)
    {
        // var_dump($rol); die();
        //Comprobar si el usuario está identificado
        $token = $request->header('Authorization');
        $jwtAuth = new \JwtAuth();
        $checkToken = $jwtAuth->checkToken($token);

        // Codigos de error por defecto
        $data = null;
        $code = 401;

        if ($checkToken) {
            $user = $jwtAuth->checkToken($token, true);
            if ($user->id_rol == $rol) {
                return $next($request);
            }
        }
        return response()->json($data, $code);
    }
}
