<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Carbon;

class EmpresaUpdateRequest extends FormRequest
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
        // throw new HttpResponseException(response()->json($this->all()));
        
        $empresa = $this->route()->id;
        $rangoEmpleados = array('1 - 10', '11 - 50', '51 - 150', '151 - 300', '301 - 500', '501 - 1000', '1001 - 5000', 'Más de 5000');
        $salarios = array('0 - 3.000.000', '3.000.001 - 10.000.000', '10.000.001 - 25.000.000', '25.000.001 - 50.000.000', '50.000.001 - 100.000.000', 'Más de 100.000.000');
        return [
            'datos-cuenta.email' => 'present|max:255|email|unique:users,email,' . $empresa->administrador->id_aut_user . ',id_aut_user',
            'datos-cuenta.contrasenia' => 'string|min:8',

            // Datos empresa
            'datos-generales-empresa.NIT' => 'required|digits_between:8,15|unique:empresas,nit,' . $empresa->id_aut_empresa . ',id_aut_empresa',
            'datos-generales-empresa.razonSocial' => 'required|string',
            'datos-generales-empresa.nombreEmpresa' => 'required|unique:empresas,nombre,' . $empresa->id_aut_empresa . ',id_aut_empresa',
            'datos-generales-empresa.anioCreacion' => 'required|numeric|between:1900,' . Carbon::now()->format("Y"),
            'datos-generales-empresa.numEmpleados' => 'required|string|in:'. implode(",",$rangoEmpleados),
            'datos-generales-empresa.ingresosEmp' => 'required|string|in:'. implode(",",$salarios),
            'datos-generales-empresa.descripcionEmpresa' => 'required|string',

            'loc-contact-empresa.idCiudad' => 'required|exists:ciudades,id_aut_ciudad',
            'loc-contact-empresa.direccionEmp' => 'required|string',
            'loc-contact-empresa.barrioEmp' => 'required|string',
            'loc-contact-empresa.codigoPostalEmp' => 'nullable|integer',
            'loc-contact-empresa.telefonoEmp' => 'nullable|integer',
            'loc-contact-empresa.emailEmp' => 'nullable|email',
            'loc-contact-empresa.sitioWebEmp' => 'nullable|string',
            // 'loc-contact-empresa.sitioWebEmp' => 'url|active_url',

            // 'sectores' => 'required',
            'sectores.subsectores' => 'required|array|min:1',
            'sectores.subsectores.*' => 'required|distinct|integer|exists:sub_sectores,id_aut_sub_sector',
            // //datos representante
            'datos-resp.nombrereplegal'  => 'required|string',
            'datos-resp.apellidoreplegal'  => 'required|string',
            'datos-resp.telefonoreplegal'  => 'nullable|numeric|digits_between:1,16',
            'datos-resp.telefonoMovilreplegal'  => 'required|numeric|digits_between:1,16',
            // 'datos-resp.barrioResp' => 'required|string',
            // 'datos-resp.ciudadResp' => 'required|exists:ciudades,id_aut_ciudad',
            // 'datos-resp.codigoPostalResp' => 'required|integer',
            'datos-resp.nombreResp' => 'required|string',
            'datos-resp.apellidoResp'  => 'required|string',
            'datos-resp.cargo'  => 'required|string',
            'datos-resp.telefonoResp'  => 'nullable|numeric|digits_between:1,16',
            'datos-resp.telefonoMovilResp'  => 'required|numeric|digits_between:1,16',
            'datos-resp.horarioContactoResp'  => 'nullable|string',
            'datos-resp.direccionTrabajoResp' => 'required|string',
            'datos-resp.emailCorpResp'  => 'required|email|unique:administrador_empresa,correo_corporativo,' . $empresa->id_aut_empresa . ',id_empresa',
        
        ];
    }
}
