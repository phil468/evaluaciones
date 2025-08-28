<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Campania;
use App\Models\ResumenRespuestasEvaluacionDesempenoCompetencia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EvaluacionDeCompetenciasController extends Controller
{
    /**
     * Display the evaluation of competencies page.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $campaniaActual = Campania::where('es_campania_actual', true)->first();
        $campaniaAnterior = $campaniaActual->relacionadoAnterior ?? null;

        $evaluaciones = [];
        $promedioGeneral = null;
        $puntajeEsperado = null;
        $tieneResultados = false;

        if ($campaniaAnterior && Auth::user()->personal_id) {
            $personalId = Auth::user()->personal_id;

            $detalles = DB::table('resumen_respuestas_evaluacion_desempeno_competencias as r')
                ->join('secciones as c', 'c.id', '=', 'r.competencia_id')
                ->select('c.id', 'c.name', DB::raw('AVG(COALESCE(r.puntaje_calibrado, r.puntaje)) as promedio'))
                ->where('r.personal_id', $personalId)
                ->where('r.campania_id', $campaniaAnterior->id)
                ->groupBy('c.id', 'c.name')
                ->get();

            $tieneResultados = $detalles->count() > 0;
            if ($tieneResultados) {
                $promedioGeneral = round($detalles->avg('promedio'), 2);
            }

            // Puntaje esperado
            $nombre = trim((string) $campaniaAnterior->name);
            if ($nombre === '2024-2025') {
                try {
                    if (class_exists(\App\Models\EncargadosPlanesDeAccion::class)) {
                        $epa = \App\Models\EncargadosPlanesDeAccion::where('empleado_id', $personalId)->first();
                        $puntajeEsperado = $epa->valor_esperado ?? null;
                    }
                } catch (\Throwable $e) {
                    $puntajeEsperado = null;
                }
            } elseif ($nombre === '2025-2026') {
                $puntajeEsperado = (float) env('EVAL_COMP_ESPERADO_2025_2026', 8.0);
            }

            // Arreglo para la vista índice
            if ($campaniaAnterior) {
                $evaluaciones[$campaniaAnterior->anio_mostrar] = [
                    'puntaje'        => $promedioGeneral,
                    'progreso'       => $tieneResultados ? 100 : 0,
                    'tieneResultados'=> $tieneResultados,
                    'campania_id'    => $campaniaAnterior->id,
                ];
            }
        }

        return view('evaluacion_de_competencias.index', compact('evaluaciones', 'campaniaAnterior', 'promedioGeneral', 'puntajeEsperado'));
        // $evaluaciones = [
        //     '2025' => [
        //         'puntaje' => null, // Si no hay resultados todavía
        //         'progreso' => 0,    // Porcentaje de progreso (0-100)
        //         'tieneResultados' => false
        //     ],
        //     '2024' => [
        //         'puntaje' => 7.34,
        //         'progreso' => 60,   // Porcentaje de progreso (0-100)
        //         'tieneResultados' => true
        //     ]
        // ];

        // return view('evaluacion_de_competencias.index', compact('evaluaciones'));
    }

    public function mostrarResultados(Request $request)
    {
        
        $validated = $request->validate([
            'campania_id' => 'required|integer|exists:campanias,id'
        ]);

        $campania = Campania::findOrFail($validated['campania_id']);
        $personalId = Auth::user()->personal_id ?? null;

        $competencias = [];
        $puntajes = [];
        $promedioGeneral = null;
        $puntajeEsperado = null;

        if ($personalId) {
            $detalles = DB::table('resumen_respuestas_evaluacion_desempeno_competencias as r')
                ->join('secciones as c', 'c.id', '=', 'r.competencia_id')
                ->select('c.name as competencia', DB::raw('AVG(COALESCE(r.puntaje_calibrado, r.puntaje)) as promedio'))
                ->where('r.personal_id', $personalId)
                ->where('r.campania_id', $campania->id)
                ->groupBy('c.id', 'c.name')
                ->orderBy('promedio', 'desc')
                ->get();

            $competencias = $detalles->pluck('competencia')->toArray();
            $puntajes = $detalles->pluck('promedio')->map(fn($v) => round((float)$v, 2))->toArray();
            $promedioGeneral = count($puntajes) ? round(collect($puntajes)->avg(), 2) : null;

            // Puntaje esperado
            $nombre = trim((string) $campania->name);
            if ($nombre === '2024-2025') {
                try {
                    if (class_exists(\App\Models\EncargadosPlanesDeAccion::class)) {
                        $epa = \App\Models\EncargadosPlanesDeAccion::where('empleado_id', $personalId)->first();
                        $puntajeEsperado = $epa->valor_esperado ?? null;
                    }
                } catch (\Throwable $e) {
                    $puntajeEsperado = null;
                }
            } elseif ($nombre === '2025-2026') {
                $puntajeEsperado = (float) env('EVAL_COMP_ESPERADO_2025_2026', 8.0);
            }
        }

        $descripcionNivel = ''; // opcional

        return view('evaluacion_de_competencias.resultados', compact(
            'campania',
            'promedioGeneral',
            'competencias',
            'puntajes',
            'puntajeEsperado',
            'descripcionNivel'
        ));

        // // Validar el id de campaña recibido
        // $request->validate([
        //     'campania_id' => 'required|integer|exists:campanias,id'
        // ]);
        // // Obtener el promedio general
        // $promedioGeneral = 7.36; // Reemplaza con la consulta a tu base de datos
        
        // // Obtener competencias y sus puntajes
        // $competencias = [
        //     'Liderazgo',
        //     'Comunicación asertiva',
        //     'Gestión y Organización',
        //     'Trabajo en equipo',
        //     'Toma de decisiones considerando impactos',
        //     'Planificación Efectiva',
        //     'Análisis Estratégico',
        //     'Aprendizaje Continuo',
        //     'Gestión de recursos',
        //     'Compromiso',
        //     'Innovación'
        // ];
        
        // $puntajes = [7.8, 7.6, 7.4, 7.9, 7.5, 8.0, 7.7, 7.5, 7.3, 7.6, 7.2];
        
        // // Descripción del nivel según el puntaje
        // $descripcionNivel = 'Muestra los comportamientos esperados en situaciones simples, con oportunidades de mejora.';
        
        // return view('evaluacion_de_competencias.resultados', compact(
        //     'promedioGeneral',
        //     'competencias',
        //     'puntajes',
        //     'descripcionNivel'
        // ));
    }

    public function detalle()
    {
        // Aquí puedes implementar la lógica para mostrar el detalle de la evaluación de competencias
        // Por ejemplo, podrías obtener los resultados específicos de una campaña y mostrarlos en una vista
        
        return view('evaluacion_de_competencias.detalle');
    }

}
