<?php

use App\NivelEstudio;
use App\NivelPrograma;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
// --------------------------------------------------------------------------------
/**
 * Guarda la información básica de un egresado.
 */
Route::post('egresados', 'API\EgresadoController@guardarInformacionBasica');
/*
* Completa la información de un egresado,(Experieincias laboral, Referidos,trabajo Actual)
*/
Route::put('completeEgresados/{idEgresado}', 'API\EgresadoController@fullInfo');
/*
*Retorna el Id del egresado para inicio de sesion
*/

Route::get('cuestionario','API\TipoObservacionController@getCuestionario');
Route::get('getIdegresados/{correo}','API\EgresadoController@getEgresadoEmail');
/*
*Validación de carnetización de egresado
*/
Route::get('carnetizacion/{correo}', 'API\CarnetizacionController@validarCarnetizacion');
/*
*Obteniendo todas las solicitudes de carnet pendientes para el Administrador
*/
Route::get('carnetizacion', 'API\CarnetizacionController@getAll');
/*
*Administrador la fecha de respuesta y el estado a "Solicitado" a "respondido" de carnet por egresados
*/
Route::get('carnetizacionUpdateAdmin', 'API\CarnetizacionController@updateAdmin');
/*
 * Obtiene todas la ciudades de un departamento.
 */
Route::get('ciudades/{idDepartamento}', 'CiudadController@getByDepartamento');
/**
 * Obtiene todas la ciudades de un pais.
 */
Route::get('ciudadesPais/{idPais}', 'CiudadController@getAllCitiesWithDeparments');
/**
 * Obtiene todos los departamentos de un país.
 */
Route::get('departamentos/{idPais}', 'DepartamentoController@getByPais');

/**
 * Datos contacto Empresa ADmin
 */
Route::get('contactoHV/{empresa}', 'OfertaController@getContactoHV');

/**
 * Obtiene todos los países.
 */
Route::get('paises', 'PaisController@getAll');
/**
 * Obtiene los niveles de estudio.
 */
Route::get('nivelesEstudio', 'API\NivelEstudioController@getAll');
/**
 * Obtiene los niveles de estudio.
 */
Route::get('nivelesEstudioU', 'API\NivelEstudioController@getAllU');
/**
 * Obtiene las facultades.
 */
Route::get('facultades', 'API\FacultadController@getAll');
/**
 * Obtiene todos los programas de una facultad.
 */
Route::get('programas/{idFacultad}', 'API\ProgramaController@getByFacultad');
/**
 * Verifica el excel de egresados dato por secretaria.
 */
//Route::group(['middleware' => 'cors'], function () {
Route::post('egresados/verificar', 'API\VerificarEgresadoController@verificar');
//});
Route::get('nivelesPrograma', function () {
    return response()->json(NivelEstudio::where('pertenece_u', 1)->get(), 200);
});

Route::post('users/validar/{codigo}', 'UserController@activarCuenta');
Route::get('users/validar/{codigo}', 'UserController@esUsuarioActivoPorCodigo');

Route::get('facultades/{idSede}', 'API\FacultadController@getBySede');
Route::get('programas/{idSede}/{idFacultad}/{idNivelEstudio}', 'API\ProgramaController@getBySedeAndFacultadAndNivelEstudio');
Route::get('sedes', 'API\SedesController@getAll');
/**
 * Obtener los servicios
 */
Route::get('servicios', 'ServicioController@getAll');
/**
 * Gestión Apoyos
 */
Route::get('apoyos', 'ApoyoController@getAll');
Route::get('apoyos/{idApoyo}', 'ApoyoController@getById');
Route::post('apoyos', 'ApoyoController@save');
Route::put('apoyos', 'ApoyoController@update');

/**
 * Gestión eventos
 */
Route::get('eventos', 'EventosController@getAll');
Route::get('eventos/{idEvento}', 'EventosController@getById');
Route::post('eventos', 'EventosController@save');
Route::put('eventos', 'EventosController@update');

/**
 * Gestión egresados
 */
Route::get('admin/egresados', 'Admin\EgresadoController@getAll');
Route::get('admin/egresados/{idEgresado}', 'Admin\EgresadoController@getById');
Route::get('admin/egresados/grados/{idEgresado}', 'GradosController@getByIdEgresado');
// --------------------------------------------------------------------------------
/**
 * Registro de una empresa
 */
