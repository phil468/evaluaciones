<!-- Modal -->
<div wire:ignore.self class="modal fade" id="updateModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
       <div class="rounded-2xl modal-content">
            <div class="text-white modal-header bg-vanguard rounded-t-2xl">                <h5 class="modal-title" id="updateModalLabel">Actualizar Pregunta</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span wire:click.prevent="cancel()" aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
					<input type="hidden" wire:model="selected_id">
            <div class="form-group">
                <label for="seccion_id">Seccion Id</label>
                <input wire:model="seccion_id" type="text" class="form-control" id="seccion_id" placeholder="Seccion Id">@error('seccion_id') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="evaluacion_id">Evaluacion Id</label>
                <input wire:model="evaluacion_id" type="text" class="form-control" id="evaluacion_id" placeholder="Evaluacion Id">@error('evaluacion_id') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="qid">Qid</label>
                <input wire:model="qid" type="text" class="form-control" id="qid" placeholder="Qid">@error('qid') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="pregunta">Pregunta</label>
                <input wire:model="pregunta" type="text" class="form-control" id="pregunta" placeholder="Pregunta">@error('pregunta') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="tipo">Tipo</label>
                <input wire:model="tipo" type="text" class="form-control" id="tipo" placeholder="Tipo">@error('tipo') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="opciones">Opciones</label>
                <input wire:model="opciones" type="text" class="form-control" id="opciones" placeholder="Opciones">@error('opciones') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="numero_orden">Numero Orden</label>
                <input wire:model="numero_orden" type="text" class="form-control" id="numero_orden" placeholder="Numero Orden">@error('numero_orden') <span class="error text-danger">{{ $message }}</span> @enderror
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
