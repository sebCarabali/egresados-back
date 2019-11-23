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

        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $user = User::whereEmail($email)->first();
            if ($user) {
                return $this->fail("El correo electrónico ya existe", 422);
            }
            return $this->success("Correcto");
        } else {
            return $this->fail("No es un correo electrónico!", 422);
        }
    }

    public function validateNit($nit)
    {
        // dd($request);
        $empresa = Empresa::whereNit($nit)->first();
        if ($empresa) {
            return $this->fail("El NIT ya existe!", 422);
        }
        return $this->success("Correcto");
    }
    public function validateNombreEmpresa($nombre)
    {
        // dd($request);
        $empresa = Empresa::whereNombre($nombre)->first();
        if ($empresa) {
            return $this->fail("El nombre ya existe!", 422);
        }
        return $this->success("Correcto");
    }
    
}
