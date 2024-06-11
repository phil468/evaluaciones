<!-- Modal -->
<div wire:ignore.self class="modal fade" id="actualizarValorModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="actualizarValorModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="rounded-2xl modal-content">
            <div class="text-white modal-header bg-vanguard rounded-t-2xl">
                <h5 class="modal-title h5" id="actualizarValorModalLabel">Actualizar Valor</h5>
                <button type="button" class="text-white close" data-dismiss="modal" aria-label="Close">
                    <span wire:click.prevent="cancel_actualizar_valor()" aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form wire:submit.prevent="actualizarValor({{$selected_id}})">
                    <fieldset wire:target="openModadActualizarValor" wire:loading.attr="disabled">
                        <div class="form-group">
                            <label for="valor">Valor</label>
                            <div class="input-group">
                                <input type="number" inputmode="decimal" class="form-control" id="valor_actualizado" wire:model.defer="valor_actualizado" placeholder="Ingrese Valor">
                                
                                <div class="input-group-append">
                                    <span class="input-group-text" wire:target="tipo_objetivo_id">
                                        {{$simbolo}}
                                    </span>
                                </div>

                                @error('valor_actualizado') <span class="error text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Actualizar</button>
                    </fieldset>
                </form>
            </div>
       </div>
    </div>
</div>
