<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class TituloController extends Controller
{
    public function findAllByPrograma($idPrograma)
    {
        return $this->success(DB::table('titulo')->where('id_programa', $idPrograma)->get());
    }
}
