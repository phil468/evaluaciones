<?php

namespace App\Http\Livewire;

use App\Mail\EstadoAprobacionPlanMail;
use App\Models\EncargadosPlanesDeAccion;
use App\Models\Objetivo;
use App\Models\PlanesDeAccion;
use App\Models\PlanesDeAccionAprobacionHistorial;
use App\Models\PlanesDeMejoraHasEvidencia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\Exports\DatatableExport;
use OwenIt\Auditing\Models\Audit;

class PlanesDeAccionTable extends LivewireDatatable
{
    public $hideable = 'inline';
    public $exportable = true;
    public $afterTableSlot = 'livewire.planes-de-accion-table';
    
    public $numeroSerieValidado=true, $fileUpload;
    public $updateMode = false;
    public $export_name = 'Planes de Desarrollo Individual';
        
    public $auditorias = [];
    public $showModalValidacion = false;
    public $planSeleccionado = null;
    public $observacionValidacion = '';
    public $estadoValidacion = '';

    public function builder()
    {
        return PlanesDeAccion::query()
        ->where('planes_de_accion.deleted_at',null)
        ->leftJoin('personal as evaluado','evaluado.id','=','planes_de_accion.empleado_id')
        ->leftJoin('personal as evaluador','evaluador.id','=','planes_de_accion.encargado_id')
        ->leftJoin('tipo_de_evaluaciones as tipo_de_evaluaciones','tipo_de_evaluaciones.id','=','planes_de_accion.proceso_id')
        ->leftJoin('tipo_de_proceso as tipo','tipo.id','=','tipo_de_evaluaciones.tipo_de_proceso_id')
        ->leftJoin('secciones as competencias','competencias.id','=','planes_de_accion.competencia_id')
        ->leftJoin('estados_de_plan_de_accion as estados_de_plan_de_accion','estados_de_plan_de_accion.id','=','planes_de_accion.estado_id')
        ->leftJoin('gerencias','gerencias.id','=','planes_de_accion.gerencia_id')
        ->leftJoin('subgerencias','subgerencias.id','=','planes_de_accion.subgerencia_id')
        ->leftJoin('areas','areas.id','=','planes_de_accion.area_id')
        ->leftJoin('encargados_planes_de_accion','encargados_planes_de_accion.id','=','planes_de_accion.encargados_planes_de_accion_id')
        ->leftJoin('planes_de_accion_configuracion', 'planes_de_accion_configuracion.id', '=', 'encargados_planes_de_accion.planes_de_accion_configuracion_id')
        ->leftJoin('campanias', 'campanias.id', '=', 'planes_de_accion_configuracion.campania_id');
        ;
    }

    public $model = PlanesDeAccion::class;
    
    public function rowClasses($row, $loop)
    {
        $classes = 'divide-x divide-gray-100 text-sm text-gray-900 ';

        if ($this->rowIsSelected($row)) {
            $classes .= 'bg-blue-100 ';
        } elseif ($row->{'callback_habilitado'} == 'Cesado') {
            $classes .= 'text-gray-500 italic bg-red-100 ';
        } else {
            $classes .= $loop->even ? 'bg-white ' : 'bg-gray-50 ';
        }
        return $classes;
    }

