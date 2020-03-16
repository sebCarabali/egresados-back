<?php

namespace App\Http\Controllers;

use App\Http\Resources\DiscapacidadResource;
use App\Repository\Eloquent\DiscapacidadRepository;

class DiscapacidadController extends Controller
{
    private $repository;

    public function __construct(DiscapacidadRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAll()
    {
        $discapacidades = $this->repository->all();

        return $this->success(DiscapacidadResource::collection($discapacidades));
    }

    public function getDiscapacidadesEgresado($idEgresado)
    {
        $discapacidadesEgresado = $this->repository->findByEgresado($idEgresado);

        return $this->success(DiscapacidadResource::collection($discapacidadesEgresado));
    }
}
