<?php

namespace App\Repository\Eloquent;

use App\Egresado;
use App\Grado;
use App\Repository\EgresadoRepositoryInterface;
use Illuminate\Http\Request;

class EgresadoRepository extends BaseRepository implements EgresadoRepositoryInterface
{
    public function __construct()
    {
        $this->model = new Egresado();
    }

    public function getGradoByPrograma($idEgresado, $idPrograma)
    {
        return Grado::where('id_programa', $idPrograma)->where('id_egresado', $idEgresado)->first();
    }

    protected function getIdStr()
    {
        return 'id_aut_egresado';
    }
    
    public function save(Request $request)
    {
    }

    public function update(Request $request, $id)
    {
    }
}
