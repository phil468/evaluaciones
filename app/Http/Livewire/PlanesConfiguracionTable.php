<?php

namespace App\Http\Livewire;

use App\Models\PlanesConfiguracion;
use DateTime;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\BooleanColumn;
use Mediconesystems\LivewireDatatables\Column;

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

            Column::name('planes_de_accion_configuracion.title')->label('Título')->searchable(),

            BooleanColumn::name('planes_de_accion_configuracion.status')->label('Estado')->searchable(),
            Column::name('planes_de_accion_configuracion.nombre_para_mostrar')->label('Nombre para mostrar')->searchable(),
            Column::name('planes_de_accion_configuracion.campania')->label('Campaña')->searchable(),

            Column::callback('planes_de_accion_configuracion.fecha_inicio', function ($fecha_inicio) {
                return $fecha_inicio ? (new DateTime($fecha_inicio))->format('d/m/Y h:i:s a') : '';
            })->label('Fecha de inicio')->searchable(),

            Column::callback('planes_de_accion_configuracion.fecha_fin', function ($fecha_fin) {
                return $fecha_fin ? (new DateTime($fecha_fin))->format('d/m/Y h:i:s a') : '';
            })->label('Fecha de fin')->searchable(),

            Column::callback('planes_de_accion_configuracion.fecha_inicio_primera_fase_matricula', function ($fecha_inicio_primera_fase_matricula) {
                return $fecha_inicio_primera_fase_matricula ? (new DateTime($fecha_inicio_primera_fase_matricula))->format('d/m/Y h:i:s a') : '';
            })->label('Fecha de inicio de la primera fase (Matrícula)')->searchable(),

            Column::callback('planes_de_accion_configuracion.fecha_fin_primera_fase_matricula', function ($fecha_fin_primera_fase_matricula) {
                return $fecha_fin_primera_fase_matricula ? (new DateTime($fecha_fin_primera_fase_matricula))->format('d/m/Y h:i:s a') : '';
            })->label('Fecha de fin de la primera fase de (Matrícula)')->searchable(),

            Column::callback('planes_de_accion_configuracion.fecha_inicio_segunda_fase', function ($fecha_inicio_segunda_fase) {
                return $fecha_inicio_segunda_fase ? (new DateTime($fecha_inicio_segunda_fase))->format('d/m/Y h:i:s a') : '';
            })->label('Fecha de inicio de la segunda fase (Resultado)')->searchable(),

            Column::callback('planes_de_accion_configuracion.fecha_fin_segunda_fase', function ($fecha_fin_segunda_fase) {
                return $fecha_fin_segunda_fase ? (new DateTime($fecha_fin_segunda_fase))->format('d/m/Y h:i:s a') : '';
            })->label('Fecha de fin de la segunda fase (Resultado)')->searchable(),

            Column::name('planes_de_accion_configuracion.identificador')->label('Identificador')->searchable(),
        ];
    }
    
    public function edit($id)
    {
        $this->emit('editPlanes', $id);
    }

}