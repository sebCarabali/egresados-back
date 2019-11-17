<?php

namespace App\Http\Controllers;

use App\AreaConocimiento;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Oferta;
use App\Cargo;
use App\Contrato;
use App\CategoriaCargo;
use App\Empresa;
use App\Http\Requests\OfertaStoreRequest;
use App\Http\Resources\AreaConocimientoResource;
use App\Http\Resources\SalarioResource;
use App\OfertaSoftware;
use App\PreguntaOferta;
use App\Salario;
use Exception;
use Illuminate\Support\Facades\DB;

class OfertaController extends Controller
{
  public function getOfertasEnEspera()
  {
    $ofertas = Oferta::where('estado', 'Pendiente')->get();

    $ofertas->load('empresa');
    $ofertas->load('areasConocimiento');
    $ofertas->load('salario');

    // Se borra el atributo pivot, el cual no es necesario
    foreach ($ofertas as $oferta) {
      foreach ($oferta->areasConocimiento as $areacon) {
        unset($areacon['pivot']);
      }
    }

    return response()->json($ofertas, 200);
  }

  public function updateEstado(Request $request, $id)
    {
        // Código de error por defecto
        $code = 400;
        $data = null;
        try {
            $this->validate(request(), [
                'estado' => 'required|string',
            ]);
            // Buscar el registro
            $oferta = Oferta::find($id);
            if (!empty($oferta) && is_object($oferta)) {
                switch ($request['estado']) {
                    case 'Aceptada':
                    case 'Rechazada':
                    case 'Pendiente':
                        $oferta->update(['estado' => $request['estado']]);
                        $data = $oferta;
                        $code = 200;
                        break;
                }
            }
        } catch (ValidationException $ev) {
            return response()->json($ev->validator->errors(), $code);
        } catch (Exception $e) {
            return response()->json($e);
        }
        return response()->json($data, $code);
    }
    public function updateEstadoProceso(Request $request, $id)
    {
        // Código de error por defecto
        $code = 400;
        $data = null;
        try {
            $this->validate(request(), [
                'estado_proceso' => 'required|string',
            ]);
            // Buscar el registro
            $oferta = Oferta::find($id);
            if (!empty($oferta) && is_object($oferta) && $oferta['estado'] != 'Pendiente') {
                switch ($request['estado_proceso']) {
                    case 'En espera':
                    case 'Activa':
                    case 'En selección':
                    case 'Finalizada con contratación':
                    case 'Finalizada sin contratación':
                    case 'Expirada':
                        $oferta->update(['estado_proceso' => $request['estado_proceso']]);
                        $data = $oferta;
                        $code = 200;
                        break;
                }
            }
        } catch (ValidationException $ev) {
            return response()->json($ev->validator->errors(), $code);
        } catch (Exception $e) {
            return response()->json($e, $code);
        }
        return response()->json($data, $code);
    }

  public function getSalarioPorModena($nombreMoneda)
  {
    // return response()->json($nombreMoneda);
    return SalarioResource::collection(Salario::where("forma_pago", $nombreMoneda)->get());
  }
  public function getAllSalario()
  {
    return response()->json(Salario::all());
  }
  public function getAllAreas()
  {
    return AreaConocimientoResource::collection(AreaConocimiento::all());
  }

