<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompletarInformacionRequest extends FormRequest
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
            'num_hijos'=>'integer',
	        'ha_trabajado'=>'require',
            'trabaja_actualmente'=>'require',

            'referidos.nombres'=>'require|string',
            'referidos.es_egresado'=>'require',
            'referidos.id_nivel_educativo'=>'integer',
            'referidos.telefono_movil'=>'require|string',
		    'referidos.parentesco'=>'require|string',
		    'referidos.correo'=>'email',
            'referidos.id_aut_programa'=>'integer',

            'experiencia.nombre_empresa'=>'require|string',
            'experiencia.dir_empresa'=>'require',
            'experiencia.trabajo_en_su_area'=>'require',
            'experiencia.id_ciudad'=>'require|integer',
            'experiencia.tel_trabajo'=>'require|string',
            'experiencia.rango_salario'=>'require|string',
            'experiencia.tipo_contrato'=>'require',
            'experiencia.sector'=>'string',
            'experiencia.cargo_nombre'=>'require|string',
            'experiencia.estado_experiencia'=>'require',
            'experiencia.cargo_nombre'=>'require',


        ];
    }
    
    public function messages()
    {
        return [
            'num_hijos.integer' => 'Debe ingresar un valor numerico.',
            'ha_trabajado.require'=>'Debe proporcionar si ha trabajado.',
            'trabaja_actualmente.require'=>'Debe proporcionar si trabaja actualmente.',

            

            'referidos.nombres.require'=>'Debe proporcionar un nombre.',
            'referidos.nombres.string'=>'Debe proporcionar un nombre en formato texto.',
            'referidos.es_egresado.require'=>'Debe identificar si es o no un egresado.',
            'referidos.id_nivel_educativo.integer'=>'Debe proporcionar un valor numerico.',
            'referidos.telefono_movil.require'=>'Debe proporcionar un numero telfonico.',
            'referidos.telefono_movil.string'=>'Debe proporcionar un conjunto de numeros de texto.',
            'referidos.parentesco.require'=>'Debe proporcionar un tipo de parentesco.',
            'referidos.parentesco.string'=>'Debe proporcionar un valor de tipo texto.',
		    'referidos.correo.email'=>'Debe proporcionar un correo electronico.',
            'referidos.id_aut_programa.integer'=>'Debe proporcionar un valor numerico.',

            'experiencia.nombre_empresa.require'=>'Debe proporcionar un nombre para la empresa.',
            'experiencia.nombre_empresa.string'=>'Debe proporcionar un conjuto de datos de tipo texto.',
            'experiencia.dir_empresa.require'=>'Debe proporcionar una direccion de la empresa.',
            'experiencia.trabajo_en_su_area.require'=>'Debe proporcionar área en la que trabaja.',
            'experiencia.id_ciudad.integer'=>'Debe proporcionar un valor numerico.',
            'experiencia.id_ciudad.require'=>'Debe porporcionar una ciudad.',
            'experiencia.tel_trabajo.require'=>'Debe proporcionar un numero de teléfono.',
            'experiencia.tel_trabajo.string'=>'Debe proporcionar un conjunto de numeros de texto.',

            'experiencia.rango_salario.require'=>'Debe proporcionar un rango salarial.',
            'experiencia.rango_salario.string'=>'Debe proporcionar un conjuto de datos tipo texto.',
            'experiencia.tipo_contrato.require'=>'Debe proporcinar un tipo de contrato de forma correcta.',
            'experiencia.sector.string'=>'Debe proporcionar un conjunto de datos tipo texto.',
            'experiencia.cargo_nombre.require'=>'Debe proporcionar un tipo de cargo valido',
            'experiencia.cargo_nombre.string'=>'Debe proporcionar un conjuto de datos tipo texto.',
            'experiencia.estado_experiencia.require'=>'Debe proporcionar un estado de experiencia correcta.'
            
        ];
    }
}
