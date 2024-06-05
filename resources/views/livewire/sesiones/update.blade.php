<!-- Modal -->
<div wire:ignore.self class="modal fade" id="updateModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
       <div class="rounded-2xl modal-content">
            <div class="text-white modal-header bg-vanguard rounded-t-2xl">                <h5 class="modal-title" id="updateModalLabel">Actualizar Sesione</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span wire:click.prevent="cancel()" aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
					<input type="hidden" wire:model="selected_id">
            <div class="form-group">
                <label for="capacitacion_id">Capacitacion Id</label>
                <input wire:model="capacitacion_id" type="text" class="form-control" id="capacitacion_id" placeholder="Capacitacion Id">@error('capacitacion_id') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="numero_de_sesion">Numero De Sesion</label>
                <input wire:model="numero_de_sesion" type="text" class="form-control" id="numero_de_sesion" placeholder="Numero De Sesion">@error('numero_de_sesion') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="fecha">Fecha</label>
                <input wire:model="fecha" type="text" class="form-control" id="fecha" placeholder="Fecha">@error('fecha') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="hora_inicio">Hora Inicio</label>
                <input wire:model="hora_inicio" type="text" class="form-control" id="hora_inicio" placeholder="Hora Inicio">@error('hora_inicio') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="hora_fin">Hora Fin</label>
                <input wire:model="hora_fin" type="text" class="form-control" id="hora_fin" placeholder="Hora Fin">@error('hora_fin') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" wire:click.prevent="cancel()" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" wire:click.prevent="update()" class="btn btn-primary">Guardar</button>
            </div>
       </div>
    </div>
</div>
