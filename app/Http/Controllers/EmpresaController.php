<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Empresa;
use App\Helpers\JwtAuth;

class EmpresaController extends Controller
{

    public function index()
    {
        $empresas = Empresa::all();

        return response()->json($empresas, 200);
    }

    public function show($id)
    {
        // Codigo de error por defecto
        $code = 404;
        $empresa = Empresa::find($id);

        if(is_object($empresa)){
            $code = 200;
            $data = $empresa;
        }else{
            $data = null;
        }
        return response()->json($data, $code);
    }

    public function update($id, Request $request){


        // Recoger los datos por POST
        $json = $request->input('json', null);
        $params_array = json_decode($json, true);

        // Código de error por defecto
        $code = 400;
        $data = null;

        if (!empty($params_array)) {

            // Eliminar lo que no queremos actualizar
            unset($params_array['id']);
            unset($params_array['nombre']);
            unset($params_array['razon_social']);
            unset($params_array['anio_creacion']);
            unset($params_array['fecha_registro']);
            unset($params_array['fecha_activacion']);


            // Buscar el registro
            $empresa = Empresa::where('id', $id)->first();

            if (!empty($empresa) && is_object($empresa)) {

                // Actualizar el registro en concreto
                $empresa->update($params_array);

                $data = $empresa;
                $code = 200;
            }
        }
        return response()->json($data, $code);
    }

    public function updateEstado($id, Request $request){


        // Recoger los datos por PUT
        $json = $request->input('json', null);
        $params_array = json_decode($json, true);

        // Código de error por defecto
        $code = 400;
        $data = null;

        if (!empty($params_array)) {

            // Buscar el registro
            $empresa = Empresa::where('id', $id)->first();

            if (!empty($empresa) && is_object($empresa)) {
              // Actualizar el registro en concreto
              $empresa->update(['estado' => $params_array['estado']]);
              $data = $empresa;
              $code = 200;

            }
        }
        return response()->json($data, $code);
    }

    private function getIdentity($request)
    {
        // Conseguir usuario autentificado
        $jwtAuth = new JwtAuth();
        $token = $request->header('Authorization', null);
        $user = $jwtAuth->checkToken($token, true);

        return $user;
    }
}
