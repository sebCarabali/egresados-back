<?php

namespace App\Search\Evento;

use App\Evento;
use App\Search\Search;

class EventoSearch extends Search
{
    protected static function getBuilder()
    {
        return (new Evento())->newQuery();
    }

    protected static function getNameSpace()
    {
        return __NAMESPACE__;
    }
}
