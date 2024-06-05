<!-- Modal -->
<div wire:ignore.self class="modal fade" id="createDataModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="createDataModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="rounded-2xl modal-content">
            <div class="text-white modal-header bg-vanguard rounded-t-2xl">                <h5 class="modal-title" id="createDataModalLabel">Nuevo Area</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true close-btn">×</span>
                </button>
            </div>
           <div class="modal-body">
				<form>
            <div class="form-group">
                <label for="name">Name</label>
                <input wire:model="name" type="text" class="form-control" id="name" placeholder="Name">@error('name') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            
            <div class="form-group">
                <label for="gerencia_id">Gerencia</label>
                <select name="gerencia_id"
                    class="form-control" wire:model.lazy="gerencia_id" class="form-control"
                    id="gerencia_id" placeholder="Gerencias">
                    <option value="">-- Seleccione --</option>
                    @foreach ($gerencias as $id => $name)
                        <option value="{{ $id }}">{{ $name }}</option>
                    @endforeach
                </select>
                @error('gerencia_id')
                    <span class="error text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label>Estado</label>
                <div class="form-check">
                    <input wire:model="estado" type="checkbox" class="form-check-input" id="estado" placeholder="Estado" checked  data-on="Activo" data-off="Inactivo" data-onstyle="success" data-offstyle="danger">
                    <label class="form-check-label" for="estado">
                        Activo
                    </label>
                    @error('estado') <span class="error text-danger">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="form-group">
                <label for="idempresa_nisira">Id empresa Nisira</label>
                <input wire:model="idempresa_nisira" type="text" class="form-control" id="idempresa_nisira" placeholder="Idempresa Nisira">@error('idempresa_nisira') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="idarea_nisira">Id area Nisira</label>
                <input wire:model="idarea_nisira" type="text" class="form-control" id="idarea_nisira" placeholder="Idarea Nisira">@error('idarea_nisira') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="fechacreacion_nisira">Fecha creacion Nisira</label>
                <input wire:model="fechacreacion_nisira" type="date" class="form-control" id="fechacreacion_nisira" placeholder="Fechacreacion Nisira">@error('fechacreacion_nisira') <span class="error text-danger">{{ $message }}</span> @enderror
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
