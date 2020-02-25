<?php

namespace App\Search\Apoyo;

use App\Apoyo;
use App\Search\Search;

class ApoyoSearch extends Search
{
    protected static function getBuilder()
    {
        return (new Apoyo())->newQuery();
    }

    protected static function getNameSpace()
    {
        return __NAMESPACE__;
    }
}
