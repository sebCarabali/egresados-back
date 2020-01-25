<?php

namespace App\Search\Egresado\Filters;

use App\Programa as ModelPrograma;
use App\Search\Filter;
use Illuminate\Database\Eloquent\Builder;

class Programa implements Filter
{
    public static function apply(Builder $builder, $value)
    {
        $idEgresadosPorPrograma = ModelPrograma::select('grados.id_egresado')
                    ->join('grados', function($join) {
                       $join->on('grados.id_programa', '=', 'programas.id_aut_programa');
                    })->where('nombre', 'ilike', "%$value%")
                            ->pluck('grados.id_egresado')->toArray();
        return $builder->whereIn('id_aut_egresado', $idEgresadosPorPrograma);
    }
}