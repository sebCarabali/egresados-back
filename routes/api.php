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

/**
 * store egresado basic info (url => '/api/egresados').
 */
Route::post('egresados', 'API\EgresadoController@storeBasicInfo');

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

