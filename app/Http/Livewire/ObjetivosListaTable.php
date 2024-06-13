<?php

namespace App\Http\Livewire;

use App\Models\Objetivo;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\Exports\DatatableExport;
use OwenIt\Auditing\Models\Audit;

class ObjetivosListaTable extends LivewireDatatable
{
    public $hideable = 'inline';
    public $exportable = true;
    public $afterTableSlot = 'components.selected';
    public $numeroSerieValidado=true, $fileUpload;
    public $updateMode = false;
    public $export_name = 'Objetivos';
    public $auditorias = [];

    public function builder()
    {
        return Objetivo::query()
        ->where('objetivos.deleted_at',null)->where('objetivos.deleted_at',null)
        ->leftJoin('tipo_de_objetivos','tipo_de_objetivos.id','=','objetivos.tipo_objetivo_id')
        ->leftJoin('personal as evaluados','evaluados.id','=','objetivos.evaluado_id')
        ->leftJoin('personal as evaluadores','evaluadores.id','=','objetivos.evaluador_id')
        ->leftjoin('evaluador_has_evaluados','evaluador_has_evaluados.id','=','objetivos.evaluador_has_evaluado_id' )
        ;
    }

    public $model = Objetivo::class;

    
    public function rowClasses($row, $loop)
    {
        return 'divide-x divide-gray-100 text-sm text-gray-900 ' . 
        (
            $this->rowIsSelected($row) ? 'bg-blue-100' : 
                ($row->{'estado.name'} == 'REGISTRADO' ? 'bg-yellow-100' : 
                    ($row->{'estado.name'} == 'REALIZADO' ? 'bg-green-100' : 
                        ($loop->even ? 'bg-red-100' : 'bg-red-100')
                    )
                )
        );
    }

    public function columns()
    {
        return [

        // Column::name('id')->label('ID')->filterable()->defaultSort('asc'),

        Column::callback(['id'], function ($id) {
                return view('components.lupa-button', ['id' => $id]);
            })->label('Ver historial')->alignCenter()->excludeFromExport(),

        //COLUMNA ESTADO DE OBJETIVO
        Column::name('estado.name')->label('Estado')->searchable()->filterable()->defaultSort('asc'),
        // Column::name('estado.color')->label('Estado Color')->searchable()->filterable()->defaultSort('asc')->hide(),
        // Column::callback(['estado_id'], function ($estado_id) {
        //     return view('components.estado', ['estado_id' => $estado_id]);
        // })->label('Estado')->alignCenter()->searchable()->filterable()->defaultSort('asc'),
        Column::name('evaluadores.name')->label('Evaluador')->searchable()->filterable()->defaultSort('asc'),
        Column::name('evaluados.name')->label('Evaluado')->searchable()->filterable()->defaultSort('asc'),
        Column::name('evaluador_has_evaluados.cargo_de_evaluado')->label('Cargo del evaluado')->searchable()->filterable()->defaultSort('asc'),
        Column::name('objetivos.meta')->label('Meta')->searchable()->filterable()->defaultSort('asc'),
        Column::callback(['objetivos.porcentaje_de_participacion','objetivos.tipo_objetivo_id'], function ($porcentaje_de_participacion,$tipo_objetivo_id) {
                return ($porcentaje_de_participacion * 100).'%';
            })->label('Porcentaje de participación')->searchable()->filterable()->defaultSort('asc'),

        Column::name('tipo_de_objetivos.unidad')->label('Tipo de objetivo')->searchable()->filterable()->defaultSort('asc'),

        Column::callback(['objetivos.resultado_anterior_o_esperado','objetivos.tipo_objetivo_id'], function ($resultado_anterior_o_esperado,$tipo_objetivo_id) {
            if ($tipo_objetivo_id == 2) { // si es porcentaje
                return ($resultado_anterior_o_esperado * 100).'%';
            } else {
                return $resultado_anterior_o_esperado;
            }
            })->label('Resultado anterior o esperado')->alignCenter()->searchable()->filterable()->defaultSort('asc'),

        Column::callback(['objetivos.minimo','objetivos.tipo_objetivo_id'], function ($minimo,$tipo_objetivo_id) {
            if ($tipo_objetivo_id == 2) { // si es porcentaje
                return ($minimo * 100).'%';
            } else {
                return $minimo;
            }
            })->label('Mínimo')->alignCenter()->searchable()->filterable()->defaultSort('asc'),
    
        Column::callback(['objetivos.maximo','objetivos.tipo_objetivo_id'], function ($maximo,$tipo_objetivo_id) {
            if ($tipo_objetivo_id == 2) { // si es porcentaje
                return ($maximo * 100).'%';
            } else {
                return $maximo;
            }
            })->label('Máximo')->alignCenter()->searchable()->filterable()->defaultSort('asc'),

            
        Column::name('objetivos.valor')->label('Valor')->searchable()->filterable()->defaultSort('asc'),

        Column::callback(['objetivos.porcentaje_de_logro_STI','objetivos.tipo_objetivo_id'], function ($porcentaje_de_logro_STI,$tipo_objetivo_id) {
            return $porcentaje_de_logro_STI ? ($porcentaje_de_logro_STI * 100).'%' : '';
        })->label('Porcentaje de logro STI')->searchable()->filterable()->defaultSort('asc'),

        Column::name('objetivos.peso_ponderado')->label('Peso ponderado')->searchable()->filterable()->defaultSort('asc'),

        // Column::name('objetivos.descripcion')->label('Objetivo')->searchable()->filterable()->defaultSort('asc'),
        // Column::name('objetivos.resultado')->label('Resultado')->searchable()->filterable()->defaultSort('asc'),
        // Column::callback(['id'], function ($id) {
        //     return 'Evidencias';
        // },[],'evidencias')->label('Evidencias')->alignCenter(),        
        
        // Column::name('evaluador_has_evaluados.area_de_evaluado')->label('Area del evaluado')->searchable()->filterable()->defaultSort('asc'),
        // Column::name('evaluador_has_evaluados.gerencia_sub_gerencia_de_evaluado')->label('Gerencia/Subgerencia del evaluado')->searchable()->filterable()->defaultSort('asc'),
        // Column::name('evaluador_has_evaluados.jerarquia')->label('Jerarquía')->searchable()->filterable()->defaultSort('asc'),
        Column::name('created_at')->label('Fecha de creacion')->searchable()->filterable()->defaultSort('asc'),
        Column::name('updated_at')->label('Fecha de Modificación')->searchable()->filterable()->defaultSort('asc'),

        ];
    }

    public function mostrarAuditorias($id)
    {
        $this->auditorias = Audit::where('auditable_id', $id)->where('auditable_type', Objetivo::class)->get()->toArray();
        $this->emit('enviarAuditorias', $this->auditorias);
    }

    public function export()
    {
        $this->forgetComputed();

        $export = new DatatableExport($this->getExportResultsSet());

        $export->setFileName('Resultados.xlsx');
        return $export->download();
    }

}