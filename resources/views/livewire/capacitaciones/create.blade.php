<!-- Modal -->
<div wire:ignore.self class="modal fade" id="createDataModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="createDataModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="rounded-2xl modal-content">
            <div class="text-white modal-header bg-vanguard rounded-t-2xl">                <h5 class="modal-title" id="createDataModalLabel">Nuevo Capacitacione</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true close-btn">×</span>
                </button>
            </div>
           <div class="modal-body">
				<form>
            <div class="form-group">
                <label for="empresa_id">Empresa Id</label>
                <input wire:model="empresa_id" type="text" class="form-control" id="empresa_id" placeholder="Empresa Id">@error('empresa_id') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="capacitaciones_tipo_id">Capacitaciones Tipo Id</label>
                <input wire:model="capacitaciones_tipo_id" type="text" class="form-control" id="capacitaciones_tipo_id" placeholder="Capacitaciones Tipo Id">@error('capacitaciones_tipo_id') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="tema_id">Tema Id</label>
                <input wire:model="tema_id" type="text" class="form-control" id="tema_id" placeholder="Tema Id">@error('tema_id') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="sede_id">Sede Id</label>
                <input wire:model="sede_id" type="text" class="form-control" id="sede_id" placeholder="Sede Id">@error('sede_id') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="fecha_capacitacion">Fecha Capacitacion</label>
                <input wire:model="fecha_capacitacion" type="text" class="form-control" id="fecha_capacitacion" placeholder="Fecha Capacitacion">@error('fecha_capacitacion') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="hora_inicio">Hora Inicio</label>
                <input wire:model="hora_inicio" type="text" class="form-control" id="hora_inicio" placeholder="Hora Inicio">@error('hora_inicio') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="hora_fin">Hora Fin</label>
                <input wire:model="hora_fin" type="text" class="form-control" id="hora_fin" placeholder="Hora Fin">@error('hora_fin') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="expositor_id">Expositor Id</label>
                <input wire:model="expositor_id" type="text" class="form-control" id="expositor_id" placeholder="Expositor Id">@error('expositor_id') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="cargo_expositor_id">Cargo Expositor Id</label>
                <input wire:model="cargo_expositor_id" type="text" class="form-control" id="cargo_expositor_id" placeholder="Cargo Expositor Id">@error('cargo_expositor_id') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="registrador_id">Registrador Id</label>
                <input wire:model="registrador_id" type="text" class="form-control" id="registrador_id" placeholder="Registrador Id">@error('registrador_id') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="cargo_registrador_id">Cargo Registrador Id</label>
                <input wire:model="cargo_registrador_id" type="text" class="form-control" id="cargo_registrador_id" placeholder="Cargo Registrador Id">@error('cargo_registrador_id') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="fecha_registro">Fecha Registro</label>
                <input wire:model="fecha_registro" type="text" class="form-control" id="fecha_registro" placeholder="Fecha Registro">@error('fecha_registro') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="activo">Activo</label>
                <input wire:model="activo" type="text" class="form-control" id="activo" placeholder="Activo">@error('activo') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="estado">Estado</label>
                <input wire:model="estado" type="text" class="form-control" id="estado" placeholder="Estado">@error('estado') <span class="error text-danger">{{ $message }}</span> @enderror
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
