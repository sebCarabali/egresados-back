<?php

use Illuminate\Http\Request;

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
/**
 * Obtiene todas la ciudades de un departamento.
 */
Route::get('ciudades/{idDepartamento}', 'CiudadController@getByDepartamento');
/**
 * Obtiene todos los departamentos de un país.
 */
Route::get('departamentos/{idPais}', 'DepartamentoController@getByPais');
/**
 * Obtiene todos los países.
 */
Route::get('paises', 'PaisController@getAll');
/**
 * Obtiene los niveles de estudio.
 */
Route::get('nivelesEstudio', 'API\NivelEstudioController@getAll');
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
    Route::post('egresados/verificar', 'API\EgresadoController@validateExcel');
//});
// --------------------------------------------------------------------------------
/**
 * Registro de una empresa
 */
Route::post('empresas/store', 'EmpresaController@store');
/**
 * Actualización  de una empresa
 */
Route::post('empresas/update', 'EmpresaController@update');
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
 * Valida si en NIT ya esta registrado
 * @param nit
 */
Route::get('validarNIT/{nit}', 'ValidadorController@validateNit');

/**
 * Ruta para el login
 */
Route::post('/login', 'UserController@login');
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
Route::put('/empresa/estado/{id}', 'EmpresaController@updateEstado');//->middleware('api.auth:0');
/**
 * update general de empresa
 */
Route::put('/empresa/{id}', 'EmpresaController@update');//->middleware('api.auth:1');
/**
 * Obtiene todas las ofertas de una empresa
 */
Route::get('/ofertas/empresa/{id}', 'OfertaController@getOfertasEmpresa');
/**
 * Obtiene todas las ofertas de una empresa
 */
Route::get('/ofertas/activas/empresa/{id}', 'OfertaController@getOfertasActivasEmpresa');
/**
 * Obtiene todas las ofertas que están en espera, vista desde administrador
 */
Route::get('/ofertas/empresas', 'OfertaController@getOfertasEnEspera');



Route::get('idiomas', 'IdiomaController@getAll');
Route::get('discapacidades', 'DiscapacidadController@getAll');
Route::get('programas', 'API\ProgramaController@getAll');
Route::get('cargos', 'CargoController@getAll');

Route::post('ofertas/store', 'OfertaController@storeOferta');

