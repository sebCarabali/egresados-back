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
        // throw new HttpResponseException(response()->json($this->json()));
        //  throw new HttpResponseException(response()->json($this->all()));


        return [
            'datos.datos-cuenta.email' => 'required|max:255|email|unique:users,email',
            'datos.datos-cuenta.contrasenia' => 'required|string|min:6',

            // Datos empresa
            'datos.datos-generales-empresa.NIT' => 'required|integer||digits_between:8,15|unique:empresas,nit',
            'datos.datos-generales-empresa.razonSocial' => 'required|string',
            'datos.datos-generales-empresa.nombreEmpresa' => 'required|unique:empresas,nombre',
            'datos.datos-generales-empresa.anioCreacion' => 'required|numeric|between:1900,' . Carbon::now()->format("Y"),
            'datos.datos-generales-empresa.numEmpleados' => 'required|string',
            'datos.datos-generales-empresa.ingresosEmp' => 'required|string',
            'datos.datos-generales-empresa.descripcionEmpresa' => 'required|string',

            'datos.loc-contact-empresa.ciudadEmp' => 'required|exists:ciudades,id_aut_ciudad',
            'datos.loc-contact-empresa.direccionEmp' => 'required|string',
            'datos.loc-contact-empresa.barrioEmp' => 'required|string',
            'datos.loc-contact-empresa.codigoPostalEmp' => 'nullable|numeric',
            'datos.loc-contact-empresa.telefonoEmp' => 'nullable|integer',
            'datos.loc-contact-empresa.emailEmp' => 'nullable|email',
            'datos.loc-contact-empresa.sitioWebEmp' => 'nullable|string',
            // 'loc-contact-empresa.sitioWebEmp' => 'url|active_url',


            'datos.sectores.*' => 'required|array|min:1',
            'datos.sectores.*.*' => 'required|distinct|integer|exists:sub_sectores,id_aut_sub_sector',

            'datos.datos-resp.nombrereplegal'  => 'required|string',
            'datos.datos-resp.apellidoreplegal'  => 'required|string',
            'datos.datos-resp.telefonoreplegal'  => 'nullable|numeric|digits_between:1,16',
            'datos.datos-resp.telefonoMovilreplegal'  => 'required|numeric|digits_between:1,16',

            'datos.datos-resp.nombreResp' => 'required|string',
            'datos.datos-resp.apellidoResp'  => 'required|string',
            'datos.datos-resp.cargo'  => 'required|string',
            'datos.datos-resp.telefonoResp'  => 'nullable|numeric|digits_between:1,16',
            'datos.datos-resp.telefonoMovilResp'  => 'required|digits_between:1,16',
            'datos.datos-resp.horarioContactoResp'  => 'nullable|string',
            'datos.datos-resp.direccionTrabajoResp' => 'required|string',
            'datos.datos-resp.emailCorpResp'  => 'required|email|unique:administrador_empresa,correo_corporativo',

            'logoInput' => 'nullable|sometimes|image|max:1024',
            'fileInput' => 'bail|required|file|mimes:pdf|max:2048',
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'datos' => json_decode($this->datos, true)
        ]);
    }

    // protected function getValidatorInstance()
    // {
    //     $data = $this->all();
    //     $data[] = json_decode($this->datos, true);
    //     $this->getInputSource()->replace($data);

    //     /*modify data before send to validator*/

    //     return parent::getValidatorInstance();
    // }
}
