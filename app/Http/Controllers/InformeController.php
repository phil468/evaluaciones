<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InformeController extends Controller
{
    /**
     * Download the evaluation report.
     *
     * @return \Illuminate\Http\Response
     */
    public function descargar()
    {
        // Aquí puedes implementar la lógica para descargar el informe
        return response()->download(storage_path('app/public/informe_evaluacion.pdf'));
    }
}
