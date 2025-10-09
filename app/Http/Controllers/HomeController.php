<?php

namespace App\Http\Controllers;

use App\Models\Campania;
use App\Models\EvaluadorHasEvaluado;
use App\Models\Personal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laravel\Socialite\Facades\Socialite;
use App\Models\Objetivo;   // <-- importar

class HomeController extends Controller
{
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

        $campaniaActual = Campania::where('es_campania_actual', true)->first();
        // campaniaActual puede ser null
        if ($campaniaActual) {
            $campaniaAnterior = $campaniaActual->relacionadoAnterior ?? null;
        } else {
            $campaniaAnterior = null;
        }

        $personalId = Auth::user()->personal_id ?? null;

        $tieneResultados = false;
        $promedioGeneral = null;
        $puntajeEsperado = null;
        $mensajeEsperado = null;
        $mensajeResultados = null;

        if ($campaniaAnterior && $personalId) {
            // Promedios por competencia del usuario (COALESCE puntaje_calibrado, puntaje)
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
            } else {
                $mensajeResultados = 'No tiene resultados para la campaña anterior.';
            }

            // Puntaje esperado según campaña
            $nombreCampania = trim((string) $campaniaAnterior->name);
            if ($nombreCampania === '2024-2025') {
                // EncargadosPlanesDeAccion.empleado_id = personal del usuario
                try {
                    if (class_exists(\App\Models\EncargadosPlanesDeAccion::class)) {
                        $epa = \App\Models\EncargadosPlanesDeAccion::where('empleado_id', $personalId)->first();
                        $puntajeEsperado = $epa->valor_esperado ?? null;
                        if ($puntajeEsperado === null) {
                            $mensajeEsperado = '';
                        }
                    } else {
                        $mensajeEsperado = 'No existe el modelo EncargadosPlanesDeAccion para obtener el puntaje esperado.';
                    }
                } catch (\Throwable $e) {
                    $mensajeEsperado = 'No fue posible obtener el puntaje esperado (error de configuración).';
                }
            } elseif ($nombreCampania === '2025-2026') {
                $puntajeEsperado = (float) env('EVAL_COMP_ESPERADO_2025_2026', 8.0);
                if (!$puntajeEsperado) {
                    $mensajeEsperado = 'No se encontró el valor esperado para 2025-2026 en el archivo .env.';
                }
            }
        }

        
        // --- Objetivos (campaña 2024-2025) ---
        $objetivosTotal = null;
        $objetivosMensaje = null;

        if (!$campaniaAnterior) {
            $objetivosMensaje = 'No existe campaña anterior.';
        } elseif (!$personalId) {
            $objetivosMensaje = 'Su usuario no está asociado a un personal.';
        } elseif (trim((string)$campaniaAnterior->name) === '2024-2025') {
            // Buscar EvaluadorHasEvaluado de evaluación por objetivos, la campania_id se encuentra en la tabla evaluaciones

            // $ehe = EvaluadorHasEvaluado::where('evaluado_id', $personalId)
            //     ->where('campania_id', $campaniaAnterior->id)
            //     ->where('evaluacion_id', 4) // evaluación por objetivos
            //     ->first();
            $ehe = EvaluadorHasEvaluado::query()
                ->where('evaluado_id', $personalId)
                ->where('evaluador_has_evaluados.evaluacion_id', 4) // evaluación por objetivos
                ->whereHas('evaluacion', function ($q) use ($campaniaAnterior) {
                    $q->where('campania_id', $campaniaAnterior->id);
                })
                ->first();

            if ($ehe) {
                try {
                    $objetivosTotal = Objetivo::calcularTotal($ehe->id); // puede ser > 100 o null
                    if ($objetivosTotal === null) {
                        $objetivosMensaje = 'No se pudo calcular su porcentaje de objetivos para la campaña anterior.';
                    }
                } catch (\Throwable $e) {
                    $objetivosMensaje = 'Ocurrió un error al calcular su porcentaje de objetivos.';
                }
            } else {
                $objetivosMensaje = 'No se encontró su evaluación por objetivos en la campaña anterior.';
            }
        } else {
            $objetivosMensaje = 'La campaña anterior no requiere cálculo de objetivos.';
        }

        return view('inicio.index', [
            'evaluacionesPendientes' => $evaluacionesPendientes,
            'campaniaAnterior'   => $campaniaAnterior,
            'tieneResultados'    => $tieneResultados,
            'promedioGeneral'    => $promedioGeneral,
            'puntajeEsperado'    => $puntajeEsperado,
            'mensajeEsperado'    => $mensajeEsperado,
            'mensajeResultados'  => $mensajeResultados,
            'evaluacionesPendientes' => $evaluacionesPendientes ?? false, // conservar lo que ya tenías
            'objetivosTotal'     => $objetivosTotal,
            'objetivosMensaje'   => $objetivosMensaje,
        ]);
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
