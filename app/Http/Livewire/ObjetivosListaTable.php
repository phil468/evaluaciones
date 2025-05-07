<?php

namespace App\Http\Livewire;

use App\Models\Objetivo;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Exports\DatatableExport;
use OwenIt\Auditing\Models\Audit;

class ObjetivosListaTable extends LivewireDatatable
{
    public $hideable = 'inline';
    public $exportable = true;
    public $afterTableSlot = 'components.selected';
    public $numeroSerieValidado=true, $fileUpload;
    public $updateMode = false;
    public $export_name = 'Objetivos';
    public $auditorias = [];

    public function builder()
    {
        return Objetivo::query()
        ->where('objetivos.deleted_at',null)
        ->leftJoin('tipo_de_objetivos','tipo_de_objetivos.id','=','objetivos.tipo_objetivo_id')
        ->leftJoin('personal as evaluados','evaluados.id','=','objetivos.evaluado_id')
        ->leftJoin('personal as evaluadores','evaluadores.id','=','objetivos.evaluador_id')
        ->leftjoin('evaluador_has_evaluados','evaluador_has_evaluados.id','=','objetivos.evaluador_has_evaluado_id' )
        ->leftJoin('estados_de_objetivos', 'estados_de_objetivos.id', '=', 'objetivos.estado_id'); // Agregar este join

        // ->leftjoin('objetivo_has_evidencias','objetivo_has_evidencias.objetivo_id','=','objetivos.id')
        ;
    }

    public $model = Objetivo::class;

    
    public function rowClasses($row, $loop)
    {
        return 'divide-x divide-gray-100 text-sm text-gray-900 ' . 
        (
            $this->rowIsSelected($row) ? 'bg-blue-100' : 
                ($row->{'estado.name'} == 'REGISTRADO' ? 'bg-yellow-100' : 
                    ($row->{'estado.name'} == 'REALIZADO' ? 'bg-green-100' : 
                        ($loop->even ? 'bg-red-100' : 'bg-red-100')
                    )
                )
        );
    }

