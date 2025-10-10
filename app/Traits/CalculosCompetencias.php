<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;

trait CalculosCompetencias 
{
    protected function calcularPromedioCompetencias($personalId, $campaniaId)
    {
        // Usar la consulta más completa (con campania_has_competencias)
        $detalles = DB::table('resumen_respuestas_evaluacion_desempeno_competencias as r')
            ->join('campania_has_competencias as chc', 'chc.id', '=', 'r.competencia_id')
            ->join('secciones as competencias', 'competencias.id', '=', 'chc.competencia_id')
            ->select('competencias.name as competencia', DB::raw('AVG(COALESCE(r.puntaje_calibrado, r.puntaje)) as promedio'))
            ->where('r.personal_id', $personalId)
            ->where('r.campania_id', $campaniaId)
            ->groupBy('competencias.id', 'competencias.name')
            ->orderBy('promedio', 'desc')
            ->get();

        if ($detalles->isEmpty()) {
            return [
                'competencias' => [],
                'puntajes' => [],
                'promedio_general' => null
            ];
        }

        $competencias = $detalles->pluck('competencia')->toArray();
        $puntajes = $detalles->pluck('promedio')->map(fn($v) => round((float)$v, 2))->toArray();
        $promedioGeneral = round(collect($puntajes)->avg(), 1);

        return [
            'competencias' => $competencias,
            'puntajes' => $puntajes,
            'promedio_general' => $promedioGeneral
        ];
    }
}