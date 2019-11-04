<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Oferta;
use App\Cargo;
use App\Contrato;
use App\CategoriaCargo;
use App\Contrato;
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
        # code...
    }
}
// Ofertas
// Contratos
// Idiomas
// Software
// Salario
// Ubicaciones
// Areas Conocimiento
// Cargo
// Sector NO Subsector, SECTOR
// Discapacidades
// Preguntas


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