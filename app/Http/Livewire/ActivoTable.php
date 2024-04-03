<?php

namespace App\Http\Livewire;

use App\Models\Activo;
use App\Models\ActivoTipo;
use App\Models\Area;
use App\Models\BajaMotivo;
use App\Models\Brand;
use App\Models\Modelo;
use App\Models\Performance;
use App\Models\Personal;
use App\Models\Status;
use App\Models\Vigencium;
use Maatwebsite\Excel\Facades\Excel;
use Mediconesystems\LivewireDatatables\Action;
use Mediconesystems\LivewireDatatables\BooleanColumn;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Exports\DatatableExport;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\NumberColumn;

class ActivoTable extends LivewireDatatable
{
    public $hideable = 'inline';
    public $exportable = true;
    // public $params = ['fileName'=>'Activos'];
    // public $filename = 'Activos';
    public $export_name = 'Activos';
    public $afterTableSlot = 'components.selected';
    public $numeroSerieValidado=true, $fileUpload;
    public $updateMode = false;

    protected $listeners = ['closeModal' => '$refresh'];

    // public function buildActions()
    // {
    //     return [

    //         // Action::value('edit')->label('Edit Selected')->group('Default Options')->callback(function ($mode, $items) {
    //         //     // $items contains an array with the primary keys of the selected items
    //         // }),

    //         // Action::value('update')->label('Update Selected')->group('Default Options')->callback(function ($mode, $items) {
    //         //     // $items contains an array with the primary keys of the selected items
    //         // }),

    //         Action::groupBy('Export Options', function () {
    //             return [
    //                 Action::value('csv')->label('Export CSV')->export('SalesOrders.csv'),
    //                 Action::value('html')->label('Export HTML')->export('SalesOrders.html'),
    //                 Action::value('xlsx')->label('Export XLSX')->export('SalesOrders.xlsx')//->styles($this->exportStyles)->widths($this->exportWidths)
    //             ];
    //         }),
    //     ];
    // }
    // public function export() {
    //     return Excel::download(new DatatableExport($model),"newFileName.xls");
    // }

    public function export()
    {
        $this->forgetComputed();

        $export = new DatatableExport($this->getExportResultsSet());

        $export->setFileName('Activos.xlsx');
        return $export->download();
    }

    public function builder()
    {
            return Activo::query()
            ->leftJoin('activo_tipos', 'activo_tipos.id', 'activos.activo_tipo_id')
            ->leftJoin('statuses', 'statuses.id', 'activos.status_id')
            ->leftJoin('brands', 'brands.id', 'activos.brand_id')
            ->leftJoin('modelos', 'modelos.id', 'activos.modelo_id')
            ->leftJoin('performances', 'performances.id', 'activos.performance_id')
            ->leftJoin('personal', 'personal.id', 'activos.personal_id')
            ->leftJoin('areas', 'areas.id', 'activos.area_id')
            ->leftJoin('vigencia', 'vigencia.id', 'activos.vigencia_id')
            ->leftJoin('baja_motivos', 'baja_motivos.id', 'activos.baja_motivo_id')
            ->leftJoin('activos as cts', 'cts.id', 'activos.ct_id')
            // ->with('ct as ct')
            ;
    }

    public $model = Activo::class;