    public function columns()
    {
        return [
        Column::name('name')->label('Descripcion')->searchable()->filterable()->defaultSort('asc'),
            
            // NUEVA COLUMNA: Campaña (a través de las relaciones)
        Column::name('campanias.name')->label('Campaña')->searchable()->filterable(),
            
            Column::callback([
                'campanias.es_campania_actual',
                'campanias.name'
            ], function ($esCampaniaActual, $nombreCampania) {
                if ($esCampaniaActual) {
                    return '<span class="px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">Actual</span>';
                } else {
                    return '<span class="px-2 py-1 text-xs font-semibold text-gray-600 bg-gray-100 rounded-full">Anterior</span>';
                }
            })->label('Tipo Campaña')->alignCenter()
            ->filterable([
                'Actual' => 'campanias.es_campania_actual = 1',
                'Anterior' => 'campanias.es_campania_actual = 0',
            ])
            ,

        Column::name('tipo_de_evaluaciones.name')->label('Proceso')->searchable()->filterable()->defaultSort('asc'),
        Column::name('tipo.name')->label('Tipo de proceso')->searchable()->filterable()->defaultSort('asc'),
        Column::name('evaluador.name')->label('Encargado')->searchable()->filterable()->defaultSort('asc'),
        Column::name('evaluado.name')->label('Personal')->searchable()->filterable()->defaultSort('asc'),

        Column::callback([
            'encargados_planes_de_accion.habilitado',
        ], function ($habilitado) {
            if ($habilitado == null) {
                return 'No relacionado';
            }
            return $habilitado ? 'Activo' : 'Cesado';
        },[],'habilitado')->label('Activo / Cesado')->searchable()->filterable(),
            
            // Estado de aprobación
            Column::callback(['planes_de_accion.estado_aprobacion'], function ($estado) {
                $colors = [
                    'borrador' => 'gray',
                    'pendiente' => 'yellow',
                    'validado' => 'green',
                    'no_validado' => 'red'
                ];
                $color = $colors[$estado] ?? 'gray';
                $texto = ucfirst(str_replace('_', ' ', $estado));
                return "<span class='px-2 py-1 text-xs font-semibold text-{$color}-800 bg-{$color}-100 rounded-full'>{$texto}</span>";
            })->label('Estado Aprobación')->alignCenter(),

        Column::name('planes_de_accion.fecha_de_revision')->label('Fecha de Revisión')->searchable()->filterable(),
        Column::name('estados_de_plan_de_accion.name')->label('Estado')->searchable()->filterable(),
        
        Column::callback(['planes_de_accion.avance'], function ($valor) {
            return $valor . '%';
        })->label('Avance')->alignCenter()->searchable()->filterable(),

        // NUEVA COLUMNA: Acciones de validación (usando fechas para determinar fase activa)
        Column::callback([
            'id',
            'campanias.es_campania_actual',
            'planes_de_accion_configuracion.fecha_inicio_primera_fase_matricula',
            'planes_de_accion_configuracion.fecha_fin_primera_fase_matricula',
            'planes_de_accion.estado_aprobacion'
        ], function ($id, $esCampaniaActual, $fechaInicioPrimeraFase, $fechaFinPrimeraFase, $estadoAprobacion) {
            // Determinar si estamos en primera fase activa usando las fechas
            $primeraFaseActiva = false;
            if ($fechaInicioPrimeraFase && $fechaFinPrimeraFase) {
                $now = now();
                $inicio = \Carbon\Carbon::parse($fechaInicioPrimeraFase);
                $fin = \Carbon\Carbon::parse($fechaFinPrimeraFase);
                $primeraFaseActiva = $inicio <= $now && $now <= $fin;
            }
            
            // Solo mostrar botones si es campaña actual, fase 1 activa y estado pendiente
            if ($esCampaniaActual && $primeraFaseActiva && $estadoAprobacion === 'pendiente') {
                return view('components.validacion-buttons', ['planId' => $id]);
            }
            return '-';
        })->label('Validación')->alignCenter()->excludeFromExport(),

        Column::callback(['id'], function ($id) {
            $evidencias = PlanesDeAccion::find($id)->evidencias()->get();
            return view('components.download-button', ['evidencias' => $evidencias, 'ruta' => 'download_evidencia_plan']);
        }, [], 'evidencias')
            ->label('Evidencias')
            ->alignCenter()
            ->excludeFromExport(),

        Column::name('competencias.name')->label('Competencia')->searchable()->filterable()->defaultSort('asc'),
        // Column::name('planes_de_accion.fecha_de_revision')->label('Fecha de Revisión')->searchable()->filterable()->defaultSort('asc'),
        // Column::name('estados_de_plan_de_accion.name')->label('Estado')->searchable()->filterable()->defaultSort('asc'),
        // Column::callback(['planes_de_accion.avance'], function ($valor) {
        //         return $valor.'%';
        //     })->label('Avance')->alignCenter()->searchable()->filterable()->defaultSort('asc'),
        // Column::callback(['id'], function ($id) {
        //     $evidencias = PlanesDeAccion::find($id)->evidencias()->get();
        //     return view('components.download-button', ['evidencias' => $evidencias, 'ruta' => 'download_evidencia_plan']);
        // },[],'evidencias')
        // ->label('Evidencias')
        // ->alignCenter()
        // ->excludeFromExport(),
        Column::name('gerencias.name')->label('Gerencia')->searchable()->filterable()->defaultSort('asc'),
        Column::name('subgerencias.name')->label('Subgerencia')->searchable()->filterable()->defaultSort('asc'),
        Column::name('areas.name')->label('Area')->searchable()->filterable()->defaultSort('asc'),
        Column::name('created_at')->label('Fecha de creacion')->searchable()->filterable()->defaultSort('asc'),
        Column::name('updated_at')->label('Fecha de Modificación')->searchable()->filterable()->defaultSort('asc'),
        ];
    }

    public function abrirModalValidacion($planId, $estado)
    {
        // dd("modal");
        // $this->procesarValidacion(); // Llamada inicial para evitar problemas de validación
        $this->planSeleccionado = $planId;
        $this->estadoValidacion = $estado;
        $this->observacionValidacion = '';
        $this->showModalValidacion = true;
        // $this->emit('abrirModalValidacion');
    }

