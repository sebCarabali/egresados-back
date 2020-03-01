<?php

namespace App\Repository\Eloquent;

use App\Evento;
use App\Repository\EventoRepositoryInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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
        $datos = $this->getDataFromRequest($request);
        $file = $request->file('fileInput');

        try {
            DB::beginTransaction();
            $evento = $this->getById($id);
            if ($evento) {
                $evento = $this->setInfoAlEvento($evento, $datos, $file);
                $evento = $evento->save();
            } else {
                throw new Exception('No se encontró el evento con id: '.$id);
            }
            DB::commit();

            return $evento;
        } catch (Exception $e) {
            DB::rollback();

            throw $e;
        }
    }

    protected function getIdStr()
    {
        return 'id_aut_evento';
    }

    private function setInfoAlEvento(Evento $evento, array $data, $file)
    {
        $evento->nombre = $data['nombre'];
        $evento->fecha_inicio = $this->getPgsqlDateFormat($data['fechaInicio']);
        $evento->fecha_fin = $this->getPgsqlDateFormat($data['fechaFin']);
        $evento->lugar = $data['lugar'];
        $evento->hora_inicio = $data['horaInicio'].':00';
        $evento->hora_fin = $data['horaFin'].':00';
        $evento->descripcion = $data['descripcion'];
        $evento->a_quien_va_dirigida = $data['dirigidoA'];
        $evento->cupos = $data['cupos'];
        if ('changed' == $data['imagePath']) {
            $evento->imagen = $this->actualizarImagen($file, $evento);
        }

        return $evento;
    }

    private function getEventoFromRequest(Request $req)
    {
        $data = $this->getDataFromRequest($req);
        $evento = new Evento();
        $evento->nombre = $data['nombre'];
        $evento->fecha_inicio = $this->getPgsqlDateFormat($data['fechaInicio']);
        $evento->fecha_fin = $this->getPgsqlDateFormat($data['fechaFin']);
        $evento->lugar = $data['lugar'];
        $evento->hora_inicio = $data['horaInicio'].':00';
        $evento->hora_fin = $data['horaFin'].':00';
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
            'horaInicio',
            'horaFin',
            'fechaFin',
            'descripcion',
            'dirigidoA',
            'imagePath'
        );
    }

    private function getPgsqlDateFormat($dateStr)
    {
        $dateArray = explode('/', $dateStr);

        return $dateArray[2].'-'.$dateArray[1].'-'.$dateArray[0];
    }

    private function actualizarImagen($file, Evento $evento)
    {
        if (!empty($evento->imagen)) {
            Storage::delete($evento->imagen, 'public');
        }

        return $file->store('eventos', 'public');
    }
}
