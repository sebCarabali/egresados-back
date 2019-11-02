<?php

namespace App\Http\Controllers;

use App\Cargo;
use App\Ciudad;
use App\Departamento;
use Illuminate\Http\Request;

use App\Empresa;
use App\Helpers\JwtAuth;
use App\Localizacion;
use App\RepresentanteEmpresa;
use App\Role;
use App\Sector;
use App\User;
use Exception;
use Illuminate\Support\Carbon;
use Illuminate\Validation\ValidationException;

use Illuminate\Support\Facades\DB;


class EmpresaController extends Controller
{

    public function index()
    {
        $empresas = Empresa::all();

        return response()->json($empresas, 200);
    }

    public function getEmpresasEnEspera()
    {
        $empresas = Empresa::orderBy('fecha_registro', 'ASC')
                          ->where('estado', 'En espera')->get();

        return response()->json($empresas, 200);
    }

    public function showAllInfo($id)
    {
        // Codigo de error por defecto
        $code = 404;
        $empresa = Empresa::find($id)->load('subSectores', 'direccion', 'representante', 'administrador');

        if (is_object($empresa)) {
            $empresa->direccion->load('ciudad');
            $empresa->direccion->ciudad->load('departamento');
            $empresa->direccion->ciudad->departamento->load('pais');

            $empresa->administrador->load('direccion');
            $empresa->administrador->direccion->load('ciudad');
            $empresa->administrador->direccion->ciudad->load('departamento');
            $empresa->administrador->direccion->ciudad->departamento->load('pais');


            // Se borra el atributo pivot, el cual no es necesario
            foreach ($empresa->subSectores as $sector) {
                unset($sector['pivot']);
            }
            $code = 200;
            $data = $empresa;
        } else {
            $data = null;
        }
        return response()->json($data, $code);
    }

    public function update($id, Request $request)
    {
        // Recoger los datos por POST
        $json = $request->input('json', null);
        $params_array = json_decode($json, true);

        // Código de error por defecto
        $code = 400;
        $data = null;

        if (!empty($params_array)) {

            // Eliminar lo que no queremos actualizar
            unset($params_array['id']);
            unset($params_array['nombre']);
            unset($params_array['razon_social']);
            unset($params_array['anio_creacion']);
            unset($params_array['fecha_registro']);
            unset($params_array['fecha_activacion']);


            // Buscar el registro
            // $empresa = Empresa::where('id', $id)->first();
            $empresa = Empresa::find($id);

            if (!empty($empresa) && is_object($empresa)) {

                // Actualizar el registro en concreto
                $empresa->update($params_array);

                $data = $empresa;
                $code = 200;
            }
        }
        return response()->json($data, $code);
    }

