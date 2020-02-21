<?php

namespace App\Search\Evento\Filters;

use App\Search\Filter;
use Illuminate\Database\Eloquent\Builder;

class Lugar implements Filter {

    public static function apply(Builder $builder, $value)
    {
        return $builder->where('lugar', 'ilike', "%{$value}%");
    }

}