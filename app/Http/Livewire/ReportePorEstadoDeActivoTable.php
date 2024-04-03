<?php

namespace App\Http\Livewire;


use App\Models\Asignacione;
use App\Models\Area;
use App\Models\AsignacionHasActivo;
use App\Models\Cargo;
use App\Models\DevolucionHasActivo;
use App\Models\Empresa;
use App\Models\Gerencia;
use App\Models\Personal;
use App\Models\Sede;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Mediconesystems\LivewireDatatables\BooleanColumn;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\NumberColumn;

class ReportePorEstadoDeActivoTable extends LivewireDatatable
{
    public $hideable = 'inline';
    public $exportable = true;
    public $afterTableSlot = 'components.selected';
    public $numeroSerieValidado=true, $fileUpload;
    public $updateMode = false;

    protected $listeners = ['closeModal' => '$refresh', 'close' => '$refresh'];

    public function builder()
    {
        // $responsable = Asignacione::pluck('responsable_id')->toArray();
        // dd($responsable);
        $asignaciones = AsignacionHasActivo::select(
            // 'asignaciones.id as ID',
            DB::raw('CONCAT("Entrega-",asignaciones.id) as ID'),
            DB::raw('"Entrega" as tipo_de_acta')
        )
            ->leftJoin('asignaciones', 'asignaciones.id', 'asignacion_has_activos.asignacion_id')        
        ->leftJoin('activos', 'activos.id', 'asignacion_has_activos.activo_id');

        // dd($asignaciones);

        $devoluciones = DevolucionHasActivo::select(
            'devoluciones.id',
            DB::raw(
                '"Devolucion" as tipo_de_acta',
            ))->leftJoin('devoluciones', 'devoluciones.id', 'devolucion_has_activos.devolucion_id')        
        ->leftJoin('activos', 'activos.id', 'devolucion_has_activos.activo_id');

        
        $registros = $asignaciones->unionAll($devoluciones);

        // dd($registros->get()->toArray());

        return $registros;

        // return Asignacione::query()
        // ->leftJoin('personal', 'personal.id', 'asignaciones.personal_id')
        // ->leftJoin('empresas', 'empresas.id', 'asignaciones.empresa_id')
        // ->leftJoin('gerencias', 'gerencias.id', 'asignaciones.gerencia_id')
        // ->leftJoin('areas', 'areas.id', 'asignaciones.area_id')
        // ->leftJoin('sedes', 'sedes.id', 'asignaciones.sede_id')
        // ->leftJoin('cargos', 'cargos.id', 'asignaciones.cargo_id')
        // ->leftJoin('asignacion_has_activos', 'asignacion_has_activos.asignacion_id', 'asignaciones.id')
        // ->leftJoin('activos', 'activos.id', 'asignacion_has_activos.activo_id')
        // ->leftJoin('personal as responsables', 'responsables.id', 'asignaciones.responsable_id')
        // ->leftJoin('areas as responsable_areas', 'responsable_areas.id', 'asignaciones.responsable_area_id')
        // ->leftJoin('cargos as responsable_cargos', 'responsable_cargos.id', 'asignaciones.responsable_cargo_id');
    }

    public $model = Asignacione::class;

    public function columns()
    {
        return [
            Column::raw('if(tipo_de_acta = "Entrega", "Entrega", if(tipo_de_acta = "Devolucion", "Devolucion", "No identificado")) as `Tipo`')
            // ->filterable($this->personal)
            // ->defaultSort('DESC')
            // ->filterable()
            // ->searchable()
            // ->sortBy('Tipo')
            ->hideable()
            ->label('Tipo'),

            // NumberColumn::name('id')
            //     ->label('ID')
            //     ->defaultSort('DESC')
            //     ->searchable()
            //     ->hideable()
            //     ->sortBy('id'),

            // Column::callback('id,pdf', function ($id,$pdf) {
            //     return view('table-actions-2', ['id' => $id, 'name'=>'','pdf'=>$pdf]);
            // })->unsortable()
            // ->label('Acciones')
            // ->excludeFromExport(),

            // DateColumn::name('fecha')
            //     ->label('Fecha')
            //     ->filterable()
            //     ->searchable()
            //     ->hideable()
            //     ->sortBy('fecha'),

            //     Column::name('personal.dni')
            //     ->filterable()
            //     ->searchable()
            //     ->sortBy('personal.dni')
            //     ->hideable()
            //     ->label('DNI'),

            // Column::name('personal.name')
            //     ->filterable($this->personal)
            //     ->searchable()
            //     ->sortBy('personal.name')
            //     ->hideable()
            //     ->label('Personal'),

            // Column::name('empresa.name')
            //     ->filterable($this->empresas)
            //     ->searchable()
            //     ->sortBy('empresas.name')
            //     ->hideable()
            //     ->label('Empresa'),

            // Column::name('gerencia.name')
            //     ->filterable($this->gerencias)
            //     ->searchable()
            //     ->sortBy('gerencias.name')
            //     ->hideable()
            //     ->label('Gerencia'),

            // Column::name('area.name')
            //     ->filterable($this->areas)
            //     ->searchable()
            //     ->sortBy('areas.name')
            //     ->hideable()
            //     ->label('Áreas'),

            // Column::name('sede.name')
            //     ->filterable($this->sedes)
            //     ->searchable()
            //     ->sortBy('sedes.name')
            //     ->hideable()
            //     ->label('Sedes'),

            // Column::name('cargo.name')
            //     ->filterable($this->cargos)
            //     ->searchable()
            //     ->sortBy('cargos.name')
            //     ->hideable()
            //     ->label('Cargo'),

            // Column::name('responsables.name')
            //     ->filterable($this->responsables)
            //     ->searchable()
            //     ->sortBy('responsables.name')
            //     ->hideable()
            //     ->label('Responsable'),
                
            // Column::name('responsable_areas.name')
            //     ->filterable($this->responsable_areas)
            //     ->searchable()
            //     ->sortBy('responsable_areas.name')
            //     ->hideable()
            //     ->label('Responsable Area'),

            // Column::name('responsable_cargos.name')
            //     ->filterable($this->responsable_cargos)
            //     ->searchable()
            //     ->sortBy('responsable_cargos.name')
            //     ->hideable()
            //     ->label('Responsable Cargo'),

            //     Column::name('activos.serial_number')
            //     // ->filterable($this->personal)
            //     ->filterable()
            //     ->searchable()
            //     ->sortBy('activos.serial_number')
            //     ->hideable()
            //     ->label('S/N'),
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
        $responsable = Asignacione::pluck('responsable_id')->toArray();
        return Personal::whereIn('id',$responsable)->orderBy('name')->pluck('name');
    }

    public function getResponsableAreasProperty()
    {
        $areas = Asignacione::pluck('responsable_area_id')->toArray();
        return Area::whereIn('id',$areas)->orderBy('name')->pluck('name');
    }

    public function getResponsableCargosProperty()
    {
        $cargos = Asignacione::pluck('responsable_cargo_id')->toArray();
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
            $record = Asignacione::where('id', $id);
            $record->timestamps = false;
            $record->first()->update([ 
                'deleted_by' => auth()->user()->id
                ]);
            $record->timestamps = true;
            $record->delete();
        }
    }

}