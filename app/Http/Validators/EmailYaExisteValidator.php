<?php

namespace App\Http\Validators;

use App\Apoyo;
use App\User;
use Illuminate\Support\Facades\DB;

class EmailYaExisteValidator
{
    /**
     * Valida si un usuario ya cuenta con el email suministrado.
     *
     * @param string $email
     * @param mixed  $idApoyo
     *
     * @return bool
     */
    public static function validarEmail($email, $idApoyo)
    {
        $apoyo = Apoyo::where('id_aut_apoyo', $idApoyo)->first();
        if ($apoyo && 0 == strcmp(
            mb_strtolower($apoyo->correo),
            mb_strtolower($email)
        )) {
            return false;
        }

        return User::where(DB::raw('LOWER(email)'), mb_strtolower($email))->exists();
    }
}
