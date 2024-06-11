<!-- Modal -->
<div wire:ignore.self class="modal fade" id="evidenciaModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="evidenciasModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="rounded-2xl modal-content">
            <div class="text-white modal-header bg-vanguard rounded-t-2xl">
                <h5 class="modal-title h5" id="evidenciasModalLabel">Subir evidencia</h5>
                <button type="button" class="text-white close" data-dismiss="modal" aria-label="Close">
                    <span wire:click.prevent="cancel_evidencias()" aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form wire:submit.prevent="uploadEvidencia({{$selected_id}})">
                    <fieldset wire:target="openModadEvidencias" wire:loading.attr="disabled">
                        <div class="form-group">
                            <label for="evidencia">Evidencia</label>
                                <input type="file" class="form-control" id="evidencia_subir" wire:model.defer="evidencia_subir" placeholder="Ingrese Valor">
                                @error('evidencia_subir') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">Cargar</button>
                    </fieldset>
                </form>
            </div>
       </div>
    </div>
</div>
