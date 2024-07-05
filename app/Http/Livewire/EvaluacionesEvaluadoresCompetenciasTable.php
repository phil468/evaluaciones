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
use Mediconesystems\LivewireDatatables\Exports\DatatableExport;
use Mediconesystems\LivewireDatatables\LabelColumn;
use Mediconesystems\LivewireDatatables\NumberColumn;

//en esta tabla vamos a mostrar los evaluadores 
class EvaluacionesEvaluadoresCompetenciasTable extends LivewireDatatable
{
    public $hideable = 'inline';
    public $exportable = true;
    public $afterTableSlot = 'components.selected';
    public $numeroSerieValidado=true, $fileUpload;
    public $updateMode = false;
    public $export_name = 'Evaluadores';

    protected $listeners = ['refreshEvaluadoresCompetencias' => '$refresh', 'limpiarSeleccionTable'=>'limpiarSeleccionTable'];

    public function builder()
    {       
        return EvaluadorHasEvaluado::query()
        ->whereHas('evaluacion', function ($query) {
            $query->where('tipo_de_evaluacion_id', TipoDeEvaluacione::COMPETENCIAS);
        })
        ->leftJoin('personal as encargado','encargado.id','=','evaluador_has_evaluados.evaluador_id')
        ->leftJoin('personal as empleado','empleado.id','=','evaluador_has_evaluados.evaluado_id')
        ->leftJoin('evaluaciones','evaluaciones.id','=','evaluador_has_evaluados.evaluacion_id')
        ;
    }

    public $model = EvaluadorHasEvaluado::class;

    public function columns()
    {
        return [
            // Column::checkbox('id')->label('ID')->alignCenter(),
            // NumberColumn::name('id')->label('ID')->filterable()->searchable(),
            Column::callback(['evaluador_has_evaluados.id','evaluador_has_evaluados.realizado', 'encargado.name', 'empleado.name', 'evaluaciones.title'], function ($id,$realizado, $evaluador, $evaluado, $evaluacion) {
                $name= $evaluador.' - '.$evaluado.' - '.$evaluacion;
                return $realizado ? view('table-actions-5', ['id' => $id , 'name' => $name, 'canEdit' => true, 'canDelete' => false]) : view('table-actions-5', ['id' => $id, 'name' => $name,]);
                // if($realizado){
                //     return view('table-actions-4', ['id' => $id , 'canEdit' => true, 'canDelete' => false]);
                // } else {
                //     return view('table-actions-4', ['id' => $id]);
                // }
                // return view('table-actions-4', ['id' => $id , 'canEdit' => false, 'canDelete' => false]);
            })->label('Acciones')->unsortable()->excludeFromExport(),

            BooleanColumn::name('realizado')->label('Realizado')->searchable()->filterable(),

            Column::name('evaluaciones.title')->label('Evaluacion')->searchable()->filterable(),

            Column::name('encargado.name')->label('Evaluador')->searchable()->filterable(),
            // Column::name('evaluador.name')->label('Evaluador')->searchable()->filterable(),

            Column::name('cargo_de_evaluador')->label('Cargo de evaluador')->searchable()->filterable(),
            Column::name('area_de_evaluador')->label('Área de evaluador')->searchable()->filterable(),
            Column::name('gerencia_sub_gerencia_de_evaluador')->label('Gerencia Sub Gerencia de evaluador')->searchable()->filterable(),

            // Column::name('empleado.name')->label('Evaluado Nombre')->searchable()->filterable(),
            Column::name('empleado.name')->label('Evaluado')->searchable()->filterable(),

            Column::name('cargo_de_evaluado')->label('Cargo de evaluado')->searchable()->filterable(),
            Column::name('area_de_evaluado')->label('Área de evaluado')->searchable()->filterable(),
            Column::name('gerencia_sub_gerencia_de_evaluado')->label('Gerencia Sub Gerencia de evaluado')->searchable()->filterable(),

            // Column::name('cantidad_requerida')->label('Cantidad requerida')->searchable()->filterable(),
            // Column::name('valor_esperado')->label('Valor Esperado')->searchable()->filterable(),
        ];
    }
    
    public function edit($id)
    {
        $this->emit('openUpdateModal');
        $this->emit('edit_evaluador', $id, 1);
    }

    // public function export()
    // {
    //     $this->exportSelected();
    // }

    public function export()
    {
        $this->forgetComputed();
        $export = new DatatableExport($this->getExportResultsSet());
        $export->setFileName('evaluadores_de_evaluacion_por_competencias.xlsx');
        return $export->download();
    }

    public function limpiarSeleccionPersonalTable()
    {
        $this->reset();
    }

    // public function delete($id)
    // {
    //     $evaluadorHasEvaluado = EvaluadorHasEvaluado::find($id);
    //     if($evaluadorHasEvaluado->realizado){
    //         session()->flash('messageEvaluadoresCompetencias', 'No se puede eliminar un evaluador que ya ha realizado la evaluación.');
    //         return;
    //     } else {
    //         $evaluadorHasEvaluado->delete();
    //         session()->flash('messageEvaluadoresCompetencias', 'Evaluador eliminado correctamente.');            
    //     }
    // }

    public function confirmDelete($id)
    {
        $this->dispatchBrowserEvent('swal:confirm', [
            'type' => 'warning',
            'title' => '¿Estás seguro?',
            'text' => 'No podrás revertir esto!',
            'id' => $id
        ]);
    }

    public function confirmImport()
    {
        $this->dispatchBrowserEvent('swal:import', [
            'type' => 'warning',
            'title' => '¿Estás seguro?',
            'text' => 'No podrás revertir esto!',
        ]);
    }

    public function destroy($id)
    {
        if ($id) {
            $record = EvaluadorHasEvaluado::find($id);
            if($record->realizado){
                $this->emit('eliminadoEvaluadoresCompetencias', 'No se puede eliminar un evaluador que ya ha realizado la evaluación.');
                return;
            } else {
                $record->delete();
                $this->emit('eliminadoEvaluadoresCompetencias', 'Evaluador eliminado correctamente.');
            }
        }
    }

}