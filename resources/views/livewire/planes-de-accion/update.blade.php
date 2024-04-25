<!-- Modal -->
<div wire:ignore.self class="modal fade" id="updateModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="rounded-2xl modal-content">
            <div class="text-white modal-header bg-vanguard rounded-t-2xl">
                <h5 class="h5 modal-title" id="updateModalLabel">Actualizar Planes De Accion</h5>
                <button type="button" class="text-white close" data-dismiss="modal" aria-label="Close">
                    <span wire:click.prevent="cancel()" aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
					<input type="hidden" wire:model="selected_id">
                    <div class="form-group">
                        <label for="name">Descripcion</label>
                        <input wire:model.defer="name" type="text" class="form-control" id="name" placeholder="Name">@error('name') <span class="error text-danger">{{ $message }}</span> @enderror
                    </div>
                    
                    {{-- <div class="form-group">
                        <label for="tipo_de_proceso_id">Tipo De Proceso Id</label>
                        <select wire:model="tipo_de_proceso_id" class="form-control" id="tipo_de_proceso_id">
                            <option value="">Seleccionar Tipo De Proceso Id</option>
                            @foreach($tipoDeProcesos as $index => $name)
                                <option value="{{ $index}}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div> --}}
                    
                    
                    <div class="form-group">
                        <label for="proceso_id">Proceso</label>
                        <select wire:model="proceso_id" class="form-control" id="proceso_id">
                            <option value="">Seleccionar Proceso</option>
                            @foreach($procesos as $index => $name)
                                <option value="{{ $index}}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
        
                    <div class="form-group">
                        <label for="encargado_id">Encargado</label>
                        <select wire:model="encargado_id" class="form-control" id="encargado_id">
                            <option value="">Seleccionar Encargado</option>
                            @foreach($personals as $index => $name)
                                <option value="{{ $index}}">{{ $name }}</option>
                            @endforeach
                        </select>
                        @error('encargado_id') <span class="error text-danger">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="empleado_id">Personal</label>
                        <select wire:model="empleado_id" class="form-control" id="empleado_id">
                            <option value="">Seleccionar Personal</option>
                            @foreach($personals as $index => $name)
                                <option value="{{ $index}}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="competencia_id">Competencia</label>
                        <select wire:model="competencia_id" class="form-control" id="competencia_id">
                            <option value="">Seleccionar Competencia</option>
                            @foreach($competencias as $index => $name)
                                <option value="{{ $index}}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="fecha_de_revision">Fecha De Revision</label>
                        <input wire:model="fecha_de_revision" type="date" class="form-control" id="fecha_de_revision" placeholder="Fecha De Revision">@error('fecha_de_revision') <span class="error text-danger">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="estado_id">Estado</label>
                        <select wire:model="estado_id" class="form-control" id="estado_id">
                            <option value="">Seleccionar Estado</option>
                            @foreach($estados as $index => $name)
                                <option value="{{ $index}}">{{ $name }}</option>
                            @endforeach
                        </select>
                        {{-- <input wire:model="estado_id" type="text" class="form-control" id="estado_id" placeholder="Estado Id">@error('estado_id') <span class="error text-danger">{{ $message }}</span> @enderror --}}
                    </div>
                    {{-- <div class="form-group">
                        <label for="gerencia_id">Gerencia Id</label>
                        <input wire:model="gerencia_id" type="text" class="form-control" id="gerencia_id" placeholder="Gerencia Id">@error('gerencia_id') <span class="error text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label for="area_id">Area Id</label>
                        <input wire:model="area_id" type="text" class="form-control" id="area_id" placeholder="Area Id">@error('area_id') <span class="error text-danger">{{ $message }}</span> @enderror
                    </div> --}}
                    <div class="form-group">
                        <label for="avance">Avance</label>
                        <input wire:model="avance" type="number" class="form-control" id="avance" placeholder="Avance">@error('avance') <span class="error text-danger">{{ $message }}</span> @enderror
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" wire:click.prevent="cancel()" class="btn btn-secondary rounded-xl" data-dismiss="modal">Cerrar</button>
                <button type="button" wire:click.prevent="update()" class="btn btn-vanguard rounded-xl">Guardar</button>
            </div>
       </div>
    </div>
</div>
