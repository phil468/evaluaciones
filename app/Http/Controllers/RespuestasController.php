<?php

namespace App\Http\Controllers;

use App\Models\Respuesta;
use Illuminate\Http\Request;

class RespuestasController extends Controller
{
    public function getData()
    {
        $respuestas = Respuesta::query()
            ->where('respuestas.deleted_at', null)
            ->with(['evaluado', 'pregunta.seccion'])
            ->limit(10000)
            ->get()
            ->map(function($respuesta) {
                return [
                    'id' => $respuesta->id,
                    'evaluado_id' => $respuesta->evaluado_id,
                    'evaluado' => $respuesta->evaluado->name ?? '',
                    'competencia' => $respuesta->pregunta->seccion->name ?? '',
                    'pregunta' => $respuesta->pregunta->pregunta ?? '',
                    'puntuacion' => $respuesta->valor_numerico,
                    'cargo_evaluado' => $respuesta->cargo_de_evaluado ?? '',
                    'area_evaluado' => $respuesta->area_de_evaluado ?? '',
                    'gerencia_evaluado' => $respuesta->gerencia_de_evaluado ?? ''
                ];
            });

        return response()->json($respuestas);
    }
}