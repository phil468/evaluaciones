<?php

namespace App\Http\Livewire;

use App\Models\CapacitacionHasPersonal;
use App\Models\Personal;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class RegistrosTable extends LivewireDatatable
{
    public $capacitacion_id,
        $selected_personal_id,
        $sede_id,
        $gerencia_id,
        $subgerencia_id,
        $area_id;

        
    protected $listeners = [
        'closeModal' => '$refresh',
        'refrescarRegistroTable' => '$refresh'
    ];

    public function builder()
    {
        return CapacitacionHasPersonal::query()->where('capacitacion_id', $this->capacitacion_id);
    }

    public function columns()
    {
        return [
            Column::callback('id,personal.name', function ($id,$name) {
                return view('livewire.capacitacion-has-personals.table-actions', ['id' => $id, 'name'=>$name]);
            })->unsortable()
            ->label('Acciones')
            ->excludeFromExport(),
            // Column::name('id')->label('ID'),
            Column::name('personal.dni')->label('DNI')->sortable()->searchable(),
            Column::name('personal.name')->label('Nombre del Personal')->sortable()->searchable()
            ->defaultSort('ASC'),
            Column::name('empresa.name')->label('Empresa')->sortable()->searchable(),
            Column::name('gerencia.name')->label('Gerencia')->sortable()->searchable(),
            Column::name('sede.name')->label('Sede')->sortable()->searchable(),
            // Column::name('subgerencia.name')->label('Subgerencia')->sortable()->searchable(),
            Column::name('area.name')->label('Área')->sortable()->searchable(),
            Column::name('cargo.name')->label('Cargo')->sortable()->searchable(),
            Column::name('planilla.name')->label('Planilla')->sortable()->searchable(),
            Column::name('tipo_de_trabajador.name')->label('Tipo de Trabajador')->sortable()->searchable(),
            Column::name('tipo_de_personal.name')->label('Tipo de Personal')->sortable()->searchable(),
            // Column::name('capacitacion_has_personal.name')->label('Capacitación')->sortable()->searchable(),
            // ->hide(),
            // Column::name('capacitacion')->label('Nombre de la Capacitación'),
            // Agrega más columnas según tus necesidades
            // Column::callback(['id'], function ($id) {
            //     return view('livewire.capacitacion-has-personal-table-actions', ['id' => $id]);
            // })->label('Acciones')->unsortable()->excludeFromExport(),
        ];
    }

    public function edit($id)
    {
        $this->emit('edit', $id);
    }

    public function destroy($id)
    {
        if ($id) {
            $record = CapacitacionHasPersonal::where('id', $id);
            $record->delete();
        }
    }
}