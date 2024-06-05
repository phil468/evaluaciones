<!-- Modal -->
<div wire:ignore.self class="modal fade" id="updateModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
       <div class="rounded-2xl modal-content">
            <div class="text-white modal-header bg-vanguard rounded-t-2xl">                <h5 class="modal-title h5" id="updateModalLabel">
                    @if ($this->selected_id == 0)
                        Nueva Capacitación
                    @else
                        Actualizar Capacitación
                    @endif
                </h5>
                <button type="button" class="text-white close" data-dismiss="modal" aria-label="Close">
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
					<input type="hidden" wire:model="selected_id">
                    
                    <div class="form-group col-sm-12 col-md-12 col-lg-6 col-xl-4">
                        <label>Habilitado</label>
                        <div class="form-check">
                            <label class="switch">
                                <input type="checkbox" wire:model="activo" id="activo" name="activo">
                                <span class="slider round"></span>
                            </label>
                            @if ($activo)
                            Activo
                            @else
                            Inactivo
                            @endif
                            @error('activo')
                                <span class="error text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group col-sm-12 col-md-12 col-lg-6 col-xl-4">
                        <label for="empresa_id">Empresa *</label>
                        <div wire:ignore>
                        <select 
                        {{-- @if ($viewMode) readonly disabled @endif  --}}
                        name="empresa_id"
                            class="form-control" 
                            id="empresa_id" 
                            placeholder="Empresas">
                            {{-- <option value="">Seleccione</option> --}}
                        </select>
                        </div>
                        @error('empresa_id')
                            <span class="error text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="form-group col-sm-12 col-md-12 col-lg-6 col-xl-4">
                        <label for="sede_id">Sede *</label>
                        <div wire:ignore>
                        <select 
                        {{-- @if ($viewMode) readonly disabled @endif  --}}
                        name="sede_id"
                            class="form-control" 
                            id="sede_id" 
                            placeholder="Sede">
                            {{-- <option value="">Seleccione</option> --}}
                        </select>
                        </div>
                        @error('sede_id')
                            <span class="error text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group col-sm-12 col-md-12 col-lg-6 col-xl-4">
                        <label for="modalidad_id">Modalidad *</label>
                        <div wire:ignore>
                        <select 
                        {{-- @if ($viewMode) readonly disabled @endif  --}}
                            name="modalidad_id"
                            class="form-control" 
                            id="modalidad_id" 
                            placeholder="Modalidades">
                            {{-- <option value="">Seleccione</option> --}}
                        </select>
                        </div>
                        @error('modalidad_id')
                            <span class="error text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group col-sm-12 col-md-12 col-lg-6 col-xl-4">
                        <label for="capacitaciones_tipo_id">Tipo de Capacitacion*</label>
                        <div wire:ignore>
                        <select 
                        {{-- @if ($viewMode) readonly disabled @endif  --}}
                        name="capacitaciones_tipo_id"
                            class="form-control" 
                            id="capacitaciones_tipo_id" 
                            placeholder="Tipo de capacitaciones">
                            {{-- <option value="">Seleccione</option> --}}
                        </select>
                        </div>
                        @error('capacitaciones_tipo_id')
                            <span class="error text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="form-group col-sm-12 col-md-12 col-lg-6 col-xl-4">
                        <label for="tema_id">Tema *</label>
                        <div wire:ignore>
                        <select 
                        {{-- @if ($viewMode) readonly disabled @endif  --}}
                        name="tema_id"
                            class="form-control" 
                            id="tema_id" 
                            placeholder="Tema">
                            {{-- <option value="">Seleccione</option> --}}
                        </select>
                        </div>
                        <div class="input-group">
                            <input type="text" name="tema_id_add" wire:model.defer="tema_id_add"
                                wire:keydown.enter='agregar_tema' wire:keydown.tab="agregar_tema"
                                wire:keydown.arrow-right="agregar_tema" id="tema_id_add" class="form-control"
                                placeholder="Ingresar TEMA y dar ENTER para agregar">
                            <button wire:click="agregar_tema" type="button" class="btn btn-primary"
                                id="agregar_tema"><i class="fas fa-plus"></i></button>
                        </div>
                        @error('tema_id')
                            <span class="error text-danger">{{ $message }}</span>
                        @enderror
                    </div>                    

                    <div class="form-group col-sm-12 col-md-12 col-lg-6 col-xl-4">
                <label for="fecha_capacitacion">Fecha Capacitacion</label>
                <input wire:model.defer="fecha_capacitacion" type="date" class="form-control" id="fecha_capacitacion" placeholder="Fecha Capacitacion">@error('fecha_capacitacion') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="form-group col-sm-12 col-md-12 col-lg-6 col-xl-4">
                <label for="hora_inicio">Hora Inicio</label>
                <input wire:model.defer="hora_inicio" type="time" class="form-control" id="hora_inicio" placeholder="Hora Inicio">@error('hora_inicio') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>

                    <div class="form-group col-sm-12 col-md-12 col-lg-6 col-xl-4">
                <label for="hora_fin">Hora Fin</label>
                <input wire:model.defer="hora_fin" type="time" class="form-control" id="hora_fin" placeholder="Hora Fin">@error('hora_fin') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            
            <div class="form-group col-sm-12 col-md-12 col-lg-6 col-xl-4">
                <label for="expositor_externo">Expositor Externo</label>
                <div class="form-check">
                    <label class="switch">
                        <input type="checkbox" wire:model="expositor_externo" id="expositor_externo" name="expositor_externo" value="1">
                        <span class="slider round"></span>
                    </label>
                    @if ($expositor_externo)
                    Externo
                    @else
                    Interno
                    @endif
                    @error('activo')
                        <span class="error text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            
            <div class="form-group col-sm-12 col-md-12 col-lg-6 col-xl-4"
            @if ($expositor_externo) style="display: none;" @endif
            >
                <label for="expositor_id">Expositor *</label>
                <div wire:ignore>
                <select 
                    name="expositor_id"
                    class="form-control" 
                    id="expositor_id" 
                    placeholder="Expositor">
                </select>
                </div>
                
                @error('expositor_id')
                    <span class="error text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group col-sm-12 col-md-12 col-lg-6 col-xl-4"
            @if ($expositor_externo) style="display: none;" @endif
            >
                <label for="cargo_expositor_id">Cargo de Expositor *</label>
                <div wire:ignore>
                <select 
                {{-- @if ($viewMode) readonly disabled @endif  --}}
                name="cargo_expositor_id"
                    class="form-control" 
                    id="cargo_expositor_id" 
                    placeholder="Cargo de Expositor">
                    {{-- <option value="">Seleccione</option> --}}
                </select>
                </div>
                @error('cargo_expositor_id')
                    <span class="error text-danger">{{ $message }}</span>
                @enderror
            </div>
                        
            <div class="form-group col-sm-12 col-md-12 col-lg-6 col-xl-4"
            @if (!$expositor_externo) style="display: none;" @endif
            >
                <label for="nombre_expositor_externo">Nombre Expositor Externo</label>
                <input 
                @if (!$expositor_externo)
                    disabled
                @endif
                wire:model.defer="nombre_expositor_externo" class="form-control" id="nombre_expositor_externo" placeholder="Nombre Expositor Externo">
                @error('nombre_expositor_externo') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            
            <div class="form-group col-sm-12 col-md-12 col-lg-6 col-xl-4">
                <label for="status_id">Estado *</label>
                <div wire:ignore>
                <select 
                {{-- @if ($viewMode) readonly disabled @endif  --}}
                name="status_id"
                    class="form-control" 
                    id="status_id" 
                    placeholder="Estado">
                    {{-- <option value="">Seleccione</option> --}}
                </select>
                </div>
                @error('status_id')
                    <span class="error text-danger">{{ $message }}</span>
                @enderror
            </div>
           
            <div class="form-group col-sm-12 col-md-12 col-lg-6 col-xl-4">
                <label for="registrador_id">Registrador *</label>
                <div wire:ignore>
                <select 
                {{-- @if ($viewMode) readonly disabled @endif  --}}
                name="registrador_id"
                    class="form-control" 
                    id="registrador_id" 
                    placeholder="Registrador">
                    {{-- <option value="">Seleccione</option> --}}
                </select>
                </div>
                @error('registrador_id')
                    <span class="error text-danger">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="form-group col-sm-12 col-md-12 col-lg-6 col-xl-4">
                <label for="cargo_registrador_id">Cargo Registrador *</label>
                <div wire:ignore>
                <select 
                {{-- @if ($viewMode) readonly disabled @endif  --}}
                name="cargo_registrador_id"
                    class="form-control" 
                    id="cargo_registrador_id" 
                    placeholder="Cargo Registrador">
                    {{-- <option value="">Seleccione</option> --}}
                </select>
                </div>
                @error('cargo_registrador_id')
                    <span class="error text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group col-sm-12 col-md-12 col-lg-6 col-xl-4">
                <label for="fecha_registro">Fecha Registro</label>
                <input wire:model.defer="fecha_registro" type="date" class="form-control" id="fecha_registro" placeholder="Fecha Registro">@error('fecha_registro') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            
            <div class="form-group col-sm-12 col-md-12 col-lg-6 col-xl-4">
                <label for="area_id">Area *</label>
                <div wire:ignore>
                <select 
                    name="area_id"
                    class="form-control" 
                    id="area_id"
                    placeholder="Area"
                    multiple
                    >
                </select>
                </div>
                @error('area_id')
                    <span class="error text-danger">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="form-group col-sm-12 col-md-12 col-lg-6 col-xl-4">
                <label for="cantidad_de_sesiones">Cantidad de sesiones</label>
                <input wire:model.defer="cantidad_de_sesiones" min="1" type="number" class="form-control" id="cantidad_de_sesiones" placeholder="Cantidad de Sesiones">@error('cantidad_de_sesiones') <span class="error text-danger">{{ $message }}</span> @enderror
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
