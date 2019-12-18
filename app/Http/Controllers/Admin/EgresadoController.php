<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Http\Controllers\Controller;
use \App\Http\Resources\EgresadoAdminResource;
use Exception;
use \App\Egresado;

class EgresadoController extends Controller {

    public function getAll(Request $request) {
        $page = $request->get('page');
        $pageSize = $request->get('pageSize');
        $egresados = \App\Helpers\BusquedaEgresados::aplicarFiltros($request);
        $results = $egresados->slice(($page - 1) * $pageSize, $pageSize)->values();
        return EgresadoAdminResource::collection(
                        new LengthAwarePaginator(
                        $results, $total = count($egresados), $pageSize, $page
                        )
        );
    }

    /**
     * Carga toda la información de un egresado, incluyendo las referencias
     * personales, los grados y el trabajo actual.
     * 
     * @param number $idEgresado
     * @return Egresado egresado
     */
    public function getById($idEgresado) {
        try {
            $egresado = Egresado::where('id_aut_egresado', $idEgresado)->firstOrFail();
            return $this->success(new EgresadoAdminResource($egresado));
        } catch (Exception $err) {
            return $this->badRequest($err->getMessage());
        }
    }

}
