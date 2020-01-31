<?php

namespace App\Search\Egresado\Filters;

use App\Search\Filter;
use Illuminate\Database\Eloquent\Builder;

class Cedula implements Filter
{
    public static function apply(Builder $builder, $value)
    {
        return $builder->where('identificacion', 'like', $value);
    }
}
