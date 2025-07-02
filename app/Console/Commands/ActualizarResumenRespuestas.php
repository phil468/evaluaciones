<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Respuesta;
use App\Models\ResumenRespuestasEvaluacionDesempenoCompetencia;
use App\Models\Pregunta;

class ActualizarResumenRespuestas extends Command
{
    protected $signature = 'resumen:actualizar';
    protected $description = 'Actualiza la tabla resumen_respuestas_evaluacion_desempeno_competencias';

    public function handle()
    {
        // Limpia la tabla resumen
        ResumenRespuestasEvaluacionDesempenoCompetencia::truncate();

        // Agrupa por campania, competencia y pregunta
        $respuestas = Respuesta::with('pregunta')
            ->get()
            ->groupBy(function($item) {
                return $item->campania_id . '-' . $item->evaluado_id . '-' . $item->pregunta->seccion_id . '-' . $item->pregunta_id;
            });

        foreach ($respuestas as $key => $grupo) {
            $primera = $grupo->first();
            $competencia_id = $primera->pregunta->seccion_id ?? null;
            $area_id = $primera->area_de_evaluado ?? null;

            $total_peso = $grupo->sum('peso');
            $puntaje = $total_peso > 0 ? $grupo->sum(function($r) { return $r->valor_numerico * $r->peso; }) / $total_peso : null;

            ResumenRespuestasEvaluacionDesempenoCompetencia::updateOrCreate(
                [
                    'personal_id' => $primera->evaluado_id,
                    'competencia_id' => $competencia_id,
                    'pregunta_id' => $primera->pregunta_id,
                    'area_id' => $area_id,
                    'campania_id' => $primera->campania_id,
                ],
                [
                    'puntaje' => $puntaje,
                    // 'puntaje_calibrado' => null, // Por ahora igual, luego puedes calibrar
                ]
            );
            // create([
            //     'personal_id' => $primera->evaluado_id,
            //     'competencia_id' => $competencia_id,
            //     'pregunta_id' => $primera->pregunta_id,
            //     'puntaje' => $puntaje,
            //     // 'puntaje_calibrado' => null, // Por ahora igual, luego puedes calibrar
            //     'area_id' => $area_id,
            //     'campania_id' => $primera->campania_id,
            // ]);
        }

        $this->info('Resumen actualizado correctamente.');
    }
}