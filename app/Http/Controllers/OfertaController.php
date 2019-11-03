<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Oferta;
use App\Cargo;
use App\CategoriaCargo;
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

        return response()->json($ofertas, 200);
    }
}
