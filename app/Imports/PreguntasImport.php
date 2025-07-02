<?php

namespace App\Imports;

use App\Models\Pregunta;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class PreguntasImport implements ToModel, WithHeadingRow, WithValidation
{
    protected $evaluacion_id;
    protected $seccion_id;

    public function __construct($evaluacion_id, $seccion_id)
    {
        $this->evaluacion_id = $evaluacion_id;
        $this->seccion_id = $seccion_id;
    }

    public function model(array $row)
    {
        return new Pregunta([
            'evaluacion_id' => $this->evaluacion_id,
            'seccion_id'    => $this->seccion_id,
            'pregunta'      => $row['pregunta'],
            'tipo'          => $row['tipo'],
            'opciones'      => $row['opciones'],
            'numero_orden'  => $row['numero_orden'],
            'qid'          => uniqid(),
        ]);
    }

    public function rules(): array
    {
        return [
            'pregunta' => 'required',
            'tipo' => 'required|in:text,radio,checkbox,select',
            'opciones' => 'required_if:tipo,radio,checkbox,select',
            'numero_orden' => 'required|numeric'
        ];
    }
}