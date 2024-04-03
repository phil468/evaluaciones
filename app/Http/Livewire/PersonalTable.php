<?php

namespace App\Http\Livewire;

use App\Models\Area;
use App\Models\Cargo;
use App\Models\Empresa;
use App\Models\Gerencia;
use App\Models\Personal;
use App\Models\Planilla;
use App\Models\Sede;
use App\Models\TipoDePersonal;
use App\Models\TipoDeTrabajador;
use Mediconesystems\LivewireDatatables\BooleanColumn;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Exports\DatatableExport;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\NumberColumn;

class PersonalTable extends LivewireDatatable
{
    public $hideable = 'inline';
    public $exportable = true;
    public $afterTableSlot = 'components.selected';
    public $numeroSerieValidado=true, $fileUpload;
    public $updateMode = false;
    public $selected = [];
    // public $showColumns = ['id', 'dni']; // Columnas predeterminadas
    public $listaParaAgregar = false;

    protected $listeners = ['closeModal' => '$refresh','limpiarSeleccionPersonalTable'=>'limpiarSeleccionPersonalTable'];

    public function limpiarSeleccionPersonalTable()
    {
        // dd('hola');
        // Limpiar la selección en el componente PersonalTable
        $this->selected = [];
        // $this->selected = [];
    }

    public function updatedSelected($value)
    {
        $this->emitUp('selectedUpdated', $value);
        // dd($value);
    }
    
    public function toggleSelectAll()
    {
        if (count($this->selected) === $this->getQuery()->count()) {
            $this->selected = [];
        } else {
            $this->selected = $this->checkboxQuery()->values()->toArray();
        }
        $this->forgetComputed();
        
        $this->emitUp('selectedUpdated', $this->selected);
    }

    public function builder()
    {
        // if(Personal::all()->count()){
        //     return Personal::query()
        //         ->leftJoin('empresas', function ($join) {
        //             $join->on('personal.empresa_id', '=', 'empresas.id');
        //         })

        //         ->leftJoin('sedes', function ($join) {
        //             $join->on('personal.sede_id', '=', 'sedes.id');
        //         })

        //         ->leftJoin('gerencias', function ($join) {
        //             $join->on('personal.gerencia_id', '=', 'gerencias.id');
        //         })

        //         ->leftJoin('areas', function ($join) {
        //             $join->on('personal.area_id', '=', 'areas.id');
        //         })

        //         ->leftJoin('cargos', function ($join) {
        //             $join->on('personal.cargo_id', '=', 'cargos.id');
        //         })
        //     ;
        // }
        // else 
        // {
            // return Personal::query()
            return Personal::query()
            ->leftJoin('empresas', 'empresas.id', 'personal.empresa_id')
            ->leftJoin('gerencias', 'gerencias.id', 'personal.gerencia_id')
            ->leftJoin('sedes', 'sedes.id', 'personal.sede_id')
            ->leftJoin('areas', 'areas.id', 'personal.area_id')
            ->leftJoin('cargos', 'cargos.id', 'personal.cargo_id')
            ->leftJoin('planillas', 'planillas.id', 'personal.planilla_id')
            ->leftJoin('tipo_de_trabajador', 'tipo_de_trabajador.id', 'personal.tipo_de_trabajador_id')
            ->leftJoin('tipo_de_personal', 'tipo_de_personal.id', 'personal.tipo_de_personal_id')
            ;
        // }
    }

    public $model = Personal::class;

