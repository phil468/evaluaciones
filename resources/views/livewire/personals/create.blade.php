<!-- Modal -->
<div wire:ignore.self class="modal fade" id="createDataModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="createDataModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="rounded-2xl modal-content">
            <div class="text-white modal-header bg-vanguard rounded-t-2xl">                <h5 class="modal-title" id="createDataModalLabel">Nuevo Personal</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true close-btn">×</span>
                </button>
            </div>
           <div class="modal-body">
				<form class="row">
            <div class="form-group col-sm-6 col-md-6 col-lg-4 col-xl-3">
                <label for="dni">DNI *</label>
                <input inputmode="numeric" wire:model="dni" type="text" class="form-control" id="dni" placeholder="Dni">@error('dni') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group col-sm-6 col-md-6 col-lg-4 col-xl-3">
                <label for="name">Nombre Completo *</label>
                <input wire:model="name" type="text" class="form-control" id="name" placeholder="Nombre Completo">@error('name') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group col-sm-6 col-md-6 col-lg-4 col-xl-3">
                <label for="nombres">Nombres</label>
                <input wire:model="nombres" type="text" class="form-control" id="nombres" placeholder="Nombres">@error('nombres') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group col-sm-6 col-md-6 col-lg-4 col-xl-3">
                <label for="apellido_paterno">Apellido Paterno</label>
                <input wire:model="apellido_paterno" type="text" class="form-control" id="apellido_paterno" placeholder="Apellido Paterno">@error('apellido_paterno') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group col-sm-6 col-md-6 col-lg-4 col-xl-3">
                <label for="apellido_materno">Apellido Materno</label>
                <input wire:model="apellido_materno" type="text" class="form-control" id="apellido_materno" placeholder="Apellido Materno">@error('apellido_materno') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            {{-- <div class="form-group col-sm-6 col-md-6 col-lg-4 col-xl-3">
                <label for="empresa_id">Empresa</label>
                <x-simple-select       
                    name="empresa_id"
                    id="empresa_id"
                    wire:model="empresa_id"
                    :options="$empresas"
                    value-field='id'
                    text-field='name'
                    placeholder="Seleccione..."
                    search-input-placeholder="Seleccione..."
                    :searchable="true"
                    class="form-control"
                    clearable="true"
                />
                @error('empresa_id') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group col-sm-6 col-md-6 col-lg-4 col-xl-3">
                <label for="sede_id">Sede</label>
                <x-simple-select       
                    name="sede_id"
                    id="sede_id"
                    wire:model="sede_id"
                    :options="$sedes"
                    value-field='id'
                    text-field='name'
                    placeholder="Seleccione..."
                    search-input-placeholder="Seleccione..."
                    :searchable="true"
                    class="form-control"
                    clearable="true"
                />
                @error('sede_id') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group col-sm-6 col-md-6 col-lg-4 col-xl-3">
                <label for="gerencia_id">Gerencia</label>
                <x-simple-select       
                    name="gerencia_id"
                    id="gerencia_id"
                    wire:model="gerencia_id"
                    :options="$gerencias"
                    value-field='id'
                    text-field='name'
                    placeholder="Seleccione..."
                    search-input-placeholder="Seleccione..."
                    :searchable="true"
                    class="form-control"
                    clearable="true"
                />
                @error('gerencia_id') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group col-sm-6 col-md-6 col-lg-4 col-xl-3">
                <label for="area_id">Area</label>
                <x-simple-select       
                    name="area_id"
                    id="area_id"
                    wire:model="area_id"
                    :options="$areas"
                    value-field='id'
                    text-field='name'
                    placeholder="Seleccione..."
                    search-input-placeholder="Seleccione..."
                    :searchable="true"
                    class="form-control"
                    clearable="true"
                />
                @error('area_id') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group col-sm-6 col-md-6 col-lg-4 col-xl-3">
                <label for="cargo_id">Cargo</label>
                <x-simple-select       
                    name="cargo_id"
                    id="cargo_id"
                    wire:model="cargo_id"
                    :options="$cargos"
                    value-field='id'
                    text-field='name'
                    placeholder="Seleccione..."
                    search-input-placeholder="Seleccione..."
                    :searchable="true"
                    class="form-control"
                    clearable="true"
                />
                @error('cargo_id') <span class="error text-danger">{{ $message }}</span> @enderror
            </div> --}}
            <div class="form-group col-sm-6 col-md-6 col-lg-4 col-xl-3">
                <label for="correo_empresa">Correo Empresa</label>
                <input inputmode="email" wire:model="correo_empresa" type="text" class="form-control" id="correo_empresa" placeholder="Correo Empresa">@error('correo_empresa') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group col-sm-6 col-md-6 col-lg-4 col-xl-3">
                <label for="celular_empresa">Celular Empresa</label>
                <input inputmode="tel" wire:model="celular_empresa" type="text" class="form-control" id="celular_empresa" placeholder="Celular Empresa">@error('celular_empresa') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group col-sm-6 col-md-6 col-lg-4 col-xl-3">
                <label for="correo_personal">Correo Personal</label>
                <input inputmode="email" wire:model="correo_personal" type="text" class="form-control" id="correo_personal" placeholder="Correo Personal">@error('correo_personal') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group col-sm-6 col-md-6 col-lg-4 col-xl-3">
                <label for="telefono_personal">Telefono Personal</label>
                <input inputmode="tel" wire:model="telefono_personal" type="text" class="form-control" id="telefono_personal" placeholder="Telefono Personal">@error('telefono_personal') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group col-sm-6 col-md-6 col-lg-4 col-xl-3">
                <label for="celular_personal">Celular Personal</label>
                <input inputmode="tel" wire:model="celular_personal" type="text" class="form-control" id="celular_personal" placeholder="Celular Personal">@error('celular_personal') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            {{-- <div class="form-group col-sm-6 col-md-6 col-lg-4 col-xl-3">
                <label for="fecha_ingreso">Fecha de Ingreso</label>
                <input wire:model="fecha_ingreso" type="date" class="form-control" id="fecha_ingreso" placeholder="Fecha de ingreso">@error('fecha_ingreso') <span class="error text-danger">{{ $message }}</span> @enderror
            </div> --}}
            {{-- <div class="form-group col-sm-6 col-md-6 col-lg-4 col-xl-3">
                <label for="foto">Foto</label>
                <input wire:model="foto" type="text" class="form-control" id="foto" placeholder="Foto">@error('foto') <span class="error text-danger">{{ $message }}</span> @enderror
            </div> --}}
            <div class="form-group col-sm-6 col-md-6 col-lg-4 col-xl-3">
                <label>Estado</label>
                <div class="form-check">
                    <label class="switch">
                        <input 
                        type="checkbox" 
                        wire:model="estado" id="estado" name="estado"
                        >
                        <span class="slider round"></span>
                    </label>
                    @if ($estado)
                        Activo
                    @else
                        Inactivo
                    @endif
                    @error('estado') <span class="error text-danger">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="form-group col-sm-6 col-md-6 col-lg-4 col-xl-3">
                <label for="genero">Genero</label>
                <select name="genero" class="form-control" wire:model.lazy="genero" class="form-control" id="genero" placeholder="Genero">
                    <option value="">Seleccione...</option>
                    <option value="H">Hombre</option>
                    <option value="M">Mujer</option>
                </select>
                @error('genero') <span class="error text-danger">{{ $message }}</span> @enderror
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
