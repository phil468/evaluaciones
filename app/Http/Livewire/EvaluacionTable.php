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

    public function builder()
    {       
        return Evaluacione::query()->where('evaluaciones.deleted_at',null);
    }

    public $model = Evaluacione::class;

    // public function columns()
    // {
    //     return [
            
    //         Column::callback('id,title', function ($id,$title) {
    //             return view('table-actions-3', ['id' => $id, 'name'=>$title]);
    //         })->unsortable()
    //         ->label('Acciones')
    //         ->excludeFromExport(),

    //         // Column::name('evaluaciones.eid')->label('ID')->searchable()->filterable(),
    //         Column::name('evaluaciones.eid')->label('ID')->searchable()->filterable(),
    //         Column::name('evaluaciones.title')->label('Evaluacion')->searchable()->filterable(),
    //         // DateColumn::name('evaluaciones.date')->label('Fecha')->searchable()->filterable(),
    //         Column::callback(['evaluaciones.date','evaluaciones.id'], function ($date,$id) {
    //             return view('livewire.editable-date-column', 
    //             [
    //                 'value' => $date,
    //                 'key' => $this->builder()->getModel()->getQualifiedKeyName(),
    //                 'column' => 'date',
    //                 'rowId' => $id,
    //             ]
    //         );
    //         })
    //         ->label('Fecha')
    //         ->searchable()
    //         ->filterable()
    //         ->exportCallback(function ($date) {
    //             $date = new \DateTime($date);
    //             return $date->format('d/m/Y H:i:s');
    //         }),

            

    //         Column::callback(['evaluaciones.status','evaluaciones.id'], function ($status, $id) {
    //             return view('livewire.editable-status-column', //'livewire.toggle-button', 
    //             [
    //                 'value' => $status,
    //                 'key' => $this->builder()->getModel()->getQualifiedKeyName(),
    //                 'column' => 'status',
    //                 'rowId' => $id,
    //             ]);
    //         })
    //         ->label('Estado')
    //         ->searchable()
    //         ->filterable()
    //         ->exportCallback(
    //             function ($value) {
    //                 return $value ? 'Activo' : 'Inactivo';
    //             }
    //         ),

    //         // Column::delete()->label('Eliminar')->alignCenter()->excludeFromExport(),
                       
    //     ];

    // }


    public function columns()
    {
        return [
            Column::name('evaluaciones.eid')->label('EID')->searchable()->filterable(),
            Column::name('evaluaciones.title')->label('Título')->searchable(),
            DateColumn::name('evaluaciones.date')->label('Fecha')->searchable(),
            BooleanColumn::name('evaluaciones.status')->label('Estado')->searchable(),
            // DateColumn::name('evaluaciones.created_at')->label('Creado en')->searchable(),
            // DateColumn::name('evaluaciones.updated_at')->label('Actualizado en')->searchable(),
            // DateColumn::name('evaluaciones.deleted_at')->label('Eliminado en')->searchable(),
            Column::name('evaluaciones.nombre_para_mostrar')->label('Nombre para mostrar')->searchable(),
            Column::name('evaluaciones.campania')->label('Campaña')->searchable(),
            Column::name('evaluaciones.mes')->label('Mes')->searchable(),
            Column::name('evaluaciones.anio')->label('Año')->searchable(),
            DateColumn::name('evaluaciones.fecha_inicio')->label('Fecha de inicio')->searchable(),
            DateColumn::name('evaluaciones.fecha_fin')->label('Fecha de fin')->searchable(),
            // Column::name('evaluaciones.identificador')->label('Identificador')->searchable(),
            // Para el campo JSON, puedes necesitar un tratamiento especial dependiendo de cómo quieras mostrar los datos
            Column::name('evaluaciones.tipo_de_evaluacion_id')->label('ID de Tipo de Evaluación')->searchable(),
            Column::callback('id,title', function ($id,$title) {
                return view('table-actions-3', ['id' => $id, 'name'=>$title]);
            })->unsortable()
            ->label('Acciones')
            ->excludeFromExport(),
        ];
    }
    
    public function edit($id)
    {
        $this->emit('edit', $id);
    }

}