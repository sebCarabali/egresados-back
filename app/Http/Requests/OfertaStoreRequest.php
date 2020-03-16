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
        $perfiles = array("Profesional", "Estudiante pregrado", "Doctor", "Especialista", "Magister");
        $contratos = array('Término indefinido', 'Contrato de aprendizaje', 'Prestación de servicios', 'Obra a labor determinada', 'Término fijo');
        $jornada = array('Medio tiempo', 'Tiempo completo', 'Tiempo parcial');
        $niveles_idiomas = array('Nativo', 'Avanzado', 'Medio', 'Bajo');
        $nivel_software = array('Ninguno', 'Nivel bajo', 'Nivel usuario', 'Nivel usuario avanzado', 'Nivel técnico', 'Nivel profesional', 'Nivel experto');
        $experiencia = array('Sin experiencia', 'Igual a', 'Mayor o igual que', 'Menor o igual que');
        $tipo_licencia = array('Ninguna','A1', 'A2', 'B1', 'B2', 'B3', 'C1', 'C2', 'C3');
        return [
            // 'informacionPrincipal.idCargo' => 'nullable|integer|exists:cargos,id_acargos',
            // 'informacionPrincipal.otroCargo' => 'required_without:informacionPrincipal.idCargo|string',
            'informacionPrincipal.cargo' => 'required|string',

            'informacionPrincipal.nombreOferta' => 'required|string',
            'informacionPrincipal.descripcion' => 'required|string',
            'informacionPrincipal.idSector' => 'required|integer|exists:sectores,id_aut_sector',
            'informacionPrincipal.nombreTempEmpresa' => 'nullable|string',
            'informacionPrincipal.numVacantes' => 'required|integer|min:1',
            'informacionPrincipal.vigenciaDias' => 'required|integer|in:8,15,30',
            'informacionPrincipal.idUbicaciones' => 'required|array|min:1',
            'informacionPrincipal.idUbicaciones.*' => 'integer|exists:ciudades,id_aut_ciudad',
            'informacionPrincipal.idAreasConocimiento' => 'required|array|min:1',
            'informacionPrincipal.idAreasConocimiento.*' => 'integer|exists:areas_conocimiento,id_aut_areaconocimiento',
            
            'contrato.tipoContrato' => 'required|string|in:'.implode(",",$contratos),
            'contrato.jornada' => 'required|string|in:'.implode(",",$jornada),
            'contrato.horario' => 'nullable|string',
            'contrato.comentariosSalario' => 'nullable|string',
            // 'contrato.formaPago' => 'required|integer|exists:salarios,id_aut_salario',
            'contrato.idRangoSalarial' => 'required|integer|exists:salarios,id_aut_salario',
            'contrato.duracion' => 'nullable|string',
            
            'requisitos.requisitosMinimos' => 'required|string',
            'requisitos.idEstudioMinimo' => 'required|integer|exists:niveles_estudio,id_aut_estudio',
            'requisitos.perfil' => 'required|string|in:'.implode(',', $perfiles),
            
            'requisitos.experienciaLaboral' => 'required|string|in:'.implode(",",$experiencia),
            'requisitos.anios' => 'required_unless:requisitos.experienciaLaboral,'.'Sin experiencia'.'|integer|min:0',
            'requisitos.licenciaConduccion' => 'nullable|string|in:'.implode(",",$tipo_licencia),
            'requisitos.idDiscapacidades' => 'nullable|array',
            'requisitos.idDiscapacidades.*' => 'integer|exists:discapacidades,id_aut_discapacidades',
            'requisitos.idiomas' => 'nullable|array',
            'requisitos.idiomas.*.id' => 'required|integer|exists:idiomas,id_aut_idioma',
            'requisitos.idiomas.*.nivel_escritura' => 'required|string|in:'.implode(",",$niveles_idiomas),
            'requisitos.idiomas.*.nivel_lectura' => 'required|string|in:'.implode(",",$niveles_idiomas),
            'requisitos.idiomas.*.nivel_conversacion' => 'required|string|in:'.implode(",",$niveles_idiomas),
            'requisitos.softwareOferta' => 'nullable|array',
            'requisitos.softwareOferta.*.nombre' => 'required|string',
            'requisitos.softwareOferta.*.nivel' => 'required|string|in:'.implode(",",$nivel_software),
            'requisitos.preguntasCandidato' => 'nullable|array',
            'requisitos.idProgramas' => 'required|array|min:1',
            'requisitos.idProgramas.*' => 'required|distinct|integer|exists:programas,id_aut_programa',

            'contactoHV.correo' => 'required|email',
            'contactoHV.nombres' => 'required|string',
            'contactoHV.apellidos' => 'required|string',
            'contactoHV.telefonoMovil' => 'required|numeric|digits_between:1,16',
        ];
    }
}
