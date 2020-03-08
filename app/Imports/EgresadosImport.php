<?php

namespace App\Imports;

use App\Exceptions\FormatoExcelException;
use Exception;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\{ToCollection};

class EgresadosImport implements ToCollection
{
    public $egresados;
    public $len;
    private $primeraLinea = true;

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
        foreach ($rows as $row) {
            if (!$this->primeraLinea) {
                $fechaGrado = $this->_obtenerFechaGrado($row[2]);
                $egresado = [
                    'consecutivo' => $row[0],
                    'actaYFecha' => $row[1],
                    'fechaGrado' => $fechaGrado,
                    'nombres' => mb_strtoupper($row[3]),
                    'apellidos' => mb_strtoupper($row[4]),
                    'cedula' => $this->_obtenerIdentificacion($row[5]),
                    'titulo' => mb_strtoupper($row[6]),
                    'mencion' => mb_strtoupper($row[7]),
                    'programa' => mb_strtoupper($row[8]),
                    'anioGrado' => explode('/', $fechaGrado)[2],
                ];
                array_push($this->egresados, $egresado);
            } elseif (!FormatoArchivoValidator::validarCabecera($row)) {
                throw new FormatoExcelException('El archivo no cumple con el formato establecido');
            }
            $this->primeraLinea = false;
        }
    }

    private function _obtenerNumeroMes($mes)
    {
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
            'diciembre' => 12,
        ];

        return $meses[$mes];
    }

    private function _obtenerFechaGrado($fecha)
    {
        preg_match(
            '/(?P<dia>[0-9]{2})(.*)(?P<mes>(enero|febrero|marzo|abril|mayo|junio|julio|agosto|septiembre|octubre|noviembre|diciembre))(.*)(?P<anio>[0-9]{4})/i',
            $fecha,
            $result
        );
        if ($result) {
            $dia = $result['dia'];
            $mes = $this->_obtenerNumeroMes($result['mes']);
            $anio = $result['anio'];
            $datestr = $mes.'/'.$dia.'/'.$anio;

            return date('m/d/Y', strtotime($datestr));
        }

        throw new Exception('No es posible transformar la fecha de grado: '.$fecha);
    }

    private function _obtenerIdentificacion($identificacion)
    {
        return preg_replace('/[^0-9]/', '', $identificacion);
    }
}
