<?php

namespace App\Http\Controllers;

use App\Models\Campania;
use App\Models\CampaniaHasEvaluado;
use App\Models\Personal;
use Illuminate\Http\Request;
use App\Traits\CalculosCompetencias; // ← Agregar trait

class ResultadosDeEquipoController extends Controller
{
    use CalculosCompetencias; // ← Usar trait
    /**
     * Muestra la vista de resumen del equipo
     */
    public function index()
    {
        $campaniaActual = Campania::where('es_campania_actual', true)->first();
        // dd($campaniaActual);

        $nombre_campania = explode('-', $campaniaActual->name)[0];
        $puntajeEsperado = (float) env('EVAL_COMP_ESPERADO_2025_2026', 8.0);

        // Obtener los miembros del equipo
        $miembrosEquipo = Personal::where('reporta_a', auth()->user()->personal_id)
         ->where('cesado', false)
            // ->with(['competencias', 'objetivos', 'planDesarrollo'])
            ->get()
            ->map(function($miembro) use ($campaniaActual, $puntajeEsperado) {
                return [
                    'id' => $miembro->id,
                    'nombre' => $miembro->name,
                    'puntaje_competencias' => $this->puntaje_competencias($miembro->id, $campaniaActual->id) ?? 'N/A',

                ];
            })
            ;
        // dd($miembrosEquipo);

        return view('resultados-equipo.index', compact('miembrosEquipo', 'nombre_campania', 'puntajeEsperado'));
    }
    
    /**
     * Muestra el detalle de un miembro específico
     */

