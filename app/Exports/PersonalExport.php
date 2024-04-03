<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use App\Models\Personal;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class PersonalExport implements FromView,WithColumnFormatting,WithTitle
{
    /**
    * @return \Illuminate\Support\Collection
    */
    
    public function columnFormats(): array
    {
        return [
            'W' => NumberFormat::FORMAT_DATE_DDMMYYYY
        ];
    }

    public function view(): View
    {
        return view('livewire.personals.exportar', [
            'personal' => Personal::all()
        ]);
    }

    public function title(): string
    {
        return 'personal';
    }
}
