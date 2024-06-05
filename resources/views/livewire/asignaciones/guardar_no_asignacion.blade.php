<!-- Modal -->
<div wire:ignore.self class="modal fade" id="guardarNoAsignacionModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="guardarNoAsignacionLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
       <div class="rounded-2xl modal-content">
            <div class="text-white modal-header bg-vanguard rounded-t-2xl">                <h5 class="modal-title" id="guardarNoAsignacionLabel">No Asignación de activos</h5>
                <button type="button" class="text-white close" data-dismiss="modal" aria-label="Close">
                    <span wire:click.prevent="cancel_no_asignacion()" aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
					<input type="hidden" wire:model="selected_activo_id">

                    <div class="form-group">
                        <label for="observaciones_no_asignacion">Observaciones </label>
                        <input type="text" wire:model.defer="observaciones_no_asignacion" class="form-control" id="observaciones_no_asignacion" placeholder="Observaciones">@error('observaciones_no_asignacion') <span class="error text-danger">{{ $message }}</span> @enderror
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" wire:click.prevent="cancel_no_asignacion()" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" wire:click.prevent="update_activo_no_asignacion({{$selected_activo_index}},1)" class="btn btn-primary close-modal">Guardar</button>
                <button type="button" wire:click.prevent="update_activo_no_asignacion({{$selected_activo_index}},0)" class="btn btn-primary close-modal">Quitar sin observaciones</button>
            </div>
       </div>
    </div>
</div>
