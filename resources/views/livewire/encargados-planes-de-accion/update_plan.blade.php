<!-- Modal -->
<div wire:ignore.self class="modal fade" id="updatePlanDataModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="rounded-2xl modal-content">
            <div class="text-white modal-header bg-vanguard rounded-t-2xl">
                <h5 class="h5 modal-title" id="updateModalLabel">
                    @if ($this->selected_id == 0)                    
                        Nuevo Plan De Mejora
                    @else
                        Actualizar Plan De Mejora
                    @endif
                </h5>
                <button type="button" class="text-white close" data-dismiss="modal" aria-label="Close">
                    <span wire:click.prevent="cancel()" aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <fieldset class="row" wire:target="edit,store,update" wire:loading.attr="disabled">
                        <input type="hidden" wire:model="selected_id">
                        
                        <div class="form-group col-sm-6 col-md-6 col-lg-6 col-xl-6">
                            <label for="proceso_id">Proceso</label>
                            <select disabled wire:model="proceso_id" class="form-control" id="proceso_id">
                                <option value="">Seleccionar Proceso</option>
                                @foreach($procesos as $index => $name)
                                    <option value="{{ $index}}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group col-sm-6 col-md-6 col-lg-6 col-xl-6">
                            <label for="competencia_id">Competencia</label>
                            <select disabled wire:model="competencia_id" class="form-control" id="competencia_id">
                                <option value="">Seleccionar Competencia</option>
                                @foreach($competencias as $index => $name)
                                    <option value="{{ $index}}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="form-group col-sm-6 col-md-6 col-lg-6 col-xl-6">
                            <label for="empleado_id">Evaluado</label>
                            <select disabled wire:model="empleado_id" class="form-control" id="empleado_id">
                                <option value="">Seleccionar Personal</option>
                                @foreach($personals as $index => $name)
                                    <option value="{{ $index}}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group col-sm-6 col-md-6 col-lg-6 col-xl-6">
                            <label for="encargado_id">Líder</label>
                            <select disabled wire:model="encargado_id" class="form-control" id="encargado_id">
                                <option value="">Seleccionar Encargado</option>
                                @foreach($personals as $index => $name)
                                    <option value="{{ $index}}">{{ $name }}</option>
                                @endforeach
                            </select>
                            @error('encargado_id') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        
                       {{-- CAMPOS DE FEEDBACK - Solo en primera fase --}}
@if($primera_fase_activa)
<div class="col-12">
    <hr class="my-3">
    {{-- <h6 class="mb-3"><i class="fas fa-comment-dots text-info"></i> Feedback</h6> --}}
</div>

<div class="form-group col-sm-6 col-md-6">
    <label class="mb-0">
        <i class="fas fa-comment-dots text-info"></i> Feedback
        @if($tiene_feedback)
            {{-- <small class="text-muted">(Solo lectura - Ya registrado)</small> --}}
        @else
            {{-- <small class="text-muted">(Opcional - Solo se puede registrar una vez)</small> --}}
        @endif
    </label>
    <textarea 
        wire:model="feedback" 
        @if($tiene_feedback) disabled @endif
        class="form-control @if($tiene_feedback) bg-light @endif" 
        rows="3"
        placeholder="@if(!$tiene_feedback) Escriba su feedback aquí... @endif"></textarea>
    @error('feedback') <span class="error text-danger">{{ $message }}</span> @enderror
</div>

