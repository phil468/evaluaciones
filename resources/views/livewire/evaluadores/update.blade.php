<!-- Modal -->
<div wire:ignore.self class="modal fade" id="updateModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
       <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title" id="updateModalLabel">
                    
                    @if ($this->selected_id == 0)
                    Nuevo Evaluador
                @else
                    Actualizar Evaluador
                @endif

                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span wire:click.prevent="cancel()" aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="row">
                        <div @if ($this->cargando) style="display: none;" @else style="display: block;" @endif
                            class="col-12 alert alert-warning" role="alert">
                            Cargando ...
                        </div>

                        <div id="actualizandoV" style="display: none;" class="col-12 alert alert-warning"
                            role="alert">
                            Actualizando Vista ...
                        </div>

					<input type="hidden" wire:model="selected_id">
                    <div class="form-group col-sm-6 col-md-6 col-lg-6 col-xl-4">
                        <label for="evaluador_id">Evaluador</label>
                        <div wire:ignore>
                            <select name="evaluador_id" class="form-control" id="evaluador_id"
                                {{-- placeholder="Empresas" --}}>
                            </select>
                        </div>
                        @error('evaluador_id') <span class="error text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group col-sm-6 col-md-6 col-lg-6 col-xl-4">
                        <label for="evaluado_id">Evaluados</label>
                        <div wire:ignore>
                            <select name="evaluado_id" class="form-control" id="evaluado_id"
                            {{-- multiple --}}
                                {{-- placeholder="Empresas" --}}>
                            </select>
                        </div>
                        @error('evaluado_id') <span class="error text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group col-sm-6 col-md-6 col-lg-6 col-xl-4">
                        <label for="evaluacion_id">Evaluación</label>
                        <div wire:ignore>
                            <select name="evaluacion_id" class="form-control" id="evaluacion_id"
                                {{-- placeholder="Empresas" --}}>
                            </select>
                        </div>
                        @error('evaluacion_id') <span class="error text-danger">{{ $message }}</span> @enderror
                    </div>

                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" wire:click.prevent="cancel()" class="btn btn-secondary"
                    data-dismiss="modal">Cerrar</button>

                @if ($this->selected_id == 0)
                    <button type="button" wire:click.prevent="store()"
                        class="btn btn-primary close-modal">Guardar</button>
                @else
                    <button type="button" wire:click.prevent="update()" class="btn btn-primary"
                        @if (!$this->updateMode) disabled @endif>Guardar</button>
                @endif

            </div>
       </div>
    </div>
</div>
