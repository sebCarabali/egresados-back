<?php

namespace App\Helpers\VerificarEgresado;

class FilasInconsistentesValidator
{
    public static function validarFila($row)
    {
        return self::validarVacios($row) || self::validarFormatos($row);
    }

    private static function validarVacios($row)
    {
        return empty($row['nombres']) ||
                empty($row['apellidos']) ||
                empty($row['cedula']) ||
                empty($row['fechaGrado']) ||
                empty($row['programa']);
    }

    private static function validarFormatos($row)
    {
        return '' == $row['fechaGrado'];
    }
}
