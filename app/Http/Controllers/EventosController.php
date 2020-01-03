<?php

namespace App\Http\Controllers;

use App\Eventos;
use App\Http\Resources\EventosResource;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EventosController extends Controller
{
    private const EVENTOS_IMAGE_PATH = 'storage/eventos';
    /**
     * Realiza el registro de un evento nuevo.
     */
    public function save(Request $req)
    {
        $data = $req->only(
            'nombre',
            'cupos',
            'lugar',
            'fechaInicio',
            'fechaFin',
            'descripcion',
            'dirigidoA'
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
                'a_quien_va_dirigida' => $data['dirigidoA']
            ]);
            $imagePath = $req->file('fileInput')->storeAs(
                $this->EVENTOS_IMAGE_PATH,
                $evento->id_aut_evento
            );
            $evento->image_path = asset($imagePath);
            DB::commit();
            return $this->success(new EventosResource($evento));
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e], 400);
        }
    }
}
