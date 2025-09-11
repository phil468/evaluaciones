<?php

namespace App\Http\Controllers;

use App\Models\EvaluadorHasEvaluado;
use Illuminate\Http\Request;

class EvaluacionController extends Controller
{
    public function show($tipo_de_evaluacion_id, $evaluacion_id)
    {
        $evaluadorHasEvaluado = EvaluadorHasEvaluado::where('evaluador_has_evaluados.id', $evaluacion_id)
            ->where('evaluador_id', auth()->user()->personal_id)
            ->when($tipo_de_evaluacion_id == 1, function ($query) {
                return $query->where('evaluador_has_evaluados.realizado', null)->where('evaluador_has_evaluados.cesado', 0);
            })
            ->leftJoin('evaluaciones', 'evaluador_has_evaluados.evaluacion_id', '=', 'evaluaciones.id')
            ->where('evaluaciones.tipo_de_evaluacion_id', $tipo_de_evaluacion_id)
            ->first();        
        
        if ($evaluadorHasEvaluado) {
            if ($tipo_de_evaluacion_id == 1) {
                return view('livewire.evaluacion.index', ['evaluacion_id' => $evaluacion_id]);
            } elseif ($tipo_de_evaluacion_id == 2) {
                return view('livewire.objetivos.index', ['evaluacion_id' => $evaluacion_id]);
            }
        } else {

            $evaluadorHasEvaluado = EvaluadorHasEvaluado::where('evaluador_has_evaluados.id',$evaluacion_id)
            ->leftJoin('evaluaciones','evaluador_has_evaluados.evaluacion_id','=','evaluaciones.id')
            ->where('evaluaciones.tipo_de_evaluacion_id',$tipo_de_evaluacion_id)
            ->first();            
            if ($evaluadorHasEvaluado) {
                if ($evaluadorHasEvaluado->realizado == 1 && $evaluadorHasEvaluado->tipo_de_evaluacion_id == 1) {
                    return redirect()->route('pendientes')->with('error', 'Ya evaluó a este empleado');
                } else if ($evaluadorHasEvaluado->evaluador_id != auth()->user()->personal_id) {
                    return redirect()->route('pendientes')->with('error', 'No tiene permisos para evaluar este personal');
                } else if ($evaluadorHasEvaluado->cesado == 1 && $evaluadorHasEvaluado->tipo_de_evaluacion_id == 1) {
                    return redirect()->route('pendientes')->with('error', 'Esta evaluación está cesada');
                }
            } else {
                return redirect()->route('pendientes')->with('error', 'No se encuentra registrada esta evaluación');
            }
        }
    }
}
