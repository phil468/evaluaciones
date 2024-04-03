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

class AreasExport implements FromView,WithColumnFormatting, WithTitle
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

    public function columnFormats(): array
    {
        return [
            'E' => NumberFormat::FORMAT_DATE_DDMMYYYY
        ];
    }

    public function view(): View
    {
        return view('livewire.areas.exportar', [
            'areas' => Area::select(
                'name',
                'estado',
                'idempresa_nisira',
                'idarea_nisira',
                // DB::raw('DATE_FORMAT(fechacreacion_nisira,"%d/%m/%Y") AS 
                'fechacreacion_nisira')
            ->get()
        ]);
    }

    public function title(): string
    {
        return 'áreas';
    }
}
