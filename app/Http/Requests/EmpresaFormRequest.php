<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Request;
use Illuminate\Validation\Rule;

class EmpresaFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $product = $this->route('a');
        if ($product == "carlos") {
            return true;
        }
        return false;
    }

    /**
     * Obtenga las reglas de validación que se aplican a la solicitud.
     *
     * @return array
     */
    public function rules()
    {
        switch ($this->method()) {
            case 'POST': {
                    return [
                        'datos-cuenta.email' => 'required|max:255|email|unique:users,email',
                        'datos-cuenta.contrasenia' => 'required|string|min:6',

                        // Datos empresa
                        'datos-generales-empresa.NIT' => 'required|integer|digits:8|unique:empresas,nit',
                        'datos-generales-empresa.razonSocial' => 'required|string',
                        'datos-generales-empresa.nombreEmpresa' => 'required|unique:empresas,nombre',
                        'datos-generales-empresa.anioCreacion' => 'required|numeric|between:1900,' . Carbon::now()->format("Y"),
                        'datos-generales-empresa.numEmpleados' => 'required|string',
                        'datos-generales-empresa.ingresosEmp' => 'required|string',
                        'datos-generales-empresa.descripcionEmpresa' => 'required|string',

                        'loc-contact-empresa.ciudadEmp' => 'required|exists:ciudades,id_aut_ciudad',
                        'loc-contact-empresa.direccionEmp' => 'required|string',
                        'loc-contact-empresa.barrioEmp' => 'required|string',
                        'loc-contact-empresa.codigoPostalEmp' => 'integer',
                        'loc-contact-empresa.telefonoEmp' => 'integer',
                        'loc-contact-empresa.emailEmp' => 'email',
                        'loc-contact-empresa.sitioWebEmp' => 'url',
                        // 'loc-contact-empresa.sitioWebEmp' => 'url|active_url',

                        // 'sectores' => 'required|array',
                        'sectores.sectores' => 'required|array',
                        'sectores.sectores.*' => 'required|integer|exists:sectores,id_aut_sector',
                        // 'sectores.sectores' => 'required|array',
                        // 'sectores.sectores.*.subSectores.*.idSector' => 'required|integer|exists:sectores,id_aut_sector',
                        // //datos representante
                        'datos-resp.nombrereplegal'  => 'required|string',
                        'datos-resp.apellidoreplegal'  => 'required|string',
                        'datos-resp.telefonoreplegal'  => 'integer',
                        'datos-resp.telefonoMovilreplegal'  => 'required|integer',
                        // 'datos-resp.barrioResp' => 'required|string',
                        // 'datos-resp.ciudadResp' => 'required|exists:ciudades,id_aut_ciudad',
                        // 'datos-resp.codigoPostalResp' => 'required|integer',
                        'datos-resp.nombreResp' => 'required|string',
                        'datos-resp.apellidoResp'  => 'required|string',
                        'datos-resp.cargo'  => 'required|string',
                        'datos-resp.telefonoResp'  => 'integer',
                        'datos-resp.telefonoMovilResp'  => 'required|integer',
                        'datos-resp.horarioContactoResp'  => 'required|string',
                        'datos-resp.direccionTrabajoResp' => 'required|string',
                        'datos-resp.emailCorpResp'  => 'required|email',
                    ];
                }
            case 'PUT': { }
            case 'PATCH': {
                    return [
                        'name' => 'required',
                        // 'email' => "unique:users,email,$this->id,id",
                        //                    OR
                        //below way will only work in Laravel ^5.5 
                        'email' => Rule::unique('users')->ignore($this->id),

//Sometimes you dont have id in $this object
//then you can use route method to get object of model 
//and then get the id or slug whatever you want like below:
// A veces no tienes id en $ este objeto
// entonces puedes usar el método de ruta para obtener el objeto del modelo
// y luego obtén el id o slug como quieras a continuación:
                        'email' => Rule::unique('users')->ignore($this->route()->user->id),
                    ];
                }
            default:
                break;
        }
    }
}
