<!-- Modal -->
<div wire:ignore.self class="modal fade" id="updateEncargadosPlanesModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="rounded-2xl modal-content">
            <div class="text-white modal-header bg-vanguard rounded-t-2xl">
                <h5 class="modal-title h5" id="updateModalLabel">
                    @if ($this->selected_id == 0)
                        Nuevo Registro Encargado de Planes
                    @else
                        Actualizar Registro Encargado de Planes
                    @endif
                </h5>
                <button type="button" class="text-white close" 
                data-dismiss="modal" 
                aria-label="Close">
                    <span 
                    wire:click.prevent="cancel()" 
                    aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">

                @if (!$this->updateMode)
                Cargando ...
                @endif

                <form>
                    <div class="row">
                        <input type="hidden" wire:model="selected_id">
                        <div class="form-group col-sm-12 col-md-12 col-lg-6 col-xl-4">
                            <label for="encargado_id">Encargado</label>                            
                            <div wire:ignore>
                                <select name="encargado_id" class="form-control" id="encargado_id"
                                    placeholder="Encargado"
                                    >
                                    <option value="">Seleccione</option>
                                </select>
                            </div>
                            @error('encargado_id') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group col-sm-12 col-md-12 col-lg-6 col-xl-4">
                            <label for="empleado_id">Empleado</label>
                            <div wire:ignore>
                                <select name="empleado_id" class="form-control" id="empleado_id"
                                    placeholder="Empleado"
                                    >
                                    <option value="">Seleccione</option>
                                </select>
                            </div>
                            @error('empleado_id') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group col-sm-12 col-md-12 col-lg-6 col-xl-4">
                            <label for="planes_de_accion_configuracion_id">Plan de Mejora</label>
                            <div wire:ignore>
                                <select name="planes_de_accion_configuracion_id" class="form-control" id="planes_de_accion_configuracion_id"
                                    placeholder="Plan de Mejora"
                                    >
                                    <option value="">Seleccione</option>
                                </select>
                            </div>
                            @error('planes_de_accion_configuracion_id') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>                            
                    </div>
                        
                    <fieldset class="row" wire:target="edit,store,update" wire:loading.attr="disabled"
                    @if (!$this->updateMode)                    
                        disabled
                    @endif
                    >
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
                        <div class="form-group col-sm-12 col-md-12 col-lg-6 col-xl-4">
                            <label for="cantidad_requerida">Cantidad Requerida</label>
                            <input wire:model.defer="cantidad_requerida" type="number" inputmode="numeric" class="form-control" id="cantidad_requerida" placeholder="Cantidad Requerida">
                            @error('cantidad_requerida') <span class="error text -danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group col-sm-12 col-md-12 col-lg-6 col-xl-4">
                            <label for="valor_esperado">Valor Esperado</label>
                            <input wire:model.defer="valor_esperado" type="number" inputmode="numeric" class="form-control" id="valor_esperado" placeholder="Valor Esperado">
                            @error('valor_esperado') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        
                        <div class="form-group col-sm-12 col-md-12 col-lg-6 col-xl-4">
                            <label for="jerarquia">Jerarquia</label>
                            <input wire:model.defer="jerarquia" type="text" class="form-control" id="jerarquia" placeholder="Jerarquia">
                            @error('jerarquia') <span class="error text -danger">{{ $message }}</span> @enderror
                        </div>
                        
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
                data-dismiss="modal"
                >Cerrar</button>

                @if ($this->selected_id == 0)
                    <button 
                    type="button" 
                    wire:target="edit,store,update,tipo_de_evaluacion_id" 
                    wire:loading.attr="disabled" 
                    wire:click.prevent="store()" 
                    class="btn btn-lg btn-vanguard rounded-xl close-modal"
                    >Guardar</button>
                @else
                    <button 
                    type="button" 
                    wire:target="edit,store,update,tipo_de_evaluacion_id" 
                    wire:loading.attr="disabled" 
                    wire:click.prevent="update()" 
                    class="btn btn-lg btn-vanguard rounded-xl"
                    >Guardar</button>
                @endif

            </div>
       </div>
    </div>
</div>
