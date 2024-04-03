<?php

namespace App\Http\Livewire;


use App\Models\Area;
use App\Models\Cargo;
use App\Models\Devolucion;
use App\Models\Empresa;
use App\Models\Gerencia;
use App\Models\Personal;
use App\Models\Sede;
use Illuminate\Support\Facades\Storage;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\NumberColumn;

class DevolucionTable extends LivewireDatatable
{
    public $hideable = 'inline';
    public $exportable = true;
    public $afterTableSlot = 'components.selected';
    public $numeroSerieValidado=true, $fileUpload;
    public $updateMode = false;

    protected $listeners = ['closeModal' => '$refresh', 'close' => '$refresh'];

    public function builder()
    {
        return Devolucion::query()
        ->leftJoin('personal', 'personal.id', 'devoluciones.personal_id')
        ->leftJoin('empresas', 'empresas.id', 'devoluciones.empresa_id')
        ->leftJoin('gerencias', 'gerencias.id', 'devoluciones.gerencia_id')
        ->leftJoin('areas', 'areas.id', 'devoluciones.area_id')
        ->leftJoin('sedes', 'sedes.id', 'devoluciones.sede_id')
        ->leftJoin('cargos', 'cargos.id', 'devoluciones.cargo_id')
        ->leftJoin('personal as responsables', 'responsables.id', 'devoluciones.responsable_id')
        ->leftJoin('areas as responsable_areas', 'responsable_areas.id', 'devoluciones.responsable_area_id')
        ->leftJoin('cargos as responsable_cargos', 'responsable_cargos.id', 'devoluciones.responsable_cargo_id');
    }

    public $model = Devolucion::class;

    public function columns()
    {
        return [

            NumberColumn::name('id')
                ->label('id')
                ->defaultSort('DESC')
                ->searchable()
                ->hideable()
                ->sortBy('id'),

            Column::callback('id,pdf', function ($id,$pdf) {
                return view('table-actions-2', ['id' => $id, 'name'=>'','pdf'=>$pdf]);
            })->unsortable()
            ->label('acciones')
            ->excludeFromExport(),

            DateColumn::name('fecha')
                ->label('fecha')
                ->filterable()
                ->searchable()
                ->hideable()
                ->sortBy('fecha'),

                Column::name('personal.dni')
                ->filterable()
                ->searchable()
                ->sortBy('personal.dni')
                ->hideable()
                ->label('dni'),

            Column::name('personal.name')
                ->filterable($this->personal)
                ->searchable()
                ->sortBy('personal.name')
                ->hideable()
                ->label('personal'),

            Column::
                callback('id', function ($id) {
                    $devolucion = Devolucion::find($id);
                    $descripcion = "";
                    $i = 1;
                    foreach ($devolucion->activos as $activo) {
                        $descripcion = $descripcion."<b>".$i.")</b> ".$activo->descripcion."<br><br>";
                        $i++;
                    }
                    return $descripcion;
                })
                ->filterable()
                ->searchable()
                ->hideable()
                ->label('activos'),

            Column::name('empresa.name')
                ->filterable($this->empresas)
                ->searchable()
                ->sortBy('empresas.name')
                ->hideable()
                ->label('empresa'),

            Column::name('gerencia.name')
                ->filterable($this->gerencias)
                ->searchable()
                ->sortBy('gerencias.name')
                ->hideable()
                ->label('gerencia'),

            Column::name('area.name')
                ->filterable($this->areas)
                ->searchable()
                ->sortBy('areas.name')
                ->hideable()
                ->label('areas'),

            Column::name('sede.name')
                ->filterable($this->sedes)
                ->searchable()
                ->sortBy('sedes.name')
                ->hideable()
                ->label('sedes'),

            Column::name('cargo.name')
                ->filterable($this->cargos)
                ->searchable()
                ->sortBy('cargos.name')
                ->hideable()
                ->label('cargo'),

            Column::name('responsables.name')
                ->filterable($this->responsables)
                ->searchable()
                ->sortBy('responsables.name')
                ->hideable()
                ->label('responsable'),
                
            Column::name('responsable_areas.name')
                ->filterable($this->responsable_areas)
                ->searchable()
                ->sortBy('responsable_areas.name')
                ->hideable()
                ->label('responsable area'),

            Column::name('responsable_cargos.name')
                ->filterable($this->responsable_cargos)
                ->searchable()
                ->sortBy('responsable_cargos.name')
                ->hideable()
                ->label('responsable cargo'),

                
            // Column::callback('id', function ($id) {
            //     return view('table-actions', ['id' => $id, 'name'=>'']);
            // })->unsortable()
            // ->label('Acciones')
            // ->excludeFromExport(),

        ];
    }

    public function getPersonalProperty()
    {
        return Personal::orderBy('name')->pluck('name');
    }

    public function getSedesProperty()
    {
        return Sede::orderBy('name')->pluck('name');
    }

    public function getEmpresasProperty()
    {
        return Empresa::orderBy('name')->pluck('name');
    }

    public function getGerenciasProperty()
    {
        return Gerencia::orderBy('name')->pluck('name');
    }

    public function getAreasProperty()
    {
        return Area::orderBy('name')->pluck('name');
    }

    public function getCargosProperty()
    {
        return Cargo::orderBy('name')->pluck('name');
    }
    
    public function getResponsablesProperty()
    {
        $responsable = Devolucion::pluck('responsable_id')->toArray();
        return Personal::whereIn('id',$responsable)->orderBy('name')->pluck('name');
    }

    public function getResponsableAreasProperty()
    {
        $areas = Devolucion::pluck('responsable_area_id')->toArray();
        return Area::whereIn('id',$areas)->orderBy('name')->pluck('name');
    }

    public function getResponsableCargosProperty()
    {
        $cargos = Devolucion::pluck('responsable_cargo_id')->toArray();
        return Cargo::whereIn('id',$cargos)->orderBy('name')->pluck('name');
    }

    public function edit($id)
    {
        $this->emit('edit', $id);
    }

    public function descargarPDF($pdf)
    {
        return Storage::disk('public')->download($pdf);

//        $this->emit('descargarPDF', $pdf);
    }

    public function destroy($id)
    {
        if ($id) {
            $record = Devolucion::where('id', $id);
            $record->timestamps = false;
            $record->first()->update([ 
                'deleted_by' => auth()->user()->id
                ]);
            $record->timestamps = true;
            $record->delete();
        }
    }

}