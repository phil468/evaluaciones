<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Campania;
use App\Models\CampaniaHasEvaluado;
use App\Models\Personal;
use App\Models\ResumenRespuestasEvaluacionDesempenoCompetencia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Traits\CalculosCompetencias; // ← Agregar trait

class EvaluacionDeCompetenciasController extends Controller
{
    use CalculosCompetencias; // ← Usar trait
    /**
     * Display the evaluation of competencies page.
     *
     * @return \Illuminate\Http\Response
     */

    public function resultados(Request $request)
    {
        // Determinar si es para otro empleado (desde resultados de equipo) o el usuario actual
        $empleadoId = $request->input('empleado_id'); // Desde resultados de equipo
        $campaniaId = $request->input('campania_id');  // ID de campaña específica

        // dd($empleadoId, $campaniaId);
        
        if ($empleadoId) {
            // Vista desde resultados de equipo - empleado específico
            $personal = Personal::findOrFail($empleadoId);
            $personalId = $personal->id;
            $esVistaEquipo = true;
        } else {
            // Vista personal del usuario autenticado
            $user = auth()->user();
            if (!$user->personal_id) {
                return redirect()->back()->with('error', 'No tienes un perfil de personal asociado.');
            }
            $personal = $user->personal;
            $personalId = $user->personal_id;
            $esVistaEquipo = false;
        }

        if (!$campaniaId) {
            return redirect()->back()->with('error', 'ID de campaña requerido.');
        }

        // Obtener la campaña
        $campania = Campania::with(['evaluaciones' => function($q) {
            $q->where('tipo_de_evaluacion_id', 1); // Solo evaluaciones de competencias
        }])->findOrFail($campaniaId);

        $evaluacion = $campania->evaluaciones->first();
        if (!$evaluacion) {
            return redirect()->back()->with('error', 'No se encontró evaluación de competencias para esta campaña.');
        }

        // Verificar permisos para ver resultados
        $now = now();
        $fechaMostrarResultados = $evaluacion->fecha_para_mostrar_resultados;
        $puedeVerResultados = $fechaMostrarResultados <= $now;

        if (!$puedeVerResultados && !$esVistaEquipo) {
            // Solo restringir en vista personal, en vista de equipo el jefe puede ver siempre
            return redirect()->back()->with('error', 'Los resultados aún no están disponibles.');
        }

        // Obtener datos de competencias y puntajes
        // $resumenData = ResumenRespuestasEvaluacionDesempenoCompetencia::where('campania_id', $campaniaId)
        //     ->where('personal_id', $personalId)
        //     ->with('competencia')
        //     ->get();
        // dd($resumenData);

        // $detalles = DB::table('resumen_respuestas_evaluacion_desempeno_competencias as r')
        //         ->join('campania_has_competencias as chc', 'chc.id', '=', 'r.competencia_id')
        //         ->join('secciones as competencias', 'competencias.id', '=', 'chc.competencia_id')
        //         // ->join('secciones as competencias', 'competencias.id', '=', 'r.competencia_id')
        //         ->select('competencias.name as competencia', DB::raw('AVG(COALESCE(r.puntaje_calibrado, r.puntaje)) as promedio'))
        //         ->where('r.personal_id', $personalId)
        //         ->where('r.campania_id', $campania->id)
        //         ->groupBy('competencias.id', 'competencias.name')
        //         ->orderBy('promedio', 'desc')
        //         ->get();

        $resultado = $this->calcularPromedioCompetencias($personalId, $campaniaId);

        if ($resultado['competencias'] === []) {
            $competencias = [];
            $puntajes = [];
            $promedioGeneral = null;
        } else {
$competencias = $resultado['competencias'];
        $puntajes = $resultado['puntajes'];
        $promedioGeneral = $resultado['promedio_general'];
            // $competencias = $detalles->pluck('competencia')->toArray();
            // $puntajes = $detalles->pluck('promedio')->map(fn($v) => round((float)$v, 1))->toArray();
            // $promedioGeneral = count($puntajes) ? round(collect($puntajes)->avg(), 1) : null;

        }

        // Obtener puntaje esperado
        $puntajeEsperado = $this->obtenerPuntajeEsperado($personalId, $campaniaId);

        // Datos adicionales para la vista
        $datosPersonal = [
            'nombre' => $personal->name,
            'cargo' => $personal->cargo->name ?? 'Sin cargo'
        ];

        // dd( 
        //     $competencias,
        //     $puntajes,
        //     $promedioGeneral,
        //     $puntajeEsperado,
        //     $esVistaEquipo,
        //     $datosPersonal
        // );

        return view('evaluacion_de_competencias.resultados', compact(
            'campania',
            'competencias',
            'puntajes',
            'promedioGeneral',
            'puntajeEsperado',
            'esVistaEquipo',
            'datosPersonal'
        ));
    }

