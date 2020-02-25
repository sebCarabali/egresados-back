<?php

namespace App\Search\Egresado;

use App\Egresado;
use App\Search\Search;

class EgresadoSearch extends Search
{
    protected static function getBuilder()
    {
        return (new Egresado)->newQuery();
    }

    protected static function getNameSpace()
    {
        return __NAMESPACE__;
    }
}
