<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\{ToCollection};
use App\Egresado;

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
                'titulo' => $row[5]
                // TODO: Obtener demas datos del excel.
            ];
            array_push($this->egresados, $egresado);
            $this->len += 1;
        }
    }

    private function _obtenerIdentificacion($identificacion)
    {
        $retorno = [];
        preg_match('/[0-9]+(.[0-9])+/', $identificacion, $retorno);
        $identificacion = count($retorno) > 0 ? str_replace('.', '', $retorno[0]) : '';
        return $identificacion;        
    }
}