<div class="form-group col-sm-6 col-md-6">
    <label class="mb-0">
        <i class="fas fa-calendar-alt text-info"></i> Fecha de Feedback
        @if(!$tiene_feedback && $plan_de_mejora_configuracion)
            <br>
            <small class="text-muted">
                Entre: {{ \Carbon\Carbon::parse($plan_de_mejora_configuracion->fecha_inicio_primera_fase_matricula)->format('d/m/Y') }}
                y {{ \Carbon\Carbon::parse($plan_de_mejora_configuracion->fecha_fin_primera_fase_matricula)->format('d/m/Y') }}
            </small>
        @endif
    </label>
    <input 
        wire:model="fecha_feedback" 
        @if($tiene_feedback) disabled @endif
        type="date" 
        class="form-control @if($tiene_feedback) bg-light @endif"
        @if(!$tiene_feedback && $plan_de_mejora_configuracion)
            min="{{ \Carbon\Carbon::parse($plan_de_mejora_configuracion->fecha_inicio_primera_fase_matricula)->format('Y-m-d') }}"
            max="{{ \Carbon\Carbon::parse($plan_de_mejora_configuracion->fecha_fin_primera_fase_matricula)->format('Y-m-d') }}"
        @endif
    >
    @if($tiene_feedback && $fecha_feedback)
        <small class="mt-1 text-muted d-block">
            Registrado: {{ \Carbon\Carbon::parse($fecha_feedback)->format('d/m/Y') }}
        </small>
    @endif
    @error('fecha_feedback') <span class="error text-danger">{{ $message }}</span> @enderror
</div>

@if($tiene_feedback)
<div class="col-12">
    <div class="alert alert-info" role="alert">
        <i class="fas fa-info-circle"></i> Este plan ya tiene un feedback registrado. No se puede modificar.
    </div>
</div>
@endif

<div class="col-12"><hr class="my-3"></div>
@endif

{{-- Solo mostrar feedbacks en segunda fase (solo lectura) --}}
@if($segunda_fase_activa && $tiene_feedback)
<div class="col-12">
    <div class="mb-3 card border-info">
        <div class="text-white card-header bg-info">
            <i class="fas fa-comment-dots"></i> Feedback Registrado
        </div>
        <div class="card-body">
            <p class="card-text">{{ $feedback }}</p>
            <small class="text-muted">
                <i class="fas fa-calendar"></i> 
                {{ \Carbon\Carbon::parse($fecha_feedback)->format('d/m/Y') }}
            </small>
        </div>
    </div>
