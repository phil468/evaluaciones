<?php

namespace App\Http\Livewire;

use App\Models\EvaluadorHasEvaluado;
use App\Models\Objetivo;
use App\Models\Respuesta;
use App\Models\TipoDeEvaluacione;
use Illuminate\Support\Facades\DB;
use Mediconesystems\LivewireDatatables\Action;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\BooleanColumn;
// use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\Exports\DatatableExport;
use Mediconesystems\LivewireDatatables\NumberColumn;

//en esta tabla vamos a mostrar los evaluadores 
class EvaluacionesEvaluadoresResultadosTable extends LivewireDatatable
{
    public $hideable = 'inline';
    public $exportable = true;
    public $afterTableSlot = 'components.selected';
    public $numeroSerieValidado=true, $fileUpload;
    public $updateMode = false;
    public $export_name = 'Evaluadores';

    protected $listeners = ['refreshEvaluadoresResultados' => '$refresh', 'limpiarSeleccionTable'=>'limpiarSeleccionTable'];

    public function builder()
    {       
        return 
        EvaluadorHasEvaluado::query()
        ->select('evaluador_has_evaluados.*')
        ->leftJoin('personal as encargado','encargado.id','=','evaluador_has_evaluados.evaluador_id')
        ->leftJoin('evaluaciones','evaluaciones.id','=','evaluador_has_evaluados.evaluacion_id')
        ->leftJoin('personal as empleado','empleado.id','=','evaluador_has_evaluados.evaluado_id')
        ->whereHas('evaluacion', function ($query) {
            $query->where('tipo_de_evaluacion_id', TipoDeEvaluacione::RESULTADOS);
        });
    }

    public $model = EvaluadorHasEvaluado::class;

    public function columns()
    {
        return [
            // Column::name('id')->label('ID')->filterable()->defaultSort('asc'),

            Column::callback(['evaluador_has_evaluados.id','evaluaciones.fecha_fin', 'encargado.name', 'empleado.name', 'evaluaciones.title'], 
            function ($id,$fecha_fin, $evaluador, $evaluado, $evaluacion) {
                $name= $evaluador.' - '.$evaluado.' - '.$evaluacion;
                return $fecha_fin < now() ? '' : view('table-actions-4', ['id' => $id , 'name' => $name]);
            })->label('Acciones')->unsortable()->excludeFromExport(),
            Column::callback(['evaluador_has_evaluados.tipo_jerarquia_id'], function ($id) {
                return ($id == 1 ? 'TIPO 1' : ($id == 2 ? 'TIPO 2' : ''));
            })->label('Tipo de Jerarquia de Objetivos')->searchable()->filterable(),

            NumberColumn::name('objetivos.id:count')->label('Objetivos Totales')->searchable()->filterable(),

            Column::callback(['evaluador_has_evaluados.id'], function ($id) {
                // Aquí, realiza las subconsultas para calcular objetivos_registrados y objetivos_completados
                // basándote en el ID del evaluador_has_evaluado. Este es un ejemplo simplificado.
                $objetivosNoRegistrados = Objetivo::where('evaluador_has_evaluado_id', $id)
                                                ->where('estado_id', null)
                                                ->count();
                $objetivosRegistrados = Objetivo::where('evaluador_has_evaluado_id', $id)
                                                ->where('estado_id', 1)
                                                ->count();
                $objetivosCompletados = Objetivo::where('evaluador_has_evaluado_id', $id)
                                                ->where('estado_id', 2)
                                                ->count();
                // Devuelve los valores calculados como desees mostrarlos en la tabla
                return "No Registrados: $objetivosNoRegistrados<br> Registrados: $objetivosRegistrados <br> Completados: $objetivosCompletados";
            },[],'Objetivos Resumen')->label('Objetivos Resumen')->searchable()->filterable(),

            Column::name('evaluaciones.title')->label('Evaluacion')->searchable()->filterable(),
            Column::name('encargado.name')->label('Evaluador')->searchable()->filterable(),
            Column::name('cargo_de_evaluador')->label('Cargo de evaluador')->searchable()->filterable(),
            Column::name('area_de_evaluador')->label('Área de evaluador')->searchable()->filterable(),
            Column::name('gerencia_sub_gerencia_de_evaluador')->label('Gerencia Sub Gerencia de evaluador')->searchable()->filterable(),

            Column::name('empleado.name')->label('Evaluado')->searchable()->filterable(),
            Column::name('cargo_de_evaluado')->label('Cargo de evaluado')->searchable()->filterable(),
            Column::name('area_de_evaluado')->label('Área de evaluado')->searchable()->filterable(),
            Column::name('gerencia_sub_gerencia_de_evaluado')->label('Gerencia Sub Gerencia de evaluado')->searchable()->filterable(),
        ];

    }
    
    public function edit($id)
    {
        $this->emit('openUpdateModal');
        $this->emit('edit_evaluador', $id, 2);
    }

    public function export()
    {
        $this->forgetComputed();
        $export = new DatatableExport($this->getExportResultsSet());
        $export->setFileName('evaluadores_de_evaluacion_por_resultados.xlsx');
        return $export->download();
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
            $record = EvaluadorHasEvaluado::find($id);
            $record->delete();

            $record = Objetivo::where('evaluador_has_evaluado_id', $id);
            $record->delete();
            
            $this->emit('refreshEvaluadoresResultados');
            $this->emit('eliminadoEvaluadoresResultados');
        }
    }

}