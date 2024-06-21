<!-- Modal -->
<div wire:ignore.self class="modal fade" id="createDataModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="createDataModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="rounded-2xl modal-content">
            <div class="text-white modal-header bg-vanguard rounded-t-2xl">                <h5 class="modal-title" id="createDataModalLabel">Nuevo Evaluador Has Evaluado</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true close-btn">×</span>
                </button>
            </div>
           <div class="modal-body">
				<form>
            <div class="form-group">
                <label for="evaluador_id">Evaluador</label>
                {{--option evaluadores--}}
                <select wire:model="evaluador_id" class="form-control" id="evaluador_id" placeholder="Evaluador Id">
                    <option value="">-- Seleccionar --</option>
                    @foreach ($evaluadores as $evaluador)
                        <option value="{{ $evaluador->id }}">{{ $evaluador->name }}</option>
                    @endforeach
                </select>
                {{-- <input wire:model="evaluador_id" type="text" class="form-control" id="evaluador_id" placeholder="Evaluador Id"> --}}
                @error('evaluador_id') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="evaluado_id">Evaluado</label>
                {{--option evaluados--}}
                <select wire:model="evaluado_id" class="form-control" id="evaluado_id" placeholder="Evaluado Id">
                    <option value="">-- Seleccionar --</option>
                    @foreach ($evaluados as $evaluado)
                        <option value="{{ $evaluado->id }}">{{ $evaluado->name }}</option>
                    @endforeach
                </select>

                {{-- <input wire:model="evaluado_id" type="text" class="form-control" id="evaluado_id" placeholder="Evaluado Id"> --}}
                @error('evaluado_id') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="evaluacion">Evaluación</label>
                <select wire:model="evaluacion_id" class="form-control" id="evaluacion" placeholder="Evaluacion">
                    <option value="">-- Seleccionar --</option>
                    @foreach ($evaluaciones as $evaluacion)
                        <option value="{{ $evaluacion->id }}">{{ $evaluacion->title }}</option>
                    @endforeach
                </select>
                @error('evaluacion') <span class="error text-danger">{{ $message }}</span> @enderror
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
