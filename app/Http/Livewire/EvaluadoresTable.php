<?php

namespace App\Http\Livewire;

use App\Models\EvaluadorHasEvaluado;
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
class EvaluadoresTable extends LivewireDatatable
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
        // dd(
        EvaluadorHasEvaluado::query()
        ->select('evaluador_has_evaluados.*')
        // ->addSelect([
        //     'realizados_competencias' => EvaluadorHasEvaluado::selectRaw('count(*)')->from('evaluador_has_evaluados as eva')->where('eva.evaluador_id', DB::raw('evaluador_has_evaluados.evaluador_id'))
        //     ->join('evaluaciones','eva.evaluacion_id','=','evaluaciones.id')
        //     ->where('evaluaciones.tipo_de_evaluacion_id',1)
        //     ->where('eva.realizado',1),
        //     'total_compretencias' => EvaluadorHasEvaluado::selectRaw('count(*)')->from('evaluador_has_evaluados as eva1')
        //     ->where('eva1.evaluador_id', DB::raw('evaluador_has_evaluados.evaluador_id'))
        //     ->join('evaluaciones','eva1.evaluacion_id','=','evaluaciones.id')
        //     ->where('evaluaciones.tipo_de_evaluacion_id',1),
        //     'total_resultados' => EvaluadorHasEvaluado::selectRaw('count(*)')->from('evaluador_has_evaluados as eva2')
        //     ->where('eva2.evaluador_id', DB::raw('evaluador_has_evaluados.evaluador_id'))
        //     ->join('evaluaciones','eva2.evaluacion_id','=','evaluaciones.id')
        //     ->where('evaluaciones.tipo_de_evaluacion_id',2),
        //     // 'pendientes' => EvaluadorHasEvaluado::all()
        //     // ->filter(function ($evaluador) {
        //     //     return $evaluador->estado_pendiente;
        //     // })->count(),
        //     // // ->get()
        //     // ->filter(function ($evaluador) {
        //     //     return $evaluador->estado_pendiente;
        //     // }),
        // ])
        ->from('evaluador_has_evaluados as evaluador_has_evaluados')
        ->groupBy('evaluador_has_evaluados.evaluador_id')
        // ->orderByRaw('realizados_competencias DESC')
        ->whereNull('evaluador_has_evaluados.deleted_at')
        ->leftJoin('personal','personal.id','=','evaluador_has_evaluados.evaluador_id')
        // ->get()
        // )
        ;
    }

    public $model = EvaluadorHasEvaluado::class;

    public function columns()
    {
        // dd($this->builder()->get());
        return [
            Column::name('personal.name')->label('Evaluador')->searchable()->filterable(),
            
            Column::callback(['evaluador_id'],function ($value) {
               
                $barra='';
                for ($i=1; $i <3 ; $i++) {
                    if ($i ==2) {
                        $pendientes = EvaluadorHasEvaluado::where('evaluador_has_evaluados.evaluador_id', $value)
                        ->select('evaluador_has_evaluados.*')
                        ->join('evaluaciones','evaluador_has_evaluados.evaluacion_id','=','evaluaciones.id')
                        ->where('evaluaciones.tipo_de_evaluacion_id',2)
                        ->get()->filter(function ($evaluador) {
                            return $evaluador->estado_no_realizado;
                        })->count();

                        $total = EvaluadorHasEvaluado::
                        where('evaluador_has_evaluados.evaluador_id', $value)
                        ->join('evaluaciones','evaluador_has_evaluados.evaluacion_id','=','evaluaciones.id')
                        ->where('evaluaciones.tipo_de_evaluacion_id',2)
                        ->count();

                        $realizados = $total-$pendientes;

                    } 
                    if ($i == 1) {
                        $realizados = EvaluadorHasEvaluado::where('evaluador_has_evaluados.evaluador_id',$value)
                        ->join('evaluaciones','evaluador_has_evaluados.evaluacion_id','=','evaluaciones.id')
                        ->where('evaluaciones.tipo_de_evaluacion_id',$i)
                        ->where('evaluador_has_evaluados.realizado',1)
                        ->count();
                        
                        $total = EvaluadorHasEvaluado::where('evaluador_has_evaluados.evaluador_id',$value)
                        ->join('evaluaciones','evaluador_has_evaluados.evaluacion_id','=','evaluaciones.id')
                        ->where('evaluaciones.tipo_de_evaluacion_id',$i)
                        ->count();
                    }
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
                        
                        $tipo_de_evaluacion = TipoDeEvaluacione::find($i);
                        
                        $barra = $barra
                        // .$realizados
                        // .$total
                         .'
                        
                        <h5 class="">'. ucfirst(mb_strtolower($tipo_de_evaluacion->name)).'</h5>
                        <div class="mb-3 rounded-xl progress" style="height: 25px;">
                        <div class="rounded-xl progress-bar '.$class.'" role="progressbar" style="width: '.$porcentaje.'%;" aria-valuenow="'.$porcentaje.'" aria-valuemin="0" aria-valuemax="100">'.$realizados.' de '. $total.'</div>
                        </div>
                        ';
                    }
                }
                
                return $barra;
                

            })->label('Avance')->excludeFromExport()
            // ->exportCallback(function ($value) {
            //     $barra='';
            //     for ($i=1; $i <3 ; $i++) {
            //         if ($i == 1) {
            //             $realizados = EvaluadorHasEvaluado::where('evaluador_has_evaluados.evaluador_id',$value)
            //             ->join('evaluaciones','evaluador_has_evaluados.evaluacion_id','=','evaluaciones.id')
            //             ->where('evaluaciones.tipo_de_evaluacion_id',$i)
            //             ->where('evaluador_has_evaluados.realizado',1)
            //             ->count();
                        
            //             $total = EvaluadorHasEvaluado::where('evaluador_has_evaluados.evaluador_id',$value)
            //             ->join('evaluaciones','evaluador_has_evaluados.evaluacion_id','=','evaluaciones.id')
            //             ->where('evaluaciones.tipo_de_evaluacion_id',$i)
            //             ->count();
                        
            //             $barra=$barra.'Ev. por Competencias : '.$realizados.' de '.$total;
            //         }
                    
            //         if ($i == 2) {
            //             $pendientes = EvaluadorHasEvaluado::where('evaluador_has_evaluados.evaluador_id', $value)
            //             ->select('evaluador_has_evaluados.*')
            //             ->join('evaluaciones','evaluador_has_evaluados.evaluacion_id','=','evaluaciones.id')
            //             ->where('evaluaciones.tipo_de_evaluacion_id',2)
            //             ->get()->filter(function ($evaluador) {
            //                 return $evaluador->estado_no_realizado;
            //             })->count();

            //             $total = EvaluadorHasEvaluado::
            //             where('evaluador_has_evaluados.evaluador_id', $value)
            //             ->join('evaluaciones','evaluador_has_evaluados.evaluacion_id','=','evaluaciones.id')
            //             ->where('evaluaciones.tipo_de_evaluacion_id',2)
            //             ->count();

            //             $realizados = $total-$pendientes;
                        
            //             $barra=$barra.' | Ev. por Resultados : '.$realizados.' de '.$total;
            //         } 

            //     }
                
            //     return $barra;
                
            // })
            ,
            //columna oculta callback de avance de realizados y total
            Column::callback(['evaluador_id'],function ($value) {
                $realizados = EvaluadorHasEvaluado::where('evaluador_has_evaluados.evaluador_id',$value)
                ->join('evaluaciones','evaluador_has_evaluados.evaluacion_id','=','evaluaciones.id')
                ->where('evaluaciones.tipo_de_evaluacion_id',1)
                ->where('evaluador_has_evaluados.realizado',1)
                ->count();
                
                $total = EvaluadorHasEvaluado::where('evaluador_has_evaluados.evaluador_id',$value)
                ->join('evaluaciones','evaluador_has_evaluados.evaluacion_id','=','evaluaciones.id')
                ->where('evaluaciones.tipo_de_evaluacion_id',1)
                ->count();
                
                return $realizados.' de '.$total;
            },[],'Seguimiento de Ev. Por Competencias')->label('Ev. Por Competencias'),

            Column::callback(['evaluador_id'],function ($value) {
                $pendientes = EvaluadorHasEvaluado::where('evaluador_has_evaluados.evaluador_id', $value)
                ->select('evaluador_has_evaluados.*')
                ->join('evaluaciones','evaluador_has_evaluados.evaluacion_id','=','evaluaciones.id')
                ->where('evaluaciones.tipo_de_evaluacion_id',2)
                ->get()->filter(function ($evaluador) {
                    return $evaluador->estado_no_realizado;
                })->count();

                $total = EvaluadorHasEvaluado::
                where('evaluador_has_evaluados.evaluador_id', $value)
                ->join('evaluaciones','evaluador_has_evaluados.evaluacion_id','=','evaluaciones.id')
                ->where('evaluaciones.tipo_de_evaluacion_id',2)
                ->count();

                $realizados = $total-$pendientes;
                
                return $realizados.' de '.$total;
            },[],'Seguimiento de Ev. Por Resultados')->label('Ev. Por Resultados'),
           
        ];

    }

    public function export()
    {
        $this->forgetComputed();

        $export = new DatatableExport($this->getExportResultsSet());

        $export->setFileName('Seguimiento_de_evaluadores.xlsx');
        return $export->download();
    }
    
}