<?php

namespace App\Http\Controllers;

use App\Models\Campania;
use App\Models\EvaluadorHasEvaluado;
use App\Models\Personal;
use App\Traits\CalculosCompetencias; // ← Agregar trait
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laravel\Socialite\Facades\Socialite;
use App\Models\Objetivo;   // <-- importar

class HomeController extends Controller
{
    use CalculosCompetencias; // ← Usar trait
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return $this->inicio();
        // return view('dash.index');
    }

    //inicio

    public function inicio()
    {
        // $evaluaciones_pendientes = 
        // EvaluadorHasEvaluado::where('evaluador_id', Auth::user()->personal_id)
        //     ->get();
        //     // ->count();
        //         $evaluacionesPendientes = EvaluadorHasEvaluado::where('evaluador_id', Auth::user()->personal_id)
        //         ->with(['evaluado', 'evaluacion'])
        //         ->get()
        //         ->filter(function($evaluacion) {
        //             return $evaluacion->estado_pendiente;
        //         })
        //         ->count();

        //         // dd($evaluacionesPendientes);
        // hasPendingEvaluations
        
        $user = Auth::user();
        // Cargar las relaciones necesarias para evitar consultas N+1
        if ($user->personal_id) {
            $user->load([
                'personal.evaluadorHasEvaluados.evaluacion', 
                // 'personal.evaluadoHasEvaluadors.evaluacion'
            ]);
        }

        $evaluacionesPendientes = $user->hasPendingEvaluations();
        $personalId = Auth::user()->personal_id ?? null;

        $campaniaActual = Campania::where('es_campania_actual', true)->first();
        $campaniaAnterior = $campaniaActual ? ($campaniaActual->relacionadoAnterior ?? null) : null;

        // --- EVALUACIÓN DE COMPETENCIAS (INDEPENDIENTE) ---
        $competenciasData = $this->procesarCompetencias($personalId, $campaniaActual, $campaniaAnterior);

        // --- EVALUACIÓN DE OBJETIVOS (INDEPENDIENTE) ---
        $objetivosData = $this->procesarObjetivos($personalId, $campaniaActual, $campaniaAnterior);

        return view('inicio.index', [
            'evaluacionesPendientes' => $evaluacionesPendientes,
            
            // Datos de Competencias
            'competenciasAnio' => $competenciasData['anio'],
            'competenciasCampania' => $competenciasData['campania'],
            'tieneResultadosCompetencias' => $competenciasData['tieneResultados'],
            'promedioGeneral' => $competenciasData['promedioGeneral'],
            'puntajeEsperado' => $competenciasData['puntajeEsperado'],
            'mensajeEsperadoCompetencias' => $competenciasData['mensajeEsperado'],
            'mensajeResultadosCompetencias' => $competenciasData['mensajeResultados'],
            
            // Datos de Objetivos
            'objetivosAnio' => $objetivosData['anio'],
            'objetivosCampania' => $objetivosData['campania'],
            'objetivosTotal' => $objetivosData['total'],
            'objetivosMensaje' => $objetivosData['mensaje'],
            'tieneResultadosObjetivos' => $objetivosData['tieneResultados'],
        ]);
        // $tieneResultados = false;
        // $promedioGeneral = null;
        // $puntajeEsperado = null;
        // $mensajeEsperado = null;
        // $mensajeResultados = null;

        // if ($campaniaAnterior && $personalId) {
        //     // Promedios por competencia del usuario (COALESCE puntaje_calibrado, puntaje)
        //     $detalles = DB::table('resumen_respuestas_evaluacion_desempeno_competencias as r')
        //         ->join('secciones as c', 'c.id', '=', 'r.competencia_id')
        //         ->select('c.id', 'c.name', DB::raw('AVG(COALESCE(r.puntaje_calibrado, r.puntaje)) as promedio'))
        //         ->where('r.personal_id', $personalId)
        //         ->where('r.campania_id', $campaniaAnterior->id)
        //         ->groupBy('c.id', 'c.name')
        //         ->get();

        //     $tieneResultados = $detalles->count() > 0;
        //     if ($tieneResultados) {
        //         $promedioGeneral = round($detalles->avg('promedio'), 1);
        //     } else {
        //         $mensajeResultados = 'No tiene resultados para la campaña anterior.';
        //     }

        //     // Puntaje esperado según campaña
        //     $nombreCampania = trim((string) $campaniaAnterior->name);
        //     if ($nombreCampania === '2024-2025') {
        //         // EncargadosPlanesDeAccion.empleado_id = personal del usuario
        //         try {
        //             if (class_exists(\App\Models\EncargadosPlanesDeAccion::class)) {
        //                 $epa = \App\Models\EncargadosPlanesDeAccion::where('empleado_id', $personalId)->first();
        //                 $puntajeEsperado = $epa->valor_esperado ?? null;
        //                 if ($puntajeEsperado === null) {
        //                     $mensajeEsperado = '';
        //                 }
        //             } else {
        //                 $mensajeEsperado = 'No existe el modelo EncargadosPlanesDeAccion para obtener el puntaje esperado.';
        //             }
        //         } catch (\Throwable $e) {
        //             $mensajeEsperado = 'No fue posible obtener el puntaje esperado (error de configuración).';
        //         }
        //     } elseif ($nombreCampania === '2025-2026') {
        //         $puntajeEsperado = (float) env('EVAL_COMP_ESPERADO_2025_2026', 8.0);
        //         if (!$puntajeEsperado) {
        //             $mensajeEsperado = 'No se encontró el valor esperado para 2025-2026 en el archivo .env.';
        //         }
        //     }
        // }

        
        // // --- Objetivos (campaña 2024-2025) ---
        // $objetivosTotal = null;
        // $objetivosMensaje = null;

        // if (!$campaniaAnterior) {
        //     $objetivosMensaje = 'No existe campaña anterior.';
        // } elseif (!$personalId) {
        //     $objetivosMensaje = 'Su usuario no está asociado a un personal.';
        // } elseif (trim((string)$campaniaAnterior->name) === '2024-2025') {
        //     // Buscar EvaluadorHasEvaluado de evaluación por objetivos, la campania_id se encuentra en la tabla evaluaciones

        //     $ehe = EvaluadorHasEvaluado::query()
        //         ->where('evaluado_id', $personalId)
        //         ->where('evaluador_has_evaluados.evaluacion_id', 4) // evaluación por objetivos
        //         ->whereHas('evaluacion', function ($q) use ($campaniaAnterior) {
        //             $q->where('campania_id', $campaniaAnterior->id);
        //         })
        //         ->first();

        //     if ($ehe) {
        //         try {
        //             $objetivosTotal = Objetivo::calcularTotal($ehe->id); // puede ser > 100 o null
        //             if ($objetivosTotal === null) {
        //                 $objetivosMensaje = 'No se pudo calcular su porcentaje de objetivos para la campaña anterior.';
        //             }
        //         } catch (\Throwable $e) {
        //             $objetivosMensaje = 'Ocurrió un error al calcular su porcentaje de objetivos.';
        //         }
        //     } else {
        //         $objetivosMensaje = 'No se encontró su evaluación por objetivos en la campaña anterior.';
        //     }
        // } else {
        //     $objetivosMensaje = 'La campaña anterior no requiere cálculo de objetivos.';
        // }

        // return view('inicio.index', [
        //     'evaluacionesPendientes' => $evaluacionesPendientes,
        //     'campaniaAnterior'   => $campaniaAnterior,
        //     'tieneResultados'    => $tieneResultados,
        //     'promedioGeneral'    => $promedioGeneral,
        //     'puntajeEsperado'    => $puntajeEsperado,
        //     'mensajeEsperado'    => $mensajeEsperado,
        //     'mensajeResultados'  => $mensajeResultados,
        //     'evaluacionesPendientes' => $evaluacionesPendientes ?? false, // conservar lo que ya tenías
        //     'objetivosTotal'     => $objetivosTotal,
        //     'objetivosMensaje'   => $objetivosMensaje,
        // ]);
    }

    

    /**
     * Procesa las evaluaciones de competencias independientemente
     */
    private function procesarCompetencias($personalId, $campaniaActual, $campaniaAnterior)
    {
        $now = now();
        $campaniaCompetencias = null;
        $anioCompetencias = null;
        
        // 1. Verificar campaña actual primero
        if ($campaniaActual && $personalId) {
            $evaluacionActual = $campaniaActual->evaluaciones()
                ->where('tipo_de_evaluacion_id', 1) // Competencias
                ->first();

            if ($evaluacionActual && $evaluacionActual->fecha_para_mostrar_resultados <= $now) {
                // Verificar si tiene resultados
                $tieneResultados = $this->tieneResultadosEnCampania($personalId, $campaniaActual->id);
                if ($tieneResultados) {
                    $campaniaCompetencias = $campaniaActual;
                    $anioCompetencias = $this->extraerAnio($campaniaActual->name);
                }
            }
        }

        // 2. Si no tiene resultados en actual, verificar anterior
        if (!$campaniaCompetencias && $campaniaAnterior && $personalId) {
            $evaluacionAnterior = $campaniaAnterior->evaluaciones()
                ->where('tipo_de_evaluacion_id', 1)
                ->first();

            if ($evaluacionAnterior && $evaluacionAnterior->fecha_para_mostrar_resultados <= $now) {
                $tieneResultados = $this->tieneResultadosEnCampania($personalId, $campaniaAnterior->id);
                if ($tieneResultados) {
                    $campaniaCompetencias = $campaniaAnterior;
                    $anioCompetencias = $this->extraerAnio($campaniaAnterior->name);
                }
            }
        }

        // 3. Verificación especial para campaña 2024-2025 (ID = 1)
        if (!$campaniaCompetencias && $personalId) {
            $campania2024 = Campania::find(1);
            if ($campania2024) {
                $evaluacion2024 = $campania2024->evaluaciones()
                    ->where('tipo_de_evaluacion_id', 1)
                    ->first();

                if ($evaluacion2024 && $evaluacion2024->fecha_para_mostrar_resultados <= $now) {
                    $tieneResultados2024 = $this->tieneResultadosEnCampania($personalId, 1);
                    $apareceEnPlanes2024 = \App\Models\EncargadosPlanesDeAccion::where('empleado_id', $personalId)
                        ->whereHas('plan_de_mejora', function($q) {
                            $q->where('campania_id', 1);
                        })
                        ->exists();

                    if ($tieneResultados2024 || $apareceEnPlanes2024) {
                        $campaniaCompetencias = $campania2024;
                        $anioCompetencias = '2024';
                    }
                }
            }
        }

        // Calcular resultados si hay campaña disponible
        if ($campaniaCompetencias && $personalId) {
            // USAR EL TRAIT para calcular competencias
            $resultado = $this->calcularPromedioCompetencias($personalId, $campaniaCompetencias->id);
            
            $tieneResultados = $resultado['promedio_general'] !== null;
            $promedioGeneral = $resultado['promedio_general'];
            $puntajeEsperado = $this->obtenerPuntajeEsperado($personalId, $campaniaCompetencias->id);
            
            $mensajeEsperado = $puntajeEsperado === null ? 
                "No se cuenta con puntaje esperado para la campaña {$anioCompetencias}." : null;
            
            $mensajeResultados = !$tieneResultados ? 
                "No tiene resultados de competencias para la campaña {$anioCompetencias}." : null;
                
        } else {
            $tieneResultados = false;
            $promedioGeneral = null;
            $puntajeEsperado = null;
            $mensajeEsperado = null;
            $mensajeResultados = 'No hay evaluaciones de competencias disponibles para mostrar.';
        }

        return [
            'campania' => $campaniaCompetencias,
            'anio' => $anioCompetencias,
            'tieneResultados' => $tieneResultados,
            'promedioGeneral' => $promedioGeneral,
            'puntajeEsperado' => $puntajeEsperado,
            'mensajeEsperado' => $mensajeEsperado,
            'mensajeResultados' => $mensajeResultados,
        ];
    }

    /**
     * Procesa las evaluaciones de objetivos independientemente
     */
    private function procesarObjetivos($personalId, $campaniaActual, $campaniaAnterior)
    {
        $now = now();
        $campaniaObjetivos = null;
        $anioObjetivos = null;
        
        // 1. Verificar campaña actual primero
        if ($campaniaActual && $personalId) {
            $evaluacionActual = $campaniaActual->evaluaciones()
                ->where('tipo_de_evaluacion_id', 2) // Objetivos
                ->first();

            if ($evaluacionActual && $evaluacionActual->fecha_para_mostrar_resultados <= $now) {
                // Solo procesar objetivos para campaña 2024-2025
                if (trim((string)$campaniaActual->name) === '2024-2025') {
                    $campaniaObjetivos = $campaniaActual;
                    $anioObjetivos = $this->extraerAnio($campaniaActual->name);
                }
            }
        }

        // 2. Si no hay en actual, verificar anterior
        if (!$campaniaObjetivos && $campaniaAnterior && $personalId) {
            $evaluacionAnterior = $campaniaAnterior->evaluaciones()
                ->where('tipo_de_evaluacion_id', 2)
                ->first();

            if ($evaluacionAnterior && $evaluacionAnterior->fecha_para_mostrar_resultados <= $now) {
                if (trim((string)$campaniaAnterior->name) === '2024-2025') {
                    $campaniaObjetivos = $campaniaAnterior;
                    $anioObjetivos = $this->extraerAnio($campaniaAnterior->name);
                }
            }
        }

        // 3. Verificación especial para campaña 2024-2025 (ID = 1)
        if (!$campaniaObjetivos && $personalId) {
            $campania2024 = Campania::find(1);
            if ($campania2024 && trim((string)$campania2024->name) === '2024-2025') {
                $evaluacion2024 = $campania2024->evaluaciones()
                    ->where('tipo_de_evaluacion_id', 2)
                    ->first();

                if ($evaluacion2024 && $evaluacion2024->fecha_para_mostrar_resultados <= $now) {
                    $campaniaObjetivos = $campania2024;
                    $anioObjetivos = '2024';
                }
            }
        }

        // Calcular objetivos si hay campaña disponible
        if ($campaniaObjetivos && $personalId) {
            $ehe = EvaluadorHasEvaluado::query()
                ->where('evaluado_id', $personalId)
                ->where('evaluador_has_evaluados.evaluacion_id', 4) // evaluación por objetivos
                ->whereHas('evaluacion', function ($q) use ($campaniaObjetivos) {
                    $q->where('campania_id', $campaniaObjetivos->id);
                })
                ->first();

            if ($ehe) {
                try {
                    $objetivosTotal = Objetivo::calcularTotal($ehe->id);
                    $tieneResultados = $objetivosTotal !== null;
                    $mensaje = $tieneResultados ? null : 
                        "No se pudo calcular su porcentaje de objetivos para la campaña {$anioObjetivos}.";
                } catch (\Throwable $e) {
                    $objetivosTotal = null;
                    $tieneResultados = false;
                    $mensaje = 'Ocurrió un error al calcular su porcentaje de objetivos.';
                }
            } else {
                $objetivosTotal = null;
                $tieneResultados = false;
                $mensaje = "No se encontró su evaluación por objetivos en la campaña {$anioObjetivos}.";
            }
        } else {
            $objetivosTotal = null;
            $tieneResultados = false;
            if (!$personalId) {
                $mensaje = 'Su usuario no está asociado a un personal.';
            } else {
                $mensaje = 'No hay evaluaciones de objetivos disponibles para mostrar.';
            }
        }

        return [
            'campania' => $campaniaObjetivos,
            'anio' => $anioObjetivos,
            'total' => $objetivosTotal,
            'tieneResultados' => $tieneResultados,
            'mensaje' => $mensaje,
        ];
    }

    /**
     * Verifica si el usuario tiene resultados en una campaña específica
     */
    private function tieneResultadosEnCampania($personalId, $campaniaId)
    {
        return \App\Models\ResumenRespuestasEvaluacionDesempenoCompetencia::where('campania_id', $campaniaId)
            ->where('personal_id', $personalId)
            ->exists();
    }

    /**
     * Obtiene el puntaje esperado según la campaña
     */
    private function obtenerPuntajeEsperado($personalId, $campaniaId)
    {
        $campania = Campania::find($campaniaId);
        if (!$campania) return null;

        $nombre = trim((string) $campania->name);
        
        if ($nombre === '2024-2025') {
            try {
                $epa = \App\Models\EncargadosPlanesDeAccion::where('empleado_id', $personalId)->first();
                return $epa->valor_esperado ?? null;
            } catch (\Throwable $e) {
                return null;
            }
        } elseif ($nombre === '2025-2026') {
            return (float) env('EVAL_COMP_ESPERADO_2025_2026', 8.0);
        }

        return null;
    }

    /**
     * Extrae el año de la campaña
     */
    private function extraerAnio($nombreCampania)
    {
        if (!$nombreCampania) return 'N/A';
        
        $partes = explode('-', $nombreCampania);
        return $partes[0] ?? 'N/A';
    }

    public function pendientes2()
    {
        return view('inicio.pendientes2');
    }

    public function pendientes()
    {
        return view('inicio.pendientes');
    }

    // public function redirectToAzure()
    // {
    //     return Socialite::driver('azure')->redirect();
    // }

}
