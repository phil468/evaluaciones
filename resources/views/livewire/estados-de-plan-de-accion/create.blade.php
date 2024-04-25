<!-- Modal -->
<div wire:ignore.self class="modal fade" id="createDataModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="createDataModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="rounded-2xl modal-content">
            <div class="text-white modal-header bg-vanguard rounded-t-2xl">
                <h5 class="modal-title h5" id="createDataModalLabel">Nuevo Estados De Plan De Accion</h5>
                <button type="button" class="text-white close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true close-btn">×</span>
                </button>
            </div>
           <div class="modal-body">
				<form>
            <div class="form-group">
                <label for="name">Name</label>
                <input wire:model="name" type="text" class="form-control" id="name" placeholder="Name">@error('name') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="color">Color</label>
                <input wire:model="color" type="color" class="form-control" id="color" placeholder="Color">@error('color') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label>Estado</label>
                <div class="form-check">
                    <input wire:model="estado" type="checkbox" class="form-check-input" id="estado" placeholder="Estado" checked  data-on="Activo" data-off="Inactivo" data-onstyle="success" data-offstyle="danger">
                    <label class="form-check-label" for="estado">
                        Activo
                    </label>
                    @error('estado') <span class="error text-danger">{{ $message }}</span> @enderror
                </div>
            </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary close-btn rounded-xl" data-dismiss="modal">Cerrar</button>
                <button type="button" wire:click.prevent="store()" class="btn btn-vanguard close-modal rounded-xl">Guardar</button>
            </div>
        </div>
    </div>
</div>
