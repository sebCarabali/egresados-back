<?php

namespace App\Repository;

interface DiscapacidadRepositoryInterface
{
    /**
     * Obtiene todas las discapacidades.
     */
    public function all();

    /**
     * Obtiene todas las discapacidades de un egresado.
     *
     * @param int $idEgresado
     *
     * @return collection discapacidades de los egresados
     */
    public function findByEgresado($idEgresado);
}
