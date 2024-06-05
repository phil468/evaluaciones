<!-- Modal -->
<div wire:ignore.self class="modal fade" id="updateActivoModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="updateActivoModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
       <div class="rounded-2xl modal-content">
            <div class="text-white modal-header bg-vanguard rounded-t-2xl">                <h5 class="modal-title" id="updateModalLabel">Editar devolución de Activo</h5>
                <button type="button" class="text-white close" data-dismiss="modal" aria-label="Close">
                    <span wire:click.prevent="cancel_activo()" aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
					<input type="hidden" wire:model="selected_activo_id">

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
                @if (!empty($accesorios_asignado_activo))
                    <div class="mb-3 form-group">
                        <label for="activo_accesorios" class="form-label">Accesorios devueltos con el activo:</label>
                        <br/>
                        @if ($accesorios_asignado_activo)
                        @foreach($accesorios_asignado_activo as $value)
                            <label>{{ Form::checkbox('activo_accesorios[]', $value['id'], in_array($value['id'], $activo_accesorios) ? true : false, array('class' => 'name', 'wire:model.defer'=>"activo_accesorios" )) }}
                            {{ $value['name'] }}</label>
                        <br/>
                        @endforeach
                            
                        @endif
                    </div>                    
                @endif

                <div class="form-group">
                    <label for="observaciones_devolucion">Observaciones de devolución</label>
                    <input type="text" wire:model.defer="observaciones_devolucion" class="form-control" id="observaciones_devolucion" placeholder="Observaciones">@error('observaciones_devolucion') <span class="error text-danger">{{ $message }}</span> @enderror
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
