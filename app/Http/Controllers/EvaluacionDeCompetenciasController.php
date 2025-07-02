<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EvaluacionDeCompetenciasController extends Controller
{
    /**
     * Display the evaluation of competencies page.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $evaluaciones = [
            '2025' => [
                'puntaje' => null, // Si no hay resultados todavía
                'progreso' => 0,    // Porcentaje de progreso (0-100)
                'tieneResultados' => false
            ],
            '2024' => [
                'puntaje' => 7.34,
                'progreso' => 60,   // Porcentaje de progreso (0-100)
                'tieneResultados' => true
            ]
        ];

        return view('evaluacion_de_competencias.index', compact('evaluaciones'));
    }

    public function mostrarResultados(Request $request)
    {
        // Validar el id de campaña recibido
        $request->validate([
            'campania_id' => 'required|integer|exists:campanias,id'
        ]);
        // Obtener el promedio general
        $promedioGeneral = 7.36; // Reemplaza con la consulta a tu base de datos
        
        // Obtener competencias y sus puntajes
        $competencias = [
            'Liderazgo',
            'Comunicación asertiva',
            'Gestión y Organización',
            'Trabajo en equipo',
            'Toma de decisiones considerando impactos',
            'Planificación Efectiva',
            'Análisis Estratégico',
            'Aprendizaje Continuo',
            'Gestión de recursos',
            'Compromiso',
            'Innovación'
        ];
        
        $puntajes = [7.8, 7.6, 7.4, 7.9, 7.5, 8.0, 7.7, 7.5, 7.3, 7.6, 7.2];
        
        // Descripción del nivel según el puntaje
        $descripcionNivel = 'Muestra los comportamientos esperados en situaciones simples, con oportunidades de mejora.';
        
        return view('evaluacion_de_competencias.resultados', compact(
            'promedioGeneral',
            'competencias',
            'puntajes',
            'descripcionNivel'
        ));
    }

    public function detalle()
    {
        // Aquí puedes implementar la lógica para mostrar el detalle de la evaluación de competencias
        // Por ejemplo, podrías obtener los resultados específicos de una campaña y mostrarlos en una vista
        
        return view('evaluacion_de_competencias.detalle');
    }

}
