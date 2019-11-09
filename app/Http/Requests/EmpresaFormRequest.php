<?php

namespace App\Http\Requests;

use App\Empresa;
use Exception;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Request;
use Illuminate\Validation\Rule;

use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Database\QueryException;
use Illuminate\Http\Exceptions\HttpResponseException;

class EmpresaFormRequest extends FormRequest
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
     * Obtenga las reglas de validación que se aplican a la solicitud.
     *
     * @return array
     */
    public function rules()
    {
        // throw new HttpResponseException(response()->json([
        //     "this" => $this->route()
        // ], JsonResponse::HTTP_NOT_FOUND));
        // $this->route()->id; //id de la empresa


        switch ($this->method()) {
            case 'POST': {
                    return [
                        'datos-cuenta.email' => 'required|max:255|email|unique:users,email',
                        'datos-cuenta.contrasenia' => 'required|string|min:6',

                        // Datos empresa
                        'datos-generales-empresa.NIT' => 'required|integer||digits_between:8,16|unique:empresas,nit',
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

                        'sectores.sectores' => 'required|array',
                        'sectores.sectores.*' => 'required|distinct|integer|exists:sectores,id_aut_sector',
                        // 'sectores.sectores' => 'required|array',
                        // 'sectores.sectores.*.subSectores.*.idSector' => 'required|integer|exists:sectores,id_aut_sector',
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
                        'datos-resp.telefonoMovilResp'  => 'required|digits_between:1,16',
                        'datos-resp.horarioContactoResp'  => 'nullable|string',
                        'datos-resp.direccionTrabajoResp' => 'required|string',
                        'datos-resp.emailCorpResp'  => 'required|email',

                        // 'archivos.logo' => 'image',
                        // 'archivos.camaraycomercio' => 'required|mimes:pdf',
                    ];
                }
            case 'PUT': {
                    $empresa = $this->route()->id;
                    // $id = $this->route()->id;
                    
                    // $empresa = Empresa::find($id);

                    // if (!$empresa) {
                    //     $this->notExists();
                    // }
                    return [

                        'datos-cuenta.email' => 'required|max:255|email|unique:users,email,' . $empresa->administrador->id_aut_user . ',id_aut_user',
                        'datos-cuenta.contrasenia' => 'string|min:6',

                        // Datos empresa
                        'datos-generales-empresa.NIT' => 'required|digits_between:8,15|unique:empresas,nit,' . $empresa->id_aut_empresa . ',id_aut_empresa',
                        'datos-generales-empresa.razonSocial' => 'required|string',
                        'datos-generales-empresa.nombreEmpresa' => 'required|unique:empresas,nombre,' . $empresa->id_aut_empresa . ',id_aut_empresa',
                        'datos-generales-empresa.anioCreacion' => 'required|numeric|between:1900,' . Carbon::now()->format("Y"),
                        'datos-generales-empresa.numEmpleados' => 'required|string',
                        'datos-generales-empresa.ingresosEmp' => 'required|string',
                        'datos-generales-empresa.descripcionEmpresa' => 'required|string',

                        'loc-contact-empresa.idCiudad' => 'required|exists:ciudades,id_aut_ciudad',
                        'loc-contact-empresa.direccionEmp' => 'required|string',
                        'loc-contact-empresa.barrioEmp' => 'required|string',
                        'loc-contact-empresa.codigoPostalEmp' => 'nullable|integer',
                        'loc-contact-empresa.telefonoEmp' => 'nullable|integer',
                        'loc-contact-empresa.emailEmp' => 'nullable|email',
                        'loc-contact-empresa.sitioWebEmp' => 'nullable|url',
                        // 'loc-contact-empresa.sitioWebEmp' => 'url|active_url',

                        // 'sectores' => 'required|array',
                        'sectores.sectores' => 'required|array',
                        'sectores.sectores.*' => 'required|distinct|integer|exists:sectores,id_aut_sector',
                        // //datos representante
                        'datos-resp.nombrereplegal'  => 'required|string',
                        'datos-resp.apellidoreplegal'  => 'required|string',
                        'datos-resp.telefonoreplegal'  => 'nullable|integer',
                        'datos-resp.telefonoMovilreplegal'  => 'required|integer',
                        // 'datos-resp.barrioResp' => 'required|string',
                        // 'datos-resp.ciudadResp' => 'required|exists:ciudades,id_aut_ciudad',
                        // 'datos-resp.codigoPostalResp' => 'required|integer',
                        'datos-resp.nombreResp' => 'required|string',
                        'datos-resp.apellidoResp'  => 'required|string',
                        'datos-resp.cargo'  => 'required|string',
                        'datos-resp.telefonoResp'  => 'nullable|integer',
                        'datos-resp.telefonoMovilResp'  => 'required|integer',
                        'datos-resp.horarioContactoResp'  => 'required|string',
                        'datos-resp.direccionTrabajoResp' => 'required|string',
                        'datos-resp.emailCorpResp'  => 'required|email',
                    ];
                }
            case 'PATCH': {
                    return [
                        'name' => 'required',
                        // 'email' => "unique:users,email,$this->id,id",
                        //                    OR
                        //a continuación solo funcionará en Laravel ^5.5 
                        'email' => Rule::unique('users')->ignore($this->id),

                        //Sometimes you dont have id in $this object
                        //then you can use route method to get object of model 
                        //and then get the id or slug whatever you want like below:
                        // A veces no tienes id en  este objeto
                        // entonces puedes usar el método de ruta para obtener el objeto del modelo
                        // y luego obtén el id o slug como quieras a continuación:
                        'email' => Rule::unique('users')->ignore($this->route()->user->id),
                    ];
                }
            default:
                break;
        }
    }
    // /**
    //  * Handle a failed validation attempt.
    //  *
    //  * @param  \Illuminate\Contracts\Validation\Validator  $validator
    //  * @return void
    //  *
    //  * @throws \Illuminate\Validation\ValidationException
    //  */
    // protected function failedValidation(Validator $validator)
    // {
    //     // throw new ValidationException($validator);
    //     $errors = (new ValidationException($validator))->errors();
    //     throw new HttpResponseException(response()->json([
    //         "status" => "failure",
    //         "status_code" => 422,
    //         "message" => __("The given data was invalid."),
    //         'errors' => $errors
    //     ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY));
    // }

    // public function notExists()
    // {
    //     throw new HttpResponseException(response()->json([
    //         "status" => "failure",
    //         "status_code" => 404,
    //         "message" => __("There is no company with id ") . $this->id,
    //         "errors" => []
    //     ], JsonResponse::HTTP_NOT_FOUND));
    // }
}
