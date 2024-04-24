<!-- Modal -->
<div wire:ignore.self class="modal fade" id="updateModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="rounded-2xl modal-content">
            <div class="text-white modal-header bg-vanguard rounded-t-2xl">
                <h5 class="modal-title" id="updateModalLabel">Actualizar Objetivo</h5>
                <button type="button" class="text-white close" data-dismiss="modal" aria-label="Close">
                    <span wire:click.prevent="cancel()" aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
					<input type="hidden" wire:model="selected_id">
            {{-- <div class="form-group">
                <label for="evaluado_id">Evaluado Id</label>
                <input wire:model="evaluado_id" type="text" class="form-control" id="evaluado_id" placeholder="Evaluado Id">@error('evaluado_id') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="evaluador_id">Evaluador Id</label>
                <input wire:model="evaluador_id" type="text" class="form-control" id="evaluador_id" placeholder="Evaluador Id">@error('evaluador_id') <span class="error text-danger">{{ $message }}</span> @enderror
            </div> --}}
            <div class="form-group">
                <label for="descripcion">Descripcion*</label>
                <textarea wire:model.defer="descripcion" type="text" class="form-control" id="descripcion" placeholder="Descripcion"> </textarea>@error('descripcion') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="tipo_objetivo_id">Tipo de Objetivo*</label>
                <select class="form-control" id="tipo_objetivo_id" wire:model="tipo_objetivo_id">
                    @foreach ($tipos_objetivo as $tipo)
                        <option value="{{ $tipo->id }}">{{ $tipo->unidad.'('.$tipo->simbolo.')' }}</option>
                    @endforeach
                </select>
                @error('tipo_objetivo_id') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="resultado">Resultado</label>
                <input wire:model="resultado" type="numeric" class="form-control" id="resultado" placeholder="Resultado" disabled>@error('resultado') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="evidencia">Evidencia (Máx: 10MB)</label>
                <input wire:model="evidencia" type="file" class="form-control" id="evidencia" placeholder="Evidencia" disabled>@error('evidencia') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" wire:click.prevent="cancel()" class="btn rounded-xl btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" wire:click.prevent="update()" class="btn rounded-xl btn-vanguard">Guardar</button>
            </div>
       </div>
    </div>
</div>
