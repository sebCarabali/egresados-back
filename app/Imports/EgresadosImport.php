<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\{ToCollection};
use App\Egresado;
use Exception;

class EgresadosImport implements ToCollection
{

    public $egresados;
    public $len;

    public function __construct()
    {
        $this->egresados = [];
        $this->len = 0;
    }

    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        foreach($rows as $row) {
            $egresado = [
                'nombres' => $row[6],
                'apellidos' => $row[7],
                'identificacion' => $this->_obtenerIdentificacion($row[8]),
                'titulo' => $row[5],
                'fecha_grado' => $this->_obtenerFechaGrado($row[2])
                // TODO: Obtener demas datos del excel.
            ];
            array_push($this->egresados, $egresado);
            $this->len += 1;
        }
    }

    private function _obtenerNumeroMes($mes) {
        $meses = [
            'enero' => 1,
            'febrero' => 2,
            'marzo' => 3,
            'abril' => 4,
            'mayo' => 5,
            'junio' => 6,
            'julio' => 7,
            'agosto' => 8,
            'septiembre' => 9,
            'octubre' => 10,
            'noviembre' => 11,
            'diciembre' => 12
        ];
        return $meses[$mes];
    }

    private function _obtenerFechaGrado($fecha) {
        preg_match('/(?P<dia>[0-9]{2})(.*)(?P<mes>(enero|febrero|marzo|abril|mayo|junio|julio|agosto|septiembre|octubre|noviembre|diciembre))(.*)(?P<anio>[0-9]{4})/i',
            $fecha, $result);
        if($result) {
            $dia = $result['dia'];
            $mes = $this->_obtenerNumeroMes($result['mes']);
            $anio = $result['anio'];
            $datestr = $mes . '/' . $dia . '/' . $anio;
            return date('m/d/Y', strtotime($datestr));
        }
        return null;
    }

    private function _obtenerIdentificacion($identificacion)
    {
        return preg_replace('/[^0-9]/', '', $identificacion);
    }
}
