<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EvaluacionPorObjetivosController extends Controller
{
    /**
     * Display the evaluation by objectives.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Aquí puedes implementar la lógica para mostrar las evaluaciones por objetivos
        return view('evaluacion_por_objetivos.index');
    }

    /**
     * Show the details of a specific evaluation by objectives.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function detalle($id)
    {
        // Aquí puedes implementar la lógica para mostrar los detalles de una evaluación específica por objetivos
        return view('evaluacion_por_objetivos.detalle', compact('id'));
    }
}
