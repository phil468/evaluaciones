<!-- Modal -->
<div wire:ignore.self class="modal fade" id="updateModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
       <div class="rounded-2xl modal-content">
            <div class="text-white modal-header bg-vanguard rounded-t-2xl">                <h5 class="modal-title" id="updateModalLabel">
                    @if ($this->selected_id == 0)                    
                    Nuevo Planilla
                    @else
                    Actualizar Planilla
                    @endif
                </h5>
                <button type="button" class="text-white close" data-dismiss="modal" aria-label="Close">
                    <span wire:click.prevent="cancel()" aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
					<input type="hidden" wire:model="selected_id">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input wire:model.defer="name" type="text" class="form-control" id="name" placeholder="Name">@error('name') <span class="error text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label>Estado</label>
                        <div class="form-check">
                            <label class="switch">
                                <input 
                                type="checkbox" 
                                wire:model="estado" id="estado" name="estado"
                                >
                                <span class="slider round"></span>
                            </label>
                            @if ($estado)
                                Activo
                            @else
                                Inactivo
                            @endif
                            @error('estado') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="sede_id">Sede *</label>
                        <div wire:ignore>
                        <select 
                        name="sede_id"
                            class="form-control" id="sede_id"
                            placeholder="Sede">
                        </select>
                        </div>
                        @error('sede_id')
                            <span class="error text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" wire:click.prevent="cancel()" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                @if ($this->selected_id == 0)                    
                <button type="button" wire:click.prevent="store()" class="btn btn-primary close-modal">Guardar</button>
                @else
                    <button type="button" wire:click.prevent="update()" class="btn btn-primary" 
                    @if (!$this->updateMode)                    
                        disabled
                    @endif >Guardar</button>
                @endif            
            </div>
       </div>
    </div>
</div>
