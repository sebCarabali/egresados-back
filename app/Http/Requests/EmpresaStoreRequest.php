<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Carbon;

class EmpresaStoreRequest extends FormRequest
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
            'datos-cuenta.email' => 'required|max:255|email|unique:users,email',
            'datos-cuenta.contrasenia' => 'required|string|min:6',

            // Datos empresa
            'datos-generales-empresa.NIT' => 'required|integer||digits_between:8,15|unique:empresas,nit',
            'datos-generales-empresa.razonSocial' => 'required|string',
            'datos-generales-empresa.nombreEmpresa' => 'required|unique:empresas,nombre',
            'datos-generales-empresa.anioCreacion' => 'required|numeric|between:1900,' . Carbon::now()->format("Y"),
            'datos-generales-empresa.numEmpleados' => 'required|string',
            'datos-generales-empresa.ingresosEmp' => 'required|string',
            'datos-generales-empresa.descripcionEmpresa' => 'required|string',

            'loc-contact-empresa.ciudadEmp' => 'required|exists:ciudades,id_aut_ciudad',
            'loc-contact-empresa.direccionEmp' => 'required|string',
            'loc-contact-empresa.barrioEmp' => 'required|string',
            'loc-contact-empresa.codigoPostalEmp' => 'nullable|numeric',
            'loc-contact-empresa.telefonoEmp' => 'nullable|integer',
            'loc-contact-empresa.emailEmp' => 'nullable|email',
            'loc-contact-empresa.sitioWebEmp' => 'nullable|url',
            // 'loc-contact-empresa.sitioWebEmp' => 'url|active_url',


            'sectores.*' => 'required|array|min:1',
            'sectores.*.*' => 'required|distinct|integer|exists:sub_sectores,id_aut_sub_sector',
            
            'datos-resp.nombrereplegal'  => 'required|string',
            'datos-resp.apellidoreplegal'  => 'required|string',
            'datos-resp.telefonoreplegal'  => 'nullable|numeric|digits_between:1,16',
            'datos-resp.telefonoMovilreplegal'  => 'required|numeric|digits_between:1,16',
            
            'datos-resp.nombreResp' => 'required|string',
            'datos-resp.apellidoResp'  => 'required|string',
            'datos-resp.cargo'  => 'required|string',
            'datos-resp.telefonoResp'  => 'nullable|numeric|digits_between:1,16',
            'datos-resp.telefonoMovilResp'  => 'required|digits_between:1,16',
            'datos-resp.horarioContactoResp'  => 'nullable|string',
            'datos-resp.direccionTrabajoResp' => 'required|string',
            'datos-resp.emailCorpResp'  => 'required|email|unique:administrador_empresa,correo_corporativo',

            // 'archivos.logo' => 'image',
            // 'archivos.camaraycomercio' => 'required|mimes:pdf',
        ];
    }
}
