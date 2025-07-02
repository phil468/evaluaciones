<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\TipoRelacionJerarquica;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class TipoRelacionJerarquicoController extends Controller
{
    public function lista()
    {
        $tipos = TipoRelacionJerarquica::where('estado', true)->get();
        return response()->json($tipos);
    }
}