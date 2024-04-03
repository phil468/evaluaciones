<?php

namespace App\Imports;

use App\Models\Area;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class AreasImport implements ToCollection, WithHeadingRow
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
                $record = Area::updateOrCreate(
                    [
                        'idarea_nisira'         => trim($row['idarea']),
                    ],
                    [
                        'name'                  => trim($row['descripcion']),
                        'estado'                => trim($row['estado']) == "" ? 1 : trim($row['estado']),
                        'idempresa_nisira'      => trim($row['idempresa']),
                        'fechacreacion_nisira'  => trim($row['fecha_ingreso'])=='' ? NULL : 
                        //date('Y-m-d',(strtotime(date('Y-m-d',(Date::excelToTimestamp(trim($row['fecha_ingreso']))))."+ 1 days")))
                        date('Y-m-d',(Date::excelToTimestamp(trim($row['fecha_ingreso']),'America/Lima'))),
                    ]
                );
            } catch (\ErrorException $e) {
                dd($e);
            }
        }
    }
}