    public function index()
    {
        $user = auth()->user();
        $evaluaciones = [];

        if ($user->personal_id) {
            // Obtener todas las campañas donde el usuario haya participado
            $campanias = CampaniaHasEvaluado::where('personal_id', $user->personal_id)
                ->with(['campania.evaluaciones' => function($q) {
                    $q->where('tipo_de_evaluacion_id', 1); // Solo evaluaciones de competencias
                }])
                ->get()
                ->unique('campania_id');
                // dd($campanias);

            foreach ($campanias as $campaniaEvaluado) {
                $campania = $campaniaEvaluado->campania;
                // dd($campania);
                if (!$campania) continue;

                // Obtener la evaluación de competencias para esta campaña
                $evaluacion = $campania->evaluaciones->first();
                if (!$evaluacion) continue;

                //año de la campaña, extraer del nombre que es tipo "2024-2025"
                $anio = explode('-', $campania->name)[0];
                
                // Verificar si ya pasó la fecha para mostrar resultados
                $now = now();
                $fechaMostrarResultados = $evaluacion->fecha_para_mostrar_resultados;
                $puedeVerResultados = $fechaMostrarResultados <= $now;

                // Obtener el resumen de respuestas para calcular el puntaje
                $resumen = ResumenRespuestasEvaluacionDesempenoCompetencia::where('campania_id', $campania->id)
                    ->where('personal_id', $user->personal_id)
                    ->get();

                $tieneResultados = $resumen->isNotEmpty();
                $puntajeObtenido = $tieneResultados ? ($resumen->avg('puntaje_calibrado') ?: $resumen->avg('puntaje')) : 0;
                
                // Obtener puntaje esperado
                $puntajeEsperado = $this->obtenerPuntajeEsperado($user->personal_id, $campania->id);

                // Determinar el progreso de la barra basado en porcentaje
                $progreso = 0;
                if ($tieneResultados && $puedeVerResultados && $puntajeEsperado > 0) {
                    $progreso = (($puntajeObtenido / $puntajeEsperado) * 100);
                } elseif ($tieneResultados && !$puedeVerResultados) {
                    $progreso = 100; // Barra completa gris para resultados pendientes
                }

                $evaluaciones[$anio] = [
                    'campania_id' => $campania->id,
                    'evaluacion_id' => $evaluacion->id,
                    'tieneResultados' => $tieneResultados,
                    'puedeVerResultados' => $puedeVerResultados,
                    'puntajeObtenido' => round($puntajeObtenido, 1),
                    'puntajeEsperado' => $puntajeEsperado,
                    'porcentaje' => round($progreso, 0),
                    'progreso' => $progreso,
                    'fecha_para_mostrar_resultados' => $fechaMostrarResultados,
                    'estado' => $this->determinarEstadoEvaluacion($tieneResultados, $puedeVerResultados)
                ];
            }
        }

        // VERIFICACIÓN ESPECIAL PARA CAMPAÑA 2024-2025 (ID = 1)
        if (!isset($evaluaciones['2024'])) { // Si no se encontró ya la campaña 2024-2025
            $campania2024 = Campania::with(['evaluaciones' => function($q) {
                $q->where('tipo_de_evaluacion_id', 1);
            }])->find(1); // ID campaña 2024-2025

            if ($campania2024) {
                // Verificar si tiene resultados en ResumenRespuestas
                $tieneResultados2024 = ResumenRespuestasEvaluacionDesempenoCompetencia::where('campania_id', 1)
                    ->where('personal_id', $user->personal_id)
                    ->exists();

                // Verificar si aparece en EncargadosPlanesDeAccion para esta campaña
                $apareceEnPlanes2024 = \App\Models\EncargadosPlanesDeAccion::where('empleado_id', $user->personal_id)
                    ->whereHas('plan_de_mejora', function($q) {
                        $q->where('campania_id', 1);
                    })
                    ->exists();

                // Si tiene resultados O aparece en planes, incluir la campaña
                if ($tieneResultados2024 || $apareceEnPlanes2024) {
                    $evaluacion2024 = $campania2024->evaluaciones->first();
                    
                    if ($evaluacion2024) {
                        $now = now();
                        $fechaMostrarResultados = $evaluacion2024->fecha_para_mostrar_resultados;
                        $puedeVerResultados = $fechaMostrarResultados <= $now;

                        // Calcular puntaje solo si tiene resultados
                        $puntaje = 0;
                        if ($tieneResultados2024) {
                            $resumen2024 = ResumenRespuestasEvaluacionDesempenoCompetencia::where('campania_id', 1)
                                ->where('personal_id', $user->personal_id)
                                ->get();
                            $puntajeObtenido = $resumen2024->avg('puntaje_calibrado') ?: $resumen2024->avg('puntaje');
                        }

                        // Obtener puntaje esperado
                        $puntajeEsperado = $this->obtenerPuntajeEsperado($user->personal_id, 1);

                        // Determinar progreso
                        $progreso = 0;
                        if ($tieneResultados2024 && $puedeVerResultados) {
                            $progreso = (($puntajeObtenido / $puntajeEsperado) * 100);
                        } elseif ($tieneResultados2024 && !$puedeVerResultados) {
                            $progreso = 100;
                        }

                        $evaluaciones['2024'] = [
                            'campania_id' => $campania2024->id,
                            'evaluacion_id' => $evaluacion2024->id,
                            'tieneResultados' => $tieneResultados2024,
                            'puedeVerResultados' => $puedeVerResultados,
                            'puntajeObtenido' => round($puntajeObtenido, 1),
                            'puntajeEsperado' => $puntajeEsperado,
                            'porcentaje' => round($progreso, 0),
                            'progreso' => $progreso,
                            'fecha_para_mostrar_resultados' => $fechaMostrarResultados,
                            'estado' => $this->determinarEstadoEvaluacion($tieneResultados2024, $puedeVerResultados)
                        ];
                    }
                }
            }
        }

        // Ordenar por año descendente
        krsort($evaluaciones);

        return view('evaluacion_de_competencias.index', compact('evaluaciones'));
    }

