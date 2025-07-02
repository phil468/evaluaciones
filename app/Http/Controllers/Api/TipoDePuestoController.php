<?php

namespace App\Http\Controllers\API;

use App\Models\TipoDePuesto;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TipoDePuestoController extends Controller
{
    public function getLista()
    {
        $tiposDePuesto = TipoDePuesto::where('estado', true)->get();
        return response()->json($tiposDePuesto);
    }
}
