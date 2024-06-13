<?php

namespace App\Http\Controllers;

use App\Models\ObjetivoHasEvidencia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EvidenciaController extends Controller
{
    //
    public function download($id)
    {
        $evidencia = ObjetivoHasEvidencia::findOrFail($id);
        return Storage::download('adm/'.$evidencia->ruta, $evidencia->name);
    }
}