    private function determinarEstadoEvaluacion($tieneResultados, $puedeVerResultados)
    {
        if (!$tieneResultados) {
            return 'sin_resultados'; // No participó en la evaluación
        }
        
        if ($tieneResultados && !$puedeVerResultados) {
            return 'resultados_pendientes'; // Tiene resultados pero no puede verlos aún
        }
        
        return 'disponible'; // Puede ver los resultados
    }

    private function obtenerPuntajeEsperado($personalId, $campaniaId)
    {
        $campania = Campania::find($campaniaId);
        if (!$campania) return 5; // Default

        $nombre = trim((string) $campania->name);
        
        if ($nombre === '2024-2025') {
            try {
                $epa = \App\Models\EncargadosPlanesDeAccion::where('empleado_id', $personalId)->first();
                return $epa->valor_esperado ?? 5;
            } catch (\Throwable $e) {
                return 5;
            }
        } elseif ($nombre === '2025-2026') {
            return (float) env('EVAL_COMP_ESPERADO_2025_2026', 8.0);
        }

        return 5; // Default
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
                ->join('secciones as competencias', 'competencias.id', '=', 'r.competencia_id')
                ->select('competencias.name as competencia', DB::raw('AVG(COALESCE(r.puntaje_calibrado, r.puntaje)) as promedio'))
                ->where('r.personal_id', $personalId)
                ->where('r.campania_id', $campania->id)
                ->groupBy('competencias.id', 'competencias.name')
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

    }

    public function detalle()
    {
        // Aquí puedes implementar la lógica para mostrar el detalle de la evaluación de competencias
        // Por ejemplo, podrías obtener los resultados específicos de una campaña y mostrarlos en una vista
        
        return view('evaluacion_de_competencias.detalle');
    }

}
