<?php

namespace App\Repository;

use App\Grado;

interface EgresadoRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Verifica si el egresado ya tiene registrado un grado para el
     * programa con id = $idPrograma.
     *
     * @param number $idEgresado
     * @param number $idPrograma
     *
     * @return Grado grado encontrado o null
     */
    public function getGradoByPrograma($idEgresado, $idPrograma);
}
