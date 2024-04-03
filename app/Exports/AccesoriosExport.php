<?php

namespace App\Exports;

use App\Models\Accesorio;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class AccesoriosExport implements FromView, WithTitle
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

    public function view(): View
    {
        return view('livewire.accesorios.exportar', [
            'accesorios' => Accesorio::all()
        ]);
    }

    public function title(): string
    {
        return 'accesorios';
    }
}
