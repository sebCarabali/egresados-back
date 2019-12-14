<?php

namespace App\Http\Controllers\API;

use App\Egresado;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Imports\EgresadosImport;
use App\Programa;
use Excel;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class VerificarEgresadoController extends Controller
{
    /**
     * Usada para verificar los egresados, que se encuentran registrados
     * contrastandolos con los que reporta la secretaria general.
     */
    public function verificar(Request $request)
    {
        $file = $request->file('fileInput');
        $validator = $this->crearValidador($file);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }
        $excelData = $this->obtenerDatosExcel($file);
        // TODO: Validar egresados
        try {
            $resultado = $this->procesaroDatosExcel($excelData);
            $data = [
                'aceptados' => $resultado['aceptados'],
                'pendientes' => $resultado['pendientes'],
                'inconsistentes' => $resultado['inconsistentes']
            ];
            return response()->json(['data' => $data], 200);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    private function crearValidador($file)
    {
        $input = [
            'file' => $file,
            'extension' => strtolower($file->getClientOriginalExtension())
        ];
        $rules = [
            'file' => 'required',
            'extension' => 'required|in:xlsx,xsl,csv,ods'
        ];
        $messages = [
            'file.required' => 'Es necesario pasar un archivo.',
            'extension.required' => 'Debe ser un archivo con extension.',
            'extension.in' => 'Debe se un arvhivo excel válido.'
        ];
        return Validator::make($input, $rules, $messages);
    }

    private function obtenerDatosExcel($file)
    {
        $import = new EgresadosImport();
        Excel::import($import, $file);
        $egresados = $import->egresados;
        return $egresados;
    }

    private function procesaroDatosExcel($excelData)
    {
        // Estados de egresados después de las validaciones.
        $inconsistentes = [];
        $aceptados = [];
        $pendientes = [];
        DB::beginTransaction();
        try {
            // Recorrer todas las filas de los datos del excel.
            foreach ($excelData as $row) {
                // Verifica si faltan campos en la fila, de se así está fila queda inconsistente.
                if ($this->esFilaInconsistente($row)) {
                    array_push($inconsistentes, $row);
                    continue;
                }
                // obtener egresados por la identificación.
                $egresado = Egresado::where('identificacion', $row['cedula'])->first();
                if (!$egresado) {
                    $this->registrarPendiente($row);
                    array_push($pendientes, $row);
                } else {
                    // Si el egresados se encuentra como EN_ESPERA
                    // cambiar estado y transformar algunos datos a los que llegan del archivo de secretaria.
                    if ($egresado->estado == 'EN_ESPERA') {
                        // cambiar estado a ACTIVO y agregar a aceptados
                        $egresado->estado = 'ACTIVO';
                        $egresado->save();
                        $row['nombres'] = $egresado->nombres;
                        $row['apellidos'] = $egresado->apellidos;
                        array_push($aceptados, $row);
                    } else if ($egresado->estado == 'PENDIENTE' || $egresado->estado == 'ACTIVO') {
                        $this->registrarNuevoGrado($egresado, $row);
                        array_push($pendientes, $row);
                    }
                }
            }
            DB::commit();
            return [
                'aceptados' => $aceptados,
                'pendientes' => $pendientes,
                'inconsistentes' => $inconsistentes
            ];
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    private function esFilaInconsistente($row)
    {
        return empty($row['nombres']) ||
            empty($row['apellidos']) ||
            empty($row['cedula']) ||
            empty($row['fechaGrado']) ||
            empty($row['programa']) ||
            empty($row['titulo']);
    }

    private function registrarNuevoGrado($egresado, $row)
    {
        $programa = Programa::where(DB::raw('upper(nombre)'), $row['programa'])->first();
        if (!$programa) {
            throw new Exception('El Programa ' . $row['programa'] . ' no existe');
        }
        $egresado->programas()->attach($programa->id_aut_programa, [
            'fecha_graduacion' => $row['fechaGrado'],
            'anio_graduacion' => $row['anioGrado'],
            'tipo_grado' => is_int($row['consecutivo']) ? '' : mb_strtoupper($row['consecutivo']),
            //'mencion_honor' => $row['mencion'],
            'titulo_especial' => strcmp(mb_strtoupper($programa->nombre), 'MÚSICA INSTRUMENTAL') == 0 ? $row['titulo'] : ''
        ]);
        return $egresado;
    }

    private function registrarPendiente($row)
    {
        $egresado = Egresado::create([
            'nombres' => $row['nombres'],
            'apellidos' => $row['apellidos'],
            'identificacion' => $row['cedula'],
            'estado' => 'PENDIENTE'
        ]);
        $respuesta = $this->registrarNuevoGrado($egresado, $row);
        return $respuesta;
    }
}
