<?php

namespace App\Http\Livewire;

use App\Models\PlanesConfiguracion;
use DateTime;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\BooleanColumn;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;

class PlanesConfiguracionTable extends LivewireDatatable
{
    public $hideable = 'inline';
    public $exportable = true;
    public $afterTableSlot = 'components.selected';
    public $numeroSerieValidado=true, $fileUpload;
    public $updateMode = false;
    public $export_name = 'Evaluadores';

    protected $listeners = ['refreshPlanes' => '$refresh','limpiarSeleccionTable'=>'limpiarSeleccionTable'];

    public function builder()
    {       
        return PlanesConfiguracion::query()->where('planes_de_accion_configuracion.deleted_at',null);
    }

    public $model = PlanesConfiguracion::class;

    public function columns()
    {
        return [
           
            Column::callback('id,title', function ($id,$title) {
                return view('table-actions-4', ['id' => $id, 'name'=>$title]);
            })->unsortable()
            ->label('Acciones')
            ->excludeFromExport(),

            Column::name('planes_de_accion_configuracion.title')
            ->label('Título')->searchable()->filterable(),

            BooleanColumn::name('planes_de_accion_configuracion.status')
            ->label('Estado')->searchable()->filterable()->sortable(),

            Column::name('planes_de_accion_configuracion.nombre_para_mostrar')
            ->label('Nombre para mostrar')->searchable()->filterable(),

            Column::name('planes_de_accion_configuracion.campania')
            ->label('Campaña')->searchable()->filterable(),

            DateColumn::name('planes_de_accion_configuracion.fecha_inicio')
            ->label('Fecha de inicio')->format('d/m/Y h:i:s a')->searchable()->filterable()->sortable(),

            DateColumn::name('planes_de_accion_configuracion.fecha_fin')
            ->label('Fecha de fin')->format('d/m/Y h:i:s a')->searchable()->filterable()->sortable(),

            DateColumn::name('planes_de_accion_configuracion.fecha_inicio_primera_fase_matricula')
            ->label('Fecha de inicio de la primera fase (Matrícula)')->format('d/m/Y h:i:s a')->searchable()->filterable()->sortable(),

            DateColumn::name('planes_de_accion_configuracion.fecha_fin_primera_fase_matricula')
            ->label('Fecha de fin de la primera fase de (Matrícula)')->format('d/m/Y h:i:s a')->searchable()->filterable()->sortable(),

            DateColumn::name('planes_de_accion_configuracion.fecha_inicio_segunda_fase')
            ->label('Fecha de inicio de la segunda fase (Resultado)')->format('d/m/Y h:i:s a')->searchable()->filterable()->sortable(),

            DateColumn::name('planes_de_accion_configuracion.fecha_fin_segunda_fase')
            ->label('Fecha de fin de la segunda fase (Resultado)')->format('d/m/Y h:i:s a')->searchable()->filterable()->sortable(),

            Column::name('planes_de_accion_configuracion.identificador')->label('Identificador')->searchable()->filterable()->sortable(),
        ];
    }
    
    public function edit($id)
    {
        $this->emit('editPlanes', $id);
    }

}