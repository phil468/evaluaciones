<?php

namespace App\Imports;

use App\Models\Area;
use App\Models\Cargo;
use App\Models\Empresa;
use App\Models\Gerencia;
use App\Models\Personal;
use App\Models\Sede;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class PersonalImport implements ToCollection, WithHeadingRow
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
            //Recuperando o Insertando Empresa
            if(!empty(trim($row['idempresa']))){
                $empresa = Empresa::firstOrCreate(
                    ['idempresa_nisira' => trim($row['idempresa'])],
                    ['name' => trim($row['empresa']) , 'estado' => 1]
                );
            }

            //Recuperando o Insertando Gerencia
            if(!empty(trim($row['idgerencia']))){
            $gerencia = Gerencia::firstOrCreate(
                ['idarea_nisira' => trim($row['idgerencia'])],
                ['name' => trim($row['gerencia']) , 'estado' => 1]
            );
            }

            //Recuperando o Insertando Area
            if(!empty(trim($row['idarea']))){
            $area = Area::firstOrCreate(
                ['idarea_nisira' => trim($row['idarea'])],
                ['name' => trim($row['area']) , 'estado' => 1]
            );
            }

            //Recuperando o Insertando Cargo
            if(!empty(trim($row['idcargo']))){
            $cargo = Cargo::firstOrCreate(
                ['idcargo_nisira' => trim($row['idcargo'])],
                ['name' => trim($row['cargo']) , 'estado' => 1]
            );
            } else {
                $cargo =  Cargo::where('name', trim($row['cargo']))->firstOr(function () {
                    return NULL;
                });
            }

            //Recuperando o Insertando Sede
            if(!empty(trim($row['idsucursal']))){
            $sede = Sede::firstOrCreate(
                ['idsede_nisira' => trim($row['idsucursal'])],
                ['name' => trim($row['sucursal']) , 'estado' => 1]
            );
            } else {
                $sede =  Sede::where('name', trim($row['sucursal']))->firstOr(function () {
                    return NULL;
                });
            }

            //Actualizando o Insertando Personal
            $record = Personal::updateOrCreate(
                [
                    'dni' => trim($row['nro_identidad']),
                ],
                [
                    'name' =>trim($row['apenom']),
                    'nombres' => trim($row['nombres']),
                    'apellido_paterno' => trim($row['apaterno']),
                    'apellido_materno' => trim($row['amaterno']),
                    'empresa_id' => $empresa->id??NULL,
                    'gerencia_id' => $gerencia->id??NULL,
                    'area_id' => $area->id??NULL,
                    'sede_id' => $sede->id??NULL,
                    'cargo_id' => $cargo->id??NULL,
                    'correo_empresa' => trim($row['correo_empresa']),
                    'celular_empresa' => trim($row['celular_empresa']),
                    'correo_personal' => trim($row['email']),
                    'telefono_personal' => trim($row['telefono']),
                    'celular_personal' => trim($row['celular']),
                    'estado' => trim($row['estado']) == "ACTIVO" ? 1 : NULL,
                    'genero' => trim($row['sexo']) == 'Masculino' ? 'H': (trim($row['sexo']) == 'Femenino' ? 'M': ''),
                    'fecha_ingreso'  => trim($row['fecha_ingreso'])=='' ? NULL : 
                        date('Y-m-d',(Date::excelToTimestamp(trim($row['fecha_ingreso']),'America/Lima')))                        
                ]
            );
        }
    }
}
