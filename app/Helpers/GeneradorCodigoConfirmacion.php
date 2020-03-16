<?php

namespace App\Helpers;

use Illuminate\Database\Eloquent\Model;

class GeneradorCodigoConfirmacion
{
    /**
     * Genera un código de confirmación.
     *
     * @param Model  $model   modelo donde se verifica si el código ya existe
     * @param string $columna nombre de la columna donde se guarda el código de confirmación
     * @param int    $tam     tamaño del código a generar
     *
     * @return string código de confirmación generado
     */
    public static function generar(Model $model, string $columna, $tam = 16)
    {
        $alfabeto = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        $yaExiste = false;
        do {
            $codigo = mb_substr(str_shuffle($alfabeto), 0, $tam);
            $yaExiste = self::yaExiste($model, $columna, $codigo);
        } while ($yaExiste);

        return $codigo;
    }

    private static function yaExiste(Model $model, string $columna, $codigo)
    {
        return $model->where($columna, $codigo)->exists();
    }
}
