<?php

namespace App\Search\Apoyo\Filters;

use App\Search\Filter;
use Illuminate\Database\Eloquent\Builder;

class Nombre implements Filter
{
    public static function apply(Builder $builder, $value)
    {
        return $builder->where('nombres', 'ilike', "%{$value}%");
    }
}
