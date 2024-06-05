<!-- Modal -->
<div wire:ignore.self class="modal fade" id="createDataModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="createDataModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="rounded-2xl modal-content">
            <div class="text-white modal-header bg-vanguard rounded-t-2xl">                <h5 class="modal-title" id="createDataModalLabel">Nuevo Asistencium</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true close-btn">×</span>
                </button>
            </div>
           <div class="modal-body">
				<form>
            <div class="form-group">
                <label for="sesion_id">Sesion Id</label>
                <input wire:model="sesion_id" type="text" class="form-control" id="sesion_id" placeholder="Sesion Id">@error('sesion_id') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="personal_id">Personal Id</label>
                <input wire:model="personal_id" type="text" class="form-control" id="personal_id" placeholder="Personal Id">@error('personal_id') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="active">Active</label>
                <input wire:model="active" type="text" class="form-control" id="active" placeholder="Active">@error('active') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="observaciones">Observaciones</label>
                <input wire:model="observaciones" type="text" class="form-control" id="observaciones" placeholder="Observaciones">@error('observaciones') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="empresa_id">Empresa Id</label>
                <input wire:model="empresa_id" type="text" class="form-control" id="empresa_id" placeholder="Empresa Id">@error('empresa_id') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="gerencia_id">Gerencia Id</label>
                <input wire:model="gerencia_id" type="text" class="form-control" id="gerencia_id" placeholder="Gerencia Id">@error('gerencia_id') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="area_id">Area Id</label>
                <input wire:model="area_id" type="text" class="form-control" id="area_id" placeholder="Area Id">@error('area_id') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="cargo_id">Cargo Id</label>
                <input wire:model="cargo_id" type="text" class="form-control" id="cargo_id" placeholder="Cargo Id">@error('cargo_id') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="planilla_id">Planilla Id</label>
                <input wire:model="planilla_id" type="text" class="form-control" id="planilla_id" placeholder="Planilla Id">@error('planilla_id') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="sede_id">Sede Id</label>
                <input wire:model="sede_id" type="text" class="form-control" id="sede_id" placeholder="Sede Id">@error('sede_id') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="tipo_de_trabajador_id">Tipo De Trabajador Id</label>
                <input wire:model="tipo_de_trabajador_id" type="text" class="form-control" id="tipo_de_trabajador_id" placeholder="Tipo De Trabajador Id">@error('tipo_de_trabajador_id') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="tipo_de_personal_id">Tipo De Personal Id</label>
                <input wire:model="tipo_de_personal_id" type="text" class="form-control" id="tipo_de_personal_id" placeholder="Tipo De Personal Id">@error('tipo_de_personal_id') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="capacitacion_id">Capacitacion Id</label>
                <input wire:model="capacitacion_id" type="text" class="form-control" id="capacitacion_id" placeholder="Capacitacion Id">@error('capacitacion_id') <span class="error text-danger">{{ $message }}</span> @enderror
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
