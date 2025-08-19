<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class EvaluadosTemplateExport implements FromArray, WithHeadings
{
    public function headings(): array
    {
        return [
            'DNI',
            'AREA',
            'PUESTO',
            // 'TIPO DE PUESTO',
            'NIVEL JERARQUICO',
            'DNI SUPERIOR',
            'HABILITADO PARA EVALUACIÓN DE COMPETENCIAS',
            'HABILITADO PARA EVALUACIÓN POR OBJETIVOS',
            'CORREO',
        ];
    }

    public function array(): array
    {
        return [
            // Ejemplo completo
            ['12345678','OPERACIONES','ANALISTA DE OPERACIONES','NIVEL I','87654321','SI','NO','usuario@empresa.com'],
            // Ejemplo con vacíos: los vacíos mantienen el valor actual en BD
            ['87654321','','','','','1','1',''],
        ];
    }
}