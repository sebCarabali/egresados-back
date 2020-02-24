<?php

namespace App\Search\Evento\Filters;

use App\Search\Filter;
use Illuminate\Database\Eloquent\Builder;

class Fecha implements Filter
{
    public static function apply(Builder $builder, $value)
    {
        return $builder->where('fecha_inicio', date('d/m/Y',strtotime($value)));
    }
}