</div>
@endif
                                    
                        <div class="form-group col-sm-6 col-md-6 col-lg-6 col-xl-6">
                            <label for="name">Compromiso SMART</label>
                            <textarea
                            @if (!$primera_fase_activa)
                                disabled
                            @endif
                            wire:model.defer="name" type="text" class="form-control" id="name" placeholder="Compromiso"> </textarea>@error('name') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group col-sm-2 col-md-2">
                            <label class="mb-0">Objetivo Medible
                                {{-- <br>
                                 <small class="text-danger">Habilitado 1ra fase</small> --}}
                            </label>
                            <input @if(!$primera_fase_activa) disabled @endif wire:model="objetivo" type="number" step="0.01" class="form-control">
                        </div>
                        
                        <div class="form-group col-sm-12 col-md-2 col-sm-2">
                            <label class="mb-0">Tipo de Objetivo 
                                {{-- <br>
                                <small class="text-muted">(Por default)</small> --}}
                            </label>
                            <select wire:model="tipo_objetivo" class="form-control">
                                <option value="numerico">Numérico</option>
                                <option value="porcentual">Porcentual</option>
                            </select>
                        </div>
            
                        <div class="form-group col-sm-12 col-md-2 col-sm-2">
                            <label for="fecha_de_revision">Fecha De Revisión
                                <br>
                                <small class="text-muted">
                                    Desde: {{ \Carbon\Carbon::parse($plan_de_mejora_configuracion->fecha_inicio_segunda_fase)->format('d/m/Y') }}
                                    Hasta: {{ \Carbon\Carbon::parse($plan_de_mejora_configuracion->fecha_fin_segunda_fase)->format('d/m/Y') }}
                                </small>                               
                            </label>
                            <input
                            @if (!$primera_fase_activa)
                                disabled
                            @endif                            
                            wire:model="fecha_de_revision"
                            type="date" class="form-control" 
                            id="fecha_de_revision" 
                            placeholder="Fecha De Revision" 
                            @if($plan_de_mejora_configuracion)
                                min="{{ \Carbon\Carbon::parse($plan_de_mejora_configuracion->fecha_inicio_segunda_fase)->format('Y-m-d') }}"
                                max="{{ \Carbon\Carbon::parse($plan_de_mejora_configuracion->fecha_fin_segunda_fase)->format('Y-m-d') }}"
                            @endif
                            >
                            @error('fecha_de_revision') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        
                        
                        @if($segunda_fase_activa)
                            <div class="form-group col-sm-12 col-md-3">
                                <label class="mb-0">Alcanzado 
                                    {{-- <small class="text-danger">Habilitado 2da fase</small> --}}
                                </label>
                                <input @if(!$segunda_fase_activa) disabled @endif wire:model="alcanzado" type="number" step="0.01" class="form-control">
                            </div>                        

                            <div class="form-group col-sm-12 col-md-3">
                                <label class="mb-0">% de cumplimiento 
                                    {{-- (calculado) --}}
                                </label>
                                <input disabled wire:model="porcentaje_cumplimiento" type="text" class="form-control">
                            </div>

                            <div class="form-group col-sm-12 col-md-3">
                                <label class="mb-0">Estado de cumplimiento
                                    {{-- (calculado) --}}
                                </label>
                                <input disabled wire:model="estado_cumplimiento" type="text" class="form-control">
                            </div>
                        @endif

                        {{-- @if (!$segunda_fase_activa)
                            <div class="form-group col-sm-12 col-md-12 col-lg-6 col-xl-6">
                                <label for="evidencias">Evidencias</label>
                                <p>
                                    <span class="error text-warning">Subir evidencias solo está activa en la fase correspondiente.</span>
                                </p>
                                 
                            </div>
                        @endif --}}
                        @if ($segunda_fase_activa)
                            <div class="form-group col-sm-12 col-md-12 col-lg-6 col-xl-6">
                                <label for="evidencias">Evidencias</label>
                                <div class="custom-file">
                                    <input 
                                        type="file" 
                                        class="custom-file-input" 
                                        id="evidencias" 
                                        wire:model="evidencias" 
                                        multiple
                                    >
                                    <label class="custom-file-label" for="evidencias">Seleccionar archivos</label>
                                </div>
                                @error('evidencias.*') <span class="error text-danger">{{ $message }}</span> @enderror
                            
                                <div wire:loading wire:target="evidencias" class="mt-2">
                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                    Cargando...
                                </div>
                                @if ($evidenciasGuardadas)
                                    <div class="mt-3">
                                        <h5>Archivos guardados:</h5>
                                        <ul>
                                            @foreach ($evidenciasGuardadas as $index => $evidencia)
                                                <li class="mb-2 d-flex justify-content-between">
                                                    <a href="{{ route('download_evidencia_plan', $evidencia['id']) }}" class="btn btn-link">
                                                        {{ $evidencia['name'] }}
                                                    </a>                                                  
													<button class="btn btn-danger" wire:click="removeEvidenciaGuardada({{ $index }})"
														onclick="confirm('¿Confirma borrar Evidencia : {{$evidencia['name']}}? \n¡Las Evidencias eliminadas no pueden ser recuperadas!')||event.stopImmediatePropagation()"
                                                        wire:loading.attr="disabled"
                                                        wire:target="removeEvidenciaGuardada({{ $index }})"
														>
														<i class="fa fa-trash"></i>
                                                        <span wire:loading wire:target="removeEvidenciaGuardada({{ $index }})" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
													</button>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            </div>
                        @endif
                        
                        {{-- <div class="form-group col-sm-12 col-md-12 col-lg-6 col-xl-6">
                            <label for="gerencia_id">Gerencia Id</label>
                            <input wire:model="gerencia_id" type="text" class="form-control" id="gerencia_id" placeholder="Gerencia Id">@error('gerencia_id') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group col-sm-12 col-md-12 col-lg-6 col-xl-6">
                            <label for="area_id">Area Id</label>
                            <input wire:model="area_id" type="text" class="form-control" id="area_id" placeholder="Area Id">@error('area_id') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div> --}}
                        
                        {{-- <div class="form-group col-sm-12 col-md-12 col-lg-6 col-xl-6">
                            <label for="avance">Avance (%)</label>
                            <input 
                            @if (!$segunda_fase_activa)
                                disabled
                            @endif
                            wire:model="avance" type="number" class="form-control" id="avance" placeholder="Avance">@error('avance') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div> --}}

                        {{-- <div class="form-group col-sm-12 col-md-6">
                            <label class="mb-0">Estado de aprobación 
                                <small class="text-muted">(Pendiente / Validado / No validado)</small>
                            </label>
                            <select 
                                @cannot('validar-planes-mejora') disabled @endcannot
                                wire:model="estado_aprobacion" class="form-control">
                                <option value=null></option>
                                <option value="pendiente">Pendiente</option>
                                <option value="validado">Validado</option>
                                <option value="no_validado">No validado</option>
                            </select>
                        </div> --}}

                        @if($estado_aprobacion === 'no_validado')
                            <div class="form-group col-sm-12">
                                <label>Observación de validación</label>
                                <textarea wire:model.defer="observacion_validacion" disabled class="form-control" rows="2"></textarea>
                            </div>
                        @endif

                        {{-- NUEVOS CAMPOS DE FEEDBACK --}}
                        {{-- @if($feedback) --}}
                        {{-- <div class="form-group col-sm-12 col-md-8">
                            <label class="mb-0">
                                <i class="fas fa-comment-dots text-info"></i> Feedback
                                <small class="text-muted"></small>
                            </label>
                            <textarea disabled wire:model="feedback" class="form-control bg-light" rows="3"></textarea>
                        </div>

                        <div class="form-group col-sm-12 col-md-4">
                            <label class="mb-0">
                                <i class="fas fa-calendar-alt text-info"></i> Fecha de Feedback
                                <small class="text-muted"></small>
                            </label>
                            <input 
                                disabled 
                                wire:model="fecha_feedback" 
                                type="text" 
                                class="form-control bg-light"
                                value="{{ $fecha_feedback ? \Carbon\Carbon::parse($fecha_feedback)->format('d/m/Y H:i') : '-' }}"
                            >
                        </div> --}}
                        {{-- @endif --}}
                    </fieldset>
                </form>
            </div>
            
            <div class="modal-footer">
                <button 
                type="button" 
                wire:click.prevent="cancel_plan()" 
                class="btn btn-secondary close-btn rounded-xl" 
                data-dismiss="modal">Cerrar</button>

                @if ($this->selected_id == 0)
                    <button
                    @if (!$primera_fase_activa && !$segunda_fase_activa)
                        disabled
                    @endif 
                    type="button" 
                    wire:loading.attr="disabled"
                    onclick="confirmarGuardado('nuevo')" 
                    class="btn btn-vanguard rounded-xl close-modal">Enviar a validación</button>
                @else
                    <button 
                    @if (!$primera_fase_activa && !$segunda_fase_activa)
                        disabled
                    @endif 
                    type="button" 
                    wire:loading.attr="disabled" 
                    onclick="confirmarGuardado('actualizar')" 
                    class="btn btn-vanguard rounded-xl">
                    Enviar a validación</button>
                @endif                {{-- <button 
                type="button" 
                wire:click.prevent="update_plan()" 
                class="btn btn-vanguard close-btn rounded-xl">Guardar</button> --}}
                
            </div>
       </div>
    </div>
</div>

{{-- @push('js')
<script>
    function confirmarGuardado(tipo) {
        Swal.fire({
            title: '¿Está seguro de guardar el Plan de Mejora?',
            html: `
                <div class="text-left">
                    <p class="mb-2">Recuerde que es importante que el plan de mejora sea <strong>realista, específico y medible</strong>.</p>
                    <p class="mb-0">Una vez guardado, el plan quedará registrado y podrá ser evaluado posteriormente.</p>
                </div>
            `,
            icon: 'question',
            iconColor: '#568ca5',
            showCancelButton: true,
            confirmButtonText: 'Sí, guardar',
            confirmButtonColor: '#568ca5',
            cancelButtonText: 'Cancelar',
            cancelButtonColor: '#6c757d',
            customClass: {
                popup: 'rounded-xl',
                confirmButton: 'rounded-xl',
                cancelButton: 'rounded-xl'
            },
            allowOutsideClick: false,
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Llamar al método de Livewire según el tipo
                if (tipo === 'nuevo') {
                    @this.call('store_plan');
                } else if (tipo === 'actualizar') {
                    @this.call('update_plan');
                }
            }
        });
    }
</script>
@endpush --}}