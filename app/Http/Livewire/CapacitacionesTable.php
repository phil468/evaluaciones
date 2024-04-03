<?php

namespace App\Http\Livewire;


use App\Models\Area;
use App\Models\Capacitacione;
use App\Models\Cargo;
use App\Models\Devolucion;
use App\Models\Empresa;
use App\Models\Gerencia;
use App\Models\Modalidade;
use App\Models\Personal;
use App\Models\Sede;
use App\Models\TipoDeCapacitacione;
use Illuminate\Support\Facades\Storage;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\NumberColumn;

class CapacitacionesTable extends LivewireDatatable
{
    public $hideable = 'inline';
    public $exportable = true;
    public $afterTableSlot = 'components.selected';
    public $numeroSerieValidado=true, $fileUpload;
    public $updateMode = false;

    protected $listeners = ['closeModal' => '$refresh', 'close' => '$refresh'];

    public function builder()
    {
        return Capacitacione::query()
        ->with('personal')
        ->leftJoin('empresas', 'empresas.id', 'capacitaciones.empresa_id')
        ->leftJoin('tipo_de_capacitaciones', 'tipo_de_capacitaciones.id', 'capacitaciones.capacitaciones_tipo_id')
        ->leftJoin('temas', 'temas.id', 'capacitaciones.tema_id')
        ->leftJoin('sedes', 'sedes.id', 'capacitaciones.sede_id')
        ->leftJoin('personal as expositores', 'expositores.id', 'capacitaciones.expositor_id')
        ->leftJoin('cargos as cargo_expositores', 'cargo_expositores.id', 'capacitaciones.cargo_expositor_id')
        ->leftJoin('personal as registradores', 'registradores.id', 'capacitaciones.registrador_id')
        ->leftJoin('cargos as cargo_registradores', 'cargo_registradores.id', 'capacitaciones.cargo_registrador_id')
        ->leftJoin('statuses', 'statuses.id', 'capacitaciones.status_id')
        ->leftJoin('modalidades', 'modalidades.id', 'capacitaciones.modalidad_id');

    }

    public $model = Capacitacione::class;

