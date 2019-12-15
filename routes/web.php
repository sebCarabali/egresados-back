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
    // $user = \App\User::whereEmail("luisafgr@unicauca.edu.co")->first();
    $user = \App\User::get()->last();
    if($user){
      $user->notify(new \App\Notifications\RegistroEmpresa());
    }
    // return view('welcome');
    return view('mail.notificacion_registro_empresa')->with("user", $user);
});
