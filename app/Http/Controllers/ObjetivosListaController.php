<?php

namespace App\Http\Controllers;

use App\Models\Objetivo;
use Illuminate\Http\Request;

class ObjetivosListaController extends Controller
{
    public function getData(Request $request)
    {
        $objetivos = Objetivo::query()
            ->where('objetivos.deleted_at', null)
            ->leftJoin('tipo_de_objetivos', 'tipo_de_objetivos.id', '=', 'objetivos.tipo_objetivo_id')
            ->leftJoin('personal as evaluados', 'evaluados.id', '=', 'objetivos.evaluado_id')
            ->leftJoin('personal as evaluadores', 'evaluadores.id', '=', 'objetivos.evaluador_id')
            ->leftJoin('evaluador_has_evaluados', 'evaluador_has_evaluados.id', '=', 'objetivos.evaluador_has_evaluado_id')
            ->leftJoin('estados_de_objetivos', 'estados_de_objetivos.id', '=', 'objetivos.estado_id')
            ->select(
                'objetivos.*',
                'evaluadores.name as evaluador_name',
                'evaluados.name as evaluado_name',
                'evaluador_has_evaluados.cargo_de_evaluado',
                'tipo_de_objetivos.unidad',
                'estados_de_objetivos.name as estado_name'
            )
            ->get()
            ->map(function($objetivo) {
                $evidencias = $objetivo->evidencias()->get();
                $estado_validacion = $this->getEstadoValidacion($objetivo, $evidencias);
                
                return [
                    'id' => $objetivo->id,
                    'estado' => $objetivo->estado_name,
                    'evaluador' => $objetivo->evaluador_name,
                    'evaluado' => $objetivo->evaluado_name,
                    'cargo_evaluado' => $objetivo->cargo_de_evaluado,
                    'meta' => $objetivo->meta,
                    'porcentaje_participacion' => number_format($objetivo->porcentaje_de_participacion, 2) . '%',
                    'tipo_objetivo' => $objetivo->unidad,
                    'resultado_anterior' => $this->formatearValor($objetivo->resultado_anterior_o_esperado, $objetivo->tipo_objetivo_id),
                    'minimo' => $this->formatearValor($objetivo->minimo, $objetivo->tipo_objetivo_id),
                    'maximo' => $this->formatearValor($objetivo->maximo, $objetivo->tipo_objetivo_id),
                    'valor' => $this->formatearValor($objetivo->valor, $objetivo->tipo_objetivo_id),
                    'evidencias' => $this->getEvidenciasTexto($objetivo),
                    'porcentaje_logro' => $objetivo->porcentaje_de_logro_STI ? number_format($objetivo->porcentaje_de_logro_STI, 2) . '%' : '',
                    'peso_ponderado' => $objetivo->peso_ponderado ? number_format($objetivo->peso_ponderado, 2) . '%' : '',
                    'estado_validacion' => $estado_validacion,
                    'created_at' => $objetivo->created_at ? $objetivo->created_at->format('d/m/Y h:i:s a') : '',
                    'updated_at' => $objetivo->updated_at ? $objetivo->updated_at->format('d/m/Y h:i:s a') : '',
                ];
            });

        return response()->json($objetivos);
    }

    private function formatearValor($valor, $tipo_objetivo_id)
    {
        if ($tipo_objetivo_id == 2) { // si es porcentaje
            return $valor ? number_format($valor, 2, '.', ',') . '%' : '';
        }
        return $valor ? number_format($valor, 2, '.', ',') : '';;
    }

    private function getEvidenciasTexto($objetivo)
    {
        $evidencias = $objetivo->evidencias()->get();
        if ($evidencias->isEmpty()) {
            return $objetivo->sin_evidencias ? "Marcado check SIN EVIDENCIAS" : "No ha cargado evidencias";
        }
        return '<button class="btn btn-sm btn-primary rounded-xl" onclick="descargarEvidencias(' . $objetivo->id . ')">
                    <i class="fas fa-download"></i> Descargar
                </button>';
    }

    private function getEstadoValidacion($objetivo, $evidencias)
    {
        $evidencias_count = $evidencias->count();
        
        if ($objetivo->grupal) {
            return ['estado' => 'success', 'mensaje' => 'OK - Objetivo grupal'];
        }

        if ($objetivo->valor >= $objetivo->minimo) {
            if ($evidencias_count == 0) {
                if ($objetivo->estado_id == 1) {
                    return ['estado' => 'warning', 'mensaje' => 'Requiere evidencias para valor ≥ ' . number_format($objetivo->minimo, 4)];
                }
                if ($objetivo->estado_id == 2) {
                    return ['estado' => 'danger', 'mensaje' => 'REALIZADO: Requiere evidencias para valor ≥ ' . number_format($objetivo->minimo, 4)];
                }
            }
            
            if ($objetivo->estado_id == 2) {
                return ['estado' => 'success', 'mensaje' => 'OK - Objetivo individual'];
            }
        } else {
            if ($evidencias_count == 0 && !$objetivo->sin_evidencias) {
                if ($objetivo->estado_id == 1) {
                    return ['estado' => 'warning', 'mensaje' => 'Debe marcar sin evidencias o agregar evidencias'];
                }
                if ($objetivo->estado_id == 2) {
                    return ['estado' => 'danger', 'mensaje' => 'REALIZADO: Debe marcar sin evidencias o agregar evidencias'];
                }
            }
        }

        return ['estado' => 'success', 'mensaje' => 'OK - Objetivo individual'];
    }
}