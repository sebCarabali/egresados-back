<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Oferta;
use App\Cargo;
use App\Contrato;
use App\CategoriaCargo;
use App\OfertaSoftware;
use App\Salario;
use Illuminate\Support\Facades\DB;

class OfertaController extends Controller
{
    public function getOfertasEnEspera()
    {
        $ofertas = Oferta::where('estado', 'Pendiente')->get();

        $ofertas->load('empresa');
        $ofertas->load('areasConocimiento');

        // Se borra el atributo pivot, el cual no es necesario
        foreach ($ofertas as $oferta) {
            foreach ($oferta->areasConocimiento as $areacon) {
                unset($areacon['pivot']);
            }
        }

        return response()->json($ofertas, 200);
    }

    public function getOfertasEmpresa($id)
    {
        $ofertas = Oferta::where('id_empresa', $id)->get();

        foreach ($ofertas as $oferta) {
            // Obtener el nombre del cargo
            $oferta['nombreCargo'] = Cargo::find($oferta->id_cargo)->first()->nombre;
        }
        return response()->json($ofertas, 200);
    }

    public function getOfertasActivasEmpresa($id)
    {
        $ofertas = Oferta::where([
            'id_empresa' => $id,
            'estado' => 'Aceptada'
        ])->get();

        foreach ($ofertas as $oferta) {
            // Obtener el nombre del cargo
            $oferta['nombreCargo'] = Cargo::find($oferta->id_cargo)->first()->nombre;
        }
        return response()->json($ofertas, 200);
    }

    public function storeOferta(Request $request)
    {
        return response()->json($request);


        // Contrato que tendrá la oferta
        $contrato = new Contrato();
        $contrato->duracion = "";
        $contrato->tipo_contrato = ""; //Enum ('Término indefinido', 'Contrato de aprendizaje', 'Prestación de servicios', 'Obra a labor determinada', 'Término fijo')
        $contrato->jornada_laboral = ""; //Enum ('Medio tiempo', 'Tiempo completo', 'Tiempo parcial');
        $contrato->horario = "";
        // si existe
        $contrato->comentarios_salario = "";

        // Salario que tendrá la oferta
        $salario = new Salario();
        // $salario->minimo = "";
        // $salario->maximo = "";
        // $salario->forma_pago = ""; // Enum  ('Moneda local', 'US Dolar')
        $salario->id_aut_salario = ""; // Enum  ('Moneda local', 'US Dolar')


        $oferta = new Oferta();
        $oferta->nombre_oferta = ""; //
        $oferta->descripcion = ""; //
        // "idCargo": 1,
        // "otroCArgo": "",
        $oferta->numero_vacantes = ""; //
        $oferta->experiencia = ""; // Enum ('Sin experiencia', 'Igual a', 'Mayor o igual que', 'Menor o igual que')
        $oferta->anios_experiencia = ""; //
        $oferta->fecha_publicacion = ""; //
        $oferta->fecha_cierre = ""; //
        $oferta->estado = "Pendiente"; // Enum ('Aceptada', 'Rechazada', 'Pendiente');  --Administrador
        $oferta->estado_proceso = "En seleccion"; // ('En seleccion', 'Desactivada', 'Expirada');  --Empresa
        $oferta->nombre_temporal_empresa = ""; //
        $oferta->licencia_conduccion = ""; // Enum ('A1', 'A2', 'B1', 'B2', 'B3', 'C1', 'C2', 'C3')
        $oferta->requisitos_minimos = ""; // TEsto descriptivo
        $oferta->id_discapacidad = ""; // Id consultado de la tabla discapacidad
        $oferta->dias_vigencia = ""; // Dias de la oferta Max 30
        // "idRequisitosMinimos": 1, // NIvel Programa


        

        // Asigna los id de las ciudades donde va a estar disponible la oferta
        $oferta->ubicaciones()->attach($ubicaiones); // Ids consultados de la tabla discapacidad

        // Asigna los id de las areas de conocimientos requeridos por la oferta
        $oferta->areasConocimiento()->attach($areas); // Ids consultados de la tabla areas de conocimiento

        // Asigna los id de los idioma requeridos en la oferta
        foreach ($idimoas as $idioma) {
            $oferta->idiomas()->attach($idioma->id, [
                "nivel_escritura" => "", //enum ('Nativo', 'Avanzado', 'Medio', 'Bajo')
                "nivel_lectura" => "", //enum ('Nativo', 'Avanzado', 'Medio', 'Bajo')
                "nivel_conversacion" => "" //enum ('Nativo', 'Avanzado', 'Medio', 'Bajo')
            ]);
        }

        // Asigna los id de los software requeridos en la oferta
        foreach ($software as $soft) {
            $s = new OfertaSoftware();
            $s->nombre = $soft['nombre'];
            $s->nombre = $soft['nivel']; // Enum ('Ninguno', 'Nivel bajo', 'Nivel usuario', 'Nivel usuario avanzado', 'Nivel técnico', 'Nivel profesional', 'Nivel experto');
            $oferta->software()->save($s);
        }

        // PREGUNTAS
    }
}
// Contratos ---
// Idiomas ---
// Software ---
// Salario --
// Ubicaciones --
// Areas Conocimiento --
// Cargo --
// Sector NO Subsector, SECTOR --
// Discapacidades --
// Preguntas
// Ofertas


// OFERTAS
// id_aut_oferta
// id_empresa
// nombre_oferta
// descripcion
// id_cargo
// id_contrato
// numero_vacantes
// id_forma_pago
// experiencia
// anios_experiencia
// fecha_publicacion
// fecha_cierre
// estado
// estado_proceso
// id_sector
// nombre_temporal_empresa
// licencia_conduccion
// requisitos_minimos
// id_discapacidad

// Contrato
// id_aut_contrato
// duracion
// tipo_contrato
// jornada_laboral
// horario
// comentarios_salario


// IDOMAS OFERTA
// id_oferta
// id_idioma
// nivel_escritura
// nivel_lectura
// nivel_conversacion

// SOFTWARE
// id_software
// id_oferta
// nombre
// nivel

// PREGUNTAS OFERTA
// id_aut_pregunta
// pregunta
// id_oferta