    public function columns()
    {
        return [

        // Column::name('id')->label('ID')->filterable()->defaultSort('asc'),

        Column::callback(['id'], function ($id) {
                return view('components.lupa-button', ['id' => $id]);
            })->label('Ver historial')->alignCenter()->excludeFromExport(),

        //COLUMNA ESTADO DE OBJETIVO
        Column::name('estado.name')->label('Estado')->searchable()->filterable()->defaultSort('asc'),
        Column::name('evaluadores.name')->label('Evaluador')->searchable()->filterable()->defaultSort('asc'),
        Column::name('evaluados.name')->label('Evaluado')->searchable()->filterable()->defaultSort('asc'),
        Column::name('evaluador_has_evaluados.cargo_de_evaluado')->label('Cargo del evaluado')->searchable()->filterable()->defaultSort('asc'),
        Column::name('objetivos.meta')->label('Meta')->searchable()->filterable()->defaultSort('asc'),
        Column::callback(['objetivos.porcentaje_de_participacion','objetivos.tipo_objetivo_id'], function ($porcentaje_de_participacion,$tipo_objetivo_id) {
                return ($porcentaje_de_participacion * 100).'%';
            })->label('Porcentaje de participación')->searchable()->filterable()->defaultSort('asc'),

        Column::name('tipo_de_objetivos.unidad')->label('Tipo de objetivo')->searchable()->filterable()->defaultSort('asc'),

        Column::callback(['objetivos.resultado_anterior_o_esperado','objetivos.tipo_objetivo_id'], function ($resultado_anterior_o_esperado,$tipo_objetivo_id) {
            if ($tipo_objetivo_id == 2) { // si es porcentaje
                return ($resultado_anterior_o_esperado * 100).'%';
            } else {
                return $resultado_anterior_o_esperado;
            }
            })->label('Resultado anterior o esperado')->alignCenter()->searchable()->filterable()->defaultSort('asc'),

        Column::callback(['objetivos.minimo','objetivos.tipo_objetivo_id'], function ($minimo,$tipo_objetivo_id) {
            if ($tipo_objetivo_id == 2) { // si es porcentaje
                return ($minimo * 100).'%';
            } else {
                return $minimo;
            }
            })->label('Mínimo')->alignCenter()->searchable()->filterable()->defaultSort('asc'),
    
        Column::callback(['objetivos.maximo','objetivos.tipo_objetivo_id'], function ($maximo,$tipo_objetivo_id) {
            if ($tipo_objetivo_id == 2) { // si es porcentaje
                return ($maximo * 100).'%';
            } else {
                return $maximo;
            }
            })->label('Máximo')->alignCenter()->searchable()->filterable()->defaultSort('asc'),

        Column::callback(['objetivos.valor','objetivos.tipo_objetivo_id'], function ($valor,$tipo_objetivo_id) {
            if ($tipo_objetivo_id == 2) { // si es porcentaje
                return $valor ? ($valor * 100.00).'%' : '';
                return ($valor).'%';
            } else {
                return $valor;
            }
            })->label('Valor')->alignCenter()->searchable()->filterable()->defaultSort('asc'),

        //Boton de desarga para evidencias las evidencias tienen la relacion Objetivo->evidencias

        Column::callback(['id','sin_evidencias'], function ($id,$sin_evidencias) {
            $evidencias = Objetivo::find($id)->evidencias()->get();
            if ($evidencias->isEmpty()) {
                if ($sin_evidencias) {
                    return "Marcado check SIN EVIDENCIAS";
                } else {
                    return 'No ha cargado evidencias';
                }
            } else {
                return view('components.download-button', ['evidencias' => $evidencias, 'ruta' => 'download']);
            }
        },
        [],'evidencias')
        ->label('Evidencias')
        ->alignCenter()
        ->exportCallback(
            function ($id,$sin_evidencias) {
                $evidencias = Objetivo::find($id)->evidencias()->get();            
                if ($evidencias->isEmpty()) {
                    if ($sin_evidencias) {
                        return "Marcado check SIN EVIDENCIAS";
                    } else {
                        return 'No ha cargado evidencias';
                    }
                } else {
                    return 'Tiene evidencias';
                }
            }
        ),
        // ->excludeFromExport(),


        Column::callback(['objetivos.porcentaje_de_logro_STI','objetivos.tipo_objetivo_id'], function ($porcentaje_de_logro_STI,$tipo_objetivo_id) {
            return $porcentaje_de_logro_STI ? ($porcentaje_de_logro_STI * 100).'%' : '';
        })->label('Porcentaje de logro STI')->searchable()->filterable()->defaultSort('asc'),

        Column::callback(['objetivos.peso_ponderado', 'objetivos.tipo_objetivo_id'], function ($peso_ponderado, $tipo_objetivo_id) {
            return $peso_ponderado ? ($peso_ponderado * 100).'%' : $peso_ponderado;
        })->label('Peso ponderado')->searchable()->filterable()->defaultSort('asc'),

        Column::callback(['id', 'grupal', 'valor', 'minimo', 'sin_evidencias', 'estado_id'], function ($id, $grupal, $valor, $minimo, $sin_evidencias, $estado_id) {
            $objetivo = Objetivo::find($id);
            $evidencias_count = $objetivo->evidencias()->count();
            
            // Si es grupal, está bien
            if ($grupal) {
                return 'OK';
            }
            
            // Para objetivos individuales
            if ($valor >= $minimo) {
                // Debe tener evidencias obligatoriamente
                if ($evidencias_count == 0) {

                    if ($estado_id == 1) {
                        return 'ADVERTENCIA';
                    }
                    if ($estado_id == 2) { // Si el objetivo está en estado "REALIZADO" y sin evidencias el estado es de peligro
                        return 'PELIGRO';
                    }

                } 

                if ($evidencias_count > 0) {
                    if ($estado_id == 1) {
                        return 'ADVERTENCIA';
                    }
                    if ($estado_id == 2) { // Si el objetivo está en estado "REALIZADO" y sin evidencias el estado es de peligro
                        return 'OK';
                    }
                }

            } else {
                // Valor menor al mínimo
                if ($evidencias_count == 0 && $sin_evidencias == 0) {                    
                    if ($estado_id == 1) {
                        return 'ADVERTENCIA';
                    }
                    if ($estado_id == 2) { // Si el objetivo está en estado "REALIZADO" y sin evidencias el estado es de peligro
                        return 'PELIGRO';
                    }
                }
            }
            
            // Si llegó aquí, está todo correcto
            return 'OK';
        },[],'info')->label('Info')->alignCenter(),

        Column::callback(['id', 'grupal', 'valor', 'minimo', 'sin_evidencias', 'estado_id'], function ($id, $grupal, $valor, $minimo, $sin_evidencias, $estado_id) {
            $objetivo = Objetivo::find($id);
            $evidencias_count = $objetivo->evidencias()->count();
            
            // Si es grupal, está bien
            if ($grupal) {
                return view('components.estado-validacion', [
                    'estado' => 'success',
                    'mensaje' => 'OK - Objetivo grupal'
                ]);
            }
            
            // Para objetivos individuales
            if ($valor >= $minimo) {
                // Debe tener evidencias obligatoriamente
                if ($evidencias_count == 0) {

                    if ($estado_id == 1) {
                        return view('components.estado-validacion', [
                            'estado' => 'warning',
                            'mensaje' => 'Requiere evidencias para valor ≥ ' . number_format($minimo, 4)
                        ]);
                    }
                    if ($estado_id == 2) { // Si el objetivo está en estado "REALIZADO" y sin evidencias el estado es de peligro
                        return view('components.estado-validacion', [
                            'estado' => 'danger',
                            'mensaje' => 'REALIZADO: Requiere evidencias para valor ≥ ' . number_format($minimo, 4)
                        ]);
                    }

                } 

                if ($evidencias_count > 0) {
                    if ($estado_id == 1) {
                        return view('components.estado-validacion', [
                            'estado' => 'warning',
                            'mensaje' => 'Requiere evidencias para valor ≥ ' . number_format($minimo, 4)
                        ]);
                    }
                    if ($estado_id == 2) { // Si el objetivo está en estado "REALIZADO" y sin evidencias el estado es de peligro
                        return view('components.estado-validacion', [
                            'estado' => 'success',
                            'mensaje' => 'OK - Objetivo individual'
                        ]);
                    }
                }

            } else {
                // Valor menor al mínimo
                if ($evidencias_count == 0 && $sin_evidencias == 0) {                    
                    if ($estado_id == 1) {
                        return view('components.estado-validacion', [
                            'estado' => 'warning',
                            'mensaje' => 'Debe marcar sin evidencias o agregar evidencias'
                        ]);
                    }
                    if ($estado_id == 2) { // Si el objetivo está en estado "REALIZADO" y sin evidencias el estado es de peligro
                        return view('components.estado-validacion', [
                            'estado' => 'danger',
                            'mensaje' => 'REALIZADO: Debe marcar sin evidencias o agregar evidencias'
                        ]);
                    }
                }
            }
            
            // Si llegó aquí, está todo correcto
            return view('components.estado-validacion', [
                'estado' => 'success',
                'mensaje' => 'OK'
            ]);
        })->label('Validación')->alignCenter(),

        DateColumn::name('created_at')->label('Fecha de creacion')->format('d/m/Y h:i:s a')->searchable()->filterable()->defaultSort('asc'),
        DateColumn::name('updated_at')->label('Fecha de Modificación')->format('d/m/Y h:i:s a')->searchable()->filterable()->defaultSort('asc'),
        DateColumn::name('deleted_at')->label('Fecha de eliminación')->format('d/m/Y h:i:s a')->searchable()->filterable()->defaultSort('asc'),

        // DateColumn::name('evaluaciones.fecha_para_mostrar_resultados')->format('d/m/Y h:i:s a')
        // ->label('Fecha para mostrar resultados')->searchable()->filterable()->sortable(),

        ];
    }

    public function mostrarAuditorias($id)
    {
        $this->auditorias = Audit::where('auditable_id', $id)->where('auditable_type', Objetivo::class)->get()->toArray();
        $this->emit('enviarAuditorias', $this->auditorias);
    }

    public function export()
    {
        $this->forgetComputed();

        $export = new DatatableExport($this->getExportResultsSet());

        $export->setFileName('Resultados de desempeno por objetivos.xlsx');
        return $export->download();
    }

}