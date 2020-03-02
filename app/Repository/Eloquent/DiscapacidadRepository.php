<?php

namespace App\Repository\Eloquent;

use App\Discapacidad;
use App\Repository\DiscapacidadRepositoryInterface;
use Illuminate\Support\Facades\DB;

class DiscapacidadRepository implements DiscapacidadRepositoryInterface
{
    public function all()
    {
        return Discapacidad::all();
    }

    public function findByEgresado($idEgresado)
    {
        $idDiscapacidadesEgresados = DB::table('egresados_discapacidades')->where('id_egresado', $idEgresado)
            ->pluck('id_discapacidad')->toArray();

        return Discapacidad::whereIn('id_aut_discapacidades', $idDiscapacidadesEgresados)->get();
    }
}
