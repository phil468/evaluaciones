<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Respuesta;
use App\Models\ResumenRespuestasEvaluacionDesempenoCompetencia;
use App\Models\Campania;
use App\Models\CampaniaHasEvaluado;

// use App\Models\CampaniaHasEvaluado;

class ActualizarResumenRespuestas extends Command
{
    // protected $signature = 'resumen:actualizar';
    protected $signature = 'resumen:actualizar {campania? : Nombre (ej. "2025-2026") o ID de la campaña}';
    protected $description = 'Actualiza la tabla resumen_respuestas_evaluacion_desempeno_competencias';

    public function handle()
    {
        $campaniaArg = $this->argument('campania');
        $campaniaId = null;

        if ($campaniaArg !== null) {
            if (is_numeric($campaniaArg)) {
                $campaniaId = (int) $campaniaArg;
            } else {
                // Ajusta el campo si tu modelo usa otro (ej.: nombre, titulo, etc.)
                $campania = Campania::where('name', $campaniaArg)->first();
                if (!$campania) {
                    $this->error('No se encontró la campaña con nombre: ' . $campaniaArg);
                    return self::FAILURE;
                }
                $campaniaId = $campania->id;
            }
        }

        // Limpiar solo lo necesario
        if ($campaniaId) {
            ResumenRespuestasEvaluacionDesempenoCompetencia::where('campania_id', $campaniaId)->delete();
        } else {
            //salir del comando si no se especifica campaña
            $this->error('Debes especificar una campaña (nombre o ID) para actualizar el resumen.');
            return self::FAILURE;
        }

        // Cargar respuestas (filtradas por campaña si se indicó)
        $respuestasQuery = Respuesta::with(['pregunta:id,seccion_id,campania_has_competencia_id']);
        if ($campaniaId) {
            $respuestasQuery->where('campania_id', $campaniaId);
        }
        $respuestas = $respuestasQuery->get();

        // Agrupar por campania, evaluado, competencia, pregunta
        $grupos = $respuestas->groupBy(function ($item) {
            $competenciaId = $item->pregunta->seccion_id ?: $item->pregunta->campania_has_competencia_id;
            return implode('-', [
                $item->campania_id,
                $item->evaluado_id,
                $competenciaId,
                $item->pregunta_id,
            ]);
        });

        $totalReg = 0;

        foreach ($grupos as $grupo) {
            $primera = $grupo->first();

            $competencia_id = $primera->pregunta->campania_has_competencia_id ?: $primera->pregunta->seccion_id ;
            if (!$competencia_id) {
                continue; // sin competencia no se resume
            }

            $campania_id = $primera->campania_id;
            $personal_id = $primera->evaluado_id;

            // Sumatoria de pesos (incluye autoeval con peso=0)
            $total_peso = $grupo->sum(function ($r) {
                return (float) ($r->peso ?? 0);
            });

            // Promedio ponderado (solo pesos > 0)
            $sumaPonderada = $grupo->sum(function ($r) {
                $peso = (float) ($r->peso ?? 0);
                $valor = (float) ($r->valor_numerico ?? 0);
                return $peso > 0 ? ($valor * $peso) : 0;
            });
            $sumaPesosPositivos = $grupo->sum(function ($r) {
                $peso = (float) ($r->peso ?? 0);
                return $peso > 0 ? $peso : 0;
            });
            $puntaje = $sumaPesosPositivos > 0 ? $sumaPonderada / $sumaPesosPositivos : null;

            // Autoevaluación: promedio simple de valores con peso = 0
            $auto = $grupo->filter(function ($r) {
                return (float) ($r->peso ?? 0) == 0.0;
            });
            $puntaje_autoevaluacion = $auto->count() > 0
                ? $auto->avg(function ($r) { return (float) ($r->valor_numerico ?? 0); })
                : null;

            ResumenRespuestasEvaluacionDesempenoCompetencia::updateOrCreate(
                [
                    'personal_id' => $personal_id,
                    'competencia_id' => $competencia_id,
                    'pregunta_id' => $primera->pregunta_id,
                    // 'area_id' => $area_id,
                    'campania_id' => $campania_id,
                ],
                [
                    'puntaje' => $puntaje,
                    'total_peso' => $total_peso,
                    'puntaje_autoevaluacion' => $puntaje_autoevaluacion,
                ]
            );

            $totalReg++;
        }

        // Actualizar el promedio por evaluado en campania_has_evaluados
        $evaluados = ResumenRespuestasEvaluacionDesempenoCompetencia::where('campania_id', $campaniaId)
            ->distinct()
            ->pluck('personal_id');

        $actualizados = 0;
        foreach ($evaluados as $personalId) {
            // 1) Promedio por competencia (promedio de preguntas de esa competencia)
            $porCompetencia = ResumenRespuestasEvaluacionDesempenoCompetencia::where('campania_id', $campaniaId)
                ->where('personal_id', $personalId)
                ->selectRaw('competencia_id, AVG(puntaje) AS avg_puntaje')
                ->groupBy('competencia_id')
                ->get();

            if ($porCompetencia->isEmpty()) continue;

            // 2) Promedio final del evaluado (promedio de los promedios por competencia)
            $puntajeFinal = $porCompetencia->avg('avg_puntaje');

            CampaniaHasEvaluado::where('campania_id', $campaniaId)
                ->where('personal_id', $personalId)
                ->update([
                    'puntaje_de_evaluacion_de_competencias' => $puntajeFinal,
                ]);

            $actualizados++;
        }

        $msg = 'Resumen actualizado correctamente.';
        if ($campaniaId) $msg .= ' Campaña ID: ' . $campaniaId;
        $msg .= ' Registros: ' . $totalReg;
        $msg .= ' Evaluados actualizados: ' . $actualizados;
        $this->info($msg);

        return self::SUCCESS;
    }
}