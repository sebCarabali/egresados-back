<?php

namespace App\Http\Controllers\API;

use App\Egresado;
use App\Exceptions\FormatoExcelException;
use App\Grado;
use App\Helpers\VerificarEgresado\FilasInconsistentesValidator;
use App\Http\Controllers\Controller;
use App\Imports\EgresadosImport;
use App\Programa;
use App\Repository\GradoRepositoryInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class VerificarEgresadoController extends Controller
{
    // Para el estado del egresado
    const ACTIVO_LOGUEADO = 'ACTIVO LOGUEADO';
    const ACTIVO_NO_LOGUEADO = 'ACTIVO NO LOGUEADO';
    // Para el tipo de grado
    const GRADO_POSTUMO = 'GRADO POSTUMO';
    const GRADO_PRIVADO = 'GRADO PRIVADO';
    // Para grados
    const ACTIVO = 'ACTIVO';
    const PENDIENTE = 'PENDIENTE';

    private $gradoRepository;

    public function __construct(GradoRepositoryInterface $gradoRepository)
    {
        $this->gradoRepository = $gradoRepository;
    }

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
        // TODO: Validar egresados
        try {
            $excelData = $this->obtenerDatosExcel($file);

            $resultado = $this->procesarDatosExcel($excelData);
            $data = [
                'aceptados' => $resultado['aceptados'],
                'pendientes' => $resultado['pendientes'],
                'inconsistentes' => $resultado['inconsistentes'],
            ];

            return response()->json(['data' => $data], 200);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        } catch (FormatoExcelException $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    private function crearValidador($file)
    {
        $input = [
            'file' => $file,
            'extension' => strtolower($file->getClientOriginalExtension()),
        ];
        $rules = [
            'file' => 'required',
            'extension' => 'required|in:xlsx,xsl,csv,ods',
        ];
        $messages = [
            'file.required' => 'Es necesario pasar un archivo.',
            'extension.required' => 'Debe ser un archivo con extension.',
            'extension.in' => 'Debe se un arvhivo excel válido.',
        ];

        return Validator::make($input, $rules, $messages);
    }

    private function obtenerDatosExcel($file)
    {
        try {
            $import = new EgresadosImport();
            Excel::import($import, $file);

            return $import->egresados;
        } catch (FormatoExcelException $e) {
            throw $e;
        }
    }

    private function procesarDatosExcel($excelData)
    {
        // Estados de egresados después de las validaciones.
        $inconsistentes = [];
        $activosLogueados = [];
        $activosNoLogueados = [];
        $clasificacion = [
            'activos_logueados' => [],
            'activos_no_logueados' => [],
        ];
        DB::beginTransaction();

        try {
            // Recorrer todas las filas de los datos del excel.
            foreach ($excelData as $row) {
                // Verifica si faltan campos en la fila, de se así está fila queda inconsistente.
                if (FilasInconsistentesValidator::validarFila($row)) {
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
        } catch (FormatoExcelException $e) {
            DB::rollback();

            throw $e;
        }
    }

    private function clasificarEgresado($egresado, $row, $activosLogueados, $activosNoLogueados)
    {
        if ($egresado) { // El egresado ya se encuentra registrad en la base de datos.
            switch ($egresado->estado) {
                case self::PENDIENTE:
                    $activosLogueados = $this->esEgresadoPendiente($egresado, $row, $activosLogueados);

                break;
                case self::ACTIVO_NO_LOGUEADO:
                    $activosNoLogueados = $this->esActivoNoLogueadoConGradoNuevo($egresado, $row, $activosNoLogueados);

                break;
                case self::ACTIVO_LOGUEADO:
                    $activosLogueados = $this->esActivoLogueadoConGradoNuevo($egresado, $row, $activosLogueados);

                break;
            }
            /* $activosLogueados = $this->esEgresadoPendiente($egresado, $row, $activosLogueados);
            $activosNoLogueados = $this->esActivoNoLogueadoConGradoNuevo($egresado, $row, $activosNoLogueados);
            $activosLogueados = $this->esActivoLogueadoConGradoNuevo($egresado, $row, $activosLogueados); */
        } else {
            $this->registrarActivoNoLogueado($row);
            array_push($activosNoLogueados, $row);
        }

        return [
            'activos_logueados' => $activosLogueados,
            'activos_no_logueados' => $activosNoLogueados,
        ];
    }

    private function esActivoLogueadoConGradoNuevo($egresado, $row, $activosLogueados)
    {
        if (self::ACTIVO_LOGUEADO === $egresado->estado) {
            if ($this->esGradoDiferente($egresado, $row['programa'], self::PENDIENTE)) {
                $this->registrarNuevoGrado($egresado, $row, self::ACTIVO);
                array_push($activosLogueados, $row);
            } else {
                $grado = $this->gradoRepository->obtenerGradoPorProgramaYEgresado($row['programa'], $egresado->id_aut_egresado);
                $grado->estado = self::ACTIVO;
                $grado->save();
                array_push($activosLogueados, $row);
            }
        }

        return $activosLogueados;
    }

    private function esActivoNoLogueadoConGradoNuevo($egresado, $row, $activosNoLogueados)
    {
        if (self::ACTIVO_NO_LOGUEADO === $egresado->estado
                && $this->esGradoDiferente($egresado, $row['programa'], self::ACTIVO)) {
            $this->registrarNuevoGrado($egresado, $row, self::ACTIVO);
            array_push($activosNoLogueados, $row);
        }

        return $activosNoLogueados;
    }

    private function esEgresadoPendiente($egresado, $row, $activosLogueados)
    {
        // si el estado de egresado es pendiente entonces cambiar a activo logueado y agregar a la lista de
        // activos logueados HE07-HU01
        if (self::PENDIENTE == $egresado->estado) {
            $egresado->estado = self::ACTIVO_LOGUEADO;
            $grados = Grado::where('id_egresado', $egresado->id_aut_egresado)->where('estado', self::PENDIENTE);
            $grados->update(['estado' => self::ACTIVO]);
            $egresado->save();
            array_push($activosLogueados, $row);
        }

        return $activosLogueados;
    }

    private function esFilaInconsistente($row)
    {
        return empty($row['nombres']) ||
                empty($row['apellidos']) ||
                empty($row['cedula']) ||
                empty($row['fechaGrado']) ||
                empty($row['programa']) ||
                empty($row['actaYFecha']) ||
                empty($row['titulo']);
    }

    private function registrarNuevoGrado($egresado, $row, $estado)
    {
        $programa = Programa::where(DB::raw('upper(nombre)'), $row['programa'])->first();
        if (!$programa) {
            throw new Exception('El Programa '.$row['programa'].' no existe');
        }
        $egresado->programas()->attach($programa->id_aut_programa, [
            'fecha_graduacion' => $row['fechaGrado'],
            'anio_graduacion' => $row['anioGrado'],
            'tipo_grado' => $this->obtenerTipoGrado($row),
            'mencion_honor' => $row['mencion'],
            'titulo_especial' => $row['titulo'],
            'estado' => $estado,
        ]);

        return $egresado;
    }

    private function obtenerTipoGrado($row)
    {
        $tipoGrado = self::GRADO_PRIVADO;
        if ($this->esGradoPostumo($row)) {
            $tipoGrado = self::GRADO_POSTUMO;
        } elseif ($this->esGradoPrivado($row)) {
            $tipoGrado = self::GRADO_PRIVADO;
        }

        return $tipoGrado;
    }

    private function registrarActivoNoLogueado($row)
    {
        $egresado = Egresado::create([
            'nombres' => $row['nombres'],
            'apellidos' => $row['apellidos'],
            'identificacion' => $row['cedula'],
            'estado' => self::ACTIVO_NO_LOGUEADO,
        ]);

        //return $this->registrarNuevoGrado($egresado, $row, self::PENDIENTE);
    }

    private function esGradoDiferente(Egresado $egresado, $programaExcel, $estado)
    {
        $programas = Grado::where('id_egresado', $egresado->id_aut_egresado)->pluck('id_programa')->toArray();
        $yaGraduadoDePrograma = Programa::where('nombre', $programaExcel)->whereIn('id_aut_programa', $programas)->exists();

        return !$yaGraduadoDePrograma;
    }

    private function esGradoPostumo($row)
    {
        return self::GRADO_POSTUMO == $row['actaYFecha'];
    }

    private function esGradoPrivado($row)
    {
        return self::GRADO_PRIVADO == $row['actaYFecha'];
    }
}
