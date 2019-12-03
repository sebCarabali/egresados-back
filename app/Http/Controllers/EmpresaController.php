<?php

namespace App\Http\Controllers;

use App\AdministradorEmpresa;
use App\Cargo;
use App\Ciudad;
use Illuminate\Http\Request;

use App\Empresa;
use App\Helpers\JwtAuth;
use App\Http\Requests\EmpresaStoreRequest;
use App\Http\Requests\EmpresaUpdateRequest;
use App\Localizacion;
use App\RepresentanteEmpresa;
use App\Role;
use App\Sector;
use App\User;
use Exception;
use Illuminate\Support\Carbon;
use Illuminate\Validation\ValidationException;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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
        $empresa = Empresa::find($id);

        if (is_object($empresa)) {
            $empresa->load('direccion', 'representante', 'administrador');
            $empresa->direccion->load('ciudad');
            $empresa->direccion->ciudad->load('departamento');
            $empresa->direccion->ciudad->departamento->load('pais');


            $empresa->administrador->load('direccion', 'user', 'cargo');

            $sectores = [];

            //Por cada subsector obtengo el sector
            foreach ($empresa->subSectores as $subSector) {
                $res = Sector::find($subSector->id_sectores);

                if (!isset($sectores->$res)) {
                    $sectores[] = $res;
                }
                // Se borra el atributo pivot, el cual no es necesario
                unset($subSector['pivot']);
            }
            $empresa['sectores'] = $sectores;

            foreach ($empresa->sectores as $sector) {
                $subsec = [];
                foreach ($empresa->subSectores as $subSector) {
                    if ($subSector->id_sectores == $sector->id_aut_sector) {
                        $subsec[] = $subSector;
                    }
                }
                $sector['subSectores'] = $subsec;
            }

            unset($empresa['subSectores']);

            $code = 200;
            $data = $empresa;
        } else {
            $data = null;
        }
        return response()->json($data, $code);
    }

    // public function update(Empresa $id,Request $request)
    public function update(EmpresaUpdateRequest $request, Empresa $id)
    {
        // Código de error por defecto
        $code = 400;
        $data = null;
        // return $this->fail("PASO LAS VALIDACIONES");
        $empresa = $id;
        $user = $empresa->administrador->user;
        $user->email = $request['datos-cuenta']['email'];
        if (!empty($request['datos-cuenta']['contrasenia'])) {
            $user->password = bcrypt($request['datos-cuenta']['contrasenia']);
        }

        $direccionEmpr = $empresa->direccion;
        if (!empty($request['loc-contact-empresa']['codigoPostalEmp'])) {
            $direccionEmpr->codigo_postal = $request['loc-contact-empresa']['codigoPostalEmp'];
        }
        $direccionEmpr->direccion = $request['loc-contact-empresa']['direccionEmp'];
        $direccionEmpr->barrio = $request['loc-contact-empresa']['barrioEmp'];
        $direccionEmpr->ciudad()->associate(Ciudad::find($request['loc-contact-empresa']['idCiudad']));

        // $empresa = new Empresa();
        $empresa->nit = $request['datos-generales-empresa']['NIT'];
        $empresa->nombre = $request['datos-generales-empresa']['nombreEmpresa'];
        $empresa->razon_social = $request['datos-generales-empresa']['razonSocial'];
        $empresa->numero_empleados = $request['datos-generales-empresa']['numEmpleados'];
        $empresa->ingresos = $request['datos-generales-empresa']['ingresosEmp'];
        if (!empty($request['loc-contact-empresa']['sitioWebEmp'])) {
            $empresa->sitio_web = $request['loc-contact-empresa']['sitioWebEmp'];
        }
        $empresa->anio_creacion = $request['datos-generales-empresa']['anioCreacion'];
        $empresa->url_doc_camaracomercio = "url pdf camara y comercio";

        if (!empty($request['loc-contact-empresa']['telefonoEmp'])) {
            $empresa->telefono = $request['loc-contact-empresa']['telefonoEmp'];
        }
        if (!empty($request['loc-contact-empresa']['emailEmp'])) {
            $empresa->correo = $request['loc-contact-empresa']['emailEmp'];
        }
        $empresa->descripcion = $request['datos-generales-empresa']['descripcionEmpresa'];


        $direccionAdministrador = $empresa->administrador->direccion;
        $direccionAdministrador->codigo_postal = $request['loc-contact-empresa']['codigoPostalEmp'];
        $direccionAdministrador->direccion = $request['datos-resp']['direccionTrabajoResp'];
        $direccionAdministrador->barrio = $request['loc-contact-empresa']['barrioEmp'];
        $direccionAdministrador->ciudad()->associate(Ciudad::find($request['loc-contact-empresa']['idCiudad']));

        $representanteLegal = $empresa->representante;
        $representanteLegal->nombre = $request['datos-resp']['nombrereplegal'];
        $representanteLegal->apellidos = $request['datos-resp']['apellidoreplegal'];
        if (!empty($request['datos-resp']['telefonoreplegal'])) {
            $representanteLegal->telefono = $request['datos-resp']['telefonoreplegal'];
        }
        $representanteLegal->telefono_movil = $request['datos-resp']['telefonoMovilreplegal'];

        $administradorEmp = $empresa->administrador;
        $administradorEmp->nombres = $request['datos-resp']['nombreResp'];
        $administradorEmp->apellidos = $request['datos-resp']['apellidoResp'];
        if($request['datos-resp']['horarioContactoResp']){
            $administradorEmp->horario_contacto = $request['datos-resp']['horarioContactoResp'];

        }
        if (!empty($request['datos-resp']['telefonoResp'])) {
            $administradorEmp->telefono = $request['datos-resp']['telefonoResp'];
        }
        $administradorEmp->telefono_movil = $request['datos-resp']['telefonoMovilResp'];

        $administradorEmp->correo_corporativo = $request['datos-resp']['emailCorpResp'];
        $ids = array();
        foreach ($request['sectores']['subsectores'] as $sect) {
            array_push($ids, $sect);
        }

        DB::beginTransaction();
        $user->update();
        $direccionEmpr->update();
        $empresa->update();
        $empresa->subSectores()->sync($ids);

        $direccionAdministrador->update();

        $cargo = Cargo::whereNombre($request['datos-resp']['cargo'])->first();
        if (!$cargo) {
            $cargo = new Cargo();
            $cargo->nombre = $request['datos-resp']['cargo'];
            $cargo->estado = false;
            $current_id = DB::table('cargos')->max('id_aut_cargos');
            $cargo->id_aut_cargos = $current_id + 1;
            $cargo->save();
        }
        $administradorEmp->cargo()->associate($cargo);
        $administradorEmp->update();

        $representanteLegal->update();

        DB::commit();
        return $this->success($empresa);
    }


    public function updateEstado($id, Request $request)
    {
        // Código de error por defecto
        $code = 400;
        $data = null;

        // Validador
        try {
            $this->validate(request(), [
                'estado' => 'required',
            ]);

            //if (!empty($params_array)) {
            // Buscar el registro
            $empresa = Empresa::find($id);

            if (!empty($empresa) && is_object($empresa)) {
                if ($request['estado'] == 'Activo' && !empty($request['limite_publicaciones'])) {
                    // Actualizar el registro en concreto

                    $empresa->update([
                        'estado' => $request['estado'],
                        'limite_publicaciones' => $request['limite_publicaciones'],
                        'fecha_activacion' => Carbon::now('-5:00'),
                    ]);
                    $data = $empresa;
                    $code = 200;
                } else if ($request['estado'] == 'En espera' || $request['estado'] == 'Inactivo') {
                    // Actualizar el registro en concreto
                    $empresa->update(['estado' => $request['estado']]);
                    $data = $empresa;
                    $code = 200;
                }
            }
        } catch (ValidationException $ev) {
            return response()->json($ev->validator->errors(), 400);
        } catch (Exception $e) {
            return response()->json($e);
        }
        return response()->json($data, $code);
    }


    /**
     * Almacene un recurso recién creado en el almacenamiento.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EmpresaStoreRequest $request)
    // public function store(Request $request)
    {
        $code = 400;
        $data = null;

        try {
            $user = new User();
            $user->email = $request['datos']['datos-cuenta']['email'];
            $user->password = bcrypt($request['datos']['datos-cuenta']['contrasenia']);
            $rol = Role::whereNombre('Empresa')->first();
            if (!$rol) {
                return $this->fail("Al parecer no hay datos en la BD!");
            }
            $user->rol()->associate($rol);

            $direccionEmpr = new Localizacion();
            if ($request['datos']['loc-contact-empresa']['codigoPostalEmp']) {
                $direccionEmpr->codigo_postal = $request['datos']['loc-contact-empresa']['codigoPostalEmp'];
            }
            $direccionEmpr->direccion = $request['datos']['loc-contact-empresa']['direccionEmp'];
            $direccionEmpr->barrio = $request['datos']['loc-contact-empresa']['barrioEmp'];
            $direccionEmpr->ciudad()->associate(Ciudad::find($request['datos']['loc-contact-empresa']['ciudadEmp']));

            $empresa = new Empresa();
            $empresa->nit = $request['datos']['datos-generales-empresa']['NIT'];
            $empresa->nombre = $request['datos']['datos-generales-empresa']['nombreEmpresa'];
            $empresa->razon_social = $request['datos']['datos-generales-empresa']['razonSocial'];
            $empresa->numero_empleados = $request['datos']['datos-generales-empresa']['numEmpleados'];
            $empresa->ingresos = $request['datos']['datos-generales-empresa']['ingresosEmp'];
            if ($request['datos']['loc-contact-empresa']['sitioWebEmp']) {
                $empresa->sitio_web = $request['datos']['loc-contact-empresa']['sitioWebEmp'];
            }
            $empresa->anio_creacion = $request['datos']['datos-generales-empresa']['anioCreacion'];
            $empresa->url_doc_camaracomercio = "url pdf camara y comercio";

            if ($request['datos']['loc-contact-empresa']['telefonoEmp']) {
                $empresa->telefono = $request['datos']['loc-contact-empresa']['telefonoEmp'];
            }
            if ($request['datos']['loc-contact-empresa']['emailEmp']) {
                $empresa->correo = $request['datos']['loc-contact-empresa']['emailEmp'];
            }

            $empresa->estado = "En espera";
            $empresa->fecha_registro = Carbon::now();
            $empresa->total_publicaciones = 0;
            $empresa->limite_publicaciones = 0;
            $empresa->num_publicaciones_actuales = 0;
            $empresa->descripcion = $request['datos']['datos-generales-empresa']['descripcionEmpresa'];

            $direccionAdministrador = new Localizacion();
            $direccionAdministrador->codigo_postal = $request['datos']['loc-contact-empresa']['codigoPostalEmp'];
            $direccionAdministrador->direccion = $request['datos']['datos-resp']['direccionTrabajoResp'];
            $direccionAdministrador->barrio = $request['datos']['loc-contact-empresa']['barrioEmp'];
            $direccionAdministrador->ciudad()->associate(Ciudad::find($request['datos']['loc-contact-empresa']['ciudadEmp']));

            $representanteLegal = new RepresentanteEmpresa();
            $representanteLegal->nombre = $request['datos']['datos-resp']['nombrereplegal'];
            $representanteLegal->apellidos = $request['datos']['datos-resp']['apellidoreplegal'];
            if ($request['datos']['datos-resp']['telefonoreplegal']) {
                $representanteLegal->telefono = $request['datos']['datos-resp']['telefonoreplegal'];
            }
            $representanteLegal->telefono_movil = $request['datos']['datos-resp']['telefonoMovilreplegal'];
            $representante = new AdministradorEmpresa();
            $representante->nombres = $request['datos']['datos-resp']['nombreResp'];
            $representante->apellidos = $request['datos']['datos-resp']['apellidoResp'];

            if($request['datos']['datos-resp']['horarioContactoResp']){

                $representante->horario_contacto = $request['datos']['datos-resp']['horarioContactoResp'];
            }


            if ($request['datos']['datos-resp']['telefonoResp']) {
                $representante->telefono = $request['datos']['datos-resp']['telefonoResp'];
            }
            $representante->telefono_movil = $request['datos']['datos-resp']['telefonoMovilResp'];
            $representante->correo_corporativo = $request['datos']['datos-resp']['emailCorpResp'];

            $ids = array();
            foreach ($request['datos']['sectores']['subsectores'] as $sect) {
                // foreach ($sect["subSectores"] as $subs) {
                // return response()->json($sect);
                // array_push($ids, $subs["idSubSector"]);
                array_push($ids, $sect);
                // }
            }

            DB::beginTransaction();

            $pdf = $request->file('fileInput')->store('/empresas/pdfs', 'files');
            $empresa->url_doc_camaracomercio = asset($pdf);
            if ($request->file('logoInput')) {
                $image_s = $request->file('logoInput')->store('/empresas/logos', 'files');
                $empresa->url_logo = asset($image_s);
            }

            $user->save();
            $direccionEmpr->save();
            $empresa->direccion()->associate($direccionEmpr);
            $empresa->save();
            // $empresa->subSectores()->sync($request['sectores']['sectores']);
            $empresa->subSectores()->sync($ids);

            $direccionAdministrador->save();
            $cargo = Cargo::whereNombre($request['datos']['datos-resp']['cargo'])->first();
            if (!$cargo) {
                $cargo = new Cargo();
                $cargo->nombre = $request['datos']['datos-resp']['cargo'];
                $cargo->estado = false;
                $current_id = DB::table('cargos')->max('id_aut_cargos');
                $cargo->id_aut_cargos = $current_id + 1;
                $cargo->save();
            }
            $representante->direccion()->associate($direccionAdministrador);
            $representante->user()->associate($user);
            $representante->cargo()->associate($cargo);
            $representante->empresa()->associate($empresa);
            $representante->save();

            $representanteLegal->empresa()->associate($empresa);
            $representanteLegal->save();
            DB::commit();
            return $this->success($empresa->id_aut_empresa);
        } catch (Exception $e) {
            return $this->fail("Registro Empresa=>" . $e->getMessage());
        }
    }

    public function uploadFiles(Empresa $empresa, Request $request)
    {
        // return response()->json(["esto llego", $request->allFiles()]);
        $request->validate([
            "fileInput" => "required|file|mimes:pdf|max:2048",
            "logoInput" => "image"
        ]);

        // $pdf = Storage::disk('files')->put('/empresas/pdfs', $request->file('fileInput'));
        $pdf = $request->file('fileInput')->storePublicly('/empresas/pdfs');
        $empresa->url_doc_camaracomercio = $pdf;
        if ($request->file('logoInput')) {
            // $image_s = Storage::disk('public')->put('/empresas/logos', $request->file('logoInput'));
            // $image_s2 = $request->file('logoInput')->store('/empresas/logos');
            // $image_s3 = $request->file('logoInput')->storePublicly('/empresas/logos');
            $image_s = $request->file('logoInput')->store('/empresas/logos', 'files');
            $empresa->url_logo = $image_s;
            $empresa->save();
            // return $this->success([ $image_s, $image_s2, $image_s3, $pdf]);
            return $this->success([$image_s, $pdf]);
        }
        $empresa->save();
        return $this->success([asset($pdf), $pdf]);
        // return response()->json(["esto llego", $request]);
    }
}
