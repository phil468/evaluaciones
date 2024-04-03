<?php

namespace App\Http\Livewire;

use App\Exports\ReporteEstadoActivosExport;
use App\Models\Activo;
use App\Models\ActivoTipo;
use App\Models\Asignacione;
use App\Models\Devolucion;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class ReportePorEstadoDeActivos extends Component
{
    public $tipo_de_activos;
    public $tipo_de_activo_id;

    public $fecha_inicio;
    public $fecha_final;

    public $activos_asignados, $activos_asignados_area, $asignados, $devueltos, $movimiento_id,
    $count_entregas, $count_devoluciones;

    public function mount() {
        $this->tipo_de_activos = ActivoTipo::orderBy('name')->get();
    }

    public function render(){
        if ($this->movimiento_id==1) {
            $asignados = 
            Asignacione::select(
                'asignaciones.*',
                DB::raw(
                    '"Entrega" as tipo_de_acta',
                ))->
            when($this->fecha_inicio, function ($query, $fecha_inicio) {
                $query->whereDate('asignaciones.created_at', '>=', date('Y-m-d', strtotime($this->fecha_inicio)));
            })
            ->when($this->fecha_final, function ($query, $fecha_final) {
                $query->whereDate('asignaciones.created_at', '<=', date('    Y-m-d', strtotime($this->fecha_final)));
            })
            ->with(
                'personal',
                'area',
                'gerencia',
                'empresa',
                'sede',
                'responsable',
                'responsable_area',
                'cargo',
                'responsable_cargo',            
                'activos',
                )            
            ->orderBy('fecha')->orderBy('id')
            ->get();

            $this->activos_asignados = $asignados->toArray();            
            $this->count_entregas = $asignados->count();
        } else if ($this->movimiento_id==2) {
            $devueltos = 
            Devolucion::select(
            'devoluciones.*',
            DB::raw(
                '"Devolucion" as tipo_de_acta',
            ))->
            when($this->fecha_inicio, function ($query, $fecha_inicio) {
            $query->whereDate('devoluciones.fecha', '>=', date('Y-m-d', strtotime($this->fecha_inicio)));
            })
            ->when($this->fecha_final, function ($query, $fecha_final) {
                $query->whereDate('devoluciones.fecha', '<=', date('    Y-m-d', strtotime($this->fecha_final)));
            })
            ->with(
                'personal',
                'area',
                'gerencia',
                'empresa',
                'sede',
                'responsable',
                'responsable_area',
                'cargo',
                'responsable_cargo',
                // 'activos_devueltos',
                // 'activos_devueltos.activo.activo_tipo',
                'activos',
            )->orderBy('fecha')->orderBy('id')
            // ->orderBy('tipo_de_acta','ASC')
            ->get();

            $this->activos_asignados = $devueltos->toArray();
            $this->count_devoluciones = $devueltos->count();
        }else {
            $devueltos = 
                Devolucion::select(
                'devoluciones.*',
                DB::raw(
                    '"Devolucion" as tipo_de_acta',
                ))->
                when($this->fecha_inicio, function ($query, $fecha_inicio) {
                $query->whereDate('devoluciones.fecha', '>=', date('Y-m-d', strtotime($this->fecha_inicio)));
            })
            ->when($this->fecha_final, function ($query, $fecha_final) {
                $query->whereDate('devoluciones.fecha', '<=', date('    Y-m-d', strtotime($this->fecha_final)));
            })
            ->with(
                'personal',
                'area',
                'gerencia',
                'empresa',
                'sede',
                'responsable',
                'responsable_area',
                'cargo',
                'responsable_cargo',
                // 'activos_devueltos',
                // 'activos_devueltos.activo.activo_tipo',
                'activos',
            );
    
            $asignados = 
            Asignacione::select(
                'asignaciones.*',
                DB::raw(
                    '"Entrega" as tipo_de_acta',
                ))->
            when($this->fecha_inicio, function ($query, $fecha_inicio) {
                $query->whereDate('asignaciones.created_at', '>=', date('Y-m-d', strtotime($this->fecha_inicio)));
            })
            ->when($this->fecha_final, function ($query, $fecha_final) {
                $query->whereDate('asignaciones.created_at', '<=', date('    Y-m-d', strtotime($this->fecha_final)));
            })
            ->with(
                'personal',
                'area',
                'gerencia',
                'empresa',
                'sede',
                'responsable',
                'responsable_area',
                'cargo',
                'responsable_cargo',            
                'activos',
                )            
            ->unionAll($devueltos)->orderBy('fecha')->orderBy('id')
            // ->orderBy('tipo_de_acta','ASC')
            ->get();
    
            $this->activos_asignados = $asignados->toArray();
           
            $this->count_entregas = Asignacione::select(
                'asignaciones.*')
            ->
            when($this->fecha_inicio, function ($query, $fecha_inicio) {
                $query->whereDate('asignaciones.created_at', '>=', date('Y-m-d', strtotime($this->fecha_inicio)));
            })
            ->when($this->fecha_final, function ($query, $fecha_final) {
                $query->whereDate('asignaciones.created_at', '<=', date('    Y-m-d', strtotime($this->fecha_final)));
            })
            ->get()->count();

            $this->count_devoluciones = 
            Devolucion::select(
                'devoluciones.*'
                )
            ->when($this->fecha_inicio, function ($query, $fecha_inicio) {
                $query->whereDate('devoluciones.fecha', '>=', date('Y-m-d', strtotime($this->fecha_inicio)));
            })
            ->when($this->fecha_final, function ($query, $fecha_final) {
                $query->whereDate('devoluciones.fecha', '<=', date('    Y-m-d', strtotime($this->fecha_final)));
            })
            ->get()->count();
        }

        return view('livewire.reporte-por-estado-de-activos.view');
    }

    public function generar_grafica()
    {   
        $this->render();
    }

    public function updatedTipoDeActivoId($value) {

    }

    public function listar_areas($id) {

    }

    public function exportar() {
        
        if ($this->movimiento_id==1) {
            $asignados = 
            Asignacione::select(
                'asignaciones.*',
                DB::raw(
                    '"Entrega" as tipo_de_acta',
                ))->
            when($this->fecha_inicio, function ($query, $fecha_inicio) {
                $query->whereDate('asignaciones.created_at', '>=', date('Y-m-d', strtotime($this->fecha_inicio)));
            })
            ->when($this->fecha_final, function ($query, $fecha_final) {
                $query->whereDate('asignaciones.created_at', '<=', date('    Y-m-d', strtotime($this->fecha_final)));
            })
            ->with(
                'personal',
                'area',
                'gerencia',
                'empresa',
                'sede',
                'responsable',
                'responsable_area',
                'cargo',
                'responsable_cargo',            
                'activos',
                )            
            ->orderBy('fecha')->orderBy('id')
            ->get();

            $this->activos_asignados = $asignados->toArray();            
            $this->count_entregas = $asignados->count();
        } else if ($this->movimiento_id==2) {
            $devueltos = 
            Devolucion::select(
            'devoluciones.*',
            DB::raw(
                '"Devolucion" as tipo_de_acta',
            ))->
            when($this->fecha_inicio, function ($query, $fecha_inicio) {
            $query->whereDate('devoluciones.fecha', '>=', date('Y-m-d', strtotime($this->fecha_inicio)));
            })
            ->when($this->fecha_final, function ($query, $fecha_final) {
                $query->whereDate('devoluciones.fecha', '<=', date('    Y-m-d', strtotime($this->fecha_final)));
            })
            ->with(
                'personal',
                'area',
                'gerencia',
                'empresa',
                'sede',
                'responsable',
                'responsable_area',
                'cargo',
                'responsable_cargo',
                // 'activos_devueltos',
                // 'activos_devueltos.activo.activo_tipo',
                'activos',
            )->orderBy('fecha')->orderBy('id')
            // ->orderBy('tipo_de_acta','ASC')
            ->get();

            $this->activos_asignados = $devueltos->toArray();
            $this->count_devoluciones = $devueltos->count();
        }else {
            $devueltos = 
                Devolucion::select(
                'devoluciones.*',
                DB::raw(
                    '"Devolucion" as tipo_de_acta',
                ))->
                when($this->fecha_inicio, function ($query, $fecha_inicio) {
                $query->whereDate('devoluciones.fecha', '>=', date('Y-m-d', strtotime($this->fecha_inicio)));
            })
            ->when($this->fecha_final, function ($query, $fecha_final) {
                $query->whereDate('devoluciones.fecha', '<=', date('    Y-m-d', strtotime($this->fecha_final)));
            })
            ->with(
                'personal',
                'area',
                'gerencia',
                'empresa',
                'sede',
                'responsable',
                'responsable_area',
                'cargo',
                'responsable_cargo',
                // 'activos_devueltos',
                // 'activos_devueltos.activo.activo_tipo',
                'activos',
            );
    
            $asignados = 
            Asignacione::select(
                'asignaciones.*',
                DB::raw(
                    '"Entrega" as tipo_de_acta',
                ))->
            when($this->fecha_inicio, function ($query, $fecha_inicio) {
                $query->whereDate('asignaciones.created_at', '>=', date('Y-m-d', strtotime($this->fecha_inicio)));
            })
            ->when($this->fecha_final, function ($query, $fecha_final) {
                $query->whereDate('asignaciones.created_at', '<=', date('    Y-m-d', strtotime($this->fecha_final)));
            })
            ->with(
                'personal',
                'area',
                'gerencia',
                'empresa',
                'sede',
                'responsable',
                'responsable_area',
                'cargo',
                'responsable_cargo',            
                'activos',
                )            
            ->unionAll($devueltos)->orderBy('fecha')->orderBy('id')
            // ->orderBy('tipo_de_acta','ASC')
            ->get();
    
            $this->activos_asignados = $asignados->toArray();
           
            $this->count_entregas = Asignacione::select(
                'asignaciones.*')
            ->
            when($this->fecha_inicio, function ($query, $fecha_inicio) {
                $query->whereDate('asignaciones.created_at', '>=', date('Y-m-d', strtotime($this->fecha_inicio)));
            })
            ->when($this->fecha_final, function ($query, $fecha_final) {
                $query->whereDate('asignaciones.created_at', '<=', date('    Y-m-d', strtotime($this->fecha_final)));
            })
            ->get()->count();

            $this->count_devoluciones = Devolucion::select(
                                'devoluciones.*')
                                ->when($this->fecha_inicio, function ($query, $fecha_inicio) {
                                $query->whereDate('devoluciones.fecha', '>=', date('Y-m-d', strtotime($this->fecha_inicio)));
                                })
                                ->when($this->fecha_final, function ($query, $fecha_final) {
                                    $query->whereDate('devoluciones.fecha', '<=', date('    Y-m-d', strtotime($this->fecha_final)));
                                })
                                ->get()->count();
        }

        return Excel::download(new ReporteEstadoActivosExport($this->activos_asignados), 'reporte_estado_activos.xlsx');
    }
}
