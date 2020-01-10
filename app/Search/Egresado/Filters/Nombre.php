<?php


namespace App\Search\Egresado\Filters;

use App\Search\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class Nombre implements Filter {

    public static function apply(Builder $builder, $value)
    {
        //$value = mb_strtoupper($value);
        return $builder->where(DB::raw("concat(nombres, ' ', apellidos)"), 'ilike', "%$value%");
    }

}