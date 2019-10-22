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

    public function show($id)
    {
        // Codigo de error por defecto
        $code = 404;
        $empresa = Empresa::find($id);

        if (is_object($empresa)) {
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

    public function updateEstado($id, Request $request){


        // Recoger los datos por PUT
        $json = $request->input('json', null);
        $params_array = json_decode($json, true);

        // Código de error por defecto
        $code = 400;
        $data = null;

        if (!empty($params_array)) {

            // Buscar el registro
            $empresa = Empresa::where('id', $id)->first();

            if (!empty($empresa) && is_object($empresa)) {
              // Actualizar el registro en concreto
              $empresa->update(['estado' => $params_array['estado']]);
              $data = $empresa;
              $code = 200;

            }
        }
        return response()->json($data, $code);
    }

    private function getIdentity($request)
    {
        // Conseguir usuario autentificado
        $jwtAuth = new JwtAuth();
        $token = $request->header('Authorization', null);
        $user = $jwtAuth->checkToken($token, true);

        return $user;
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        // Código de error por defecto
        $code = 400;
        $data = null;
        if (request()->ajax()) {
            try {
                // return response()->json(request("sectores"));
                $this->validate(request(), [
                    //Datos usuario login
                    'email' => 'required|max:255|email|unique:users,email',
                    'contrasenia' => 'required|string|min:6',

                    // Datos empresa
                    'NIT' => 'required|integer|unique:empresas,nit',
                    'nombreEmpresa' => 'required|unique:empresas,nombre',
                    'razonSocial' => 'required|string',
                    'anioCreacion' => 'required|numeric|between:1900,' . Carbon::now()->format("Y"),
                    'sitioWebEmp' => 'required|url',
                    'codigoPostalEmp' => 'required|integer',
                    'direccionEmp' => 'required|string',
                    'barrioEmp' => 'required|string',

                    'ingresosEmp' => 'required|string',
                    'numEmpleados' => 'required|integer',

                    'ciudadEmp' => 'required|exists:ciudades,id',

                    // 'sectores' => 'required|array',
                    // 'sectores.*' => 'required|integer|distinct|exists:sectores,id',
                    'sectores' => 'required|integer|exists:sectores,id',
                    // //datos representante
                    'nombreResp' => 'required|string',
                    // 'apellidoResp'  => 'required|string',
                    'cargo'  => 'required|string',
                    'telefonoResp'  => 'required|integer',
                    'telefonoMovilResp'  => 'required|integer',
                    'emailCorpResp'  => 'required|email',
                    'dir_empresa' => 'required|integer',
                    'codigoPostalResp' => 'required_if:dir_empresa,0|integer',
                    'direccionResp' => 'required_if:dir_empresa,0|string',
                    'barrioResp' => 'required_if:dir_empresa,0|string',

                    'ciudadResp' => 'required_if:dir_empresa,0|exists:ciudades,id',
                ]);


                $user = new User();
                $user->email = request('email');
                $user->password = bcrypt(request('contrasenia'));
                // return response()->json(request());
                $user->rol()->associate(Role::where('nombre', 'Empresa')->firstOrFail());
                // return response()->json($user);

                $direccionEmpr = new Localizacion();
                $direccionEmpr->codigo_postal = request('codigoPostalEmp');
                $direccionEmpr->direcccion = request('direccionEmp');
                $direccionEmpr->barrio = request('barrioEmp');
                $direccionEmpr->ciudad()->associate(Ciudad::find(request('ciudadEmp'))->firstOrFail());
                // return response()->json($direccionEmpr);

                $empresa = new Empresa();
                $empresa->nit = request('NIT');
                $empresa->nombre = request('nombreEmpresa');
                $empresa->razon_social = request('razonSocial');
                $empresa->numero_empleados = request('numEmpleados');
                $empresa->ingresos = request('ingresosEmp');
                $empresa->sitio_web = request('sitioWebEmp');
                $empresa->anio_creacion = request('anioCreacion');
                // return response()->json($empresa);

                $empresa->estado = false;
                $empresa->fecha_registro = Carbon::now();
                $empresa->total_publicaciones = 0;
                $empresa->limite_publicaciones = 0;
                $empresa->num_publicaciones_actuales = 0;

                $dir_empresa = request('dir_empresa');
                $direccionRepresen = new Localizacion();
                if (!$dir_empresa) {
                    $direccionRepresen->codigo_postal = request('codigoPostalResp');
                    $direccionRepresen->direcccion = request('direccionResp');
                    $direccionRepresen->barrio = request('barrioResp');
                    $direccionRepresen->ciudad()->associate(Ciudad::find(request('ciudadResp'))->firstOrFail());
                }
                // return response()->json($direccionRepresen);

                $representante = new RepresentanteEmpresa();
                $representante->nombres = request('nombreResp');
                $representante->apellidos = "";
                // $representante->apellidos = request('apellidoResp');
                $representante->telefono = request('telefonoResp');
                $representante->telefono_movil = request('telefonoMovilResp');
                $representante->correo_corporativo = request('emailCorpResp');
                // return response()->json($representante);
                // return response()->json(Cargo::find(request('rep_id_cargo'))->firstOrFail());
                $representante->cargo()->associate(Cargo::firstOrCreate(["nombre"=>request('cargo')]));
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
