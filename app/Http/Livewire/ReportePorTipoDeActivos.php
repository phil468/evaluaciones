<?php

namespace App\Http\Livewire;
use App\Models\Activo;
use App\Models\ActivoTipo;
use App\Models\Gerencia;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ReportePorTipoDeActivos extends Component
{
    public $tipo_de_activos;
    public $tipo_de_activo_id;
    public $gerencia_id;

    public $activos_asignados, $activos_asignados_area;

    public function mount() {
        $this->tipo_de_activos = ActivoTipo::orderBy('name')->get();
        $this->cargarActivosPorGerencia();
    }

    public function render(){
        return view('livewire.reporte-por-tipo-de-activos.view');
    }

    public function cargarActivosPorGerencia(){
        $activos_asignados = 
        Gerencia::select(
        DB::raw('
            count(*) as cuenta',
        ),
            // 'areas.name as area',
            'gerencias.id as gerencia_id',
            'gerencias.name as gerencia',
            'status_id as status_id',
            'statuses.name as estado',
        )
        ->where('activos.estado','=',1)        
        ->when($this->tipo_de_activo_id, function ($query, $tipo_de_activo_id) {
            $query->where('activos.activo_tipo_id', '=', $this->tipo_de_activo_id);
        })
        // ->whereNotNull('activos.area_id')
        ->whereNotNull('gerencias.id')
			->orderBy('gerencias.name','asc')
			->orderBy('statuses.name','asc')
            // ->groupBy('areas.gerencia_id')            
            ->groupBy('gerencias.name')
            ->groupBy('areas.gerencia_id')
            // ->groupBy('areas.name')
            ->groupBy('statuses.name')
            ->groupBy('status_id')

            
			->rightJoin('areas', function ($join) {
				$join->on('gerencias.id', '=', 'areas.gerencia_id');
			})

			->rightJoin('activos', function ($join) {
				$join->on('areas.id', '=', 'activos.area_id');
			})
            
			->rightJoin('statuses', function ($join) {
				$join->on('activos.status_id', '=', 'statuses.id');
			})
        ;

        $result = DB::table($activos_asignados)
        ->select('gerencia',
            'gerencia_id',
            DB::raw('sum(cuenta) as total'),
            DB::raw('SUM(CASE WHEN estado = "Asignado" THEN cuenta ELSE 0 END) as asignado'),
            DB::raw('SUM(CASE WHEN estado = "Stock" THEN cuenta ELSE 0 END) as stock'),
            DB::raw('SUM(CASE WHEN estado = "Baja" THEN cuenta ELSE 0 END) as baja'),
            )
            ->groupBy('gerencia_id')
            ->get();

        $this->activos_asignados = $result->toArray();
    }
    public function updatedTipoDeActivoId($value) {
        $this->cargarActivosPorGerencia();
        $this->activos_asignados_area=null;
        $this->gerencia_id = null;
    }

    public function listar_areas($id) {
        $this->cargarActivosPorGerencia();

        $activos_asignados_area = 
        Gerencia::select(
        DB::raw('
            count(*) as cuenta',
        ),
            'areas.name as area',
            'areas.id as area_id',
            // 'gerencias.id as gerencia_id',
            // 'gerencias.name as gerencia',
            'status_id as status_id',
            'statuses.name as estado',
        )
        ->where('activos.estado','=',1)
        ->where('gerencias.id','=',$id)
        ->when($this->tipo_de_activo_id, function ($query, $tipo_de_activo_id) {
            $query->where('activos.activo_tipo_id', '=', $this->tipo_de_activo_id);
        })
        // ->whereNotNull('activos.area_id')
        // ->whereNotNull('gerencias.id')
			// ->orderBy('gerencias.name','asc')
			->orderBy('statuses.name','asc')
            // ->groupBy('areas.gerencia_id')            
            // ->groupBy('gerencias.name')
            // ->groupBy('areas.gerencia_id')
            // ->groupBy('areas.name')
            ->groupBy('statuses.name')
            ->groupBy('areas.name')
            ->groupBy('areas.id')
            ->groupBy('status_id')

            
			->rightJoin('areas', function ($join) {
				$join->on('gerencias.id', '=', 'areas.gerencia_id');
			})

			->rightJoin('activos', function ($join) {
				$join->on('areas.id', '=', 'activos.area_id');
			})
            
			->rightJoin('statuses', function ($join) {
				$join->on('activos.status_id', '=', 'statuses.id');
			})
        ;

        $result_area = DB::table($activos_asignados_area)
        ->select('area',
            // 'gerencia_id',
            DB::raw('sum(cuenta) as total'),
            DB::raw('SUM(CASE WHEN estado = "Asignado" THEN cuenta ELSE 0 END) as asignado'),
            DB::raw('SUM(CASE WHEN estado = "Stock" THEN cuenta ELSE 0 END) as stock'),
            DB::raw('SUM(CASE WHEN estado = "Baja" THEN cuenta ELSE 0 END) as baja'),
            )
            ->groupBy('area_id')
            ->get();

        $this->activos_asignados_area = $result_area->toArray();
        $this->gerencia_id = $id;
        // dd($this->activos_asignados_area[0]->area);

    }
}
