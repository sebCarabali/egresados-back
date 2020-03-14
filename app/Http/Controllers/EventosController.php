<?php

namespace App\Http\Controllers;

use App\Evento;
use App\Http\Resources\EventosResource;
use App\Repository\EventoRepositoryInterface;
use App\Search\Evento\EventoSearch;
use Exception;
use Illuminate\Http\Request;

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
        $evento = $this->repository->getById($idEvento);

        return new EventosResource($evento);
    }

    public function update(Request $request, $id)
    {
        try {
            $evento = $this->repository->update($request, $id);

            return $this->success($evento);
        } catch (Exception $e) {
            return $this->fail($e->getMessage());
        }
    }

    public function getAllWithoutPaging()
    {
        $eventos = $this->repository->getAll();

        return $this->success(EventosResource::collection($eventos));
    }
}
