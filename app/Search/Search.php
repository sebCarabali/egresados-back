<?php

namespace App\Search;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

abstract class Search
{

    public static function apply(Request $filters)
    {
        $query = static::applyDecorators($filters);
        return $query->get();
    }

    private static function applyDecorators(Request $request)
    {
        $builder = static::getBuilder();
        foreach($request->all() as $filterName => $value) {
            $decorator = static::createFilterDecorator($filterName);
            if(class_exists($decorator)) {
                $builder = $decorator::apply($builder, $value);
            }
        }
        return $builder;
    }

    private static function createFilterDecorator($name)
    {
        $namespace = static::getNameSpace();
        return $namespace . '\\Filters\\' . str_replace(' ', '',
             ucwords(str_replace('_', ' ', $name)));
    }

    protected static abstract function getBuilder();

    protected static abstract function getNameSpace();
}
