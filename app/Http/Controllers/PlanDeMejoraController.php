<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PlanDeMejoraController extends Controller
{
    public function index()
    {
        // Obtener los datos de los planes de mejora
        $planes = [
            '2025' => [
                'progreso' => 0,
                'tieneResultados' => false
            ],
            '2024' => [
                'progreso' => 70,
                'tieneResultados' => true
            ]
        ];
        
        return view('plan_de_mejora.index', compact('planes'));
    }

    public function detalle(Request $request)
    {
        $anio = $request->anio;
        // Aquí obtienes los detalles específicos para el año seleccionado
        
        return view('planes_de_mejora.detalle', compact('anio'));
    }
}