<?php

namespace App\Http\Controllers;

use App\Http\Resources\RolResource;
use App\Role;
use Illuminate\Http\Request;

class RolController extends Controller
{
    public function getAll()
    {
        return RolResource::collection(Role::all());
    }
}
