<!-- Modal -->
<div wire:ignore.self class="modal fade" id="updateModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
       <div class="rounded-2xl modal-content">
            <div class="text-white modal-header bg-vanguard rounded-t-2xl">                <h5 class="modal-title" id="updateModalLabel">Actualizar Asignacion Has Activo</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span wire:click.prevent="cancel()" aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
					<input type="hidden" wire:model="selected_id">
            <div class="form-group">
                <label for="activo_id">Activo Id</label>
                <input wire:model="activo_id" type="text" class="form-control" id="activo_id" placeholder="Activo Id">@error('activo_id') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="asignacion_id">Asignacion Id</label>
                <input wire:model="asignacion_id" type="text" class="form-control" id="asignacion_id" placeholder="Asignacion Id">@error('asignacion_id') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="accesorios_entregados">Accesorios Entregados</label>
                <input wire:model="accesorios_entregados" type="text" class="form-control" id="accesorios_entregados" placeholder="Accesorios Entregados">@error('accesorios_entregados') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="accesorios_devueltos">Accesorios Devueltos</label>
                <input wire:model="accesorios_devueltos" type="text" class="form-control" id="accesorios_devueltos" placeholder="Accesorios Devueltos">@error('accesorios_devueltos') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="performance_id">Performance Id</label>
                <input wire:model="performance_id" type="text" class="form-control" id="performance_id" placeholder="Performance Id">@error('performance_id') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="vigencia_id">Vigencia Id</label>
                <input wire:model="vigencia_id" type="text" class="form-control" id="vigencia_id" placeholder="Vigencia Id">@error('vigencia_id') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="fecha_de_vigencia">Fecha De Vigencia</label>
                <input wire:model="fecha_de_vigencia" type="text" class="form-control" id="fecha_de_vigencia" placeholder="Fecha De Vigencia">@error('fecha_de_vigencia') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="devuelto">Devuelto</label>
                <input wire:model="devuelto" type="text" class="form-control" id="devuelto" placeholder="Devuelto">@error('devuelto') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="fecha_de_devolucion">Fecha De Devolucion</label>
                <input wire:model="fecha_de_devolucion" type="text" class="form-control" id="fecha_de_devolucion" placeholder="Fecha De Devolucion">@error('fecha_de_devolucion') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="observaciones">Observaciones</label>
                <input wire:model="observaciones" type="text" class="form-control" id="observaciones" placeholder="Observaciones">@error('observaciones') <span class="error text-danger">{{ $message }}</span> @enderror
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
