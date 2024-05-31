<?php

namespace App\Http\Livewire;

use App\Models\EvaluadorHasEvaluado;
use App\Models\Personal;
use App\Models\Pregunta;
use App\Models\Respuesta;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\BooleanColumn;
// use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\Exports\DatatableExport;

use function PHPUnit\Framework\callback;

class RespuestasTable extends LivewireDatatable
{
    public $hideable = 'inline';
    public $exportable = true;
    public $afterTableSlot = 'components.selected';
    public $numeroSerieValidado=true, $fileUpload;
    public $updateMode = false;
    public $export_name = 'Respuestas';
    // public $export_columns = ['id','evaluado_id','pregunta_id','valor_numerico','valor_texto'];
    // public $export_filename = 'Respuestas';
    // public $respuestas;

    public function builder()
    {
        // $respuestas = Respuesta::query()
        return Respuesta::query()
        // ->selectRaw(Crypt::decryptString('respuestas.pregunta id'))
        ->where('respuestas.deleted_at',null)->where('preguntas.deleted_at',null)
        ->leftJoin('preguntas','preguntas.id','=','respuestas.pregunta_id')
        ->leftJoin('personal','personal.id','=','respuestas.evaluado_id')
        ->leftJoin('secciones','secciones.id','=','preguntas.seccion_id')
        ->leftJoin('evaluaciones','evaluaciones.id','=','preguntas.evaluacion_id')
        // ->leftJoin('evaluador_has_evaluados','evaluador_has_evaluados.evaluado_id','=','respuestas.evaluado_id')
        //cargo
        ->leftJoin('cargos','cargos.id','=','personal.cargo_id')
        ;

        // return $this->respuestas;
    }

    public $model = Respuesta::class;

    public function columns()
    {
        return [
            Column::callback(['evaluado_id'], function ($evaluado_id) {
                $evaluado = Personal::select('name')
                ->where('id', Crypt::decryptString($evaluado_id))
                ->first();
                return $evaluado->name;
            },[],'0')->label('Nombres y apellidos del evaluado')
            ->searchable()->filterable()->defaultSort('asc'),

            Column::callback(['id'], function($id) {
                return Respuesta::find($id)->pregunta->seccion->name;
            },[],'competencia')->label('Competencia')->searchable()->filterable()->defaultSort('asc'),

            Column::callback(['pregunta_id'], function ($pregunta_id){
                return Pregunta::find(Crypt::decryptString($pregunta_id))->pregunta;
            })->label('Pregunta')->searchable()->filterable()->defaultSort('asc'),

            Column::callback(['valor_numerico'], function ($v) {
                return Crypt::decryptString($v);
            })->label('Puntuación')->searchable()->filterable()->defaultSort('asc'),

            Column::callback(['respuestas.evaluado_id'], function ($id) {
                $cargo_de_evaluado = EvaluadorHasEvaluado::select('evaluador_has_evaluados.cargo_de_evaluado')
                ->where('evaluador_has_evaluados.evaluado_id',Crypt::decryptString($id))
                ->where('evaluador_has_evaluados.deleted_at',null)
                ->where('evaluador_has_evaluados.evaluacion_id','<>',4)
                ->first();
                return $cargo_de_evaluado->cargo_de_evaluado;
            },[],'1')->label('Cargo del evaluado')->searchable()->filterable()->defaultSort('asc'),

            Column::callback(['respuestas.evaluado_id'], function ($id) {
                $area_de_evaluado = EvaluadorHasEvaluado::select('evaluador_has_evaluados.area_de_evaluado')
                ->where('evaluador_has_evaluados.evaluado_id',Crypt::decryptString($id))
                ->where('evaluador_has_evaluados.deleted_at',null)
                ->where('evaluador_has_evaluados.evaluacion_id','<>',4)
                ->first();
                return $area_de_evaluado->area_de_evaluado;
            },[],'2')->label('Area del evaluado')->searchable()->filterable()->defaultSort('asc'),
            
            Column::callback(['respuestas.evaluado_id'], function ($id) {
                $gerencia_sub_gerencia_de_evaluado = EvaluadorHasEvaluado::select('evaluador_has_evaluados.gerencia_sub_gerencia_de_evaluado')
                ->where('evaluador_has_evaluados.evaluado_id',Crypt::decryptString($id))
                ->where('evaluador_has_evaluados.deleted_at',null)
                ->where('evaluador_has_evaluados.evaluacion_id','<>',4)
                ->first();
                return $gerencia_sub_gerencia_de_evaluado->gerencia_sub_gerencia_de_evaluado;
            },[],'3')->label('Gerencia/Subgerencia del evaluado')->searchable()->filterable()->defaultSort('asc'),
            
            Column::callback(['respuestas.evaluado_id'], function ($id) {
                $jerarquia = EvaluadorHasEvaluado::select('evaluador_has_evaluados.jerarquia')
                ->where('evaluador_has_evaluados.evaluado_id',Crypt::decryptString($id))
                ->where('evaluador_has_evaluados.deleted_at',null)
                ->where('evaluador_has_evaluados.evaluacion_id','<>',4)
                ->first();
                return $jerarquia->jerarquia;
            },[],'4')->label('Jerarquia')->searchable()->filterable()->defaultSort('asc')
            // ->exportCallback(function(){
            //     return '1';
            // })
            ,

        ];
    }

    public function export()
    {
        $this->forgetComputed();

        $export = new DatatableExport($this->getExportResultsSet());

        $export->setFileName('respuestas_de_evaluacion_por_competencias.xlsx');
        return $export->download();
    }

}