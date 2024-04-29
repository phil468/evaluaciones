<?php

namespace App\Http\Livewire;

use App\Models\Area;
use App\Models\Ensayo;
use App\Models\Evaluacione;
use App\Models\Seccione;
use App\Models\Status;
use App\Models\TipoDeEnsayo;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class Dashboard extends Component
{
    public
    $gerencias,
    $secciones,
    $tipo_de_ensayos,
    $areas
    // $tipo_de_ensayos,
    // $areas,
    // $estados,
    // $tipo_de_ensayo_id=[],
    // $status_id=[],
    // $area_id=[],
    // $fecha_inicio,
    // $fecha_final,

    // $fechas,
    // $numero_de_dias,

    // $areas_solicitantes,
    // $ensayos_solicitados_por_area,

    // $tipo_de_ensayos_solicitados,
    // $ensayos_solicitados_por_tipo,

    // $mostrar_grafica = null,
    // $mostrar_grafica3 = null,
    // $mostrar_grafica4 = null,
    
    // $resumen
    ;

    public function mount($personal_id=null)
    {
        // $this->tipo_de_ensayos = TipoDeEnsayo::orderBy('name')->pluck('name','id')->toArray();
        // $this->secciones = Seccione::orderBy('name')->pluck('name', 'id')->toArray();
        $this->areas = Area::orderBy('name')->pluck('name', 'id')->toArray();
        $this->gerencias = Gerencias::orderBy('name')->pluck('name', 'id')->toArray();
        $this->evaluaciones = Evaluacione::orderBy('name')->pluck('name', 'id')->toArray();
    }

    public function render()
    {
        return view('livewire.dashboard.view');
    }

    public function generar_grafica()
    {   
    
        // $this->tipo_de_ensayo_id = count($this->tipo_de_ensayo_id[0]) == 0 ? [] : $this->tipo_de_ensayo_id[0];
        $resumen = null;

        $resumen = Ensayo::
            select(
                DB::raw('DATE_FORMAT(ensayos.created_at, "%Y-%m-%d") as fecha'),
                DB::raw('SUM(ensayos.numero_de_dias_evaluados_real) as numero_de_dias'),
            )
            ->orderBy(
                'fecha',
            )            
            ->whereNull('ensayos.deleted_at')
            ->having('numero_de_dias','>',0)
            ->groupBy('fecha')
            ->when(($this->tipo_de_ensayo_id), function ($query, $tipo_de_ensayo_id) {
                $query->whereIn('ensayos.tipo_de_ensayo_id', $this->tipo_de_ensayo_id);
            })
            ->when(($this->status_id), function ($query, $status_id) {
                $query->whereIn('ensayos.status_id', $this->status_id);
            })
            ->when(($this->area_id), function ($query, $area_id) {
                $query->whereIn('users.area_id', $this->area_id);
            })
            ->when(($this->fecha_inicio), function ($query, $fecha_inicio) {
        		$query->whereDate('ensayos.created_at', '>=', date('Y-m-d', strtotime($this->fecha_inicio)));
            })
            ->when(($this->fecha_final), function ($query, $fecha_final) {
        		$query->whereDate('ensayos.created_at', '<=', date('Y-m-d', strtotime($this->fecha_final)));
            })
            ->leftJoin('users', function ($join) {
                $join->on('ensayos.solicitante_id', '=', 'users.id');
            })->get();            

            $this->fechas = $resumen->pluck('fecha')->values()->toArray(); //
            $this->numero_de_dias = $resumen->pluck('numero_de_dias')->values()->toArray(); //


            if(count($this->fechas)) {
                $this->mostrar_grafica = true;
            } else {
                $this->mostrar_grafica = false;
            }
            
        $resumen3 = null;
        
        $resumen3 = Ensayo::
            select(
                DB::raw('areas.name as area'),
                DB::raw('count(ensayos.id) as cantidad'),
            )
            ->orderBy(
                'area',
            )            
            ->whereNull('ensayos.deleted_at')
            ->having('cantidad','>',0)
            ->groupBy('area')

            ->when(($this->tipo_de_ensayo_id), function ($query, $tipo_de_ensayo_id) {
                $query->whereIn('ensayos.tipo_de_ensayo_id', $this->tipo_de_ensayo_id);
            })
            ->when(($this->status_id), function ($query, $status_id) {
                $query->whereIn('ensayos.status_id', $this->status_id);
            })
            ->when(($this->area_id), function ($query, $area_id) {
                $query->whereIn('users.area_id', $this->area_id);
            })
            ->when(($this->fecha_inicio), function ($query, $fecha_inicio) {
        		$query->whereDate('ensayos.created_at', '>=', date('Y-m-d', strtotime($this->fecha_inicio)));
            })
            ->when(($this->fecha_final), function ($query, $fecha_final) {
        		$query->whereDate('ensayos.created_at', '<=', date('Y-m-d', strtotime($this->fecha_final)));
            })
            ->leftJoin('users', function ($join) {
                $join->on('ensayos.solicitante_id', '=', 'users.id');
            })
            
            ->leftJoin('areas', function ($join) {
                $join->on('users.area_id', '=', 'areas.id');
            })
            ->get()
            ;

            $this->areas_solicitantes = $resumen3->pluck('area')->values()->toArray(); //
            $this->ensayos_solicitados_por_area = $resumen3->pluck('cantidad')->values()->toArray(); //

            if(count($this->areas_solicitantes)) {
                $this->mostrar_grafica3 = true;
            } else {
                $this->mostrar_grafica3 = false;
            }
            
            $resumen4 = null;
        
            $resumen4 = Ensayo::
                select(
                    DB::raw('tipo_de_ensayos.name as tipo'),
                    DB::raw('count(ensayos.id) as cantidad'),
                    )
                ->orderBy(
                    'tipo',
                )
                ->whereNull('ensayos.deleted_at')
                ->having('cantidad','>',0)
                ->groupBy('tipo')
    
                ->when(($this->tipo_de_ensayo_id), function ($query, $tipo_de_ensayo_id) {
                    $query->whereIn('ensayos.tipo_de_ensayo_id', $this->tipo_de_ensayo_id);
                })
                ->when(($this->status_id), function ($query, $status_id) {
                    $query->whereIn('ensayos.status_id', $this->status_id);
                })
                ->when(($this->area_id), function ($query, $area_id) {
                    $query->whereIn('users.area_id', $this->area_id);
                })
                ->when(($this->fecha_inicio), function ($query, $fecha_inicio) {
                    $query->whereDate('ensayos.created_at', '>=', date('Y-m-d', strtotime($this->fecha_inicio)));
                })
                ->when(($this->fecha_final), function ($query, $fecha_final) {
                    $query->whereDate('ensayos.created_at', '<=', date('Y-m-d', strtotime($this->fecha_final)));
                })    
                ->leftJoin('users', function ($join) {
                    $join->on('ensayos.solicitante_id', '=', 'users.id');
                })

                ->leftJoin('tipo_de_ensayos', function ($join) {
                    $join->on('ensayos.tipo_de_ensayo_id', '=', 'tipo_de_ensayos.id');
                })
                ->get();
    
                $this->tipo_de_ensayos_solicitados = $resumen4->pluck('tipo')->values()->toArray(); //
                $this->ensayos_solicitados_por_tipo = $resumen4->pluck('cantidad')->values()->toArray(); //
    
                if(count($this->tipo_de_ensayos_solicitados)) {
                    $this->mostrar_grafica4 = true;
                } else {
                    $this->mostrar_grafica4 = false;
                }
    
        //Para enviar la actualización
        $this->emit('dataUpdated');
    }

}
