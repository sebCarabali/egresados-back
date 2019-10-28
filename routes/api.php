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
Route::post('egresados', 'API\EgresadoController@storeBasicInfo');
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
 * 
 */
// --------------------------------------------------------------------------------
Route::post('empresas/store', 'EmpresaController@store');

Route::get('sectores-subsectores', 'SectorController@getAllSectors');
Route::get('paises', 'PaisController@getAllCountries');
Route::get('departamentos/{pais}', 'PaisController@getAllDepartments');
Route::get('ciudades/{dep}', 'DepartamentoController@getAllCitiesDepartment');

Route::get('aaa/{email}', function($email){
    dd($email);
});
Route::get('validarUsuario/{email}', 'ValidadorController@validateEmail');
Route::get('validarNIT/{nit}', 'ValidadorController@validateNit');

