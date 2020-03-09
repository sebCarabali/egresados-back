<?php

namespace App\Repository;

use Illuminate\Http\Request;

interface EventoRepositoryInterface extends BaseRepositoryInterface
{
    public function save(Request $request);

    public function update(Request $request, $id);

    public function getAll();

    public function getById($id);
}
