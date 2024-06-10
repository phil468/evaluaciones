<?php

namespace App\Http\Livewire;

use App\Models\Objetivo;
use App\Models\PlanesDeAccion;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\Exports\DatatableExport;
use OwenIt\Auditing\Models\Audit;

class PlanesDeAccionTable extends LivewireDatatable
{
    public $hideable = 'inline';
    public $exportable = true;
    public $afterTableSlot = 'components.selected';
    public $numeroSerieValidado=true, $fileUpload;
    public $updateMode = false;
    public $export_name = 'Planes de Mejora';
    
    public $auditorias = [];

    public function builder()
    {
        return PlanesDeAccion::query()
        // ->select()
        ->where('planes_de_accion.deleted_at',null)
        ->leftJoin('personal as evaluado','evaluado.id','=','planes_de_accion.empleado_id')
        ->leftJoin('personal as evaluador','evaluador.id','=','planes_de_accion.encargado_id')
        ->leftJoin('tipo_de_evaluaciones as tipo_de_evaluaciones','tipo_de_evaluaciones.id','=','planes_de_accion.proceso_id')
        ->leftJoin('tipo_de_proceso as tipo','tipo.id','=','tipo_de_evaluaciones.tipo_de_proceso_id')
        ->leftJoin('secciones as competencias','competencias.id','=','planes_de_accion.competencia_id')
        ->leftJoin('estados_de_plan_de_accion as estados_de_plan_de_accion','estados_de_plan_de_accion.id','=','planes_de_accion.estado_id')
        ->leftJoin('gerencias','gerencias.id','=','planes_de_accion.gerencia_id')
        ->leftJoin('subgerencias','subgerencias.id','=','planes_de_accion.subgerencia_id')
        ->leftJoin('areas','areas.id','=','planes_de_accion.area_id');
    }

    public $model = PlanesDeAccion::class;

    public function columns()
    {
        return [
        // Column::callback(['id'], function ($id) {
        //         return view('components.lupa-button', ['id' => $id]);
        //     })->label('Ver historial')->alignCenter(),
        Column::name('name')->label('Descripcion')->searchable()->filterable()->defaultSort('asc'),
        Column::name('tipo_de_evaluaciones.name')->label('Proceso')->searchable()->filterable()->defaultSort('asc'),
        Column::name('tipo.name')->label('Tipo de proceso')->searchable()->filterable()->defaultSort('asc'),
        Column::name('evaluador.name')->label('Encargado')->searchable()->filterable()->defaultSort('asc'),
        Column::name('evaluado.name')->label('Personal')->searchable()->filterable()->defaultSort('asc'),
        Column::name('competencias.name')->label('Competencia')->searchable()->filterable()->defaultSort('asc'),
        Column::name('planes_de_accion.fecha_de_revision')->label('Fecha de Revisión')->searchable()->filterable()->defaultSort('asc'),
        Column::name('estados_de_plan_de_accion.name')->label('Estado')->searchable()->filterable()->defaultSort('asc'),
        Column::name('planes_de_accion.avance')->label('Avance')->searchable()->filterable()->defaultSort('asc'),
        Column::name('gerencias.name')->label('Gerencia')->searchable()->filterable()->defaultSort('asc'),
        Column::name('subgerencias.name')->label('Subgerencia')->searchable()->filterable()->defaultSort('asc'),
        Column::name('areas.name')->label('Area')->searchable()->filterable()->defaultSort('asc'),
        Column::name('created_at')->label('Fecha de creacion')->searchable()->filterable()->defaultSort('asc'),
        Column::name('updated_at')->label('Fecha de Modificación')->searchable()->filterable()->defaultSort('asc'),
        ];
    }

    public function mostrarAuditorias($id)
    {
        $this->auditorias = Audit::where('auditable_id', $id)->where('auditable_type', PlanesDeAccion::class)->get()->toArray();
        $this->emit('enviarAuditorias', $this->auditorias);
    }

    
    public function export()
    {
        $this->forgetComputed();

        $export = new DatatableExport($this->getExportResultsSet());

        $export->setFileName('Planes.xlsx');
        return $export->download();
    }

}