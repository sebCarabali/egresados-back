<?php

namespace App\Http\Controllers;

use App\Eventos;
use App\Http\Resources\EventosResource;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EventosController extends Controller {

    const EVENTOS_IMAGE_PATH = 'storage/eventos';

    /**
     * Realiza el registro de un evento nuevo.
     */
    public function save(Request $req) {
        $data = $req->only(
                'nombre', 'cupos', 'lugar', 'fechaInicio', 'fechaFin', 'descripcion', 'dirigidoA'
        );
        DB::beginTransaction();
        try {
            $evento = Eventos::create([
                        'nombre' => $data['nombre'],
                        'fecha_inicio' => $data['fechaInicio'],
                        'fecha_fin' => $data['fechaFin'],
                        'descripcion' => $data['descripcion'],
                        'cupos' => $data['cupos'],
                        'lugar' => $data['lugar'],
                        'a_quien_va_dirigida' => $data['dirigidoA'],
                        'image_path' => $req->file('fileInput')->store(
                                'storage/eventos', 'public')
            ]);
            DB::commit();
            return $this->success(new EventosResource($evento));
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getTraceAsString()], 400);
        }
    }

    /**
     * 
     * @param Request $request
     * @return type 
     */
    public function getAll(Request $request) {
        $page = $request->get('page');
        $pageSize = $request->get('pageSize');
        $eventos = \App\Helpers\BusquedaEventos::apply($request);
        $results = $eventos->slice(($page - 1) * $pageSize, $pageSize)->values();
        return EventosResource::collection(
                        new LengthAwarePaginator(
                        $results, $total = count($eventos), $pageSize, $page
                        )
        );
    }

    public function getById($idEvento) {
        try {
            $evento = Eventos::find($idEvento)->firstOrFail();
            $evento->image_path = asset(Storage::get($evento->id_aut_evento));
            return $this->success(new EventosResource($evento));
        } catch (Exception $e) {
            return $this->notFound('No se encontró el evento solicitado: ' . $e->getMessage());
        }
    }

}
