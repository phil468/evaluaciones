<?php

namespace App\Http\Controllers;

use App\Models\Campania;
use App\Models\CampaniaHasCompetencia;
use App\Models\Dominio;
use App\Models\Pregunta;
use Illuminate\Support\Str;

class ImportarPreguntasService
{
    protected $campania;
    protected $validationMessages = [];

    public function __construct(Campania $campania)
    {
        $this->campania = $campania;
    }

    public function validateRow($row, $index)
    {
        $status = [
            'valid' => true,
            'messages' => [],
            'action' => 'create',
            'icon' => '✅'
        ];

        //Orden debe ser un número entero positivo
        if (!is_numeric($row['numero_orden']) || (int)$row['numero_orden'] <= 0) {
            $status['valid'] = false;
            $status['messages'][] = "Fila {$index}: El número de orden debe ser un número entero positivo";
            $status['icon'] = '❌';
            return $status;
        }

        // Validar campos requeridos
        if (!$this->validateRequiredFields($row)) {
            $status['valid'] = false;
            $status['messages'][] = "Fila {$index}: Faltan campos requeridos";
            $status['icon'] = '❌';
            return $status;
        }

        // Validar que la campaña coincida con la actual
        if ($row['campaña'] !== $this->campania->name) {
            $status['valid'] = false;
            $status['messages'][] = "Fila {$index}: La campaña debe ser {$this->campania->name}";
            $status['icon'] = '❌';
            return $status;
        }

        $campania = Campania::where('name', $row['campaña'])->first();
        if (!$campania) {
            $status['valid'] = false;
            $status['messages'][] = "Fila {$index}: No se encontró la campaña {$row['campaña']}";
            $status['icon'] = '❌';
            return $status;
        }

        // Validar que existe la competencia y obtener campania_has_competencia_id
        $chc = CampaniaHasCompetencia::whereHas('competencia', function($q) use ($row) {
            $q->where('name', $row['competencia']);
        })->where('campania_id', $this->campania->id)->first();

        if (!$chc) {
            $status['valid'] = false;
            $status['messages'][] = "Fila {$index}: No se encontró la competencia {$row['competencia']} para esta campaña";
            $status['icon'] = '❌';
            return $status;
        }

        // Validar que existe el dominio
        $dominio = Dominio::where('name', $row['dominio'])
        ->where('campania_id', $this->campania->id)
        ->first();
        if (!$dominio) {
            $status['valid'] = false;
            $status['messages'][] = "Fila {$index}: No se encontró el dominio {$row['dominio']}";
            $status['icon'] = '❌';
            return $status;
        }       

        // Verificar si es una actualización
        $existingPregunta = Pregunta::withTrashed()
            ->where('dominio_id', $dominio->id)
            // ->where('campania_has_competencia_id', $chc->id)
            ->where('numero_orden', $row['numero_orden'])
            ->first();

        if ($existingPregunta) {
            if ($row['quitar'] === 'x') {
                $status['action'] = 'delete';
                $status['icon'] = '🗑️';
                return $status;
            } else {
                $status['action'] = 'update';
                $status['icon'] = '⚠️';
                // Validar la pregunta
                if (!$this->validatePreguntaFormat($row['pregunta'])) {
                    $status['valid'] = false;
                    $status['messages'][] = "Fila {$index}: La pregunta debe tener al menos 2 palabras y 3 caracteres";
                    $status['icon'] = '❌';
                    return $status;
                }
                $status['messages'][] = "Fila {$index}: Pregunta existente, se actualizará";
                return $status;
            }
        } else if ($row['quitar'] === 'x') {
            $status['valid'] = false;
            $status['messages'][] = "Fila {$index}: No se puede eliminar una pregunta que no existe";
            $status['icon'] = '❌';
            return $status;
        }

        // Validar formato de pregunta
        if (!$this->validatePreguntaFormat($row['pregunta'])) {
            $status['valid'] = false;
            $status['messages'][] = "Fila {$index}: La pregunta debe tener al menos 2 palabras y 3 caracteres";
            $status['icon'] = '❌';
            return $status;
        }

        // // Validar número de orden consecutivo
        // $lastOrder = Pregunta::where('dominio_id', $dominio->id)
        //     ->where('campania_has_competencia_id', $chc->id)
        //     ->max('numero_orden');
        
        // if ($row['numero_orden'] != ($lastOrder + 1)) {
        //     $status['valid'] = false;
        //     $status['messages'][] = "Fila {$index}: El número de orden debe ser consecutivo. Siguiente número esperado: " . ($lastOrder + 1);
        //     $status['icon'] = '❌';
        //     return $status;
        // }

        return $status;
    }

    private function validateRequiredFields($row)
    {
        return !empty($row['pregunta']) && 
               !empty($row['competencia']) && 
               !empty($row['dominio']) && 
               !empty($row['campaña']) && 
               !empty($row['numero_orden']);
    }

    private function validatePreguntaFormat($pregunta)
    {
        $words = str_word_count($pregunta);
        return strlen($pregunta) >= 10 && $words >= 3;
    }

    public function procesarPregunta($row)
    {
        $chc = CampaniaHasCompetencia::whereHas('competencia', function($q) use ($row) {
            $q->where('name', $row['competencia']);
        })->where('campania_id', $this->campania->id)->first();

        $dominio = Dominio::where('name', $row['dominio'])
        ->where('campania_id', $this->campania->id)
        ->first();

        $pregunta = Pregunta::withTrashed()
            ->where('dominio_id', $dominio->id)
            // ->where('campania_has_competencia_id', $chc->id)
            ->where('numero_orden', $row['numero_orden'])
            ->first();

        if ($row['quitar'] === 'x') {
            if ($pregunta) {
                $pregunta->forceDelete();
            }
            return;
        }

        $data = [
            'pregunta' => $row['pregunta'],
            'campania_has_competencia_id' => $chc->id,
            'seccion_id' => $chc->competencia_id,
            'dominio_id' => $dominio->id,
            'numero_orden' => $row['numero_orden'],
            'estado' => 1
        ];

        if ($pregunta) {
            $pregunta->fill($data);
            $pregunta->save();
        } else {
            Pregunta::create($data);
        }
    }
}
