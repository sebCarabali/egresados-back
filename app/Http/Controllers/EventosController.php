<?php

namespace App\Http\Controllers;

use App\Evento;
use App\Http\Resources\EventosResource;
use App\Repository\EventoRepositoryInterface;
use App\Search\Evento\EventoSearch;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class EventosController extends Controller
{
    const EVENTOS_IMAGE_PATH = 'storage/eventos';

    private $repository;

    public function __construct(EventoRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Realiza el registro de un evento nuevo.
     */
    public function save(Request $req)
    {
        try {
            $egresado = $this->repository->save($req);

            return $this->success($egresado);
        } catch (Exception $e) {
            return $this->fail($e->getMessage());
        }
    }

    /**
     * @return type
     */
    public function getAll(Request $request)
    {
        $eventos = $this->repository->getAllWithPaging($request, new EventoSearch());

        return EventosResource::collection($eventos);
    }

    public function getById($idEvento)
    {
        return $this->repository->getById($idEvento);
    }

    public function update(Request $request)
    {
        $data = $request->only(
            'evento'
        );

        try {
            DB::beginTransaction();
            $evento = Evento::where('id_aut_evento', $data['evento']['id'])->firstOrFail();
            /*if ($request->has('fileInput') && $request->get('fileInput') != null) {
                $evento = $this->actualizarImagen($request->file('fileInput'));
            }*/
            $eventoRet = $this->setInfoAlEvento($evento, $data['evento']);
            DB::commit();

            return $this->success($eventoRet);
        } catch (Exception $e) {
            DB::rollback();

            return $this->badRequest($e->getMessage());
        }
    }

    public function getAllWithoutPaging()
    {
        $eventos = $this->repository->getAll();

        return $this->success(EventosResource::collection($eventos));
    }

    private function setInfoAlEvento(Evento $evento, array $data)
    {
        $evento->nombre = $data['nombre'];
        $evento->fecha_inicio = $this->getPgsqlDateFormat($data['fechaInicio']);
        $evento->fecha_fin = $this->getPgsqlDateFormat($data['fechaFin']);
        $evento->lugar = $data['lugar'];
        $evento->descripcion = $data['descripcion'];
        $evento->a_quien_va_dirigida = $data['dirigidoA'];
        $evento->cupos = $data['cupos'];
        $evento->save();

        return $evento;
    }

    private function actualizarImagen($file, Evento $evento)
    {
        // TODO: Guardar nueva imagen del evento y eliminar la existente.
        if (!empty(!$evento->imagem)) {
            Storage::delete($evento->imagen, 'public');
        }
        $evento->imagen = $file->store('storage/eventos', 'public');

        return $evento;
    }
    
    private function getPgsqlDateFormat($dateStr) {
        $dateArray = explode('/', $dateStr);
        return $dateArray[2] . '-' . $dateArray[1] . '-' . $dateArray[0];
    }

}
