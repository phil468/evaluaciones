<?php

namespace App\Http\Livewire;

use App\Models\EvaluadorHasEvaluado;
use App\Models\Respuesta;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\BooleanColumn;
// use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\Column;

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
        ->where('respuestas.deleted_at',null)->where('preguntas.deleted_at',null)
        ->leftJoin('preguntas','preguntas.id','=','respuestas.pregunta_id')
        ->leftJoin('personal','personal.id','=','respuestas.evaluado_id')
        ->leftJoin('secciones','secciones.id','=','preguntas.seccion_id')
        ->leftJoin('evaluaciones','evaluaciones.id','=','preguntas.evaluacion_id')
        // ->leftJoin('evaluador_has_evaluados','evaluador_has_evaluados.evaluado_id','=','respuestas.evaluado_id')
        //cargo
        ->leftJoin('cargos','cargos.id','=','personal.cargo_id')
        ;
    }

    public $model = Respuesta::class;

    public function columns()
    {
        return [
        Column::name('personal.name')->label('Nombres y apellidos del evaluado')->searchable()->filterable()->defaultSort('asc'),
        Column::name('cargos.name')->label('Cargo del evaluado')->searchable()->filterable()->defaultSort('asc'),
        Column::name('secciones.name')->label('Competencia')->searchable()->filterable()->defaultSort('asc'),
        Column::name('preguntas.pregunta')->label('Pregunta')->searchable()->filterable()->defaultSort('asc'),
        Column::name('valor_numerico')->label('Puntuación')->searchable()->filterable()->defaultSort('asc'),
        Column::callback(['respuestas.evaluado_id','pregunta.evaluacion_id'], function ($id,$evaluacion_id) {
            $cargo_de_evaluado = EvaluadorHasEvaluado::select('evaluador_has_evaluados.cargo_de_evaluado')
            ->where('evaluador_has_evaluados.evaluado_id',$id)
            ->where('evaluador_has_evaluados.deleted_at',null)
            ->where('evaluador_has_evaluados.evaluacion_id',$evaluacion_id)
            ->first();
            return $cargo_de_evaluado->cargo_de_evaluado;
        },[],'1')->label('Cargo del evaluado')->searchable()->filterable()->defaultSort('asc'),
        Column::callback(['respuestas.evaluado_id','pregunta.evaluacion_id'], function ($id,$evaluacion_id) {
            $area_de_evaluado = EvaluadorHasEvaluado::select('evaluador_has_evaluados.area_de_evaluado')
            ->where('evaluador_has_evaluados.evaluado_id',$id)
            ->where('evaluador_has_evaluados.deleted_at',null)
            ->where('evaluador_has_evaluados.evaluacion_id',$evaluacion_id)
            ->first();
            return $area_de_evaluado->area_de_evaluado;
        },[],'2')->label('Area del evaluado')->searchable()->filterable()->defaultSort('asc'),
        Column::callback(['respuestas.evaluado_id','pregunta.evaluacion_id'], function ($id,$evaluacion_id) {
            $gerencia_sub_gerencia_de_evaluado = EvaluadorHasEvaluado::select('evaluador_has_evaluados.gerencia_sub_gerencia_de_evaluado')
            ->where('evaluador_has_evaluados.evaluado_id',$id)
            ->where('evaluador_has_evaluados.deleted_at',null)
            ->where('evaluador_has_evaluados.evaluacion_id',$evaluacion_id)
            ->first();
            return $gerencia_sub_gerencia_de_evaluado->gerencia_sub_gerencia_de_evaluado;
        },[],'3')->label('Gerencia/Subgerencia del evaluado')->searchable()->filterable()->defaultSort('asc'),
        
        Column::callback(['respuestas.evaluado_id','pregunta.evaluacion_id'], function ($id,$evaluacion_id) {
            $jerarquia = EvaluadorHasEvaluado::select('evaluador_has_evaluados.jerarquia')
            ->where('evaluador_has_evaluados.evaluado_id',$id)
            ->where('evaluador_has_evaluados.deleted_at',null)
            ->where('evaluador_has_evaluados.evaluacion_id',$evaluacion_id)
            ->first();
            return $jerarquia->jerarquia;
        },[],'4')->label('Jerarquia')->searchable()->filterable()->defaultSort('asc'),

        ];
        //
    }
}