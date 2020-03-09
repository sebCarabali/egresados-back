<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use \App\Http\Resources\GradosResource;
use App\Egresado;
use App\Grado;

class ActualizarGradosController extends Controller
{
    // Metodo que para obtener los informacion basica de los grados de un Egresado 
    public function getGrados($idEgresado){
        $listaGrados = Grado::join('programas','grados.id_programa','programas.id_aut_programa')
        ->where('grados.id_egresado',$idEgresado)
        ->select('grados.fecha_graduacion','grados.anio_graduacion','programas.nombre','grados.mencion_honor','grados.estado')->get();
        return response()->json($listaGrados,200);
    }

    public function update(Request $request, $idEgresado){
        $grado = Grado::join('programas','grados.id_programa','programas.id_aut_programa')
        ->where('grados.id_egresado',$idEgresado)->first();

        $egresado=Egresado::find($idEgresado);

        $grado->mension_honor=$request->get('mension');
        $grado->fecha_graduacion=date('Y/m/d', strtotime($request->get('fechaGraduacion')));
        $grado->anio_graduacion=$request->get('anioGraduacion');

        $programa=$request->get('id_programa');
        $comentarios=$request->get('comentario');

        guardarActualizar($grado,$id_programa,$comentarios);

    }
    
    public function guardarActualizar(Grado $grado, $id_programa, array $comentarios,Egresado $egresado){
         //Guardar Actualización de grado
        return DB::transaction(function () use ($grado, $id_programa, $comentarios, $egresado) {
            $egresado->programas()->detach();
            $egresado->programas()->attach($id_programa);
            
            $grado->tipoObservacion()->detach();
            foreach ($comentarios As $comentario){
                $grado->tipoObservacion()->attach($comentario);
            }
        });
    }

    public function validarGradosRelacionados($id_programa,$idGradoNuevo){
        $grado_programa=Grado::join('programas', 'grados.id_programa','programas.id_aut_programa')
        ->where('programas.id_aut_programa',$id_programa)
        ->select('grados.id_aut_programa','grados.id_aut_grado')->first();
        
        if($idGradoNuevo != $grado_programa['id_aut_grado'] and $id_programa==$grado_programa['id_aut_programa']){
            return false;
        }else{
            return true;
        }
    }

    public function validarTitulos($id_programa){
        $titulo= Titulos::where('id_programa','$id_programa');
        return response()->json($titulo,200);
    }

    public function agregarGrado(Request $request,$idEgresado){
        $egresado=Egresado::find($idEgresado);
        

        
        $grado = array(
            'id_programa' => $request->get('id_programa'),
            'mension_honor' => $request->get('mension_honor'),
            'titulo_especial' => $request->get('titulo_especial'),
            'fecha_grado' => date('Y/m/d', strtotime($request->get('fecha_grado'))),
            'anio_graduacion' => $request->get('anio_graduacion'),
            'estado'=>"PENDIENTE"
        );
        $comentarios=$request->get('comentario');

        
        guardarNuevoGrado($idPrograma,$comentarios,$egresado,$nuevoGrado);
    }

    public function guardarNuevoGrado(array $comentarios,Egresado $egresado,array $nuevoGrado){
        return DB::transaction(function () use ($comentarios,$egresado,$nuevoGrado) {
            $egresado->programas()->attach($grado['id_programa'], [
                'mencion_honor' => array_key_exists('mension_honor', $grado) ? $grado['mension_honor'] : 'No',
                'titulo_especial' => array_key_exists('titulo_especial', $grado) ? $grado['titulo_especial'] : '',                
                'fecha_graduacion' => $grado['fecha_grado'],
                'anio_graduacion' => $grado['anio_graduacion'],
                'estado' => 'PENDIENTE'
            ]);
        
            $nuevoGrado =Grado::where('id_egresado',$egresado->id_aut_egresado)
            ->where('id_programa',$grado['id_programa']);
            foreach ($comentarios as $comentario){
                $nuevoGrado->tipoObservacion()->attach($comentario["id_aut_comentario"], ["respuesta" => $comentario["respuesta"]]);
            }
        });
    }
}
