<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GuardarInformacionBasicaRequest extends FormRequest
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
            'correo' => 'required|email|unique:egresados|unique:users,email',
            'correo_alternativo' => 'email',
            'identificacion' => 'required',
            'nombres' => 'required',
            'grupo_etnico' => 'required',
            'apellidos' => 'required',
            'genero' => 'required',
            'fecha_nacimiento' => 'required|date',
            'id_lugar_nacimiento' => 'required',
            'id_lugar_expedicion' => 'required',
            //'direccion' => 'required',
            'id_lugar_residencia' => 'required',
            'discapacidad' => 'required',
            'estado_civil' => 'required',
            'id_nivel_educativo' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'correo.required' => 'Debe proporcionar un correo electrónico.',
            'correo.email' => 'Debe proporcionar un correo electrónico válido.',
            'identificacion.required' => 'Debe proporcionar su número de identificación',
            'identificacion.unique' => 'El número de identificación proporcionado ya se encuentra registrado.',
            'correo.unique' => 'Ya existe un registro con este correo electrónico.',
            'correo_alternativo.required' => 'Debe proporcionar un correo electrónico alternativo.',
            'correo_alternativo.email' => 'Debe proporcionar un correo electrónico alternativo válido.',
            'nombres.required' => 'Debe proporcionar sus nombres.',
            'num_hijos.required' => 'Debe proporcionar el número de hijos.',
            'apellidos.required' => 'Debe proporcionar sus apellidos.',
            'genero.required' => 'Debe proporcionar su género.',
            'fecha_nacimiento.required' => 'Debe seleccionar una fecha de nacimiento.',
            'fecha_nacimiento.date' => 'Debe seleccionar una fecha válida.',
            'id_ciudad_nacimiento.required' => 'Debe porporcionar una ciudad de nacimiento.',
            'grupo_etnico.required' => 'Debe proporcionar un grupo etnico.',
            'discapacidad.required' => 'Debe indicar si tiene una discapacidad o ninguna.',
            'estado_civil.required' => 'Debe proporcionar su estado civil.',
            'id_nivel_educativo.required' => 'Debe seleccionar su nivel de estudio.',
            'direccion.required' => 'Debe proporcionar una dirección de residencia.',
            'id_ciudad_residencia' => 'Debe proporcionar la ciudad en la cual reside.',
        ];
    }
}
