<?php

namespace App\Helpers;

class PgDateHelper
{
    /**
     * Transforma una fecha en formato dd/MM/yyyy a yyyy-MM-dd.
     *
     * @param string $dateStr
     *
     * @return string fecha en nuevo formato
     */
    public static function stringToPgSqlFormat($dateStr)
    {
        $dateArr = explode('/', $dateStr);

        return $dateArr[2].'-'.$dateArr[1].'-'.$dateArr[0];
    }

    /**
     * Transforma una fecha en formato yyyy-MM-dd a dd/MM/yyyy.
     *
     * @param mixed $dateStr
     *
     * @return string fecha en nuevo formato
     */
    public static function pgsqlToStringFormat($dateStr)
    {
        $dateArray = explode('-', $dateStr);

        return $dateArray[2].'/'.$dateArray[1].'/'.$dateArray[0];
    }

    public static function machete($dateStr)
    {
        $dateArr = explode('-', $dateStr);

        $dia = substr($dateArr[2], 0, 2);

        return $dateArr[0].'-'.$dateArr[1].'-'.$dia;
    }
}
