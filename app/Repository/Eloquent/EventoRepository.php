<?php

namespace App\Repository\Eloquent;

use App\Evento;
use App\Repository\EventoRepositoryInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EventoRepository extends BaseRepository implements EventoRepositoryInterface
{
    public function __construct()
    {
        $this->model = new Evento();
    }

    public function save(Request $request)
    {
        $evento = $this->getEventoFromRequest($request);
        DB::beginTransaction();

        try {
            $evento = $evento->save();
            DB::commit();

            return $evento;
        } catch (Exception $e) {
            DB::rollback();

            throw $e;
        }
    }

    public function update(Request $request, $id)
    {
    }

    protected function getIdStr()
    {
        return 'id_aut_evento';
    }

    private function getEventoFromRequest(Request $req)
    {
        $data = $this->getDataFromRequest($req);
        $evento = new Evento();
        $evento->nombre = $data['nombre'];
        $evento->fecha_inicio = $this->getPgsqlDateFormat($data['fechaInicio']);
        $evento->fecha_fin = $this->getPgsqlDateFormat($data['fechaFin']);
        $evento->lugar = $data['lugar'];
        $evento->descripcion = $data['descripcion'];
        $evento->a_quien_va_dirigida = $data['dirigidoA'];
        $evento->cupos = $data['cupos'];
        $evento->imagen = $this->saveImage($evento, $req->file('fileInput'));

        return $evento;
    }

    private function saveImage(Evento $evento, $image)
    {
        $filename = $evento->nombre.'_'.time().'.'.$image->getClientOriginalName();

        return $image->store('eventos', 'public');
    }

    private function getDataFromRequest(Request $req)
    {
        return $req->only(
            'nombre',
            'cupos',
            'lugar',
            'fechaInicio',
            'fechaFin',
            'descripcion',
            'dirigidoA'
        );
    }
    
    private function getPgsqlDateFormat($dateStr) {
        $dateArray = explode('/', $dateStr);
        return $dateArray[2] . '-' . $dateArray[1] . '-' . $dateArray[0];
    }
}
