<?php

namespace App\Http\Livewire;

use App\Models\Evaluacione;
use App\Models\EvaluadorHasEvaluado;
use App\Models\Respuesta;
use Illuminate\Support\Facades\DB;
use Mediconesystems\LivewireDatatables\Action;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\BooleanColumn;
// use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\NumberColumn;

//en esta tabla vamos a mostrar los evaluadores 
class EvaluacionTable extends LivewireDatatable
{
    public $hideable = 'inline';
    public $exportable = true;
    public $afterTableSlot = 'components.selected';
    public $numeroSerieValidado=true, $fileUpload;
    public $updateMode = false;
    public $export_name = 'Evaluadores';

    protected $listeners = ['closeModal' => '$refresh','limpiarSeleccionPersonalTable'=>'limpiarSeleccionPersonalTable'];

    public function builder()
    {       
        return Evaluacione::query()
        ->where('evaluaciones.deleted_at',null)
        ->leftJoin('tipo_de_evaluaciones','tipo_de_evaluaciones.id','=','evaluaciones.tipo_de_evaluacion_id');
    }

    public $model = Evaluacione::class;

    public function columns()
    {
        return [
            // Column::name('evaluaciones.eid')->label('EID')->searchable()->filterable(),

            
            Column::callback('id,title', function ($id,$title) {
                return view('table-actions-3', ['id' => $id, 'name'=>$title]);
            })->unsortable()
            ->label('Acciones')
            ->excludeFromExport(),

            Column::name('tipo_de_evaluaciones.name')->label('ID de Tipo de Evaluación')->searchable(),
            Column::name('evaluaciones.title')->label('Título')->searchable(),

            // DateColumn::name('evaluaciones.date')->label('Fecha')->searchable(),

            BooleanColumn::name('evaluaciones.status')->label('Estado')->searchable(),
            Column::name('evaluaciones.nombre_para_mostrar')->label('Nombre para mostrar')->searchable(),
            Column::name('evaluaciones.campania')->label('Campaña')->searchable(),

            // Column::name('evaluaciones.mes')->label('Mes')->searchable(),
            // Column::name('evaluaciones.anio')->label('Año')->searchable(),

            DateColumn::name('evaluaciones.fecha_inicio')->label('Fecha de inicio')->searchable(),
            DateColumn::name('evaluaciones.fecha_fin')->label('Fecha de fin')->searchable(),
            
            DateColumn::name('evaluaciones.fecha_inicio_primera_fase_matricula')->label('Fecha de inicio de la primera fase (Matrícula)')->searchable(),
            DateColumn::name('evaluaciones.fecha_fin_primera_fase_matricula')->label('Fecha de fin de la primera fase de (Matrícula)')->searchable(),
            DateColumn::name('evaluaciones.fecha_inicio_segunda_fase')->label('Fecha de inicio de la segunda fase (Resultado)')->searchable(),
            DateColumn::name('evaluaciones.fecha_fin_segunda_fase')->label('Fecha de fin de la segunda fase (Resultado)')->searchable(),

            // Column::name('evaluaciones.minimo')
            // ->callback('evaluaciones.minimo',
            // )
            // ->label('Mínimo %')->searchable(),

            Column::callback('evaluaciones.minimo', function ($minimo) {
                return $minimo ? ($minimo*100).'%' : '';
            })->label('Mínimo %')->searchable(),

            Column::callback('evaluaciones.maximo', function ($maximo) {
                return $maximo ? ($maximo*100).'%' : '';
            })->label('Máximo %')->searchable(),

            // Column::name('evaluaciones.maximo')->label('Máximo %')->searchable(),

            Column::name('evaluaciones.identificador')->label('Identificador')->searchable(),
        ];
    }
    
    public function edit($id)
    {
        $this->emit('edit', $id);
    }

}