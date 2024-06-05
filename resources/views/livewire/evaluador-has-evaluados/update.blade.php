<!-- Modal -->
<div wire:ignore.self class="modal fade" id="updateModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="rounded-2xl modal-content">
            <div class="text-white modal-header bg-vanguard rounded-t-2xl">
                <h5 class="modal-title" id="updateModalLabel">
                    @if ($this->selected_id == 0)
                        Nuevo Evaluador
                    @else
                        Actualizar Evaluador
                    @endif
                    Actualizar Evaluador Has Evaluado
                </h5>
                <button type="button" class="text-white close" data-dismiss="modal" aria-label="Close">
                    <span wire:click.prevent="cancel()" aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
					<input type="hidden" wire:model="selected_id">
                    <div class="form-group">
                        <label for="evaluador_id">Evaluador Id</label>
                        <input wire:model="evaluador_id" type="text" class="form-control" id="evaluador_id" placeholder="Evaluador Id">@error('evaluador_id') <span class="error text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label for="evaluado_id">Evaluado Id</label>
                        <input wire:model="evaluado_id" type="text" class="form-control" id="evaluado_id" placeholder="Evaluado Id">@error('evaluado_id') <span class="error text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label for="evaluacion">Evaluacion</label>
                        <input wire:model="evaluacion" type="text" class="form-control" id="evaluacion" placeholder="Evaluacion">@error('evaluacion') <span class="error text-danger">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="cargo_de_evaluador">Cargo de Evaluador</label>
                        <input wire:model="cargo_de_evaluador" type="text" class="form-control" id="cargo_de_evaluador" placeholder="Cargo de Evaluador">
                        @error('cargo_de_evaluador') <span class="error text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label for="area_de_evaluador">Área de Evaluador</label>
                        <input wire:model="area_de_evaluador" type="text" class="form-control" id="area_de_evaluador" placeholder="Área de Evaluador">
                        @error('area_de_evaluador') <span class="error text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label for="gerencia_sub_gerencia_de_evaluador">Gerencia Sub Gerencia de Evaluador</label>
                        <input wire:model="gerencia_sub_gerencia_de_evaluador" type="text" class="form-control" id="gerencia_sub_gerencia_de_evaluador" placeholder="Gerencia Sub Gerencia de Evaluador">
                        @error('gerencia_sub_gerencia_de_evaluador') <span class="error text-danger">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="cargo_de_evaluado">Cargo de Evaluado</label>
                        <input wire:model="cargo_de_evaluado" type="text" class="form-control" id="cargo_de_evaluado" placeholder="Cargo de Evaluado">
                        @error('cargo_de_evaluado') <span class="error text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label for="area_de_evaluado">Área de Evaluado</label>
                        <input wire:model="area_de_evaluado" type="text" class="form-control" id="area_de_evaluado" placeholder="Área de Evaluado">
                        @error('area_de_evaluado') <span class="error text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label for="gerencia_sub_gerencia_de_evaluado">Gerencia Sub Gerencia de Evaluado</label>
                        <input wire:model="gerencia_sub_gerencia_de_evaluado" type="text" class="form-control" id="gerencia_sub_gerencia_de_evaluado" placeholder="Gerencia Sub Gerencia de Evaluado">
                        @error('gerencia_sub_gerencia_de_evaluado') <span class="error text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label for="cantidad_requerida">Cantidad Requerida</label>
                        <input wire:model="cantidad_requerida" type="text" class="form-control" id="cantidad_requerida" placeholder="Cantidad Requerida">
                        @error('cantidad_requerida') <span class="error text -danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label for="valor_esperado">Valor Esperado</label>
                        <input wire:model="valor_esperado" type="text" class="form-control" id="valor_esperado" placeholder="Valor Esperado">
                        @error('valor_esperado') <span class="error text-danger">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="jerarquia">Jerarquia</label>
                        <input wire:model="jerarquia" type="text" class="form-control" id="jerarquia" placeholder="Jerarquia">
                        @error('jerarquia') <span class="error text -danger">{{ $message }}</span> @enderror
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