    public function columns()
    {
        if ($this->listaParaAgregar) {
            $columns = [
                Column::checkbox()
                ->label('Add'),

                NumberColumn::name('id')
                    ->label('ID')
                    ->defaultSort('DESC')
                    ->searchable()
                    ->hideable()
                    ->sortBy('id'),

                Column::name('dni')
                    ->filterable('dni')
                    ->searchable()
                    ->hideable()
                    ->label('dni')
                    ->sortBy('dni'),

                Column::name('name')
                    ->filterable('name')
                    ->searchable()
                    ->hideable()
                    ->label('nombre completo')
                    ->sortBy('name'),

                BooleanColumn::name('estado')
                    ->filterable('estado')
                    ->searchable()
                    ->hideable()
                    ->label('estado')
                    ->sortBy('estado')
                    ->exportCallback(function ($var) {
                        return $age = $var == 1 ? 'HABILITADO' :null;
                    }),

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
                    ->label('area'),
    
                Column::name('sede.name')
                    ->filterable($this->sedes)
                    ->searchable()
                    ->sortBy('sedes.name')
                    ->hideable()
                    ->label('sede'),
    
                Column::name('cargo.name')
                    ->filterable($this->cargos)
                    ->searchable()
                    ->sortBy('cargos.name')
                    ->hideable()
                    ->label('cargo'),
    
                Column::name('planilla.name')
                    ->filterable($this->planillas)
                    ->searchable()
                    ->sortBy('planillas.name')
                    ->hideable()
                    ->label('planilla'),
    
                Column::name('tipo_trabajador.name')
                    ->filterable($this->tipo_trabajador)
                    ->searchable()
                    ->sortBy('tipo_trabajador.name')
                    ->hideable()
                    ->label('Tipo de Trabajador'),
                    
                Column::name('tipo_personal.name')
                ->filterable($this->tipo_personal)
                ->searchable()
                ->sortBy('tipo_personal.name')
                ->hideable()
                ->label('Tipo de Personal'),
                
                DateColumn::name('fecha_ingreso')
                    ->filterable('fecha_ingreso')
                    ->searchable()
                    ->hideable()
                    ->label('fecha ingreso')
                    ->sortBy('fecha_ingreso'),
                    
                Column::name('SEXO')
                ->filterable(['M','F',''])
                ->searchable()
                ->hideable()
                ->label('SEXO')
                ->sortBy('SEXO'),
            ];

        } else {
        $columns = [
            Column::checkbox()
            ->label('Add')
            ,

            NumberColumn::name('id')
                ->label('ID')
                ->defaultSort('DESC')
                ->searchable()
                ->hideable()
                ->sortBy('id'),

            Column::callback('id,name', function ($id,$name) {
                return view('livewire.personals.table-actions', ['id' => $id, 'name'=>$name]);
            })->unsortable()
            ->label('Acciones')
            ->excludeFromExport()
            ->hide(),           

            Column::name('dni')
                ->filterable('dni')
                ->searchable()
                ->hideable()
                ->label('dni')
                ->sortBy('dni'),
            
            Column::name('name')
                ->filterable('name')
                ->searchable()
                ->hideable()
                ->label('nombre completo')
                ->sortBy('name'),
                
            Column::name('nombres')
            ->filterable('nombres')
            ->searchable()
            ->hideable()
            ->label('nombres')
            ->sortBy('nombres'),
                
            Column::name('apellido_paterno')
                ->filterable('apellido_paterno')
                ->searchable()
                ->hideable()
                ->label('apellido paterno')
                ->sortBy('apellido_paterno'),
            
            Column::name('apellido_materno')
                ->filterable('apellido_materno')
                ->searchable()
                ->hideable()
                ->label('apellido materno')
                ->sortBy('apellido_materno'),
            
            BooleanColumn::name('estado')
                ->filterable('estado')
                ->searchable()
                ->hideable()
                ->label('estado')
                ->sortBy('estado')
                ->exportCallback(function ($var) {
                    return $age = $var == 1 ? 'HABILITADO' :null;
                }),

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
                ->label('area'),

            Column::name('sede.name')
                ->filterable($this->sedes)
                ->searchable()
                ->sortBy('sedes.name')
                ->hideable()
                ->label('sede'),

            Column::name('cargo.name')
                ->filterable($this->cargos)
                ->searchable()
                ->sortBy('cargos.name')
                ->hideable()
                ->label('cargo'),

            Column::name('planilla.name')
                ->filterable($this->planillas)
                ->searchable()
                ->sortBy('planillas.name')
                ->hideable()
                ->label('planilla'),

            Column::name('tipo_trabajador.name')
                ->filterable($this->tipo_trabajador)
                ->searchable()
                ->sortBy('tipo_trabajador.name')
                ->hideable()
                ->label('Tipo de Trabajador'),
                
            Column::name('tipo_personal.name')
            ->filterable($this->tipo_personal)
            ->searchable()
            ->sortBy('tipo_personal.name')
            ->hideable()
            ->label('Tipo de Personal'),

            // Column::name('correo_empresa')
            //     ->filterable('correo_empresa')
            //     ->searchable()
            //     ->hideable()
            //     ->label('correo cmpresa')
            //     ->sortBy('correo_empresa'),
            
            // Column::name('celular_empresa')
            // ->filterable('celular_empresa')
            // ->searchable()
            // ->hideable()
            // ->label('celular empresa')
            // ->sortBy('celular_empresa'),
                
            // Column::name('correo_personal')
            // ->filterable('correo_personal')
            // ->searchable()
            // ->hideable()
            // ->label('correo personal')
            // ->sortBy('correo_personal'),
            
            // Column::name('telefono_personal')
            //     ->filterable('telefono_personal')
            //     ->searchable()
            //     ->hideable()
            //     ->label('telefono personal')
            //     ->sortBy('telefono_personal'),
                
            // Column::name('celular_personal')
            // ->filterable('celular_personal')
            // ->searchable()
            // ->hideable()
            // ->label('celular personal')
            // ->sortBy('celular_personal'),
            
            DateColumn::name('fecha_ingreso')
                ->filterable('fecha_ingreso')
                ->searchable()
                ->hideable()
                ->label('fecha ingreso')
                ->sortBy('fecha_ingreso'),
                

                Column::name('SEXO')
                ->filterable(['M','F',''])
                ->searchable()
                ->hideable()
                ->label('SEXO')
                ->sortBy('SEXO'),
        ];
        }
        // Filtrar columnas según $showColumns
        // $filteredColumns = collect($columns)->only($this->showColumns)->all();
// Filtrar columnas según $visibleColumns
// $filteredColumns = collect($columns)->filter(function ($column) {
//     // dd($column->base);
//     $columnName = $column->base;
//     return isset($this->visibleColumns[$columnName]) ? $this->visibleColumns[$columnName] : true;
// })->all();

// return $filteredColumns;
        return $columns;
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
    
    public function getPlanillasProperty()
    {
        return Planilla::orderBy('name')->pluck('name');
    }

    public function getTipoTrabajadorProperty()
    {
        return TipoDeTrabajador::orderBy('name')->pluck('name');
    }
    
    public function getTipoPersonalProperty()
    {
        return TipoDePersonal::orderBy('name')->pluck('name');
    }
    

    // public function getBrandsProperty()
    // {
    //     return Brand::orderBy('name')->pluck('name');
    // }

    // public function getModelsProperty()
    // {
    //     return Componente::orderBy('model')->groupBy('model')->pluck('model');
    // }

    // public function getComponentTypesProperty()
    // {
    //     return ComponentType::orderBy('name')->pluck('name');
    // }

    // public function getComponentPerformancesProperty()
    // {
    //     return ComponentPerformance::orderBy('name')->pluck('name');
    // }

    // public function getComponentStatusesProperty()
    // {
    //     return ComponentStatus::orderBy('name')->pluck('name');
    // }

    // public function getCpusProperty()
    // {
    //     return Cpu::orderBy('name')->pluck('name');
    //}

    
    public function export()
    {
        $this->forgetComputed();

        $export = new DatatableExport($this->getExportResultsSet());

        $export->setFileName('Personal.xlsx');
        return $export->download();
    }

    public function edit($id)
    {
        $this->emit('edit', $id);
    }

    public function destroy($id)
    {
        if ($id) {
            $record = Personal::where('id', $id);
            $record->delete();
        }
    }
}