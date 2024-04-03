<?php

namespace App\Http\Livewire;

use App\Models\EvaluadorHasEvaluado;
use App\Models\Respuesta;
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
        ->groupBy('evaluador_id')
        ->where('evaluador_has_evaluados.deleted_at',null)
        ->leftJoin('personal','personal.id','=','evaluador_has_evaluados.evaluador_id');
    }

    public $model = Respuesta::class;

    public function columns()
    {
        return [
            Column::name('personal.name')->label('Evaluador')->searchable()->filterable()->defaultSort('asc'),
            Column::callback('evaluador_id',function ($value) {

                $realizados = EvaluadorHasEvaluado::where('evaluador_id',$value)->where('realizado',1)->count();
                $total = EvaluadorHasEvaluado::where('evaluador_id',$value)->count();

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
                
                $barra = '<div class="progress" style="height: 25px;">
                <div class="progress-bar '.$class.'" role="progressbar" style="width: '.$porcentaje.'%;" aria-valuenow="'.$porcentaje.'" aria-valuemin="0" aria-valuemax="100">'.$realizados.' de '. $total.'</div>
                </div>';
                return $barra;
            })->label('Avance')->exportCallback(function ($value) {
                $realizados = EvaluadorHasEvaluado::where('evaluador_id',$value)->where('realizado',1)->count();
                $total = EvaluadorHasEvaluado::where('evaluador_id',$value)->count();
                return $realizados.' de '.$total;
            }),
            
            //exportar realizados y total pero que no sean visibles
        ];

    }
}