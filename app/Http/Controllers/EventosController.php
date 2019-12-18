<?php

namespace App\Http\Controllers;

use App\Evento;
use App\Http\Resources\EventosResource;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;

class EventosController extends Controller {

    const EVENTOS_IMAGE_PATH = 'storage/eventos';

    /**
     * Realiza el registro de un evento nuevo.
     */
    public function save(Request $req) {
        //return $this->success($req->file('fileInput')->getClientOriginalName());
        $data = $req->only(
                'nombre', 'cupos', 'lugar', 'fechaInicio', 'fechaFin', 'descripcion', 'dirigidoA'
        );
        DB::beginTransaction();
        try {
            $evento = new Evento();
            $evento->nombre = $data['nombre'];
            $evento->fecha_inicio = date('m/d/Y', strtotime($data['fechaInicio']));
            $evento->fecha_fin = date('m/d/Y', strtotime($data['fechaFin']));
            $evento->lugar = $data['lugar'];
            $evento->descripcion = $data['descripcion'];
            $evento->a_quien_va_dirigida = $data['dirigidoA'];
            $evento->cupos = $data['cupos'];
            $evento->image_path = $req->file('fileInput')->store('storage/eventos', 'public');
            $evento->save();
            DB::commit();
            return $this->success(new EventosResource($evento));
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage()], 400);
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
            return $this->success(new EventosResource($evento));
        } catch (Exception $e) {
            return $this->notFound('No se encontró el evento solicitado: ' . $e->getMessage());
        }
    }

    public function update(Request $request) {
        $data = $request->only(
                'evento', 'cupos', 'lugar', 'fechaInicio', 'fechaFin', 'descripcion', 'dirigidoA', 'id'
        );
        try {
            DB::beginTransaction();
            $evento = Evento::where('id_aut_evento', $data['id'])->firstOrFail();
            if ($request->has('fileInput') && $request->get('fileInput') != null) {
                $evento = $this->actualizarImagen($request->file('fileInput'));
            }
            $eventoRet = $this->setInfoAlEvento($evento, $data);
            DB::commit();
            return $this->success($eventoRet);
        } catch (Exception $e) {
            DB::rollback();
            return $this->badRequest($e->getMessage());
        }
    }

    private function setInfoAlEvento(Evento $evento, array $data) {
        $evento->nombre = $data['nombre'];
        $evento->fecha_inicio = date('m/d/Y', strtotime($data['fechaInicio']));
        $evento->fecha_fin = date('m/d/Y', strtotime($data['fechaFin']));
        $evento->lugar = $data['lugar'];
        $evento->descripcion = $data['descripcion'];
        $evento->a_quien_va_dirigida = $data['dirigidoA'];
        $evento->cupos = $data['cupos'];
        $evento->save();
        return $evento;
    }

    private function actualizarImagen($file, Evento $evento) {
        // TODO: Guardar nueva imagen del evento y eliminar la existente.
        if (!empty(!$evento->image_path)) {
            Storage::delete($evento->image_path, 'public');
        }
        $evento->image_path = $file->store('storage/eventos', 'public');
        return $evento;
    }
    
    public function getAllWithoutPaging()
    {
        return $this->success(EventosResource::collection(Evento::all()));
    }

}
