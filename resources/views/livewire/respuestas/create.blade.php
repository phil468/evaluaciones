<!-- Modal -->
<div wire:ignore.self class="modal fade" id="createDataModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="createDataModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="rounded-2xl modal-content">
            <div class="text-white modal-header bg-vanguard rounded-t-2xl">                <h5 class="modal-title" id="createDataModalLabel">Nuevo Respuesta</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true close-btn">×</span>
                </button>
            </div>
           <div class="modal-body">
				<form>
            <div class="form-group">
                <label for="pregunta_id">Pregunta Id</label>
                <input wire:model="pregunta_id" type="text" class="form-control" id="pregunta_id" placeholder="Pregunta Id">@error('pregunta_id') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="opcion_id">Opcion Id</label>
                <input wire:model="opcion_id" type="text" class="form-control" id="opcion_id" placeholder="Opcion Id">@error('opcion_id') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="valor_numerico">Valor Numerico</label>
                <input wire:model="valor_numerico" type="text" class="form-control" id="valor_numerico" placeholder="Valor Numerico">@error('valor_numerico') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="valor_texto">Valor Texto</label>
                <input wire:model="valor_texto" type="text" class="form-control" id="valor_texto" placeholder="Valor Texto">@error('valor_texto') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="evaluado_id">Evaluado Id</label>
                <input wire:model="evaluado_id" type="text" class="form-control" id="evaluado_id" placeholder="Evaluado Id">@error('evaluado_id') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary close-btn" data-dismiss="modal">Cerrar</button>
                <button type="button" wire:click.prevent="store()" class="btn btn-primary close-modal">Guardar</button>
            </div>
        </div>
    </div>
</div>
