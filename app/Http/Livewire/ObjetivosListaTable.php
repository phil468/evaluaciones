<?php

namespace App\Http\Livewire;

use App\Models\Objetivo;
use App\Models\Respuesta;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\BooleanColumn;
// use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\Column;

class ObjetivosListaTable extends LivewireDatatable
{
    public $hideable = 'inline';
    public $exportable = true;
    public $afterTableSlot = 'components.selected';
    public $numeroSerieValidado=true, $fileUpload;
    public $updateMode = false;
    public $export_name = 'Objetivos';

    public function builder()
    {
        return Objetivo::query()
        ->where('objetivos.deleted_at',null)->where('objetivos.deleted_at',null)
        ->leftJoin('tipo_de_objetivos','tipo_de_objetivos.id','=','objetivos.tipo_objetivo_id')
        ->leftJoin('personal as evaluado','evaluado.id','=','objetivos.evaluado_id')
        ->leftJoin('personal as evaluador','evaluador.id','=','objetivos.evaluador_id')
        //cargo
        // ->leftJoin('cargos','cargos.id','=','personal.cargo_id')
        ;
    }

    public $model = Objetivo::class;

    public function columns()
    {
        return [
        Column::name('evaluador.name')->label('Evaluador')->searchable()->filterable()->defaultSort('asc'),
        Column::name('evaluado.name')->label('Evaluado')->searchable()->filterable()->defaultSort('asc'),
        Column::name('objetivos.descripcion')->label('Objetivo')->searchable()->filterable()->defaultSort('asc'),
        Column::name('tipo_de_objetivos.unidad')->label('Tipo de objetivo')->searchable()->filterable()->defaultSort('asc'),
        Column::name('objetivos.resultado')->label('Resultado')->searchable()->filterable()->defaultSort('asc'),
        Column::name('objetivos.evidencia')->label('Evidencia')->searchable()->filterable()->defaultSort('asc'),

        // Column::name('cargos.name')->label('Cargo del evaluado')->searchable()->filterable()->defaultSort('asc'),
        // Column::name('preguntas.pregunta')->label('Pregunta')->searchable()->filterable()->defaultSort('asc'),
        // Column::name('valor_numerico')->label('Puntuación')->searchable()->filterable()->defaultSort('asc'),
        // Column::name('evaluaciones.title')->label('Evaluación')->searchable()->filterable()->defaultSort('asc'),
        //Column::name('valor_texto')->label('Valor texto')->searchable()->filterable()->defaultSort('asc'),
        //Column::name('created_at')->label('Fecha de creacion')->searchable()->filterable()->defaultSort('asc'),

        ];
        //
    }
}