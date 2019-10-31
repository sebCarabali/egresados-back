<?php

namespace App\Http\Controllers;

use App\Empresa;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ValidadorController extends Controller
{
    public function validateEmail($email)
    {
        // dd($request);
       $user = User::whereEmail($email)->first();
       if ($user) {
           return response()->json("El correo ya existe", 200);
       }
       return response()->json("Correcto", 200);
    }

    public function validateNit($nit)
    {
        // dd($request);
       $user = Empresa::whereNit($nit)->first();
       if ($user) {
           return response()->json("El correo NIT existe", 200);
       }
       return response()->json("Correcto", 200);
    }
}
