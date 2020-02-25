<?php

namespace App\Search\Apoyo\Filters;

use App\Search\Filter;
use Illuminate\Database\Eloquent\Builder;

class Apellido implements Filter
{
    public static function apply(Builder $builder, $value)
    {
        return $builder->where('apellidos', 'ilike', "%{$value}%");
    }
}
