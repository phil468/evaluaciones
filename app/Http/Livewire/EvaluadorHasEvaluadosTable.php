<?php

namespace App\Http\Livewire;

use App\Models\EvaluadorHasEvaluado;
use App\Models\Respuesta;
use App\Models\TipoDeEvaluacione;
use Illuminate\Support\Facades\DB;
use Mediconesystems\LivewireDatatables\Action;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\BooleanColumn;
// use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\NumberColumn;

//en esta tabla vamos a mostrar los evaluadores 
class EvaluadorHasEvaluadoTable extends LivewireDatatable
{
    public $hideable = 'inline';
    public $exportable = true;
    public $afterTableSlot = 'components.selected';
    public $numeroSerieValidado=true, $fileUpload;
    public $updateMode = false;
    public $export_name = 'Evaluadores';

    public function builder()
    {       
        return EvaluadorHasEvaluado::query()
         ->select('evaluador_has_evaluados.*')
        ->leftJoin('personal','personal.id','=','evaluador_has_evaluados.evaluador_id')
        ->leftJoin('personal2','personal2.id','=','evaluador_has_evaluados.evaluado_id')
        ->leftJoin('evaluaciones','evaluaciones.id','=','evaluador_has_evaluados.evaluacion_id')
        ;
    }

    public $model = EvaluadorHasEvaluado::class;

    public function columns()
    {
        // ['evaluador_id','evaluado_id','evaluacion_id','realizado','tipo_de_evaluacion_id',
        //     'cargo_de_evaluador',
        //     'area_de_evaluador',
        //     'gerencia_sub_gerencia_de_evaluador',
        //     'cargo_de_evaluado',
        //     'area_de_evaluado',
        //     'gerencia_sub_gerencia_de_evaluado'
        // ]
        return [
            Column::name('personal.name')->label('Evaluador')->searchable()->filterable(),
            Column::name('personal2.name')->label('Evaluado')->searchable()->filterable(),
            Column::name('evaluaciones.title')->label('Evaluacion')->searchable()->filterable(),
            Column::name('evaluador_has_evaluados.realizado')->label('Realizado')->searchable()->filterable(),
            Column::name('evaluador_has_evaluados.evaluacion_id')->label('Evaluacion')->searchable()->filterable(),
            Column::name('evaluador_has_evaluados.realizado')->label('Realizado')->searchable()->filterable(),
            Column::name('evaluador_has_evaluados.created_at')->label('Fecha')->searchable()->filterable(),
            Column::name('cargo_de_evaluador')->label('Cargo de evaluador')->searchable()->filterable(),
            Column::name('cargo_de_evaluado')->label('Cargo de evaluado')->searchable()->filterable(),
            
            Column::callback(['evaluador_has_evaluados.id'], function ($id) {
                return view('table-actions-3', ['id' => $id]);
            })->label('Acciones')->unsortable()->excludeFromExport(),
        ];

    }
}