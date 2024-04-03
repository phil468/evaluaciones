<?php

namespace App\Exports;

use App\Models\Sede;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class SedesExport implements FromView,WithColumnFormatting,WithTitle
{
    /**
    * @return \Illuminate\Support\Collection
    */
    
    public function columnFormats(): array
    {
        return [
            'D' => NumberFormat::FORMAT_DATE_DDMMYYYY
        ];
    }

    public function view(): View
    {
        return view('livewire.sedes.exportar', [
            'sedes' => Sede::all()
        ]);
    }

    public function title(): string
    {
        return 'sedes';
    }
}
