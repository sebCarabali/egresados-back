<?php

namespace App\Http\Controllers\API;

use App\Egresado;
use App\Programa;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Imports\EgresadosImport;
use Excel;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class VerificarEgresadoController extends Controller {

    // Para el estado del egresado
    const ACTIVO_LOGUEADO = 'ACTIVO LOGUEADO';
    const ACTIVO_NO_LOGUEADO = 'ACTIVO NO LOGUEADO';
    // Para el tipo de grado
    const GRADO_POSTUMO = 'GRADO POSTUMO';
    const GRADO_PRIVADO = 'GRADO PRIVADO';
    // Para grados
    const ACTIVO = 'ACTIVO';
    const PENDIENTE = 'PENDIENTE';

    /**
     * Usada para verificar los egresados, que se encuentran registrados
     * contrastandolos con los que reporta la secretaria general.
     */
    public function verificar(Request $request) {
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

    private function crearValidador($file) {
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

    private function obtenerDatosExcel($file) {
        $import = new EgresadosImport();
        Excel::import($import, $file);
        $egresados = $import->egresados;
        return $egresados;
    }

    private function procesaroDatosExcel($excelData) {
        // Estados de egresados después de las validaciones.
        $inconsistentes = [];
        $activosLogueados = [];
        $activosNoLogueados = [];
        $clasificacion = [
            'activos_logueados' => [],
            'activos_no_logueados' => []
        ];
        DB::beginTransaction();
        try {
            // Recorrer todas las filas de los datos del excel.
            foreach ($excelData as $row) {
                // Verifica si faltan campos en la fila, de se así está fila queda inconsistente.
                if ($this->esFilaInconsistente($row)) {
                    array_push($inconsistentes, $row);
                    continue;
                }
                $egresado = Egresado::where('identificacion', $row['cedula'])->first();
                $clasificacion = $this->clasificarEgresado($egresado, $row, $activosLogueados, $activosNoLogueados);
                $activosLogueados = $clasificacion['activos_logueados'];
                $activosNoLogueados = $clasificacion['activos_no_logueados'];
            }
            DB::commit();
            return ['aceptados' => $activosLogueados, 'pendientes' => $activosNoLogueados, 'inconsistentes' => $inconsistentes];
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    private function clasificarEgresado($egresado, $row, $activosLogueados, $activosNoLogueados) {
        if ($egresado) { // El egresado ya se encuentra registrad en la base de datos.
            $activosLogueados = $this->esEgresadoPendiente($egresado, $row, $activosLogueados);
            $activosNoLogueados = $this->esActivoNoLogueadoConGradoNuevo($egresado, $row, $activosNoLogueados);
            $activosLogueados = $this->esActivoLogueadoConGradoNuevo($egresado, $row, $activosLogueados);
        } else {
            $this->registrarActivoNoLogueado($row);
            array_push($activosNoLogueados, $row);
        }
        return [
            'activos_logueados' => $activosLogueados,
            'activos_no_logueados' => $activosNoLogueados
        ];
    }

    private function esActivoLogueadoConGradoNuevo($egresado, $row, $activosLogueados) {
        if($egresado->estado === self::ACTIVO_LOGUEADO
                && $this->esGradoDiferente($egresado, $row['programa'])){
            $this->registrarNuevoGrado($egresado, $row);
            array_push($activosLogueados, $row);
        }
        return $activosLogueados;
    }

    private function esActivoNoLogueadoConGradoNuevo($egresado, $row, $activosNoLogueados) {
        if ($egresado->estado === self::ACTIVO_NO_LOGUEADO 
                && $this->esGradoDiferente($egresado, $row['programa'])) {
            $this->registrarNuevoGrado($egresado, $row);
            array_push($activosNoLogueados, $row);
        }
        return $activosNoLogueados;
    }

    private function esEgresadoPendiente($egresado, $row, $activosLogueados) {
        // si el estado de egresado es pendiente entonces cambiar a activo logueado y agregar a la lista de
        // activos logueados HE07-HU01
        if ($egresado->estado == self::PENDIENTE) {
            $egresado->estado = self::ACTIVO_LOGUEADO;
            // si el campo fecha está vacio, se cambia por el de excel de secretaria
            if (empty($egresado->fecha_graduacion)) {
                $egresado->fecha_graduacion = $row['fechaGrado'];
            }
            // si el campo mención está vacio, se cambia por el de excel de secretaria
            if (empty($egresado->mencion_honor)) {
                $egresado->mencion_honor = $row['mencion'];
            }
            $egresado->save();
            array_push($activosLogueados, $row);
        }
        return $activosLogueados;
    }

    private function esFilaInconsistente($row) {
        return empty($row['nombres']) ||
                empty($row['apellidos']) ||
                empty($row['cedula']) ||
                empty($row['fechaGrado']) ||
                empty($row['programa']) ||
                empty($row['actaYFecha']) ||
                empty($row['titulo']);
    }

    private function registrarNuevoGrado($egresado, $row) {
        $programa = Programa::where(DB::raw('upper(nombre)'), $row['programa'])->first();
        if (!$programa) {
            throw new Exception('El Programa ' . $row['programa'] . ' no existe');
        }  
        $egresado->programas()->attach($programa->id_aut_programa, [
            'fecha_graduacion' => $row['fechaGrado'],
            'anio_graduacion' => $row['anioGrado'],
            'tipo_grado' => $this->obtenerTipoGrado($row),
            'mencion_honor' => $row['mencion'],
            'titulo_especial' => $row['titulo'],
            'estado' => self::ACTIVO
        ]);
        return $egresado;
    }
    
    private function obtenerTipoGrado($row)
    {
        $tipoGrado = self::GRADO_PRIVADO;
        if($this->esGradoPostumo($row)) {
            $tipoGrado = self::GRADO_POSTUMO;
        } else if($this->esGradoPrivado($row)) {
            $tipoGrado = self::GRADO_PRIVADO;
        }
        return $tipoGrado;
    }

    private function registrarActivoNoLogueado($row) {
        $egresado = Egresado::create([
                    'nombres' => $row['nombres'],
                    'apellidos' => $row['apellidos'],
                    'identificacion' => $row['cedula'],
                    'estado' => self::ACTIVO_NO_LOGUEADO
        ]);
        $respuesta = $this->registrarNuevoGrado($egresado, $row);
        return $respuesta;
    }

    private function esGradoDiferente(Egresado $egresado, $programaExcel) {
        $idProgramasEgresado = DB::table('grados')->select('id_programa')
                        ->where('id_egresado', $egresado->id_aut_egresado)
                        ->where('estado', self::ACTIVO)
                        ->pluck('id_programa')->toArray();
        $programas = Programa::select('nombre')->whereIn('id_aut_programa', $idProgramasEgresado)->get();
        $yaGraduadoDelPrograma = $programas->contains(function($key, $value) use ($programaExcel) {
            return $value === $programaExcel;
        });
        // Si no se ha graduado del programa retorna true de lo contrario falso
        return !$yaGraduadoDelPrograma;
    }

    private function esGradoPostumo($row) {
        return $row['actaYFecha'] == self::GRADO_POSTUMO;
    }

    private function esGradoPrivado($row) {
        return $row['actaYFecha'] == self::GRADO_PRIVADO;
    }

}
