<?php

namespace App\Http\Controllers;

use App\Apoyo;
use App\Helpers\CrearUsuario;
use App\Http\Resources\ApoyoResource;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApoyoController extends Controller
{
    public function getAll(Request $request)
    {
        return ApoyoResource::collection(Apoyo::all());
    }

    public function getById($idApoyo)
    {
        $apoyo = Apoyo::where('id_aut_apoyo', $idApoyo)->first();
        if ($apoyo) {
            return new ApoyoResource($apoyo);
        }
        return response()->json(['error' => 'No se encontró el apoyo con id: ' . $idApoyo], 400);
    }

    public function save(Request $request)
    {
        $data = $request->only('nombres', 'apellidos', 'correo', 'correoSecundario', 'servicios');
        DB::beginTransaction();
        try {
            $apoyo = new Apoyo([
                'nombres' => $data['nombres'],
                'apellidos' => $data['apellidos'],
                'nombre_rol' => $data['nombreRol'],
                'correo' => $data['correo'],
                'correo_secundario' => $data['correoSecundario']
            ]);
            $crearUsuarioHelper = new CrearUsuario();
            $usuario = $crearUsuarioHelper->crearUsuarioApoyo($apoyo);
            $apoyo->usuario()->associate($usuario);
            $apoyo->save();
            foreach ($data['servicios'] as $servicio) {
                $apoyo->servicios()->attach($servicio['id']);
            }
            DB::commit();
            return response()->json(['data' => new ApoyoResource($apoyo)], 200);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function update(Request $request)
    {
        $data = $request->only('id','nombres', 'apellidos', 'nombreRol', 'correo', 'correoSecundario', 'servicios');
        DB::beginTransaction();
        try {
            $apoyo = Apoyo::where('id_aut_apoyo', $data['id'])->first();
            if($apoyo) {
                $apoyo->nombres = $data['nombres'];
                $apoyo->apellidos = $data['apellidos'];
                $apoyo->nombre_rol = $data['nombreRol'];
                $apoyo->correo_secundario = $data['correoSecundario'];
                $idServicios = [];
                foreach($data['servicios'] as $serv) {
                    array_push($idServicios, $serv['id']);
                }
                $apoyo->servicios()->sync($idServicios);
                $apoyo->save();
                DB::commit();
                return response()->json(['data' => new ApoyoResource($apoyo)], 201);
            }
        } catch (Exception $e) { 
            DB::rollback();
            return response()->json(['error' => 'Error actualizando datos del apoyo: ' . $e->getMessage()], 400);
        }
    }
}
