<?php

namespace App\Repository;

interface GradoRepositoryInterface extends BaseRepositoryInterface
{
    public function obtenerGradoPorProgramaYEgresado($nombrePrograma, $idEgresado);
}
