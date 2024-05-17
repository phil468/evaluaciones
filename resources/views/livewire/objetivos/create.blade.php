<!-- Modal -->
<div wire:ignore.self class="modal fade" id="createDataModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="createDataModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="rounded-2xl modal-content">
            <div class="text-white modal-header bg-vanguard rounded-t-2xl">
                <h5 class="modal-title" id="createDataModalLabel">Nuevo Objetivo</h5>
                <button type="button" class="text-white close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true close-btn">×</span>
                </button>
            </div>
           <div class="modal-body">
				<form>
            {{-- <div class="form-group">
                <label for="evaluado_id">Evaluado Id</label>
                <input wire:model="evaluado_id" type="text" class="form-control" id="evaluado_id" placeholder="Evaluado Id">@error('evaluado_id') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="evaluador_id">Evaluador Id</label>
                <input wire:model="evaluador_id" type="text" class="form-control" id="evaluador_id" placeholder="Evaluador Id">@error('evaluador_id') <span class="error text-danger">{{ $message }}</span> @enderror
            </div> --}}
            <div class="form-group">
                <label for="descripcion">Meta*</label>
                <textarea wire:model.defer="descripcion" type="text" class="form-control" id="descripcion" placeholder="Descripcion"> </textarea>@error('descripcion') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="tipo_objetivo_id">Tipo de Objetivo*</label>
                <select class="form-control" id="tipo_objetivo_id" wire:model="tipo_objetivo_id" value="2" disabled>
                    <option value="">Seleccione un tipo de objetivo</option>
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
                <label for="participacion">% Participac.</label>
                <input type="text" class="form-control" value="40%" id="participacion" placeholder="% Participac." disabled>
            </div>
            
            <div class="form-group">
                <label for="evidencia">Evidencia (Máx: 10MB)</label>
                <input wire:model="evidencia" type="file" class="form-control" id="evidencia" placeholder="Evidencia" disabled>@error('evidencia') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>


            <div class="form-group">
                <label for="resultadoEsperado">Result. Anterior / Esperado</label>
                <input type="text" value="30%" class="form-control" id="resultadoEsperado" placeholder="Result. Anterior / Esperado" disabled>
            </div>
            <div class="form-group">
                <label for="minimo">Mínimo 80%</label>
                <input type="text" value="24%" class="form-control" id="minimo" placeholder="Mínimo 80%" disabled>
            </div>
            <div class="form-group">
                <label for="maximo">Máximo 120%</label>
                <input type="text" value="36%" class="form-control" id="maximo" placeholder="Máximo 120%" disabled>
            </div>
            <div class="form-group">
                <label for="valor">Valor</label>
                <input type="number" class="form-control" id="valor" placeholder="Valor" disabled>
            </div>
            <div class="form-group">
                <label for="logroSTI">% Logr. STI</label>
                <input type="number" class="form-control" id="logroSTI" placeholder="% Logr. STI" disabled>
            </div>
            <div class="form-group">
                <label for="pesoPond">Peso Pond.</label>
                <input type="number" class="form-control" id="pesoPond" placeholder="Peso Pond." disabled>
            </div>


                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn rounded-xl btn-secondary close-btn" data-dismiss="modal">Cerrar</button>
                <button type="button" wire:click.prevent="store()" class="btn rounded-xl btn-vanguard close-modal">Guardar</button>
            </div>
        </div>
    </div>
</div>
