<?php

namespace App\Search\Egresado\Filters;

use App\Search\Filter;
use Illuminate\Database\Eloquent\Builder;

class Estado implements Filter
{
    public static function apply(Builder $builder, $value)
    {
        $value = mb_strtoupper($value);
        return $builder->where('estado', $value);
    }
}
