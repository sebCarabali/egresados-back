<?php

namespace App\Repository\Eloquent;

use App\Grado;
use App\Programa;
use App\Repository\GradoRepositoryInterface;

class GradoRepository extends BaseRepository implements GradoRepositoryInterface
{
    public function __construct()
    {
        $this->model = new Grado();
    }

    public function obtenerGradoPorProgramaYEgresado($nombrePrograma, $idEgresado)
    {
        $programa = Programa::where('nombre', $nombrePrograma)->pluck('id_aut_programa')->first();

        return $this->model->where('id_programa', $programa)->where('id_egresado', $idEgresado)->first();
    }

    public function update(\Illuminate\Http\Request $request, $id)
    {
    }

    public function save(\Illuminate\Http\Request $request)
    {
    }

    protected function getIdStr()
    {
        return 'id_aut_grado';
    }
}