  // public function storeOferta(Empresa $empresa, Request $request)
  public function storeOferta(Empresa $empresa, OfertaStoreRequest $request)
  {
    // return response()->json($request);
    try {

      DB::beginTransaction();
      // Contrato que tendrá la oferta
      $contrato = new Contrato();
      $contrato->tipo_contrato = $request['contrato']['tipoContrato']; //Enum ('Término indefinido', 'Contrato de aprendizaje', 'Prestación de servicios', 'Obra a labor determinada', 'Término fijo')
      $contrato->jornada_laboral = $request['contrato']['jornada']; //Enum ('Medio tiempo', 'Tiempo completo', 'Tiempo parcial');
      if (isset($request['contrato']['duracion'])) {
        $contrato->duracion = $request['contrato']['duracion'];
      }
      if (isset($request['contrato']['horario'])) {
        $contrato->horario = $request['contrato']['horario'];
      }
      if (isset($request['contrato']['comentariosSalario'])) {
        $contrato->comentarios_salario = $request['contrato']['comentariosSalario'];
      }

      $contrato->save();
      // Se busca o crea el cargo

      $id_cargo = null;
      if (isset($request['informacion-principal']['idCargo'])) {
        $id_cargo = $request['informacion-principal']['idCargo'];
      } else {
        $cargo = new Cargo();
        $cargo->nombre = $request['informacion-principal']['otroCargo'];
        $cargo->estado = false;
        $current_id = DB::table('cargos')->max('id_aut_cargos');
        $cargo->id_aut_cargos = $current_id + 1;
        $cargo->save();
        $id_cargo =  $cargo->id_aut_cargos;
      }

      $oferta = new Oferta();
      $oferta->id_empresa = $empresa->id_aut_empresa;
      $oferta->nombre_oferta = $request['informacion-principal']['nombreOferta']; //
      $oferta->descripcion = $request['informacion-principal']['descripcion']; //
      $oferta->id_cargo = $id_cargo;
      $oferta->id_contrato = $contrato->id_aut_contrato;

      $oferta->numero_vacantes = $request['informacion-principal']['numVacantes']; //
      $oferta->id_forma_pago = $request['contrato']['formaPago'];
      $oferta->experiencia = $request['requisitos']['experienciaLaboral']; // Enum ('Sin experiencia', 'Igual a', 'Mayor o igual que', 'Menor o igual que')
      $oferta->anios_experiencia = $request['requisitos']['anios']; //
      // $oferta->fecha_publicacion = ""; //
      // $oferta->fecha_cierre = ""; //
      $oferta->estado = "Pendiente"; // Enum ('Aceptada', 'Rechazada', 'Pendiente');  --Administrador
      $oferta->estado_proceso = "En espera"; // ('En seleccion', 'Desactivada', 'Expirada');  --Empresa
      $oferta->id_sector = $request['informacion-principal']['idSector'];
      if (isset($request['informacion-principal']['nombreTempEmpresa'])) {
        $oferta->nombre_temporal_empresa = $request['informacion-principal']['nombreTempEmpresa']; //
      }
      if (isset($request['requisitos']['licenciaConduccion'])) {
        $oferta->licencia_conduccion = $request['requisitos']['licenciaConduccion']; // Enum ('A1', 'A2', 'B1', 'B2', 'B3', 'C1', 'C2', 'C3')
      }
      $oferta->requisitos_minimos = $request['requisitos']['requisitosMinimos']; // TEsto descriptivo
      if (isset($request['requisitos']['idDiscapacidad'])) {
        $oferta->id_discapacidad = $request['requisitos']['idDiscapacidad']; // Id consultado de la tabla discapacidad
      }
      $oferta->num_dias_oferta = $request['informacion-principal']['vigenciaDias']; // Dias de la oferta Max 30

      $oferta->id_aut_nivprog = $request['requisitos']['idrequisitosMinimos']; // NIvel Programa

      $oferta->save();
      // $empresa->ofertas()->save($oferta);
      // Asigna los id de los idioma requeridos en la oferta
      foreach ($request['requisitos']['idiomas'] as $idioma) {
        $oferta->idiomas()->attach($idioma["id"], [
          "nivel_escritura" => $idioma["nivel_escritura"], //enum ('Nativo', 'Avanzado', 'Medio', 'Bajo')
          "nivel_lectura" => $idioma["nivel_lectura"], //enum ('Nativo', 'Avanzado', 'Medio', 'Bajo')
          "nivel_conversacion" => $idioma["nivel_conversacion"] //enum ('Nativo', 'Avanzado', 'Medio', 'Bajo')
        ]);
      }

      // Asigna los id de las ciudades donde va a estar disponible la oferta
      $oferta->ubicaciones()->attach($request['informacion-principal']['ubicacion']); // Ids consultados de la tabla discapacidad

      // Asigna los id de las areas de conocimientos requeridos por la oferta
      $oferta->areasConocimiento()->attach($request['informacion-principal']['idAreaConocimiento']); // Ids consultados de la tabla areas de conocimiento


      // Asigna los id de los software requeridos en la oferta
      foreach ($request['requisitos']['softwareOferta'] as $soft) {
        $software = new OfertaSoftware();
        $software->nombre = $soft['nombre'];
        $software->nivel = $soft['nivel'];
        $oferta->software()->save($software);
      }

      // PREGUNTAS
      foreach ($request['requisitos']['preguntasCandidato'] as $pregunta) {
        $p = new PreguntaOferta();
        $p->pregunta = $pregunta;
        $oferta->preguntas()->save($p);
      }


      DB::commit();
      return $this->success($oferta);
    } catch (Exception $e) {
      return $this->fail("Registro oferta => " . $e->getMessage());
    }
  }
}


// 'informacion-principal.idCargo'
// 'informacion-principal.otroCargo'
// 'informacion-principal.nombreOferta'
// 'informacion-principal.descripcion'
// 'informacion-principal.idSector'
// 'informacion-principal.nombreTempEmpresa'
// 'informacion-principal.numVacantes'
// 'informacion-principal.vigenciaDias'
// 'informacion-principal.ubicacion'
// 'informacion-principal.idAreaConocimiento'

// 'contrato']['tipoContrato'
// 'contrato']['jornada'
// 'contrato.horario'
// 'contrato.comentariosSalario'
// 'contrato.formaPago'
// 'contrato.duracion'

// 'requisitos.experienciaLaboral'
// 'requisitos.anios'
// 'requisitos.licenciaConduccion'
// 'requisitos.requisitosMinimos'
// 'requisitos.idDiscapacidad'
// 'requisitos.idrequisitosMinimos'
// 'requisitos.idiomas'
// 'requisitos.softwareOferta'
// 'requisitos.preguntasCandidato'
