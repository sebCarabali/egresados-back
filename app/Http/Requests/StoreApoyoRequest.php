<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreApoyoRequest extends FormRequest
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
            'nombres' => 'required',
            'apellidos' => 'required',
            'nombreRol' => 'required',
            'correo' => 'email|required|unique:apoyos|unique:users,email',
            'correoSecundario' => 'email|required'
        ];
    }

    public function messages()
    {
        return [
            'nombres.required' => 'El nombre es requerido',
            'apellidos.required' => 'El apellido es requerido',
            'nombreRol' => 'El rol es requerido',
            'correo.email' => 'Debe ser un email válido',
            'correo.required' => 'El correo es requerido',
            'correo.unique' => 'Ya se encuentra registrado un usuario con el correo',
            'correoSecundario.email' => 'El correo secundario es requerido',
            'correoSecundario.required' => 'El correo secundario es requerido'
        ];
    }
}
