<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromArray;

class PersonalTemplateExport implements FromArray, WithHeadings
{
    // public function array(): array
    // {
    //     return []; // Solo cabeceras
    // }
    public function headings(): array
    {
        return ['DNI','AREA','PUESTO','TIPO DE PUESTO','DNI SUPERIOR','CORREO'];
    }

    public function array(): array
    {
        return [
            ['12345678','OPERACIONES','ANALISTA DE OPERACIONES','ANALISTA','87654321','usuario@empresa.com'],
        ];
    }
}