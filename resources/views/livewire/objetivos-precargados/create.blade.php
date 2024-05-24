<!-- Modal -->
<div wire:ignore.self class="modal fade" id="createDataModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="createDataModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="rounded-2xl modal-content">
            <div class="text-white modal-header bg-vanguard rounded-t-2xl">
                <h5 class="modal-title h5" id="createDataModalLabel">Nuevo Objetivos Precargado</h5>
                <button type="button" class="text-white close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true close-btn">×</span>
                </button>
            </div>
           <div class="modal-body">
				<form>
                    <div class="row">
                        <div class="col-12">
                            <div class="row">
                                <div class="form-group col-sm-6 col-md-6 col-lg-4 col-xl-3">
                                    <label for="grupal">Grupal*</label>
                                    <select class="form-control" id="grupal" wire:model="grupal">
                                            <option value=1>SÍ</option>
                                            <option value=0>NO</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                            @if(isset($grupal) && $grupal == 1)
                                <div class="form-group col-sm-12 col-md-12 col-lg-8 col-xl-6">
                                    <label for="meta">Meta*</label>
                                    <textarea  wire:model.defer="meta" type="text" class="form-control" id="meta" placeholder="Meta" id="" cols="30" rows="2">
                                    </textarea>
                                    @error('meta') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>
                            @endif
                            
                            <div class="form-group col-sm-6 col-md-6 col-lg-4 col-xl-3">
                                <label for="porcentaje_de_participacion">Porcentaje De Participación*</label>
                                <div class="input-group">
                                    <input  inputmode="decimal" wire:model.defer="porcentaje_de_participacion" type="number" class="form-control" id="porcentaje_de_participacion" placeholder="Porcentaje De Participacion">
                                    <div class="input-group-append">
                                        <span class="input-group-text">%</span>
                                    </div>
                                    @error('porcentaje_de_participacion') <span class="error text-danger">{{ $message }}</span> @enderror                                
                                </div>
                            </div>
                            {{-- <div class="form-group col-sm-6 col-md-6 col-lg-4 col-xl-3">
                                <label for="evidencias">Evidencias</label>
                                <input wire:model.defer="evidencias" type="text" class="form-control" id="evidencias" placeholder="Evidencias">@error('evidencias') <span class="error text-danger">{{ $message }}</span> @enderror
                            </div> --}}
                            @if(isset($grupal) && $grupal == 1)
                                <div class="form-group col-sm-6 col-md-6 col-lg-4 col-xl-3">
                                    <label for="tipo_objetivo_id">Tipo de Objetivo*</label>
                                    <select class="form-control" id="tipo_objetivo_id" wire:model="tipo_objetivo_id">
                                        @foreach ($tipos_objetivo as $tipo)
                                            <option value="{{ $tipo->id }}">{{ $tipo->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('tipo_objetivo_id') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>
                            @endif
                            
                            @if(isset($grupal) && $grupal == 1)
                                <div class="form-group col-sm-6 col-md-6 col-lg-4 col-xl-3">
                                    <label for="resultado_anterior_o_esperado">Resultado Anterior/Esperado</label>
                                    <div class="input-group">
                                        <input inputmode="decimal" wire:loading.attr="disabled" wire:target="tipo_objetivo_id" wire:model.defer="resultado_anterior_o_esperado" type="number" class="form-control" id="resultado_anterior_o_esperado" placeholder="Resultado Anterior O Esperado">
                                        <div class="input-group-append">
                                            <span class="input-group-text" wire:loading.remove wire:target="tipo_objetivo_id">
                                                {{$simbolo}}
                                            </span>
                                            <span class="input-group-text" wire:loading wire:target="tipo_objetivo_id">
                                                <i>Actualizando...</i>
                                            </span>
                                        </div>
                                        @error('resultado_anterior_o_esperado') <span class="error text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            @endif
                            
                            <div class="form-group col-sm-6 col-md-6 col-lg-4 col-xl-3">
                                <label for="minimo">Mínimo</label>
                                <div class="input-group">
                                    <input wire:model.defer="minimo" inputmode="decimal" min="0" type="number" class="form-control" id="minimo" placeholder="Minimo">
                                    <div class="input-group-append">
                                        <span class="input-group-text">%</span>
                                    </div>
                                    @error('minimo') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="form-group col-sm-6 col-md-6 col-lg-4 col-xl-3">
                                <label for="maximo">Máximo</label>
                                <div class="input-group">
                                    <input wire:model.defer="maximo" inputmode="decimal" min="0" type="number" class="form-control" id="maximo" placeholder="Maximo">
                                    <div class="input-group-append">
                                        <span class="input-group-text">%</span>
                                    </div>
                                    @error('maximo') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            {{-- <div class="form-group col-sm-6 col-md-6 col-lg-4 col-xl-3">
                                <label for="valor">Valor</label>
                                <input wire:model.defer="valor" type="text" class="form-control" id="valor" placeholder="Valor">@error('valor') <span class="error text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group col-sm-6 col-md-6 col-lg-4 col-xl-3">
                                <label for="porcentaje_de_logro_STI">Porcentaje De Logro Sti</label>
                                <input wire:model.defer="porcentaje_de_logro_STI" type="text" class="form-control" id="porcentaje_de_logro_STI" placeholder="Porcentaje De Logro Sti">@error('porcentaje_de_logro_STI') <span class="error text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group col-sm-6 col-md-6 col-lg-4 col-xl-3">
                                <label for="peso_ponderado">Peso Ponderado</label>
                                <input wire:model.defer="peso_ponderado" type="text" class="form-control" id="peso_ponderado" placeholder="Peso Ponderado">@error('peso_ponderado') <span class="error text-danger">{{ $message }}</span> @enderror
                            </div> --}}
                            <div class="form-group col-sm-6 col-md-6 col-lg-4 col-xl-3">
                                <label for="evaluacion_id">Evaluación</label>
                                <select class="form-control" id="evaluacion_id" wire:model="evaluacion_id">
                                    @foreach ($evaluaciones as $tipo)
                                        <option value="{{ $tipo->id }}">{{ $tipo->title }}</option>
                                    @endforeach
                                </select>
                                {{-- <input wire:model.defer="evaluacion_id" type="text" class="form-control" id="evaluacion_id" placeholder="Evaluacion"> --}}
                                @error('evaluacion_id') <span class="error text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary close-btn rounded-xl" data-dismiss="modal">Cerrar</button>
                <button type="button" wire:click.prevent="store()" class="btn btn-vanguard close-modal rounded-xl">Guardar</button>
            </div>
        </div>
    </div>
</div>
