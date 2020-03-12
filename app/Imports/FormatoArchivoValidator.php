<?php

namespace App\Imports;

class FormatoArchivoValidator
{
    private const RULES = [
        'consecutivo',
        'acta y fecha',
        'fecha de grado',
        'nombres',
        'apellidos',
        'cédula',
        'título',
        'mención',
        'programa',
    ];

    public static function validarCabecera($header)
    {
        $retvalue = true;
        foreach ($header as $idx => $value) {
            if (0 != strcmp(mb_strtolower($value), self::RULES[$idx])) {
                $retvalue = false;

                break;
            }
        }

        return $retvalue;
    }
}
