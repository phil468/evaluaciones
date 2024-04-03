<?php

namespace App\Imports;

use App\Models\Area;
use App\Models\Cargo;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class CargosImport implements ToCollection, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) 
        {
            try {
                $record = Cargo::updateOrCreate(
                    [
                        'idcargo_nisira'         => trim($row['idcargo']),
                    ],
                    [
                        'name'                  => trim($row['descripcion']),
                        'estado'                => trim($row['estado']) == "" ? 1 : trim($row['estado']),
                        'fechacreacion_nisira'  => trim($row['fechacreacion'])=='' ? NULL : 
                        date('Y-m-d',(Date::excelToTimestamp(trim($row['fechacreacion']),'America/Lima'))),
                        //date('Y-m-d',(strtotime(date('Y-m-d',(Date::excelToTimestamp(trim($row['fechacreacion']))))."+ 1 days")))
                    ]
                );
            } catch (\ErrorException $e) {
                dd($e);
            }
        }
    }
}