    public function columns()
    {
        return [
            NumberColumn::name('id')
                ->filterable()
                ->label('ID')
                ->defaultSort('DESC')
                ->searchable()
                ->hideable()
                ->sortBy('id'),
                
            Column::callback('id', function ($id) {
                return view('table-actions', ['id' => $id]);
            })->unsortable()
            ->label('Acciones')
            ->excludeFromExport(),

            Column::name('tipo_de_capacitaciones.name')
                ->filterable($this->tipo_de_capacitaciones)
                ->searchable()
                ->sortBy('tipo_de_capacitaciones.name')
                ->hideable()
                ->label('Tipo de capacitación'),
            
            Column::name('temas.name')
                ->filterable()
                ->searchable()
                ->sortBy('temas.name')
                ->hideable()
                ->label('Tema'),

            // Column::callback('id,pdf', function ($id,$pdf) {
            //     return view('table-actions-2', ['id' => $id, 'name'=>'','pdf'=>$pdf]);
            // })->unsortable()
            // ->label('acciones')
            // ->excludeFromExport(),

            DateColumn::name('fecha_capacitacion')
                ->label('Fecha')
                ->filterable()
                ->searchable()
                ->hideable()
                ->sortBy('fecha_capacitacion'),
                
            Column::name('empresa.name')
                ->filterable($this->empresas)
                ->searchable()
                ->sortBy('empresas.name')
                ->hideable()
                ->label('Empresa'),

            // Column::name('expositores.dni')
            //     ->filterable()
            //     ->searchable()
            //     ->sortBy('expositores.dni')
            //     ->hideable()
            //     ->label('dni'),

            Column::name('modalidades.name')
                ->filterable($this->modalidades)
                ->searchable()
                ->sortBy('modalidades.name')
                ->hideable()
                ->label('Modalidad'),

            Column::name('expositores.name')
                ->filterable($this->expositores)
                ->searchable()
                ->sortBy('expositores.name')
                ->hideable()
                ->label('Expositor'),

            Column::name('cargo_expositores.name')
                ->filterable($this->cargos)
                ->searchable()
                ->sortBy('cargo_expositores.name')
                ->hideable()
                ->label('Cargo de expositor'),
            
            //columna expositor externo
            Column::name('nombre_expositor_externo')
                ->filterable()
                ->searchable()
                ->sortBy('nombre_expositor_externo')
                ->hideable()
                ->label('Expositor externo'),
            
            // Column::
            //     callback('id', function ($id) {
            //         $devolucion = Devolucion::find($id);
            //         $descripcion = "";
            //         $i = 1;
            //         foreach ($devolucion->activos as $activo) {
            //             $descripcion = $descripcion."<b>".$i.")</b> ".$activo->descripcion."<br><br>";
            //             $i++;
            //         }
            //         return $descripcion;
            //     })
            //     ->filterable()
            //     ->searchable()
            //     ->hideable()
            //     ->label('activos'),

            // Column::name('area.name')
            //     ->filterable($this->areas)
            //     ->searchable()
            //     ->sortBy('areas.name')
            //     ->hideable()
            //     ->label('areas'),

            Column::name('sede.name')
                ->filterable($this->sedes)
                ->searchable()
                ->sortBy('sedes.name')
                ->hideable()
                ->label('Sede'),

            Column::name('registradores.name')
                ->filterable($this->registradores)
                ->searchable()
                ->sortBy('registradores.name')
                ->hideable()
                ->label('Registrador'),
            
            Column::name('cargo_registradores.name')
                ->filterable($this->registrador_cargos)
                ->searchable()
                ->sortBy('cargo_registradores.name')
                ->hideable()
                ->label('Cargo de registrador'),

            NumberColumn::name('cantidad_de_sesiones')
                ->label('Sesiones')
                ->filterable()
                ->searchable()
                ->hideable()
                ->sortBy('cantidad_de_sesiones')
                ->alignCenter(),

            Column::callback('id,cantidad_de_sesiones', function ($id) {
                    $capacitacion = Capacitacione::find($id);
                    return $capacitacion->cantidad_personas;
                    //return view('table-actions', ['id' => $id]);
                })
                ->label('Registrados')
                // ->filterable()
                ->searchable()
                // ->sortBy('id,cantidad_de_sesiones')
                ->hideable(),
                

            // Column::name('responsable_areas.name')
            //     ->filterable($this->responsable_areas)
            //     ->searchable()
            //     ->sortBy('responsable_areas.name')
            //     ->hideable()
            //     ->label('responsable area'),

            // Column::name('registrador_cargos.name')
            //     ->filterable($this->registrador_cargos)
            //     ->searchable()
            //     ->sortBy('registrador_cargos.name')
            //     ->hideable()
            //     ->label('registrador cargo'),

                
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

    public function getModalidadesProperty()
    {
        return Modalidade::orderBy('name')->pluck('name');
    }

    // public function getGerenciasProperty()
    // {
    //     return Gerencia::orderBy('name')->pluck('name');
    // }

    public function getAreasProperty()
    {
        return Area::orderBy('name')->pluck('name');
    }

    public function getCargosProperty()
    {
        return Cargo::orderBy('name')->pluck('name');
    }
    
    public function getRegistradoresProperty()
    {
        $responsable = Capacitacione::pluck('registrador_id')->toArray();
        return Personal::whereIn('id',$responsable)->orderBy('name')->pluck('name');
    }

    public function getExpositoresProperty()
    {
        $exdpositor = Capacitacione::pluck('expositor_id')->toArray();
        return Personal::whereIn('id',$exdpositor)->orderBy('name')->pluck('name');
    }

    // public function getResponsableAreasProperty()
    // {
    //     $areas = Capacitacione::pluck('cargo_registrador_id')->toArray();
    //     return Area::whereIn('id',$areas)->orderBy('name')->pluck('name');
    // }
    
    public function getTipoDeCapacitacionesProperty()
    {
        $tipoDeCapacitaciones = Capacitacione::pluck('capacitaciones_tipo_id')->toArray();
        return TipoDeCapacitacione::whereIn('id',$tipoDeCapacitaciones)->orderBy('name')->pluck('name');
    }

    public function getRegistradorCargosProperty()
    {
        $cargos = Capacitacione::pluck('cargo_registrador_id')->toArray();
        return Cargo::whereIn('id',$cargos)->orderBy('name')->pluck('name');
    }

    public function edit($id)
    {
        $this->emit('edit', $id);
    }

//     public function descargarPDF($pdf)
//     {
//         return Storage::disk('public')->download($pdf);

// //        $this->emit('descargarPDF', $pdf);
//     }

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