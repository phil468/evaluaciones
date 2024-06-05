<!-- Modal -->
<div wire:ignore.self class="modal fade" id="updateActivoModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="updateActivoModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
       <div class="rounded-2xl modal-content">
            <div class="text-white modal-header bg-vanguard rounded-t-2xl">                <h5 class="modal-title" id="updateModalLabel">Editar Activo</h5>
                <button type="button" class="text-white close" data-dismiss="modal" aria-label="Close">
                    <span wire:click.prevent="cancel_activo()" aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
					<input type="hidden" wire:model="selected_activo_id">
                                        
                    <div class="form-group col-sm-6 col-md-6 col-lg-4 col-xl-3">
                        <label>Regularización</label>
                        <div class="form-check">
                            <input wire:model="regularizacion" type="checkbox" class="form-check-input" id="regularizacion" placeholder="regularizacion" checked  data-on="Activo" data-off="Inactivo" data-onstyle="success" data-offstyle="danger">
                            <label class="form-check-label" for="regularizacion">
                                Si/No
                            </label>
                            @error('regularizacion') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="fecha_de_asignacion">Fecha de Asignación</label>
                        <input type="date" wire:model.defer="fecha_de_asignacion" class="form-control" id="fecha_de_asignacion" placeholder="Fecha de Asignación">@error('fecha_de_asignacion') <span class="error text-danger">{{ $message }}</span> @enderror
                    </div>                    
                    
                    <div class="form-group">
                        <label for="activo_id">Condición *</label>

                        <select 
                        {{-- @if ($viewMode) readonly disabled @endif  --}}
                        name="performance_id" class="form-control" wire:model.defer="performance_id" class="form-control" id="performance_id" placeholder="Condición">
                            <option value="">-- Seleccione --</option>
                            @if ($condiciones)
                            @foreach ($condiciones as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                            @endif									
                        </select>
                        @error('performance_id') <span class="error text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">                        
                        <label for="activo_id">Vigencia *</label>
                        <select 
                        {{-- @if ($viewMode) readonly disabled @endif  --}}
                        name="vigencia_id" class="form-control" wire:model.defer="vigencia_id" class="form-control" id="vigencia_id" placeholder="Vigencia">
                            <option value="">-- Seleccione --</option>
                            @if ($vigencias)
                                @foreach ($vigencias as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach	                                
                            @endif										
                        </select>
                        @error('vigencia_id') <span class="error text-danger">{{ $message }}</span> @enderror
                    </div>
            
                    <div class="form-group">
                        <label for="fecha_vigencia">Fecha de Vigencia</label>
                        <input type="date" wire:model.defer="fecha_vigencia" class="form-control" id="fecha_vigencia" placeholder="Fecha de Vigencia">@error('fecha_vigencia') <span class="error text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label for="observaciones_activo">Observaciones</label>
                        <input type="text" wire:model.defer="observaciones_activo" class="form-control" id="observaciones_activo" placeholder="Observaciones">@error('observaciones_activo') <span class="error text-danger">{{ $message }}</span> @enderror
                    </div>
                    
		{{-- <div class="mb-3">
            <label for="activo_accesorios" class="form-label">Accesorios:</label>
             <br/>
              @foreach($accesorio as $value)
                  <label>{{ Form::checkbox('activo_accesorios[]', $value->id, in_array($value->id, $activoAccesorios) ? true : false, array('class' => 'name')) }}
                  {{ $value->name }}</label>
              <br/>
              @endforeach            
        </div> --}}
            <div class="mb-3">
                <label for="activo_accesorios" class="form-label">Accesorios asociados al tipo de activo:</label>
                 <br/>
                 @if ($accesorios)
                    @foreach($accesorios as $value)
                        <label>{{ Form::checkbox('activo_accesorios[]', $value->id, in_array($value->id, $activo_accesorios) ? true : false, array('class' => 'name', 'wire:model.defer'=>"activo_accesorios", 'disabled'=>($value->stock ? false : true) )) }}
                        {{ $value->name.' ('.$value->stock.')'  }}</label>
                    <br/>
                    @endforeach                     
                 @endif
            </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" wire:click.prevent="cancel_activo()" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" wire:click.prevent="update_activo({{$selected_activo_index}})" class="btn btn-primary close-modal">Guardar</button>
            </div>
       </div>
    </div>
</div>
