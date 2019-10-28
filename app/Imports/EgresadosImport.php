<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class EgresadosImport implements ToCollection
{

    public $egresados;

    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
     
        $this->egresados = array();
        foreach($rows as $row) {
            $egresado = [
                'nombres' => $row[6],
                'apellidos' => $row[7],
                'cedula' => $row[8]
            ];
            array_push($this->egresados, $egresado);
        }
    }
}
