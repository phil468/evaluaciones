<?php

namespace App\Exports;

use App\Models\Area;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class ReporteEstadoActivosExport implements FromView,WithColumnFormatting, WithTitle
{
    /**
    * @return \Illuminate\Support\Collection
    */

    // public function map($area): array
    // {
    //     return [
    //         Date::dateTimeToExcel($area->fechacreacion_nisira),
    //     ];
    // }

    
    public function __construct($array) {
        $this->activos_asignados = $array;
    }

    public function columnFormats(): array
    {
        return [
            'D' => NumberFormat::FORMAT_DATE_DDMMYYYY
        ];
    }

    public function view(): View
    {
        return view('livewire.reporte-por-estado-de-activos.exportar', [
            'activos_asignados' => $this->activos_asignados,
        ]);
    }

    public function title(): string
    {
        return 'reporte-por-estado-de-activos';
    }
}
