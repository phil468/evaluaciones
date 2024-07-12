<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class RespuestasExport implements FromCollection, WithHeadings
{
    protected $datosParaExportar;

    public function __construct($datosParaExportar)
    {
        $this->datosParaExportar = $datosParaExportar;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return collect($this->datosParaExportar);
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'ID de evaluado',
            'Evaluado',
            'Competencia',
            'Pregunta',
            'Puntuación',
            'Cargo del evaluado',
            'Area del evaluado',
            'Gerencia / Subgerencia del evaluado',
        ];
    }
}
