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
