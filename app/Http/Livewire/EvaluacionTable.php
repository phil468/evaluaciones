<?php

namespace App\Http\Livewire;

use App\Models\Evaluacione;
use App\Models\EvaluadorHasEvaluado;
use App\Models\Respuesta;
use DateTime;
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

    protected $listeners = ['closeModalEvaluacion' => '$refresh','limpiarSeleccionTable'=>'limpiarSeleccionTable'];

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
            Column::callback('id,title', function ($id,$title) {
                return view('table-actions-4', ['id' => $id, 'name'=>$title]);
            })->unsortable()
            ->label('Acciones')
            ->excludeFromExport(),

            Column::name('tipo_de_evaluaciones.name')->label('Tipo de Evaluación')->searchable()->filterable(),
            Column::name('evaluaciones.title')->label('Título')->searchable()->filterable(),

            BooleanColumn::name('evaluaciones.status')->label('Estado')->searchable()->filterable()->sortable(),
            Column::name('evaluaciones.nombre_para_mostrar')->label('Nombre para mostrar')->searchable()->filterable(),
            Column::name('evaluaciones.campania')->label('Campaña')->searchable()->filterable(),

            DateColumn::name('evaluaciones.fecha_inicio')->format('d/m/Y h:i:s a')
            ->label('Fecha de inicio')->searchable()->filterable()->sortable(),

            DateColumn::name('evaluaciones.fecha_fin')->format('d/m/Y h:i:s a')
            ->label('Fecha de fin')->searchable()->filterable()->sortable(),

            DateColumn::name('evaluaciones.fecha_inicio_primera_fase_matricula')->format('d/m/Y h:i:s a')
            ->label('Fecha de inicio de la primera fase (Matrícula)')->searchable()->filterable()->sortable(),

            DateColumn::name('evaluaciones.fecha_fin_primera_fase_matricula')->format('d/m/Y h:i:s a')
            ->label('Fecha de fin de la primera fase (Matrícula)')->searchable()->filterable()->sortable(),

            DateColumn::name('evaluaciones.fecha_inicio_segunda_fase')->format('d/m/Y h:i:s a')
            ->label('Fecha de inicio de la segunda fase (Resultado)')->searchable()->filterable()->sortable(),

            DateColumn::name('evaluaciones.fecha_fin_segunda_fase')
            ->label('Fecha de fin de la segunda fase (Resultado)')->format('d/m/Y h:i:s a')->searchable()->filterable()->sortable(),

            DateColumn::name('evaluaciones.fecha_para_mostrar_resultados')->format('d/m/Y h:i:s a')
            ->label('Fecha para mostrar resultados')->searchable()->filterable()->sortable(),

            Column::callback('evaluaciones.minimo', function ($minimo) {
                return $minimo ? ($minimo*100).'%' : '';
            })->label('Mínimo %')->searchable(),

            Column::callback('evaluaciones.maximo', function ($maximo) {
                return $maximo ? ($maximo*100).'%' : '';
            })->label('Máximo %')->searchable(),

            Column::name('evaluaciones.identificador')->label('Identificador')->searchable()->filterable(),
        ];
    }
    
    public function edit($id)
    {
        $this->emit('edit', $id);
    }

}