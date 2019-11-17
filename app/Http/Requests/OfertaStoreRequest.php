<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OfertaStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'informacion-principal.idCargo' => 'nullable|integer|exists:cargos,id_aut_cargos',
            'informacion-principal.otroCargo' => 'required_without:informacion-principal.idCargo|string',
            'informacion-principal.nombreOferta' => 'required|string',
            'informacion-principal.descripcion' => 'required|string',
            'informacion-principal.idSector' => 'required|integer|exists:sectores,id_aut_sector',
            'informacion-principal.nombreTempEmpresa' => 'nullable|string',
            'informacion-principal.numVacantes' => 'required|integer|min:1',
            'informacion-principal.vigenciaDias' => 'required|integer|min:7|max:30',
            'informacion-principal.ubicacion' => 'required|array|min:1',
            'informacion-principal.ubicacion.*' => 'integer|exists:ciudades,id_aut_ciudad',
            'informacion-principal.idAreaConocimiento' => 'required|array|min:1',
            'informacion-principal.idAreaConocimiento.*' => 'integer|exists:areas_conocimiento,id_aut_areaconocimiento',
            'contrato.tipoContrato' => 'required|string',
            'contrato.jornada' => 'required|string',
            'contrato.horario' => 'nullable|string',
            'contrato.comentariosSalario' => 'nullable|string',
            'contrato.formaPago' => 'required|integer|exists:salarios,id_aut_salario',
            'contrato.duracion' => 'nullable|string',
            'requisitos.experienciaLaboral' => 'required|string',
            'requisitos.anios' => 'required|integer',
            'requisitos.licenciaConduccion' => 'nullable|string',
            'requisitos.requisitosMinimos' => 'required|string',
            'requisitos.idDiscapacidad' => 'nullable|integer|exists:discapacidades,id_aut_discapacidades',
            'requisitos.idrequisitosMinimos' => 'required|integer|exists:nivel_programa,id_aut_nivprogra',
            'requisitos.idiomas' => 'nullable|array',
            'requisitos.idiomas.*.id' => 'required|integer|exists:idiomas,id_aut_idioma',
            'requisitos.idiomas.*.nivel_escritura' => 'required|string',
            'requisitos.idiomas.*.nivel_lectura' => 'required|string',
            'requisitos.idiomas.*.nivel_conversacion' => 'required|string',
            'requisitos.softwareOferta' => 'nullable|array',
            'requisitos.softwareOferta.*.nombre' => 'required|string',
            'requisitos.softwareOferta.*.nivel' => 'required|string',
            'requisitos.preguntasCandidato' => 'nullable|array',
            'requisitos.preguntasCandidato.*' => 'string',
        ];
    }
}
