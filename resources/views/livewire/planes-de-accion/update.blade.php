<!-- Modal -->
<div wire:ignore.self class="modal fade" id="updateModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="rounded-2xl modal-content">
            <div class="text-white modal-header bg-vanguard rounded-t-2xl">
                <h5 class="h5 modal-title" id="updateModalLabel">Actualizar Planes De Accion</h5>
                <button type="button" class="text-white close" data-dismiss="modal" aria-label="Close">
                    <span wire:click.prevent="cancel()" aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
					<input type="hidden" wire:model="selected_id">
            <div class="form-group">
                <label for="encargado_id">Encargado Id</label>
                <input wire:model="encargado_id" type="text" class="form-control" id="encargado_id" placeholder="Encargado Id">@error('encargado_id') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="empleado_id">Empleado Id</label>
                <input wire:model="empleado_id" type="text" class="form-control" id="empleado_id" placeholder="Empleado Id">@error('empleado_id') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="competencia_id">Competencia Id</label>
                <input wire:model="competencia_id" type="text" class="form-control" id="competencia_id" placeholder="Competencia Id">@error('competencia_id') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="tipo_de_proceso_id">Tipo De Proceso Id</label>
                <input wire:model="tipo_de_proceso_id" type="text" class="form-control" id="tipo_de_proceso_id" placeholder="Tipo De Proceso Id">@error('tipo_de_proceso_id') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="proceso_id">Proceso Id</label>
                <input wire:model="proceso_id" type="text" class="form-control" id="proceso_id" placeholder="Proceso Id">@error('proceso_id') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="fecha_de_revision">Fecha De Revision</label>
                <input wire:model="fecha_de_revision" type="text" class="form-control" id="fecha_de_revision" placeholder="Fecha De Revision">@error('fecha_de_revision') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="estado_id">Estado Id</label>
                <input wire:model="estado_id" type="text" class="form-control" id="estado_id" placeholder="Estado Id">@error('estado_id') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="gerencia_id">Gerencia Id</label>
                <input wire:model="gerencia_id" type="text" class="form-control" id="gerencia_id" placeholder="Gerencia Id">@error('gerencia_id') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="area_id">Area Id</label>
                <input wire:model="area_id" type="text" class="form-control" id="area_id" placeholder="Area Id">@error('area_id') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="avance">Avance</label>
                <input wire:model="avance" type="text" class="form-control" id="avance" placeholder="Avance">@error('avance') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="name">Name</label>
                <input wire:model="name" type="text" class="form-control" id="name" placeholder="Name">@error('name') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" wire:click.prevent="cancel()" class="btn btn-secondary rounded-xl" data-dismiss="modal">Cerrar</button>
                <button type="button" wire:click.prevent="update()" class="btn btn-vanguard rounded-xl">Guardar</button>
            </div>
       </div>
    </div>
</div>
