<?php

namespace App\Http\Livewire;

use App\Models\Objetivo;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\Column;
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

    public function columns()
    {
        return [
        Column::callback(['id'], function ($id) {
                return view('components.lupa-button', ['id' => $id]);
            })->label('Ver historial')->alignCenter(),
        Column::name('evaluadores.name')->label('Evaluador')->searchable()->filterable()->defaultSort('asc'),
        Column::name('evaluados.name')->label('Evaluado')->searchable()->filterable()->defaultSort('asc'),
        Column::name('objetivos.descripcion')->label('Objetivo')->searchable()->filterable()->defaultSort('asc'),
        Column::name('tipo_de_objetivos.unidad')->label('Tipo de objetivo')->searchable()->filterable()->defaultSort('asc'),
        Column::name('objetivos.resultado')->label('Resultado')->searchable()->filterable()->defaultSort('asc'),
        Column::name('objetivos.evidencia')->label('Evidencia')->searchable()->filterable()->defaultSort('asc'),
        Column::name('evaluador_has_evaluados.cargo_de_evaluado')->label('Cargo del evaluado')->searchable()->filterable()->defaultSort('asc'),
        Column::name('evaluador_has_evaluados.area_de_evaluado')->label('Area del evaluado')->searchable()->filterable()->defaultSort('asc'),
        Column::name('evaluador_has_evaluados.gerencia_sub_gerencia_de_evaluado')->label('Gerencia/Subgerencia del evaluado')->searchable()->filterable()->defaultSort('asc'),
        Column::name('evaluador_has_evaluados.jerarquia')->label('Jerarquía')->searchable()->filterable()->defaultSort('asc'),
        Column::name('created_at')->label('Fecha de creacion')->searchable()->filterable()->defaultSort('asc'),
        Column::name('updated_at')->label('Fecha de Modificación')->searchable()->filterable()->defaultSort('asc'),

        ];
    }

    public function mostrarAuditorias($id)
    {
        $this->auditorias = Audit::where('auditable_id', $id)->where('auditable_type', Objetivo::class)->get()->toArray();
        $this->emit('enviarAuditorias', $this->auditorias);
    }
}