Route::post('empresas/store', 'EmpresaController@store');
Route::post('empresas/oferta/store/{empresa}', 'OfertaController@storeOferta');
Route::post('empresas/oferta/update/{oferta}', 'OfertaController@updateOferta');
Route::post('empresas/storeArchivos/{empresa}', 'EmpresaController@uploadFiles')->where(['id' => '[0-9]+']);
Route::get('ofertas/postulados/{oferta}', 'OfertaController@getAllPostulados');
Route::put('postulado/{postulado}/{oferta}/estado', 'OfertaController@changeStatePostulado');
Route::get('getEmpresa/{email}','EmpresaController@getEmpresaEmail');


/**
 * Actualización  de una empresa
 */
// Route::post('empresas/update/{id}', 'EmpresaController@update');
/**
 * Obtiene los sectores
 */
Route::get('sectores', 'SectorController@getAll');
/**
 * Obtiene los sectores
 */
Route::get('sectores-subsectores', 'SectorController@getAllSectors');
/**
 * Obtiene todos los países registrados
 */
Route::get('paises', 'PaisController@getAllCountries');
/**
 * Obtiene los Departamentos que pertenecen a un País.
 * @param pais
 */
Route::get('departamentos/{pais}', 'PaisController@getAllDepartments');
/**
 * Obtiene las Ciudades que pertenecen a un Departamento.
 * @param dep
 */
Route::get('ciudades/{dep}', 'DepartamentoController@getAllCitiesDepartment');
/**
 * Valida si el correo ya esta registrado
 * @param email
 */
Route::get('validarUsuario/{email}', 'ValidadorController@validateEmail');
/**
 * Valida si el correo ya esta registrado
 * @param email
 */
Route::get('validarEmailAdmin/{email}', 'ValidadorController@validateEmailAdmin');
/**
 * Valida si en NIT ya esta registrado
 * @param nit
 */
Route::get('validarNIT/{nit}', 'ValidadorController@validateNit');
/**
 * Valida si en nombre de la empresa ya esta registrado
 * @param nit
 */
Route::get('validarNombreEmpresa/{nombre}', 'ValidadorController@validateNombreEmpresa');


/**
 * Obtiene las empresas que están en estado de espera
 */
Route::get('/empresa/enEspera', 'EmpresaController@getEmpresasEnEspera');
/**
 * Obtiene toda la información de una empresa en específico
 */
Route::get('empresa/{id}', 'EmpresaController@showAllInfo');
/**
 * Cambia el estado de una empresa
 */
Route::put('/empresa/estado/{id}', 'EmpresaController@updateEstado'); //->middleware('api.auth:0');
/**
 * update general de empresa
 */
// Route::put('/empresa/{id}', 'EmpresaController@update');//->middleware('api.auth:1');
Route::put('/empresa/{id}', 'EmpresaController@update')->where(['id' => '[0-9]+']); //->middleware('api.auth:1');
/**
 * Obtiene todas las ofertas de una empresa
 */
Route::get('/ofertas/empresa/{id}', 'OfertaController@getOfertasEmpresa');
/**
 * Obtiene todas las ofertas, vistas desde administrador
 */
Route::get('/ofertas', 'OfertaController@getAllOfertas');
/**
 * Obtiene todas las ofertas que están en espera, vista desde administrador
 */
Route::get('/ofertas/empresas', 'OfertaController@getOfertasEnEspera');
/**
 * Cambia el estado de una oferta desde la empresa
 */
Route::put('/ofertas/estado/{id}', 'OfertaController@updateEstado');
/**
 * Cambia el estado de una oferta desde la empresa
 */
Route::put('/ofertas/estado-proceso/{id}', 'OfertaController@updateEstadoProceso');
/**
 * Cambia el estado de una oferta desde la empresa
 */
Route::get('/ofertas/{id}', 'OfertaController@getOferta');



Route::get('idiomas', 'IdiomaController@getAll');
Route::get('discapacidades', 'DiscapacidadController@getAll');
Route::get('programas', 'API\ProgramaController@getAll');
Route::get('programas/nivel_programa/{idNivelPrograma}', 'API\ProgramaController@getByNivelPrograma');
Route::get('cargos', 'CargoController@getAll');
Route::get('salarios/{moneda}', 'OfertaController@getSalarioPorModena');
// Route::get('salarios', 'OfertaController@getAllSalario');
Route::get('areasConocimiento', 'OfertaController@getAllAreas');

Route::post('ofertas/store', 'OfertaController@storeOferta');
Route::post('login', 'AuthController@login');

Route::get('encriptar/{pass}', function ($pass) {
    return Hash::make($pass);
});
Route::group(['middleware' => ['jwt.verify']], function () {
    /*AÑADE AQUI LAS RUTAS QUE QUIERAS PROTEGER CON JWT*/ });
