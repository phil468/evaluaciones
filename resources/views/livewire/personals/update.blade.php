<!-- Modal -->
<div wire:ignore.self class="modal fade" id="updateModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
       <div class="rounded-2xl modal-content">
            <div class="text-white modal-header bg-vanguard rounded-t-2xl">                
                <h5 class="modal-title" id="updateModalLabel">
                @if ($this->selected_id == 0)                    
                        Nuevo Personal
                @else
                        Actualizar Personal
                @endif
                </h5>
                <button type="button" class="text-white close" data-dismiss="modal" aria-label="Close">
                    <span wire:click="cancel()" aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                <fieldset class="row" 
                @if (!$this->updateMode)                    
                    disabled
                @endif
                >
                @if (!$this->updateMode)
                <div class="col-12 alert alert-warning" role="alert">
                    Cargando ...
                </div>
                @endif

					<input type="hidden" wire:model="selected_id">
                                <div class="form-group col-sm-6 col-md-6 col-lg-4 col-xl-3">
                <label for="dni">DNI *</label>
                <div class="input-group">
                    <input 
                    inputmode="numeric" 
                    wire:model.defer="dni" 
                    type="text" 
                    class="form-control" 
                    id="dni" 
                    placeholder="Dni"
                    wire:keydown.enter='buscar_dni' 
                    wire:keydown.tab="buscar_dni"
                    wire:keydown.arrow-right="buscar_dni"
                    >
                    @error('dni') <span class="error text-danger">{{ $message }}</span> @enderror
                    @if ($selected_id == 0)                    
                        <a wire:click="buscar_dni()" type="button" class="btn btn-primary"><i class="fas fa-search"></i></a>
                    @endif
                </div>
            @if (session()->has('message-busqueda-dni'))
            <div wire:poll.4s class="btn btn-sm btn-info" style="margin-top:0px; margin-bottom:0px;"> {{ session('message-busqueda-dni') }} </div>
            @endif
            </div>
            <div class="form-group col-sm-6 col-md-6 col-lg-4 col-xl-3">
                <label for="name">Nombre Completo *</label>
                <input wire:model.defer="name" type="text" class="form-control" id="name" placeholder="Nombre Completo">@error('name') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group col-sm-6 col-md-6 col-lg-4 col-xl-3">
                <label for="nombres">Nombres</label>
                <input wire:model.defer="nombres" type="text" class="form-control" id="nombres" placeholder="Nombres">@error('nombres') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group col-sm-6 col-md-6 col-lg-4 col-xl-3">
                <label for="apellido_paterno">Apellido Paterno</label>
                <input wire:model.defer="apellido_paterno" type="text" class="form-control" id="apellido_paterno" placeholder="Apellido Paterno">@error('apellido_paterno') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group col-sm-6 col-md-6 col-lg-4 col-xl-3">
                <label for="apellido_materno">Apellido Materno</label>
                <input wire:model.defer="apellido_materno" type="text" class="form-control" id="apellido_materno" placeholder="Apellido Materno">@error('apellido_materno') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            
            <div class="form-group col-sm-6 col-md-6 col-lg-4 col-xl-3">
                <label for="empresa_id">Empresa *</label>
                <div wire:ignore>
                <select 
                name="empresa_id"
                    class="form-control" 
                    id="empresa_id" 
                    placeholder="Empresas">
                </select>
                </div>
                @error('empresa_id')
                    <span class="error text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-sm-6 col-md-6 col-lg-4 col-xl-3">
                <label for="gerencia_id">Gerencia *</label>
                <div wire:ignore>
                <select 
                name="gerencia_id"
                    class="form-control" 
                    id="gerencia_id" placeholder="Gerencias">
                </select>
                </div>
                @error('gerencia_id')
                    <span class="error text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group col-sm-6 col-md-6 col-lg-4 col-xl-3">
                <label for="sede_id">Sede *</label>
                <div wire:ignore>
                <select 
                name="sede_id"
                    class="form-control" id="sede_id"
                    placeholder="Sede">
                </select>
                </div>
                @error('sede_id')
                    <span class="error text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-sm-6 col-md-6 col-lg-4 col-xl-3">
                <label for="area_id">Area *</label>
                <div wire:ignore>
                <select 
                name="area_id"
                    class="form-control" id="area_id"
                    placeholder="Area">
                </select>
                </div>
                @error('area_id')
                    <span class="error text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-sm-6 col-md-6 col-lg-4 col-xl-3">
                <label for="cargo_id">Cargo *</label>
                <div wire:ignore>
                <select 
                name="cargo_id"
                    class="form-control" id="cargo_id"
                    placeholder="Cargo">
                </select>
                </div>
                @error('cargo_id')
                    <span class="error text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-sm-6 col-md-6 col-lg-4 col-xl-3">
                <label for="correo_empresa">Correo Empresa</label>
                <input inputmode="email" wire:model.defer="correo_empresa" type="text" class="form-control" id="correo_empresa" placeholder="Correo Empresa">@error('correo_empresa') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group col-sm-6 col-md-6 col-lg-4 col-xl-3">
                <label for="celular_empresa">Celular Empresa</label>
                <input inputmode="tel" wire:model.defer="celular_empresa" type="text" class="form-control" id="celular_empresa" placeholder="Celular Empresa">@error('celular_empresa') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group col-sm-6 col-md-6 col-lg-4 col-xl-3">
                <label for="correo_personal">Correo Personal</label>
                <input inputmode="email" wire:model.defer="correo_personal" type="text" class="form-control" id="correo_personal" placeholder="Correo Personal">@error('correo_personal') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group col-sm-6 col-md-6 col-lg-4 col-xl-3">
                <label for="telefono_personal">Telefono Personal</label>
                <input inputmode="tel" wire:model.defer="telefono_personal" type="text" class="form-control" id="telefono_personal" placeholder="Telefono Personal">@error('telefono_personal') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group col-sm-6 col-md-6 col-lg-4 col-xl-3">
                <label for="celular_personal">Celular Personal</label>
                <input inputmode="tel" wire:model.defer="celular_personal" type="text" class="form-control" id="celular_personal" placeholder="Celular Personal">@error('celular_personal') <span class="error text-danger">{{ $message }}</span> @enderror
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
                    <option value="">-- Seleccione --</option>
                    <option value="H">Hombre</option>
                    <option value="M">Mujer</option>
                </select>
                @error('genero') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
                </fieldset>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" wire:click="cancel()" class="btn btn-secondary" 
                data-dismiss="modal"
                >Cerrar</button>

                @if ($this->selected_id == 0)                    
                <button type="button" wire:click.prevent="store()" class="btn btn-primary close-modal">Guardar</button>
                @else
                    <button type="button" wire:click.prevent="update()" class="btn btn-primary" 
                    @if (!$this->updateMode)                    
                        disabled
                    @endif >Guardar</button>
                @endif
            </div>
       </div>
    </div>
</div>