    public function cerrarModalValidacion()
    {
        $this->showModalValidacion = false;
        $this->planSeleccionado = null;
        $this->estadoValidacion = '';
        $this->observacionValidacion = '';
    }

    public function procesarValidacion()
    {
        // dd("procesar");
        // $this->estadoValidacion = $estadoValidacion; // 'validado' o 'no_validado'
        // $this->observacionValidacion = $observacion;
        $this->validate([
            'observacionValidacion' => $this->estadoValidacion === 'no_validado' ? 'required|min:10' : 'nullable',
        ], [
            'observacionValidacion.required' => 'La observación es obligatoria para rechazar un plan.',
            'observacionValidacion.min' => 'La observación debe tener al menos 10 caracteres.',
        ]);

        $plan = PlanesDeAccion::with(['empleado', 'encargado', 'encargados_planes_de_accion'])->findOrFail($this->planSeleccionado);
        $estadoAnterior = $plan->estado_aprobacion;

        // Actualizar el plan
        $plan->update([
            'estado_aprobacion' => $this->estadoValidacion,
            'observacion_validacion' => $this->observacionValidacion
        ]);

        // Registrar en historial
        PlanesDeAccionAprobacionHistorial::create([
            'planes_de_accion_id' => $plan->id,
            'user_id' => Auth::id(),
            'estado_anterior' => $estadoAnterior,
            'estado_nuevo' => $this->estadoValidacion,
            'observacion' => $this->observacionValidacion
        ]);

        // Verificar si se deben enviar todos los planes del encargado
        $this->verificarYEnviarCorreoCompleto($plan);

        // Mensaje de éxito
        $mensaje = $this->estadoValidacion === 'validado' 
            ? 'Plan validado correctamente' 
            : 'Plan rechazado. Se ha notificado al empleado.';
        
        session()->flash('message', $mensaje);

        $this->cerrarModalValidacion();
        $this->emit('refreshDatatable');
    }

    /**
     * Verifica si todos los planes del encargado están validados/no_validados
     * y envía el correo con todos los planes si se cumple la condición
     */
    protected function verificarYEnviarCorreoCompleto($plan)
    {
        if (!$plan->encargados_planes_de_accion_id) {
            return; // No tiene encargado asociado
        }

        $encargadoPlan = EncargadosPlanesDeAccion::with([
            'planesDeMejora.competencia',
            'empleado.user',
            'encargado.user'
        ])->find($plan->encargados_planes_de_accion_id);

        if (!$encargadoPlan) {
            return;
        }

        // Obtener todos los planes relacionados
        $todosLosPlanes = $encargadoPlan->planesDeMejora;
        
        // Verificar condición 1: Se alcanzó la cantidad requerida
        if ($todosLosPlanes->count() < $encargadoPlan->cantidad_requerida) {
            return; // No se han ingresado todos los planes requeridos
        }

        // Verificar condición 2: Ningún plan debe estar en estado 'pendiente'
        $hayPendientes = $todosLosPlanes->contains('estado_aprobacion', 'pendiente');
        
        if ($hayPendientes) {
            return; // Aún hay planes pendientes de validación
        }

        // Verificar condición 3: Todos deben estar en 'validado' o 'no_validado'
        $todosRevisados = $todosLosPlanes->every(function($p) {
            return in_array($p->estado_aprobacion, ['validado', 'no_validado']);
        });

        if (!$todosRevisados) {
            return; // No todos están en estado final
        }

        // ✅ Todas las condiciones se cumplen: Enviar correo
        try {
            if ($encargadoPlan->encargado && $encargadoPlan->encargado->user && $encargadoPlan->encargado->user->email) {
                Mail::to($encargadoPlan->encargado->user->email)
                    ->send(new EstadoAprobacionPlanMail($encargadoPlan));
                
                session()->flash('success', 'Todos los planes han sido revisados. Se ha enviado notificación al encargado.');
            }
        } catch (\Exception $e) {
            \Log::error('Error enviando correo de planes validados: ' . $e->getMessage());
            session()->flash('warning', 'Planes actualizados pero no se pudo enviar el email: ' . $e->getMessage());
        }
    }

    public function mostrarAuditorias($id)
    {
        $this->auditorias = Audit::where('auditable_id', $id)->where('auditable_type', PlanesDeAccion::class)->get()->toArray();
        $this->emit('enviarAuditorias', $this->auditorias);
    }
    
    public function export()
    {
        $this->forgetComputed();

        $export = new DatatableExport($this->getExportResultsSet());
        $export->setFileName('Planes.xlsx');
        return $export->download();
    }

//     public function render()
//     {
//         return view('livewire.planes-de-accion-table');
//     }
}
