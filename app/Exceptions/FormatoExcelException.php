<?php

namespace App\Exceptions;

use Exception;

class FormatoExcelException extends Exception
{
    public function __construct()
    {
        $this->code = 701;
        $this->message = 'El archivo no cumple con el formato establecido';
    }
}
