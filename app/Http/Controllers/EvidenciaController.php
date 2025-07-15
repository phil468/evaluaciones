<?php

namespace App\Http\Controllers;

use App\Models\ObjetivoHasEvidencia;
use App\Models\ObjetivoPrecargadoHasEvidencia;
use App\Models\PlanesDeMejoraHasEvidencia;
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

    public function download_evidencia_plan($id)
    {
        $evidencia = PlanesDeMejoraHasEvidencia::findOrFail($id);
        return Storage::download('adm/'.$evidencia->ruta, $evidencia->name);
    }
    //
    public function download_evidencia_objetivo_precargado($id)
    {
        $evidencia = ObjetivoPrecargadoHasEvidencia::findOrFail($id);
        return Storage::download('adm/'.$evidencia->ruta, $evidencia->name);
    }

}
