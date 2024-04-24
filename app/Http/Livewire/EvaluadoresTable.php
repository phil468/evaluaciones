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

    public function builder()
    {       
        return EvaluadorHasEvaluado::query()
         ->select('evaluador_has_evaluados.*')
        ->addSelect([
            'realizados' => EvaluadorHasEvaluado::selectRaw('count(*)')
                ->whereColumn('evaluador_id', 'evaluador_has_evaluados.evaluador_id')
                ->where('realizado', 1),
            'total' => EvaluadorHasEvaluado::selectRaw('count(*)')
        ])
        ->groupBy('evaluador_has_evaluados.evaluador_id')
        ->orderByRaw('realizados DESC')
        ->where('evaluador_has_evaluados.deleted_at',null)
        ->leftJoin('personal','personal.id','=','evaluador_has_evaluados.evaluador_id');
    }

    public $model = EvaluadorHasEvaluado::class;

    public function columns()
    {
        //
        // dd($this->model::query()->where('id', '=', $this->model::query()->first()->id)->get()->first()->realizados);
        return [
            Column::name('personal.name')->label('Evaluador')->searchable()->filterable(),
            Column::callback('evaluador_has_evaluados.evaluador_id',function ($value) {
               
                // $realizados = $this->model::query()->where('evaluador_has_evaluados.evaluador_id',$value)->first()->realizados;
                // $total = $this->model::query()->where('evaluador_has_evaluados.evaluador_id',$value)->first()->total;
                
                // $realizados = EvaluadorHasEvaluado::where('evaluador_id',$value)->where('realizado',1)->count();
                // $total = EvaluadorHasEvaluado::where('evaluador_id',$value)->count();

                // //mostrar una barra de progreso
                // if($total == 0){
                //     $porcentaje = 0;
                // }else {
                //     $porcentaje = ($realizados/$total)*100;
                //     $porcentaje = round($porcentaje,2);                    
                // }
                
                // if ($realizados == 0) {
                //     $class = 'bg-white';
                //     $porcentaje = 100;
                // } else if ($total == $realizados) {
                //     $class = 'bg-primary';
                // } else {
                //     $class = 'bg-secondary';
                // }
                
                // $barra = '<div class="progress" style="height: 25px;">
                // <div class="progress-bar '.$class.'" role="progressbar" style="width: '.$porcentaje.'%;" aria-valuenow="'.$porcentaje.'" aria-valuemin="0" aria-valuemax="100">'.$realizados.' de '. $total.'</div>
                // </div>';
                // return $barra;


                $barra='';
                for ($i=1; $i <3 ; $i++) {
                    $realizados = EvaluadorHasEvaluado::where('evaluador_has_evaluados.evaluador_id',$value)
                    ->join('evaluaciones','evaluador_has_evaluados.evaluacion_id','=','evaluaciones.id')
                    ->where('evaluaciones.tipo_de_evaluacion_id',$i)
                    ->where('evaluador_has_evaluados.realizado',1)
                    ->count();
                    
                    $total = EvaluadorHasEvaluado::where('evaluador_has_evaluados.evaluador_id',$value)
                    ->join('evaluaciones','evaluador_has_evaluados.evaluacion_id','=','evaluaciones.id')
                    ->where('evaluaciones.tipo_de_evaluacion_id',$i)
                    // ->where('evaluador_has_evaluados.realizado',1)
                    ->count();

                    if ($total > 0) {
                        //mostrar una barra de progreso
                        $porcentaje = ($realizados/$total)*100;
                        $porcentaje = round($porcentaje,2);                
                        
                        if ($realizados == 0) {
                            $class = 'bg-white';
                            $porcentaje = 100;
                        } else if ($total == $realizados) {
                            $class = 'bg-primary';
                        } else {
                            $class = 'bg-secondary';
                        }
                        
                        $tipo_de_evaluacion = TipoDeEvaluacione::find($i);
                        
                        $barra = $barra .'
                        
                        <h5 class="">'. ucfirst(mb_strtolower($tipo_de_evaluacion->name)).'</h5>
                        <div class="mb-3 progress" style="height: 25px;">
                        <div class="progress-bar '.$class.'" role="progressbar" style="width: '.$porcentaje.'%;" aria-valuenow="'.$porcentaje.'" aria-valuemin="0" aria-valuemax="100">'.$realizados.' de '. $total.'</div>
                        </div>
                        ';
                    }
                    
                }
                
                return $barra;
                

            })->label('Avance')->exportCallback(function ($value) {
                
                // $realizados = $this->model::query()->where('evaluador_id',$value)->first()->realizados;
                // $total = $this->model::query()->where('evaluador_id',$value)->first()->total;
                
                $realizados = EvaluadorHasEvaluado::where('evaluador_id',$value)->where('realizado',1)->count();
                $total = EvaluadorHasEvaluado::where('evaluador_id',$value)->count();
                return $realizados.' de '.$total;
            }),
            
            // Column::callback('evaluador_has_evaluados.evaluador_id',function ($value) {
            //     $realizados = EvaluadorHasEvaluado::where('evaluador_id',$value)->where('realizado',1)->count();
            //     return (int) $realizados;
            // })->label('Realizadoss')
            // ->filterable()->searchable()->sortBy(function ($builder, $direction) {
            //     // dd($builder);
            //     return $builder->orderBy('realizados', $direction);
            // }),
            
        ];

    }
}