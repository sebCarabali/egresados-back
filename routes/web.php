<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*
  Authorization Middlewares:
  0: Administrador
  1: Empresa
  2: Egresado
*/
Route::get('/', function () {
    // dd(\App\Departamento::all());
    return view('welcome');
});

Route::post('/api/login', 'UserController@login');

Route::get('/api/empresa', 'EmpresaController@index');
Route::post('/api/empresa', 'EmpresaController@store');
Route::get('/api/empresa/{id}', 'EmpresaController@show');
Route::put('/api/empresa/{id}', 'EmpresaController@update')->middleware('api.auth:1');

Route::put('/api/empresa/estado/{id}', 'EmpresaController@updateEstado');//->middleware('api.auth:0');
