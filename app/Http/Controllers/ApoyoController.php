<?php

namespace App\Http\Controllers;

use App\Apoyo;
use App\ConfirmacionCorreo;
use App\Helpers\CrearUsuario;
use App\Helpers\GeneradorCodigoConfirmacion;
use App\Http\Requests\StoreApoyoRequest;
use App\Http\Resources\ApoyoResource;
use App\Http\Validators\EmailYaExisteValidator;
use App\Notifications\CambioCorreoApoyoNotification;
use App\Search\Apoyo\ApoyoSearch;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class ApoyoController extends Controller
{
    public function getAll(Request $request)
    {
        $apoyos = ApoyoSearch::apply($request);
        $page = $request->get('page');
        $pageSize = $request->get('pageSize');
        $results = $apoyos->slice(($page - 1) * $pageSize, $pageSize)->values();

        return ApoyoResource::collection(
            new LengthAwarePaginator(
                $results,
                $total = count($apoyos),
                $pageSize,
                $page
            )
        );
    }

    public function getById($idApoyo)
    {
        $apoyo = Apoyo::where('id_aut_apoyo', $idApoyo)->first();
        if ($apoyo) {
            return new ApoyoResource($apoyo);
        }

        return response()->json(['error' => 'No se encontró el apoyo con id: '.$idApoyo], 400);
    }

    public function save(StoreApoyoRequest $request)
    {
        $data = $request->only('nombres', 'nombreRol', 'apellidos', 'correo', 'correoSecundario', 'servicios');

        DB::beginTransaction();

        try {
            $apoyo = new Apoyo([
                'nombres' => $data['nombres'],
                'apellidos' => $data['apellidos'],
                'nombre_rol' => $request->get('nombreRol'),
                'correo' => $data['correo'],
                'correo_secundario' => $request->has('correoSecundario') ? $data['correoSecundario'] : '',
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
        $data = $request->only('id', 'nombres', 'apellidos', 'nombreRol', 'correo', 'correoSecundario', 'servicios', 'usuario');

        if (EmailYaExisteValidator::validarEmail($data['correo'], $data['id'])) {
            return $this->fail('Un usuario se encuentra utilizando el correo electrónico que desea modificar');
        }

        DB::beginTransaction();

        try {
            $apoyo = Apoyo::where('id_aut_apoyo', $data['id'])->first();
            if ($apoyo) {
                $apoyo->nombres = $data['nombres'];
                $apoyo->apellidos = $data['apellidos'];
                $apoyo->nombre_rol = $data['nombreRol'];
                $actualizoCorreo = $this->actualizarEmail($apoyo, $data['correo']);
                if (!$actualizoCorreo) {
                    $usuario = $apoyo->usuario()->first();
                    $usuario->activo = $data['usuario']['activo'];
                    $usuario->save();
                }
                $apoyo->correo_secundario = $data['correoSecundario'];
                $idServicios = [];
                foreach ($data['servicios'] as $serv) {
                    array_push($idServicios, $serv['id']);
                }
                $apoyo->servicios()->sync($idServicios);
                $apoyo->save();
                DB::commit();

                return response()->json(['data' => new ApoyoResource($apoyo)], 201);
            }
        } catch (Exception $e) {
            DB::rollback();

            return response()->json(['error' => 'Error actualizando datos del apoyo: '.$e->getMessage()], 400);
        }
    }

    public function activarNuevoEmailBy($codigoConfirmacion)
    {
        DB::beginTransaction();

        try {
            $confirmacionCorreo = ConfirmacionCorreo::where('link_confirmacion', $codigoConfirmacion)->first();
            if (!$confirmacionCorreo) {
                return $this->fail('El link de confirmación ha caducado o ya se ha utilizado');
            }

            // Cambiar email en las tablas users y apoyos
            $apoyo = $confirmacionCorreo->apoyo()->first();
            $apoyo->correo = $confirmacionCorreo->nuevo_correo;
            $apoyo->save();

            $usuario = $apoyo->usuario()->first();
            $usuario->email = $confirmacionCorreo->nuevo_correo;
            $usuario->activo = true;
            $usuario->save();

            $confirmacionCorreo->delete();

            DB::commit();

            return $this->success(['mensaje' => 'Correo electrónico actualizado con éxito']);
        } catch (Exception $e) {
            DB::rollback();

            return $this->fail('Correo electrónico no se actualizo. Comuniquese con el área de egresados para más información');
        }
    }

    public function estaEnProcesoDeActivacion($id)
    {
        $estaEnProceso = ConfirmacionCorreo::where('id_apoyos_fk', $id)->exists();

        return $this->success(['enProceso' => $estaEnProceso]);
    }

    private function actualizarEmail($apoyo, $nuevo)
    {
        if (0 != strcmp(mb_strtolower($apoyo->correo), mb_strtolower($nuevo))) {
            $confirmacionCorreo = ConfirmacionCorreo::where('id_apoyos_fk', $apoyo->id_aut_apoyo)->first();
            if (!$confirmacionCorreo) {
                $confirmacionCorreo = new ConfirmacionCorreo();
            }

            $codigo = GeneradorCodigoConfirmacion::generar($confirmacionCorreo, 'nuevo_correo');
            $confirmacionCorreo->nuevo_correo = $nuevo;
            $confirmacionCorreo->id_apoyos_fk = $apoyo->id_aut_apoyo;
            $confirmacionCorreo->link_confirmacion = $codigo;

            $usuario = $apoyo->usuario()->first();
            $usuario->activo = false;
            $usuario->save();

            $guardo = $confirmacionCorreo->save();

            $confirmacionCorreo->notify(new CambioCorreoApoyoNotification($codigo));

            return true;
        }

        return false;
    }
}
