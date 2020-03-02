<?php

namespace App\Repository;

use App\Search\Search;
use Illuminate\Http\Request;

interface BaseRepositoryInterface
{
    /**
     * Retorna todos los registros.
     */
    public function getAll();

    /**
     * Retorna todos los registros paginados.
     */
    public function getAllWithPaging(Request $request, Search $search);

    /**
     * Retorna el registro cuyo id sea igual a $id.
     *
     * @param mixed $id
     */
    public function getById($id);

    /**
     * Crea un nuevo registro.
     */
    public function save(Request $request);

    /**
     * Actualiza el registro cuyo id sea igual a $id.
     *
     * @param mixed $id
     */
    public function update(Request $request, $id);

    /**
     * eliminar el registro cuyo id sea igual a $id.
     *
     * @param mixed $id
     */
    public function delete($id);
}
