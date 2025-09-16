<?php

namespace App\Http\Livewire;

use App\Exports\RespuestasExport;
use App\Models\EvaluadorHasEvaluado;
use App\Models\Personal;
use App\Models\Pregunta;
use App\Models\Respuesta;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;
use Maatwebsite\Excel\Facades\Excel;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\BooleanColumn;
// use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\Exports\DatatableExport;
use Mediconesystems\LivewireDatatables\NumberColumn;

use function PHPUnit\Framework\callback;

class RespuestasTable extends LivewireDatatable
{
    public $hideable = 'inline';
    public $exportable = true;
    public $afterTableSlot = 'components.selected';
    public $numeroSerieValidado=true, $fileUpload;
    public $updateMode = false;
    public $export_name = 'Respuestas';

    public function builder()
    {
        return Respuesta::query()
        ->where('respuestas.deleted_at',null);
    }

    public $model = Respuesta::class;

    public function columns()
    {
        return [
            NumberColumn::name('id')->label('ID')->filterable()->searchable()->defaultSort('asc'),

            NumberColumn::callback(['id'], function ($id) {
                return Respuesta::find($id)->evaluado_id;
            },[],'evaluado_id')->label('ID de evaluado')->searchable()->filterable()->defaultSort('asc'),

            Column::callback(['id'], function ($id) {
                return Respuesta::find($id)->evaluado->name;
            },[],'evaluado')->label('Evaluado')->searchable()->filterable()->defaultSort('asc'),

            Column::callback(['id'], function($id) {
                return Respuesta::find($id)->pregunta->seccion->name;
            },[],'competencia')->label('Competencia')->searchable()->filterable()->defaultSort('asc'),
           
            Column::callback(['id'], function ($id){
                return Respuesta::find($id)->pregunta->pregunta;
            },[],'pregunta')->label('Pregunta')->searchable()->filterable()->defaultSort('asc'),

            Column::callback(['id'], function ($id) {
                return Respuesta::find($id)->valor_numerico;
            },[],'puntuacion')->label('Puntuación')->searchable()->filterable()->defaultSort('asc'),

            Column::name('peso')->label('Peso')->searchable()->filterable()->defaultSort('asc'),

            Column::callback(['id'], function ($id) {
                $respuesta = Respuesta::find($id);
                return $respuesta->cargo_de_evaluado ?? '';
            },[],'11')->label('Cargo del evaluado')->searchable()->filterable()->defaultSort('asc'),

            Column::callback(['id'], function ($id) {
                $respuesta = Respuesta::find($id);
                return $respuesta->area_de_evaluado ?? '';
            },[],'22')->label('Area del evaluado')->searchable()->filterable()->defaultSort('asc'),
            
            Column::callback(['id'], function ($id) {
                $respuesta = Respuesta::find($id);
                return $respuesta->gerencia_de_evaluado ?? '';
            },[],'33')->label('Gerencia / Subgerencia del evaluado')->searchable()->filterable()->defaultSort('asc'),

        ];
    }

    public function export()
    {
        $datosParaExportar = [];

        Respuesta::with(['evaluado', 'pregunta.seccion'])
        ->chunk(2000, function ($respuestas) use (&$datosParaExportar) {
            foreach ($respuestas as $respuesta) {
                $datosParaExportar[] = [
                    'ID' => $respuesta->id,
                    'ID de evaluado' => $respuesta->evaluado_id,
                    'Evaluado' => $respuesta->evaluado->name ?? '',
                    'Competencia' => $respuesta->pregunta->seccion->name ?? '',
                    'Pregunta' => $respuesta->pregunta->pregunta ?? '',
                    'Puntuación' => $respuesta->valor_numerico,
                    'Cargo del evaluado' => $respuesta->cargo_de_evaluado ?? '',
                    'Area del evaluado' => $respuesta->area_de_evaluado ?? '',
                    'Gerencia / Subgerencia del evaluado' => $respuesta->gerencia_de_evaluado ?? ''
                ];
            }
        });

        return Excel::download(new RespuestasExport($datosParaExportar), 'respuestas.xlsx');

    }   

}