    public function updateEstado($id, Request $request)
    {
        // Recoger los datos por PUT
        $json = $request->input('json', null);
        $params_array = json_decode($json, true);

        // Código de error por defecto
        $code = 400;
        $data = null;

        if (!empty($params_array)) {
            // Buscar el registro
            $empresa = Empresa::find($id);

            if (!empty($empresa) && is_object($empresa)) {

                if ($params_array['estado'] == 'Activo' && !empty($params_array['limite_publicaciones'])) {
                    // Actualizar el registro en concreto

                    $empresa->update([
                        'estado' => $params_array['estado'],
                        'limite_publicaciones' => $params_array['limite_publicaciones'],
                        'fecha_activacion' => Carbon::now('-5:00'),
                    ]);
                    $data = $empresa;
                    $code = 200;
                } else if ($params_array['estado'] == 'En espera' || $params_array['estado'] == 'Inactivo') {
                    // Actualizar el registro en concreto
                    $empresa->update(['estado' => $params_array['estado']]);
                    $data = $empresa;
                    $code = 200;
                }
            }
        }
        return response()->json($data, $code);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Código de error por defecto
        $code = 400;
        $data = null;
        if (request()->ajax()) {
            try {
                // return response()->json($request);
                $this->validate(request(), [
                    //Datos usuario login
                    // datos-cuenta
                    // datos-generales-empresa
                    // datos-resp
                    // loc-contact-empresa
                    // sectores
                    'datos-cuenta.email' => 'required|max:255|email|unique:users,email',
                    'datos-cuenta.contrasenia' => 'required|string|min:6',

                    // Datos empresa
                    'datos-generales-empresa.NIT' => 'required|integer|unique:empresas,nit',
                    'datos-generales-empresa.anioCreacion' => 'required|numeric|between:1900,' . Carbon::now()->format("Y"),
                    'datos-generales-empresa.descripcionEmpresa' => 'required|string',
                    'datos-generales-empresa.ingresosEmp' => 'required|string',
                    'datos-generales-empresa.nombreEmpresa' => 'required|unique:empresas,nombre',
                    'datos-generales-empresa.numEmpleados' => 'required|integer',
                    'datos-generales-empresa.razonSocial' => 'required|string',

                    'loc-contact-empresa.barrioEmp' => 'required|string',
                    'loc-contact-empresa.ciudadEmp' => 'required|exists:ciudades,id',
                    'loc-contact-empresa.codigoPostalEmp' => 'required|integer',
                    'loc-contact-empresa.direccionEmp' => 'required|string',
                    'loc-contact-empresa.sitioWebEmp' => 'required|url',

                    // 'sectores' => 'required|array',
                    // 'sectores.*' => 'required|integer|distinct|exists:sectores,id',
                    'sectores.sectores' => 'required|integer|exists:sectores,id',
                    // //datos representante
                    'dir_empresa' => 'required|integer',
                    'datos-resp.apellidoResp'  => 'required|string',
                    'datos-resp.barrioResp' => 'required_if:dir_empresa,0|string',
                    'datos-resp.cargo'  => 'required|string',
                    'datos-resp.ciudadResp' => 'required_if:dir_empresa,0|exists:ciudades,id',
                    'datos-resp.codigoPostalResp' => 'required_if:dir_empresa,0|integer',
                    'datos-resp.direccionResp' => 'required_if:dir_empresa,0|string',
                    'datos-resp.emailCorpResp'  => 'required|email',
                    'datos-resp.nombreResp' => 'required|string',
                    'datos-resp.telefonoMovilResp'  => 'required|integer',
                    'datos-resp.telefonoResp'  => 'required|integer',


                ]);


                $user = new User();
                $user->email = request('datos-cuenta.email');
                $user->password = bcrypt(request('datos-cuenta.contrasenia'));
                // return response()->json(request());
                $user->rol()->associate(Role::where('nombre', 'Empresa')->firstOrFail());
                // return response()->json($user);

                $direccionEmpr = new Localizacion();
                $direccionEmpr->codigo_postal = request('loc-contact-empresa.codigoPostalEmp');
                $direccionEmpr->direcccion = request('loc-contact-empresa.direccionEmp');
                $direccionEmpr->barrio = request('loc-contact-empresa.barrioEmp');
                $direccionEmpr->ciudad()->associate(Ciudad::find(request('loc-contact-empresa.ciudadEmp'))->firstOrFail());
                // return response()->json($direccionEmpr);

                $empresa = new Empresa();
                $empresa->nit = request('datos-generales-empresa.NIT');
                $empresa->nombre = request('datos-generales-empresa.nombreEmpresa');
                $empresa->razon_social = request('datos-generales-empresa.razonSocial');
                $empresa->numero_empleados = request('datos-generales-empresa.numEmpleados');
                $empresa->ingresos = request('datos-generales-empresa.ingresosEmp');
                $empresa->sitio_web = request('loc-contact-empresa.sitioWebEmp');
                $empresa->anio_creacion = request('datos-generales-empresa.anioCreacion');
                // return response()->json($empresa);

                $empresa->estado = false;
                $empresa->fecha_registro = Carbon::now();
                $empresa->total_publicaciones = 0;
                $empresa->limite_publicaciones = 0;
                $empresa->num_publicaciones_actuales = 0;

                $dir_empresa = request('dir_empresa');
                $direccionRepresen = new Localizacion();
                if (!$dir_empresa) {
                    $direccionRepresen->codigo_postal = request('datos-resp.codigoPostalResp');
                    $direccionRepresen->direcccion = request('datos-resp.direccionResp');
                    $direccionRepresen->barrio = request('datos-resp.barrioResp');
                    $direccionRepresen->ciudad()->associate(Ciudad::find(request('datos-resp.ciudadResp'))->firstOrFail());
                }
                // return response()->json($direccionRepresen);

                $representante = new RepresentanteEmpresa();
                $representante->nombres = request('datos-resp.nombreResp');
                // $representante->apellidos = "";
                $representante->apellidos = request('datos-resp.apellidoResp');
                $representante->telefono = request('datos-resp.telefonoResp');
                $representante->telefono_movil = request('datos-resp.telefonoMovilResp');
                $representante->correo_corporativo = request('datos-resp.emailCorpResp');
                // return response()->json($representante);
                // return response()->json(Cargo::find(request('rep_id_cargo'))->firstOrFail());
                $representante->cargo()->associate(Cargo::firstOrCreate(["nombre"=>request('datos-resp.cargo')]));
                // return response()->json($representante);

                DB::transaction(function () use ($user, $direccionEmpr, $empresa, $direccionRepresen, $representante, $dir_empresa) {

                    $user->save();
                    $direccionEmpr->save();
                    $empresa->user()->associate($user);
                    $empresa->direccion()->associate($direccionEmpr);
                    $empresa->save();

                    if ($dir_empresa) {
                        $representante->direccion()->associate($direccionEmpr);
                    } else {
                        $direccionRepresen->save();
                        $representante->direccion()->associate($direccionRepresen);
                    }
                    $representante->save();
                });
                return response()->json($empresa, 200);
            } catch (ValidationException $e) {
                return response()->json($e->validator->errors(), $code);
            } catch (Exception $e) {
                return response()->json($e);
            }
        }
        //
        // abort(401);
        return response()->json($data, $code);
    }


}


// Departamento
    // Ciudad
// Sectores
