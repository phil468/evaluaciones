<!-- Modal -->
{{-- <div wire:ignore.self class="modal fade" id="updateModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
       <div class="rounded-2xl modal-content">
            <div class="text-white modal-header bg-vanguard rounded-t-2xl">                <h5 class="modal-title" id="updateModalLabel">Actualizar Asignacione</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span wire:click.prevent="cancel()" aria-hidden="true">×</span>
                </button>
            </div> --}}
            @if ($update)

            <div>
                <div class="modal-body">
                    <form>
                        <input type="hidden" wire:model="selected_id">
                        <div class="form-group">
                            <label for="personal_id">Personal Id</label>
                            <input wire:model="personal_id" type="text" class="form-control" id="personal_id" placeholder="Personal Id">@error('personal_id') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label for="empresa_id">Empresa Id</label>
                            <input wire:model="empresa_id" type="text" class="form-control" id="empresa_id" placeholder="Empresa Id">@error('empresa_id') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label for="gerencia_id">Gerencia Id</label>
                            <input wire:model="gerencia_id" type="text" class="form-control" id="gerencia_id" placeholder="Gerencia Id">@error('gerencia_id') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label for="sede_id">Sede Id</label>
                            <input wire:model="sede_id" type="text" class="form-control" id="sede_id" placeholder="Sede Id">@error('sede_id') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label for="area_id">Area Id</label>
                            <input wire:model="area_id" type="text" class="form-control" id="area_id" placeholder="Area Id">@error('area_id') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label for="cargo_id">Cargo Id</label>
                            <input wire:model="cargo_id" type="text" class="form-control" id="cargo_id" placeholder="Cargo Id">@error('cargo_id') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label for="fecha">Fecha</label>
                            <input wire:model="fecha" type="text" class="form-control" id="fecha" placeholder="Fecha">@error('fecha') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label for="responsable_id">Responsable Id</label>
                            <input wire:model="responsable_id" type="text" class="form-control" id="responsable_id" placeholder="Responsable Id">@error('responsable_id') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label for="responsable_area_id">Responsable Area Id</label>
                            <input wire:model="responsable_area_id" type="text" class="form-control" id="responsable_area_id" placeholder="Responsable Area Id">@error('responsable_area_id') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label for="responsable_cargo_id">Responsable Cargo Id</label>
                            <input wire:model="responsable_cargo_id" type="text" class="form-control" id="responsable_cargo_id" placeholder="Responsable Cargo Id">@error('responsable_cargo_id') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        {{-- <div class="form-group">
                            <label for="created_by">Created By</label>
                            <input wire:model="created_by" type="text" class="form-control" id="created_by" placeholder="Created By">@error('created_by') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label for="updated_by">Updated By</label>
                            <input wire:model="updated_by" type="text" class="form-control" id="updated_by" placeholder="Updated By">@error('updated_by') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label for="deleted_by">Deleted By</label>
                            <input wire:model="deleted_by" type="text" class="form-control" id="deleted_by" placeholder="Deleted By">@error('deleted_by') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div> --}}
                        <div class="form-group">
                            <label for="pdf">Pdf</label>
                            <input wire:model="pdf" type="text" class="form-control" id="pdf" placeholder="Pdf">@error('pdf') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
    
                    </form>
                </div>
    
                {{-- <div class="modal-footer">
                    <button type="button" wire:click.prevent="cancel()" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="button" wire:click.prevent="update()" class="btn btn-primary">Guardar</button>
                </div>
           </div>
        </div> --}}
            </div>
                
            @endif

