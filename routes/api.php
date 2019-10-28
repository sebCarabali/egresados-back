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

Route::get('departamentos', 'DepartamentoController@getAllDepartments');
Route::get('ciudades/departamento/{dep}', 'DepartamentoController@getAllCitiesDepartment');
Route::get('sectores', 'SectorController@getAllSectors');

Route::post('/login', 'UserController@login');

Route::get('/empresa', 'EmpresaController@index');

Route::post('/empresa', 'EmpresaController@store');
Route::get('/empresa/enEspera', 'EmpresaController@getEmpresasEnEspera');
Route::get('/empresa/{id}', 'EmpresaController@showAllInfo');
Route::put('/empresa/estado/{id}', 'EmpresaController@updateEstado');//->middleware('api.auth:0');
Route::put('/empresa/{id}', 'EmpresaController@update');//->middleware('api.auth:1');


Route::get('/ofertas/empresa/{id}', 'OfertaController@getOfertasEmpresa');

// Admin
Route::get('/ofertas/empresas', 'OfertasController@getOfertasEnEspera');
