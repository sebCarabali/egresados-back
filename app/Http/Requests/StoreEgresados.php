<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEgresados extends FormRequest
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

    public function messages() 
    {
        return [
            'identificacion.required' => 'La identificación es obligatorio',
            'nombres.required' => 'Los nombres es obligatorio',
            'apellidos.required' => 'Los Apellidos es obligatorio',
            'genero.required' => 'El género es obligatorio',
            'id_lugar_expedicion.required' => 'El lugar de expedición es requerido',
            'fecha_nacimiento.required' => 'La fecha de nacimiento es requerida',
            'id_ciudad_nacimiento.required' => 'La Ciudad de nacimiento es requerida',
            'codigo_postal.required' => 'El código postal es requerido',
            'direccion.required' => 'La dirección es requerida',
            'id_ciudad_residencia.required' => 'La ciudad de residencia es requerida',
            'mension_honor.required' => 'La mensión de honor es requerida',
            'mension_honor.boolean' => 'La mensión de honor debe tener valores 1 o 0',
            'correo.require' => 'El correo es requerido',
            'correo.email' => 'El correo debe ser una dirección de correo válida'            
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'identificacion' => 'required',
            'nombres' => 'required',
            'apellidos' => 'required',
            'genero' => 'required',
            'id_lugar_expedicion' => 'required',
            'fecha_nacimiento' => 'required',
            'id_ciudad_nacimiento' => 'required',
            'codigo_postal' => 'required',
            'direccion' => 'required',
            'id_cuidad_residencia' => 'required',
            'tipo' => 'required',
            'mension_honor' => 'required:boolean',
            'correo' => 'required:email'
        ];
    }
}
