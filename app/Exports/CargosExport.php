<?php

namespace App\Exports;

use App\Models\Area;
use App\Models\Cargo;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class CargosExport implements FromView,WithColumnFormatting, WithTitle
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
            'C' => NumberFormat::FORMAT_DATE_DDMMYYYY
        ];
    }

    public function view(): View
    {
        return view('livewire.cargos.exportar', [
            'cargos' => Cargo::select(
                'name',
                'estado',
                'idcargo_nisira',
                // DB::raw('DATE_FORMAT(fechacreacion_nisira,"%d/%m/%Y") AS 
                'fechacreacion_nisira')
            ->get()
        ]);
    }

    public function title(): string
    {
        return 'cargos';
    }
}
