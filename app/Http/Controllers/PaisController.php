<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Pais;

class PaisController extends Controller
{
    public function getAll()
    {
        return response()->json(Pais::all(), 200);
    }
}