    public function columns()
    {
        return [
            NumberColumn::name('id')
                ->filterable('id')
                ->label('id')
                ->defaultSort('DESC')
                ->searchable()
                ->hideable()
                ->sortBy('id'),

            Column::callback('id,serial_number', function ($id,$serial_number) {
                return view('table-actions', ['id' => $id, 'name'=>$serial_number]);
            })->unsortable()
            ->label('Acciones')
            ->excludeFromExport(),

            BooleanColumn::name('estado')
                ->filterable('estado')
                ->searchable()
                ->hideable()
                ->label('habilitado')
                ->sortBy('estado')
                ->sortable()
                ->exportCallback(function ($var) {
                    return $age = $var == 1 ? 'HABILITADO' :null;
                }),

                // Column::name('activo_tipo.ID')
                // // ->filterable($this->activo_tipos)
                // // ->searchable()
                // // ->sortBy('activo_tipos.name')
                // ->hide()
                // ->label('ID Tipo de activo'),

            Column::name('activo_tipo.name')
                ->filterable($this->activo_tipos)
                ->searchable()
                ->sortBy('activo_tipos.name')
                ->hideable()
                ->label('tipo de activo'),

            Column::name('brand.name')
                ->filterable($this->brands)
                ->searchable()
                ->sortBy('brands.name')
                ->hideable()
                ->label('marca'),

                Column::name('modelo.name')
                ->filterable($this->modelos_name)
                ->searchable()
                ->sortBy('modelos.name')
                ->hideable()
                ->label('nombre de modelo'),

            Column::name('modelo.codigo')
            ->filterable($this->modelos_codigo)
            ->searchable()
            ->sortBy('modelos.codigo')
            ->hideable()
            ->label('codigo de modelo'),

            Column::name('serial_number')
                ->filterable('serial_number')
                ->searchable()
                ->hideable()
                ->label('numero de serie')
                ->sortBy('serial_number'),

            Column::name('status.name')
            ->filterable($this->statuses)
            ->searchable()
            ->sortBy('statuses.name')
            ->hideable()
            ->label('estado de activo'),

            Column::name('performance.name')
            ->filterable($this->performances)
            ->searchable()
            ->sortBy('performances.name')
            ->hideable()
            ->label('condicion'),

            Column::name('imei1')
            ->filterable('imei1')
            ->searchable()
            ->hideable()
            ->label('imei1')
            ->sortBy('imei1'),

            Column::name('imei2')
            ->filterable('imei2')
            ->searchable()
            ->hideable()
            ->label('imei2')
            ->sortBy('imei2'),

            Column::name('mac_address')
            ->filterable('mac_address')
            ->searchable()
            ->hideable()
            ->label('mac address')
            ->sortBy('mac_address'),

            Column::name('cts.serial_number')
            ->filterable('cts.serial_number')
            ->searchable()
            ->hideable()
            ->label('ct')
            ->sortBy('cts.serial_number'),

            Column::name('personal.dni')
            ->filterable('personal.dni')
            ->searchable()
            ->sortBy('personal.dni')
            ->hideable()
            ->label('dni'),

            Column::name('personal.name')
            ->filterable($this->personal)
            ->searchable()
            ->sortBy('personal.name')
            ->hideable()
            ->label('nombre de personal'),

            Column::name('area.name')
            ->filterable($this->areas)
            ->searchable()
            ->sortBy('areas.name')
            ->hideable()
            ->label('area'),

            Column::name('orden_compra')
                ->filterable('orden_compra')
                ->searchable()
                ->hideable()
                ->label('orden de compra')
                ->sortBy('orden_compra'),
                                        
            DateColumn::name('fecha_compra')
           ->filterable('fecha_compra')
           ->searchable()
           ->hideable()
           ->label('fecha de compra')
           ->sortable()
           ->sortBy('fecha_compra'),
           
           DateColumn::name('year')
           ->filterable('year')
           ->searchable()
           ->hideable()
           ->label('año')
           ->sortable()
           ->sortBy('year'),

           DateColumn::name('fecha_asignacion')
           ->filterable('fecha_asignacion')
           ->searchable()
           ->hideable()
           ->label('fecha de asignacion')
           ->sortable()
           ->sortBy('fecha_asignacion'),

           DateColumn::name('created_at')
           ->filterable('created_at')
           ->searchable()
           ->hideable()
           ->label('fecha de creacion')
           ->sortable()
           ->sortBy('created_at'),

            Column::name('vigencia.name')
                ->filterable($this->vigencia)
                ->searchable()
                ->sortBy('vigencia.name')
                ->hideable()
                ->label('vigencia'),

            Column::name('fecha_de_vigencia')
                ->filterable('fecha_de_vigencia')
                ->searchable()
                ->sortBy('fecha_de_vigencia')
                ->hideable()
                ->label('fecha de vigencia'),
                                        
            Column::name('baja_motivo.name')
            ->filterable($this->baja_motivos)
            ->searchable()
            ->sortBy('baja_motivos.name')
            ->hideable()
            ->label('motivo de baja'),

            Column::name('observations')
                ->filterable('observations')
                ->searchable()
                ->hideable()
                ->label('observaciones')
                ->sortBy('observations'),
                
            Column::name('observaciones_no_asignacion')
            ->filterable('observaciones_no_asignacion')
            ->searchable()
            ->hideable()
            ->label('observaciones (preasignados)')
            ->sortBy('observaciones_no_asignacion')
            ->excludeFromExport(),

            BooleanColumn::name('regularizacion')
            ->filterable('regularizacion')
            ->searchable()
            ->hideable()
            ->label('regularizacion')
            ->sortBy('regularizacion')
            ->sortable()
            ->exportCallback(function ($var) {
                return $age = $var == 1 ? 'SI' : null;
            }),
        ];
    }

    public function getActivoTiposProperty()
    {
        return ActivoTipo::orderBy('name')->pluck('name');
    }

    public function getPersonalProperty()
    {
        return Personal::orderBy('name')->pluck('name');
    }

    public function getBrandsProperty()
    {
        return Brand::orderBy('name')->pluck('name');
    }

    public function getModelosCodigoProperty()
    {
        return Modelo::orderBy('codigo')->pluck('codigo');
    }

    public function getModelosNameProperty()
    {
        return Modelo::orderBy('name')->pluck('name');
    }

    public function getStatusesProperty()
    {
        return Status::orderBy('name')->pluck('name');
    }

    public function getPerformancesProperty()
    {
        return Performance::orderBy('name')->pluck('name');
    }

    public function getVigenciaProperty()
    {
        return Vigencium::orderBy('name')->pluck('name');
    }

    public function getBajaMotivosProperty()
    {
        return BajaMotivo::orderBy('name')->pluck('name');
    }

    public function getAreasProperty()
    {
        return Area::orderBy('name')->pluck('name');
    }

    
    // public function getCtsProperty()
    // {
    //     return Activo::	orderBy('activos.serial_number')->where('activos.estado',1)
    //     ->leftJoin('activo_tipos', 'activo_tipos.id', 'activos.activo_tipo_id')
    //     ->where('activo_tipos.name','Cargador de laptop')
    //     ->select( 'activos.id as id','activos.serial_number as name')
    //     ->get()->toArray();
    // }

    public function edit($id)
    {
        $this->emit('edit', $id);
    }

    public function destroy($id)
    {
        if ($id) {
            $record = Activo::where('id', $id);
            $record->timestamps = false;
            $record->first()->update([ 
                'deleted_by' => auth()->user()->id
                ]);
            $record->timestamps = true;
            $record->delete();
        }
    }
}