<?php

namespace App\Http\Livewire;

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
        // Column::name('evaluaciones.title')->label('Evaluación')->searchable()->filterable()->defaultSort('asc'),
        //Column::name('valor_texto')->label('Valor texto')->searchable()->filterable()->defaultSort('asc'),
        //Column::name('created_at')->label('Fecha de creacion')->searchable()->filterable()->defaultSort('asc'),

        ];
        //
    }
}