    public function detalle($id)
    {
        $personal = Personal::findOrFail($id);
        $user = auth()->user();
        
        // Datos del miembro
        $miembro = [
            'id' => $personal->id,
            'nombre' => $personal->name,
            'cargo' => $personal->cargo->name ?? 'Sin cargo'
        ];
        
        // Obtener todas las campañas donde el miembro haya participado
        $campanias = CampaniaHasEvaluado::where('personal_id', $personal->id)
            ->with(['campania.evaluaciones'])
            ->get()
            ->unique('campania_id');

        $competencias = [];
        $objetivos = [];
        $pdi = [];

        // Procesar cada campaña
        foreach ($campanias as $campaniaEvaluado) {
            $campania = $campaniaEvaluado->campania;
            if (!$campania) continue;

            $anio = explode('-', $campania->name)[0];
            
            // COMPETENCIAS (tipo_de_evaluacion_id = 1)
            $evaluacionComp = $campania->evaluaciones->where('tipo_de_evaluacion_id', 1)->first();
            if ($evaluacionComp) {
                $competencias[$anio] = $this->procesarEvaluacion($personal->id, $campania->id, $evaluacionComp, 'competencias');
            }

            // OBJETIVOS (tipo_de_evaluacion_id = 2)  
            $evaluacionObj = $campania->evaluaciones->where('tipo_de_evaluacion_id', 2)->first();
            if ($evaluacionObj) {
                $objetivos[$anio] = $this->procesarEvaluacion($personal->id, $campania->id, $evaluacionObj, 'objetivos');
            }

            // PDI (Planes de Mejora)
            $pdi[$anio] = $this->procesarPDI($personal->id, $campania->id);
        }

        // VERIFICACIÓN ESPECIAL PARA CAMPAÑA 2024-2025 (ID = 1)
        if (!isset($competencias['2024'])) {
            $this->procesarCampania2024($personal->id, $competencias, $objetivos, $pdi);
        }

        // Ordenar por año descendente
        krsort($competencias);
        krsort($objetivos);
        krsort($pdi);
        
        return view('resultados-equipo.detalle', compact(
            'miembro',
            'competencias', 
            'objetivos',
            'pdi'
        ));
    }

private function procesarEvaluacion($personalId, $campaniaId, $evaluacion, $tipo)
{
    $now = now();
    $fechaMostrarResultados = $evaluacion->fecha_para_mostrar_resultados;
    $puedeVerResultados = $fechaMostrarResultados <= $now;

    if ($tipo === 'competencias') {
        // Obtener puntaje de competencias
        // USAR EL TRAIT para obtener el cálculo consistente
        $resultado = $this->calcularPromedioCompetencias($personalId, $campaniaId);
        
        $tieneResultados = $resultado['promedio_general'] !== null;
        $puntajeObtenido = $resultado['promedio_general'] ?? 0;
        // $resumen = \App\Models\ResumenRespuestasEvaluacionDesempenoCompetencia::where('campania_id', $campaniaId)
        //     ->where('personal_id', $personalId)
        //     ->get();

        // $tieneResultados = $resumen->isNotEmpty();
        // $puntajeObtenido = $tieneResultados ? ($resumen->avg('puntaje_calibrado') ?: $resumen->avg('puntaje')) : 0;
        
        // Obtener puntaje esperado
        $puntajeEsperado = $this->obtenerPuntajeEsperado($personalId, $campaniaId);
        
    } elseif ($tipo === 'objetivos') {
        // Obtener puntaje de objetivos (implementar según tu lógica)
        $tieneResultados = false; // Implementar consulta a objetivos
        $puntajeObtenido = 0; // Implementar cálculo
        $puntajeEsperado = 5; // o el valor que corresponda
    }

    // Calcular progreso
    $progreso = 0;
    if ($tieneResultados && $puedeVerResultados && $puntajeEsperado > 0) {
        $progreso = ((round($puntajeObtenido, 1) / $puntajeEsperado) * 100);
    } elseif ($tieneResultados && !$puedeVerResultados) {
        $progreso = 100; // Barra completa gris
    }

    return [
        'campania_id' => $campaniaId, // ← Asegúrate de incluir esto
        'tieneResultados' => $tieneResultados,
        'puedeVerResultados' => $puedeVerResultados,
        'puntajeObtenido' => round($puntajeObtenido, 1),
        'puntajeEsperado' => $puntajeEsperado,
        'progreso' => round($progreso, 0),
        'estado' => $this->determinarEstado($tieneResultados, $puedeVerResultados),
        'esVistaEquipo' => true,
        // 'tieneResultados' => $tieneResultados,
        // 'puedeVerResultados' => $puedeVerResultados,
        // 'puntajeObtenido' => round($puntajeObtenido, 1),
        // 'puntajeEsperado' => $puntajeEsperado,
        // 'progreso' => round($progreso, 0),
        // 'estado' => $this->determinarEstado($tieneResultados, $puedeVerResultados)
    ];
}

private function procesarPDI($personalId, $campaniaId)
{
    // Verificar si existe plan de mejora para este empleado y campaña
    $planMejora = \App\Models\EncargadosPlanesDeAccion::where('empleado_id', $personalId)
        ->whereHas('plan_de_mejora', function($q) use ($campaniaId) {
            $q->where('campania_id', $campaniaId);
        })
        ->with('plan_de_mejora')
        ->first();

    if (!$planMejora) {
        return [
            'tieneResultados' => false,
            'puedeVerResultados' => false,
            'progreso' => 0,
            'estado' => 'sin_resultados'
        ];
    }

    $now = now();
    $planConfig = $planMejora->plan_de_mejora;
    
    // Verificar si puede ver resultados (basado en las fases del plan)
    $puedeVerResultados = ($planConfig->primera_fase_activa || $planConfig->segunda_fase_activa) && 
                         !$planMejora->estado_pendiente;

    // Calcular progreso del PDI (puedes ajustar esta lógica según tus necesidades)
    $progreso = 0;
    if ($planMejora && !$planMejora->estado_pendiente) {
        $progreso = 100; // Completado
    } elseif ($planMejora && $planMejora->estado_pendiente) {
        $progreso = 50; // En progreso
    }

    return [
        'tieneResultados' => true,
        'puedeVerResultados' => $puedeVerResultados,
        'progreso' => $progreso,
        'estado' => $planMejora->estado_pendiente ? 'resultados_pendientes' : 'disponible'
    ];
}

private function procesarCampania2024($personalId, &$competencias, &$objetivos, &$pdi)
{
    $campania2024 = \App\Models\Campania::with(['evaluaciones'])->find(1);
    if (!$campania2024) return;

    // Verificar si tiene resultados o aparece en planes
    $tieneResultados2024 = \App\Models\ResumenRespuestasEvaluacionDesempenoCompetencia::where('campania_id', 1)
        ->where('personal_id', $personalId)
        ->exists();

    $apareceEnPlanes2024 = \App\Models\EncargadosPlanesDeAccion::where('empleado_id', $personalId)
        ->whereHas('plan_de_mejora', function($q) {
            $q->where('campania_id', 1);
        })
        ->exists();

    if ($tieneResultados2024 || $apareceEnPlanes2024) {
        // Competencias 2024
        $evaluacionComp2024 = $campania2024->evaluaciones->where('tipo_de_evaluacion_id', 1)->first();
        // if ($evaluacionComp2024) {
        //     $competencias['2024'] = $this->procesarEvaluacion($personalId, 1, $evaluacionComp2024, 'competencias');
        // }

        if ($evaluacionComp2024) {
            // Usar el trait para obtener datos consistentes
            $resultado = $this->calcularPromedioCompetencias($personalId, 1);
            
            $now = now();
            $fechaMostrarResultados = $evaluacionComp2024->fecha_para_mostrar_resultados;
            $puedeVerResultados = $fechaMostrarResultados <= $now;
            
            $tieneResultados = $resultado['promedio_general'] !== null;
            $puntajeObtenido = $resultado['promedio_general'] ?? 0;
            $puntajeEsperado = $this->obtenerPuntajeEsperado($personalId, 1);
            
            // Calcular progreso
            $progreso = 0;
            if ($tieneResultados && $puedeVerResultados && $puntajeEsperado > 0) {
                $progreso = ((round($puntajeObtenido, 1) / $puntajeEsperado) * 100);
            } elseif ($tieneResultados && !$puedeVerResultados) {
                $progreso = 100;
            }
            
            $competencias['2024'] = [
                'campania_id' => 1,
                'tieneResultados' => $tieneResultados,
                'puedeVerResultados' => $puedeVerResultados,
                'puntajeObtenido' => round($puntajeObtenido, 1),
                'puntajeEsperado' => $puntajeEsperado,
                'progreso' => round($progreso, 0),
                'estado' => $this->determinarEstado($tieneResultados, $puedeVerResultados),
                'esVistaEquipo' => true
            ];
        }

        // Objetivos 2024
        $evaluacionObj2024 = $campania2024->evaluaciones->where('tipo_de_evaluacion_id', 2)->first();
        if ($evaluacionObj2024) {
            $objetivos['2024'] = $this->procesarEvaluacion($personalId, 1, $evaluacionObj2024, 'objetivos');
        }

        // PDI 2024
        $pdi['2024'] = $this->procesarPDI($personalId, 1);
    }
}

private function obtenerPuntajeEsperado($personalId, $campaniaId)
{
    $campania = \App\Models\Campania::find($campaniaId);
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

private function determinarEstado($tieneResultados, $puedeVerResultados)
{
    if (!$tieneResultados) {
        return 'sin_resultados';
    }
    
    if ($tieneResultados && !$puedeVerResultados) {
        return 'resultados_pendientes';
    }
    
    return 'disponible';
}

public function puntaje_competencias($personalId, $campaniaId)
    {
        $resultado = $this->calcularPromedioCompetencias($personalId, $campaniaId);
        return $resultado['promedio_general'] ?? 'N/A';
    }

    // public function puntaje_competencias($personalId, $campaniaId)
    // {
    //     $detalles = \DB::table('resumen_respuestas_evaluacion_desempeno_competencias as resumen')
    //         ->join('secciones as competencias', 'competencias.id', '=', 'resumen.competencia_id')
    //         ->select('competencias.id', 'competencias.name', \DB::raw('AVG(COALESCE(resumen.puntaje_calibrado, resumen.puntaje)) as promedio'))
    //         ->where('resumen.personal_id', $personalId)
    //         ->where('resumen.campania_id', $campaniaId)
    //         ->groupBy('competencias.id', 'competencias.name')
    //         ->get();

    //     if ($detalles->count() > 0) {
    //         return round($detalles->avg('promedio'), 1);
    //     }

    //     return 'N/A';
    // }
}
