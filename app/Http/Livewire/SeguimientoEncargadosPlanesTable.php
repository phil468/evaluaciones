<?php

namespace App\Http\Livewire;

use App\Models\EncargadosPlanesDeAccion;
use App\Models\EvaluadorHasEvaluado;
use App\Models\PlanesDeAccion;
use App\Models\Respuesta;
use App\Models\TipoDeEvaluacione;
use Illuminate\Support\Facades\DB;
use Mediconesystems\LivewireDatatables\Action;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\BooleanColumn;
// use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\Exports\DatatableExport;
use Mediconesystems\LivewireDatatables\NumberColumn;

//en esta tabla vamos a mostrar los evaluadores 
class SeguimientoEncargadosPlanesTable extends LivewireDatatable
{
    public $hideable = 'inline';
    public $exportable = true;
    public $afterTableSlot = 'components.selected';
    public $numeroSerieValidado=true, $fileUpload;
    public $updateMode = false;
    public $export_name = 'Evaluadores';

    protected $listeners = ['refreshEvaluadores' => '$refresh'];

    public function builder()
    {       
        return 
        EncargadosPlanesDeAccion::query()
        ->join('personal','encargados_planes_de_accion.encargado_id','=','personal.id')
        ->select('encargados_planes_de_accion.*','personal.name')
        ->groupBy('encargados_planes_de_accion.encargado_id');
        
    }

    public $model = EncargadosPlanes::class;

    public function columns()
    {
        // dd(EncargadosPlanesDeAccion::
        // join('personal','encargados_planes_de_accion.encargado_id','=','personal.id')
        // ->select('encargados_planes_de_accion.*','personal.name')
        // ->groupBy('encargados_planes_de_accion.encargado_id')->get()->toArray());
        
        return [
            Column::name('personal.name')->label('Encargado')->searchable()->filterable(),
            
            Column::callback(['encargado_id'],function ($encargado_id) {
                $barra='';

                $encargados = EncargadosPlanesDeAccion::
                select(
                    'encargado.name as encargado',
                    'empleado.name as empleado',
                    'encargados_planes_de_accion.empleado_id', 
                    'encargados_planes_de_accion.cantidad_requerida', 
                    DB::raw('COUNT(planes_de_accion.id) as total_planes')
                )
                ->join('personal as encargado','encargados_planes_de_accion.encargado_id','=','encargado.id')
                ->join('personal as empleado','encargados_planes_de_accion.empleado_id','=','empleado.id')
                ->leftJoin('planes_de_accion','encargados_planes_de_accion.empleado_id','=','planes_de_accion.empleado_id')
                ->where('encargados_planes_de_accion.encargado_id',$encargado_id)
                ->whereNull('encargados_planes_de_accion.deleted_at')
                ->whereNull('planes_de_accion.deleted_at')
                ->groupBy(
                    'encargado.name',
                    'empleado.name',
                    'encargados_planes_de_accion.empleado_id',
                    'encargados_planes_de_accion.cantidad_requerida'
                )->get();

                // Filtrar los realizados
                $totalRealizados = $encargados->filter(function($encargado) {
                    return $encargado->cantidad_requerida > 0 && $encargado->cantidad_requerida == $encargado->total_planes;
                });

                // Contar los realizados
                $realizados = $totalRealizados->count();

                // Contar el total de encargados
                $total = $encargados->count();

                if ($total > 0) {
                    //mostrar una barra de progreso
                    $porcentaje = ($realizados/$total)*100;
                    $porcentaje = round($porcentaje,2);                
                    
                    if ($realizados == 0) {
                        $class = 'bg-white';
                        $porcentaje = 100;
                    } else if ($total == $realizados) {
                        $class = 'bg-secondary';
                    } else {
                        $class = 'bg-primary';
                    }
                    
                    // $tipo_de_evaluacion = TipoDeEvaluacione::find($i);
                    
                    $barra = $barra
                     .'
                    <div class="mb-3 rounded-xl progress" style="height: 25px;">
                    <div class="rounded-xl progress-bar '.$class.'" role="progressbar" style="width: '.$porcentaje.'%;" aria-valuenow="'.$porcentaje.'" aria-valuemin="0" aria-valuemax="100">'.$realizados.' de '. $total.'</div>
                    </div>
                    ';
                }
                
                return $barra;
                
            })->label('Avance')->excludeFromExport()

            ,
            //columna oculta callback de avance de realizados y total
            Column::callback(['encargado_id','empleado_id'],function ($encargado_id,$empleado_id) {
                $encargados = EncargadosPlanesDeAccion::
                select(
                    'encargado.name as encargado',
                    'empleado.name as empleado',
                    'encargados_planes_de_accion.empleado_id', 
                    'encargados_planes_de_accion.cantidad_requerida', 
                    DB::raw('COUNT(planes_de_accion.id) as total_planes')
                )
                ->join('personal as encargado','encargados_planes_de_accion.encargado_id','=','encargado.id')
                ->join('personal as empleado','encargados_planes_de_accion.empleado_id','=','empleado.id')
                ->leftJoin('planes_de_accion','encargados_planes_de_accion.empleado_id','=','planes_de_accion.empleado_id')
                ->where('encargados_planes_de_accion.encargado_id',$encargado_id)
                ->whereNull('encargados_planes_de_accion.deleted_at')
                ->whereNull('planes_de_accion.deleted_at')
                ->groupBy(
                    'encargado.name',
                    'empleado.name',
                    'encargados_planes_de_accion.empleado_id',
                    'encargados_planes_de_accion.cantidad_requerida'
                )->get();

                // Filtrar los realizados
                $totalRealizados = $encargados->filter(function($encargado) {
                    return $encargado->cantidad_requerida > 0 && $encargado->cantidad_requerida == $encargado->total_planes;
                });

                // Contar los realizados
                $realizados = $totalRealizados->count();

                // Contar el total de encargados
                $total = $encargados->count();
                
                return $realizados.' de '.$total;
            },[],'Avances')->label('Planes de Mejora Completos'),
        ];
    }

    public function export()
    {
        $this->forgetComputed();

        $export = new DatatableExport($this->getExportResultsSet());

        $export->setFileName('Seguimiento_de_encargados_planes_de mejora.xlsx');
        return $export->download();
    }
    
}