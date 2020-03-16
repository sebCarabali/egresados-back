<?php

namespace App\Http\Validators;

use App\User;
use Illuminate\Support\Facades\DB;

class EmailYaExisteValidator
{
    /**
     * Valida si un usuario ya cuenta con el email suministrado.
     *
     * @param string $email
     *
     * @return bool
     */
    public static function validarEmail($email)
    {
        return User::where(DB::raw('LOWER(email)'), mb_strtolower($email))->exists();
    }
}
