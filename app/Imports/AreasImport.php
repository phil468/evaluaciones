<?php

namespace App\Imports;

use App\Models\Area;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class AreasImport implements ToCollection, WithHeadingRow, WithChunkReading
{
    
    const TIPOS = ['GERENCIA CORPORATIVA','GERENCIA','SUBGERENCIA','AREA'];

    // Cache local para evitar consultas repetidas
    protected array $cache = [];

    public function collection(Collection $rows)
    {
        DB::transaction(function() use ($rows) {
            foreach ($rows as $row) {
                $corp  = $this->norm($row['gerencia_corporativa'] ?? null);
                $ger   = $this->norm($row['gerencia'] ?? null);
                $sub   = $this->norm($row['subgerencia'] ?? null);
                $area  = $this->norm($row['area'] ?? null);

                $corpId = $gerId = $subId = null;

                if ($corp) {
                    $corpId = $this->upsertArea(4, $corp, null);
                }
                if ($ger) {
                    $gerId = $this->upsertArea(3, $ger, $corpId);
                }
                if ($sub) {
                    $subId = $this->upsertArea(2, $sub, $gerId ?: $corpId);
                }
                if ($area) {
                    // Padre preferente: subgerencia > gerencia > corporativa
                    $parent = $subId ?: ($gerId ?: $corpId);
                    $this->upsertArea(1, $area, $parent);
                }
            }
        });
    }

    public function chunkSize(): int
    {
        return 1000;
    }

    private function upsertArea(int $tipo, string $name, ?int $parentId): int
    {
        $key = $tipo.'|'.$name;
        if (isset($this->cache[$key])) {
            // Actualiza padre si cambió
            $area = $this->cache[$key];
            if ($parentId && $area->area_superior_id !== $parentId) {
                $area->area_superior_id = $parentId;
                $area->save();
            }
            return $area->id;
        }

        $area = Area::updateOrCreate(
            ['tipo_id' => $tipo, 'name' => $name],
            [
                'area_superior_id' => $parentId,
                'estado' => true,
            ]
        );
        $this->cache[$key] = $area;

        return $area->id;
    }

    // Normaliza como en el mutator, pero lo hacemos antes para usar como clave
    private function norm(?string $v): ?string
    {
        if (!$v) return null;
        $v = trim($v);
        $v = preg_replace('/\s+/', ' ', $v);
        $v = (string) Str::of($v)->ascii();
        $v = mb_strtoupper($v);
        return $v ?: null;
    }
    // /**
    // * @param array $row
    // *
    // * @return \Illuminate\Database\Eloquent\Model|null
    // */
    // public function collection(Collection $rows)
    // {
    //     foreach ($rows as $row) 
    //     {
    //         try {
    //             $record = Area::updateOrCreate(
    //                 [
    //                     'idarea_nisira'         => trim($row['idarea']),
    //                 ],
    //                 [
    //                     'name'                  => trim($row['descripcion']),
    //                     'estado'                => trim($row['estado']) == "" ? 1 : trim($row['estado']),
    //                     'idempresa_nisira'      => trim($row['idempresa']),
    //                     'fechacreacion_nisira'  => trim($row['fecha_ingreso'])=='' ? NULL : 
    //                     //date('Y-m-d',(strtotime(date('Y-m-d',(Date::excelToTimestamp(trim($row['fecha_ingreso']))))."+ 1 days")))
    //                     date('Y-m-d',(Date::excelToTimestamp(trim($row['fecha_ingreso']),'America/Lima'))),
    //                 ]
    //             );
    //         } catch (\ErrorException $e) {
    //             dd($e);
    //         }
    //     }
    // }
}
