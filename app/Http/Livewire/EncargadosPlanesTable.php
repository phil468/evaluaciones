<?php

namespace App\Http\Livewire;

use App\Models\EncargadosPlanesDeAccion;
// use App\Models\EvaluadorHasEvaluado;
use App\Models\Respuesta;
use App\Models\TipoDeEvaluacione;
use Illuminate\Support\Facades\DB;
use Mediconesystems\LivewireDatatables\Action;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\BooleanColumn;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\Exports\DatatableExport;
use Mediconesystems\LivewireDatatables\NumberColumn;

//en esta tabla vamos a mostrar los evaluadores 
class EncargadosPlanesTable extends LivewireDatatable
{
    public $hideable = 'inline';
    public $exportable = true;
    public $beforeTableSlot = 'components.edicionMasivaEncargadosPlanes';
    public $selected = [];
    public $afterTableSlot = 'components.selected';
    public $numeroSerieValidado=true, $fileUpload;
    public $updateMode = false;
    public $export_name = 'Evaluadores';
    public $modalEdicionMasiva = '#updateRegistroModal';

    protected $listeners = [
        'refreshEncargadosPlanesTable' => '$refresh',
        'limpiarSeleccionEncargadosPlanesTable'=>'limpiarSeleccion'
    ];

    public function builder()
    {       
        return EncargadosPlanesDeAccion::query()
        ->leftJoin('personal as encargados','encargados.id','=','encargados_planes_de_accion.encargado_id')
        ->leftJoin('personal as empleados','empleados.id','=','encargados_planes_de_accion.empleado_id')
        ->leftJoin('planes_de_accion_configuracion', 'planes_de_accion_configuracion.id', '=', 'encargados_planes_de_accion.planes_de_accion_configuracion_id');
        ;
    }

    public $model = EncargadosPlanesDeAccion::class;
    
    public function rowClasses($row, $loop)
    {
        // dd($row);
        $classes = 'divide-x divide-gray-100 text-sm text-gray-900 ';

        if ($this->rowIsSelected($row)) {
            $classes .= 'bg-blue-100 ';
        } elseif ($row->{'callback_habilitado'} == 'Cesado') {
            $classes .= 'text-gray-500 italic bg-red-100 ';
        } else {
            $classes .= $loop->even ? 'bg-white ' : 'bg-gray-50 ';
        }

        return $classes;
    }

    public function limpiarSeleccion()
    {
        $this->selected = [];
    }

    public function edicionMasiva()
    {
        $this->emitUp('editMassive', $this->selected);
    }

    public function columns()
    {
        return [
            Column::checkbox()->label('Seleccionar'),
            Column::callback(['encargados_planes_de_accion.id'], function ($id) {
                return view('table-actions-4', ['id' => $id]);
            })->label('Acciones')->unsortable()->excludeFromExport(),
            // Column::name('evaluaciones.title')->label('Evaluacion')->searchable()->filterable(),
            
            Column::callback(['habilitado'], function ($habilitado) {
                return $habilitado ? 'Activo' : 'Cesado';
            },[],'habilitado')->label('Estado')->searchable()->filterable(),

            BooleanColumn::name('encargados_planes_de_accion.realizado')->label('Realizado')->searchable()->filterable(),
            Column::name('plan_de_mejora.title')->label('Plan de Mejora')->searchable()->filterable(),

            Column::name('encargados.name')->label('Evaluador')->searchable()->filterable(),
            Column::name('cargo_de_evaluador')->label('Cargo de evaluador')->searchable()->filterable(),
            Column::name('area_de_evaluador')->label('Área de evaluador')->searchable()->filterable(),
            Column::name('gerencia_sub_gerencia_de_evaluador')->label('Gerencia Sub Gerencia de evaluador')->searchable()->filterable(),

            Column::name('empleados.name')->label('Evaluado')->searchable()->filterable(),
            Column::name('cargo_de_evaluado')->label('Cargo de evaluado')->searchable()->filterable(),
            Column::name('area_de_evaluado')->label('Área de evaluado')->searchable()->filterable(),
            Column::name('gerencia_sub_gerencia_de_evaluado')->label('Gerencia Sub Gerencia de evaluado')->searchable()->filterable(),

            Column::name('cantidad_requerida')->label('Cantidad requerida')->searchable()->filterable(),
            Column::name('valor_esperado')->label('Valor Esperado')->searchable()->filterable(),
        ];
    }
    
    public function edit($id)
    {
        $this->emit('openEncargadosPlanesModal');
        $this->emit('edit', $id);
    }

    public function export()
    {
        $this->forgetComputed();
        $export = new DatatableExport($this->getExportResultsSet());
        $export->setFileName('evaluadores_de_planes_de_mejora.xlsx');
        return $export->download();
    }

    public function limpiarSeleccionPersonalTable()
    {
        $this->reset();
    }

    public function delete($id)
    {
        $evaluadorHasEvaluado = EncargadosPlanesDeAccion::find($id);
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
            $record = EncargadosPlanesDeAccion::where('id', $id);
            $record->delete();
        }
    }

    // public function rowCallback($row)
    // {
    //     dd($row->habilitado);
    //     if ($row->habilitado == 0) {
    //         return [
    //             'style' => 'color: gray; font-style: italic;',
    //         ];
    //     }
    //     return [];
    // }

}