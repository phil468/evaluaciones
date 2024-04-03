<?php

namespace App\Imports;

use App\Models\Accesorio;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class AccesoriosImport implements  ToCollection, WithHeadingRow
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
                $record = Accesorio::updateOrCreate(
                    [
                        'name' => trim($row['descripcion']),
                    ],
                    [
                        'estado' => trim($row['estado']) == "" ? 1 : trim($row['estado']),
                        'stock'  => trim($row['stock'])
                        
                    ]
                );

        }
    }
}
