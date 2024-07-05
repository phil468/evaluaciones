<!-- Modal -->
<div wire:ignore.self class="modal fade" id="updateModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="rounded-2xl modal-content">
            <div class="text-white modal-header bg-vanguard rounded-t-2xl">
                <h5 class="modal-title h5" id="updateModalLabel" wire:loading.remove wire:target="edit">
                    @if ($this->selected_id == 0)
                        Nuevo Registro Evaluadores
                    @else
                        Actualizar Registro Evaluadores
                    @endif
                </h5>
                <button type="button" class="text-white close" data-dismiss="modal" aria-label="Close">
                    <span wire:click.prevent="cancel()" aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                
                @if (!$this->updateMode)
                Cargando ...
                @endif

                <form>
                    <fieldset class="row" wire:target="edit,store,update" wire:loading.attr="disabled"
                    @if (!$this->updateMode)                    
                        disabled
                    @endif
                    >

                        <input type="hidden" wire:model="selected_id">
                        
                        <div class="form-group col-sm-12 col-md-12 col-lg-6 col-xl-4">
                            <label for="evaluador_id">Evaluador</label>
                            <div wire:ignore>
                                <select name="evaluador_id" class="form-control" id="evaluador_id"
                                    placeholder="Encargado"
                                    >
                                    <option value="">Seleccione</option>
                                </select>
                            </div>
                            @error('evaluador_id') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        
                        <div class="form-group col-sm-12 col-md-12 col-lg-6 col-xl-4">
                            <label for="evaluado_id">Evaluado</label>
                            <div wire:ignore>
                                <select name="evaluado_id" class="form-control" id="evaluado_id"
                                    placeholder="Empleado"
                                    >
                                    <option value="">Seleccione</option>
                                </select>
                            </div>
                            @error('evaluado_id') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        
                        <div class="form-group col-sm-12 col-md-12 col-lg-6 col-xl-4">
                            <label for="evaluacion_id">Evaluación</label>
                            <div wire:ignore>
                                <select name="evaluacion_id" class="form-control" id="evaluacion_id"
                                    placeholder="Evaluación"
                                    >
                                    <option value="">Seleccione</option>
                                </select>
                            </div>
                            @error('evaluacion_id') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div> 
                        
                        <div class="form-group col-sm-12 col-md-12 col-lg-6 col-xl-4">
                            <label for="cargo_de_evaluador">Cargo de Evaluador</label>
                            <input wire:model.defer="cargo_de_evaluador" type="text" class="form-control" id="cargo_de_evaluador" placeholder="Cargo de Evaluador">
                            @error('cargo_de_evaluador') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group col-sm-12 col-md-12 col-lg-6 col-xl-4">
                            <label for="area_de_evaluador">Área de Evaluador</label>
                            <input wire:model.defer="area_de_evaluador" type="text" class="form-control" id="area_de_evaluador" placeholder="Área de Evaluador">
                            @error('area_de_evaluador') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group col-sm-12 col-md-12 col-lg-6 col-xl-4">
                            <label for="gerencia_sub_gerencia_de_evaluador">Gerencia Sub Gerencia de Evaluador</label>
                            <input wire:model.defer="gerencia_sub_gerencia_de_evaluador" type="text" class="form-control" id="gerencia_sub_gerencia_de_evaluador" placeholder="Gerencia Sub Gerencia de Evaluador">
                            @error('gerencia_sub_gerencia_de_evaluador') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        
                        <div class="form-group col-sm-12 col-md-12 col-lg-6 col-xl-4">
                            <label for="cargo_de_evaluado">Cargo de Evaluado</label>
                            <input wire:model.defer="cargo_de_evaluado" type="text" class="form-control" id="cargo_de_evaluado" placeholder="Cargo de Evaluado">
                            @error('cargo_de_evaluado') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group col-sm-12 col-md-12 col-lg-6 col-xl-4">
                            <label for="area_de_evaluado">Área de Evaluado</label>
                            <input wire:model.defer="area_de_evaluado" type="text" class="form-control" id="area_de_evaluado" placeholder="Área de Evaluado">
                            @error('area_de_evaluado') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group col-sm-12 col-md-12 col-lg-6 col-xl-4">
                            <label for="gerencia_sub_gerencia_de_evaluado">Gerencia Sub Gerencia de Evaluado</label>
                            <input wire:model.defer="gerencia_sub_gerencia_de_evaluado" type="text" class="form-control" id="gerencia_sub_gerencia_de_evaluado" placeholder="Gerencia Sub Gerencia de Evaluado">
                            @error('gerencia_sub_gerencia_de_evaluado') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        {{-- <div class="form-group col-sm-12 col-md-12 col-lg-6 col-xl-4">
                            <label for="cantidad_requerida">Cantidad Requerida</label>
                            <input wire:model="cantidad_requerida" type="text" class="form-control" id="cantidad_requerida" placeholder="Cantidad Requerida">
                            @error('cantidad_requerida') <span class="error text -danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group col-sm-12 col-md-12 col-lg-6 col-xl-4">
                            <label for="valor_esperado">Valor Esperado</label>
                            <input wire:model="valor_esperado" type="text" class="form-control" id="valor_esperado" placeholder="Valor Esperado">
                            @error('valor_esperado') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        
                        <div class="form-group col-sm-12 col-md-12 col-lg-6 col-xl-4">
                            <label for="jerarquia">Jerarquia</label>
                            <input wire:model="jerarquia" type="text" class="form-control" id="jerarquia" placeholder="Jerarquia">
                            @error('jerarquia') <span class="error text -danger">{{ $message }}</span> @enderror
                        </div> --}}

                        @if ($this->tipo_de_evaluacion_id == 2)
                            <div class="form-group col-sm-12 col-md-12 col-lg-6 col-xl-4">
                                <label for="jerarquia">Jerarquía</label>
                                <div>
                                    <select name="jerarquia" class="form-control" id="jerarquia" placeholder="Evaluación" wire:model='jerarquia'>
                                        <option value="">Seleccione</option>
                                        <option value=1>TIPO 1 (INDIVIDUAL)</option>
                                        <option value=2>TIPO 2 (GRUPAL)</option>
                                    </select>
                                </div>
                                @if (session()->has('cambioJerarquia'))
                                    <div class="btn btn-sm btn-warning" style="margin-top:0px; margin-bottom:0px;"> {{ session('cambioJerarquia') }} </div>
                                @endif
                                @error('jerarquia') <span class="error text-danger">{{ $message }}</span> @enderror
                            </div> 
                        @endif
                        
                    </fieldset>
                </form>
            </div>
            <div class="modal-footer">
                <button 
                type="button" 
                wire:target="edit,store,update" 
                wire:loading.attr="disabled" 
                wire:click.prevent="cancel()" 
                class="btn btn-secondary rounded-xl" 
                @if (!$this->updateMode)                    
                    disabled
                @endif
                data-dismiss="modal">
                    Cerrar
                </button>

                @if ($this->selected_id == 0)
                    <button 
                    type="button" 
                    wire:target="edit,store,update,tipo_de_evaluacion_id, jerarquia" 
                    wire:loading.attr="disabled" 
                    wire:click.prevent="store()" 
                    @if (!$this->updateMode)                    
                        disabled
                    @endif
                    class="btn btn-lg btn-vanguard rounded-xl close-modal">
                    Guardar</button>
                @else
                    <button 
                    type="button" 
                    wire:target="edit,store,update,tipo_de_evaluacion_id, jerarquia" 
                    wire:loading.attr="disabled"
                    @if (!$this->updateMode)                    
                        disabled
                    @endif
                    @if (session()->has('cambioJerarquia'))
                        x-on:click="confirm('¿Confirma que desea actualizar? Eliminará los objetivos y los volverá a cargar. \n ¡Los objetivos eliminados no pueden ser recuperados!') ? $wire.update() : event.stopImmediatePropagation()"
                    @else
                        wire:click.prevent="update()"
                    @endif
                    {{-- wire:click="update()" --}}
                    class="btn btn-lg btn-vanguard rounded-xl"
                    >Guardar</button>
                @endif

            </div>
       </div>
    </div>
</div>
