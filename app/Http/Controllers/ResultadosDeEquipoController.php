<?php

namespace App\Http\Controllers;

use App\Models\Personal;
use Illuminate\Http\Request;

class ResultadosDeEquipoController extends Controller
{
    /**
     * Muestra la vista de resumen del equipo
     */
    public function index()
    {
        // Obtener los miembros del equipo
        $miembrosEquipo = Personal::where('reporta_a', auth()->user()->personal_id)
            ->with(['competencias', 'objetivos', 'planDesarrollo'])
            ->get()
            ->map(function($miembro) {
                return [
                    'id' => $miembro->id,
                    'nombre' => $miembro->nombre_completo,
                    'puntaje_competencias' => $miembro->competencias->avg('puntaje') ?? 'N/A',
                    'puntaje_pdi' => $miembro->planDesarrollo->progreso ?? 'N/A',
                    'puntaje_objetivos' => $miembro->objetivos->avg('cumplimiento') ?? 'N/A',
                ];
            });
            
        return view('resultados-equipo.index', compact('miembrosEquipo'));
    }
    
    /**
     * Muestra el detalle de un miembro específico
     */
    public function detalle($id)
    {
        // Obtener el miembro del equipo
        $personal = Personal::findOrFail($id);
        
        // Datos del miembro
        $miembro = [
            'id' => $personal->id,
            'nombre' => $personal->nombre_completo,
            'cargo' => $personal->cargo->nombre
        ];
        
        // Datos de competencias
        $competencias = [
            '2025' => [
                'progreso' => 0,
                'tieneResultados' => false
            ],
            '2024' => [
                'progreso' => 67,
                'tieneResultados' => true
            ]
        ];
        
        // Datos de objetivos
        $objetivos = [
            '2025' => [
                'progreso' => 0,
                'tieneResultados' => false
            ],
            '2024' => [
                'progreso' => 75,
                'tieneResultados' => true
            ]
        ];
        
        // Datos de PDI
        $pdi = [
            '2025' => [
                'progreso' => 0,
                'tieneResultados' => false
            ],
            '2024' => [
                'progreso' => 60,
                'tieneResultados' => true
            ]
        ];
        
        return view('resultados-equipo.detalle', compact(
            'miembro',
            'competencias',
            'objetivos',
            'pdi'
        ));
    }
}
