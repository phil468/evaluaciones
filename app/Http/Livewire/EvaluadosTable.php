<?php

namespace App\Http\Livewire;

use App\Models\EvaluadorHasEvaluado;
use App\Models\Respuesta;
use App\Models\TipoDeEvaluacione;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\Column;

class EvaluadosTable extends LivewireDatatable
{
    public $hideable = 'inline';
    public $exportable = true;
    public $afterTableSlot = 'components.selected';
    public $numeroSerieValidado=true, $fileUpload;
    public $updateMode = false;
    public $export_name = 'Evaluados';

    public function builder()
    {
        return EvaluadorHasEvaluado::query()
        ->groupBy('evaluado_id')
        ->where('evaluador_has_evaluados.deleted_at',null)
        ->leftJoin('personal','personal.id','=','evaluador_has_evaluados.evaluado_id');
    }

    public function exportFilename()
    {
        return 'Evaluados.xlsx';
    }

    public $model = Respuesta::class;

    public function columns()
    {
        return [
            Column::name('personal.name')->label('Evaluado')->searchable()->filterable()->defaultSort('asc'),
            Column::callback('evaluado_id',function ($value) {

                $barra='';
                for ($i=1; $i <3 ; $i++) {
                    $realizados = EvaluadorHasEvaluado::where('evaluador_has_evaluados.evaluado_id',$value)
                    ->join('evaluaciones','evaluador_has_evaluados.evaluacion_id','=','evaluaciones.id')
                    ->where('evaluaciones.tipo_de_evaluacion_id',$i)
                    ->where('evaluador_has_evaluados.realizado',1)
                    ->count();
                    
                    $total = EvaluadorHasEvaluado::where('evaluado_id',$value)
                    ->join('evaluaciones','evaluador_has_evaluados.evaluacion_id','=','evaluaciones.id')
                    ->where('evaluaciones.tipo_de_evaluacion_id',$i)
                    ->count();

                    if ($total > 0) {
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
                        
                        $barra = $barra .'
                        
                        <h5 class="">'. ucfirst(mb_strtolower($tipo_de_evaluacion->name)).'</h5>
                        <div class="mb-3 rounded-xl progress" style="height: 25px;">
                        <div class="rounded-xl progress-bar '.$class.'" role="progressbar" style="width: '.$porcentaje.'%;" aria-valuenow="'.$porcentaje.'" aria-valuemin="0" aria-valuemax="100">'.$realizados.' de '. $total.'</div>
                        </div>
                        ';
                    }
                    
                }
                
                return $barra;
            })->label('Avance')->exportCallback(function ($value) {
                $realizados = EvaluadorHasEvaluado::where('evaluado_id',$value)->where('realizado',1)->count();
                $total = EvaluadorHasEvaluado::where('evaluado_id',$value)->count();
                return $realizados.' de '.$total;
            })          
        ];

    }
}