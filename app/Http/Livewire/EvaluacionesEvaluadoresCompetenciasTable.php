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
        // ->leftJoin('personal as empleado','empleado.id','=','evaluador_has_evaluados.evaluado_id');
        // ->with('evaluacion','evaluador','evaluado')
        ;
    }

    public $model = EvaluadorHasEvaluado::class;

    public function columns()
    {
        return [
            // Column::name('id'),
            Column::callback(['id'], function ($id) {
                return view('table-actions-4', ['id' => $id]);
            })->label('Acciones')->unsortable()->excludeFromExport(),

            BooleanColumn::name('realizado')->label('Realizado')->searchable()->filterable(),

            Column::name('evaluacion.title')->label('Evaluacion')->searchable()->filterable(),

            Column::name('encargado.name')->label('Evaluador')->searchable()->filterable(),
            // Column::name('evaluador.name')->label('Evaluador')->searchable()->filterable(),

            Column::name('cargo_de_evaluador')->label('Cargo de evaluador')->searchable()->filterable(),
            Column::name('area_de_evaluador')->label('Área de evaluador')->searchable()->filterable(),
            Column::name('gerencia_sub_gerencia_de_evaluador')->label('Gerencia Sub Gerencia de evaluador')->searchable()->filterable(),

            // Column::name('empleado.name')->label('Evaluado Nombre')->searchable()->filterable(),
            Column::name('evaluado.name')->label('Evaluado')->searchable()->filterable(),

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

    public function export()
    {
        $this->exportSelected();
    }

    public function limpiarSeleccionPersonalTable()
    {
        $this->reset();
    }

    public function delete($id)
    {
        $evaluadorHasEvaluado = EvaluadorHasEvaluado::find($id);
        $evaluadorHasEvaluado->delete();
        session()->flash('message', 'Evaluador eliminado correctamente.');
    }

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
            $record = EvaluadorHasEvaluado::where('id', $id);
            $record->delete();
        }
    }

}