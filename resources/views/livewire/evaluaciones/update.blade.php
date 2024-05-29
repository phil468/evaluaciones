<!-- Modal -->
<div wire:ignore.self class="modal fade" id="updateModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="rounded-2xl modal-content">
            <div class="text-white modal-header bg-vanguard rounded-t-2xl">
                <h5 class="modal-title" id="updateModalLabel">Actualizar Evaluacion</h5>
                <button type="button" class="text-white close" data-dismiss="modal" aria-label="Close">
                    <span wire:click.prevent="cancel()" aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <fieldset class="row" wire:target="edit,store,update" wire:loading.attr="disabled">
					    <input type="hidden" wire:model="selected_id">
                        {{-- <div class="form-group col-sm-12 col-md-12 col-lg-8 col-xl-6">
                            <label for="eid">Eid</label>
                            <input wire:model="eid" type="text" class="form-control" id="eid" placeholder="Eid">@error('eid') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div> --}}
                        <div class="form-group col-sm-12 col-md-12 col-lg-8 col-xl-6">
                            <label for="title">Título</label>
                            <input wire:model="title" type="text" class="form-control" id="title" placeholder="Title">@error('title') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        {{-- <div class="form-group col-sm-12 col-md-12 col-lg-8 col-xl-6">
                            <label for="date">Date</label>
                            <input wire:model="date" type="date" class="form-control" id="date" placeholder="Date">@error('date') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div> --}}
                        <div class="form-group col-sm-12 col-md-12 col-lg-8 col-xl-6">
                            <label for="status">Status</label>
                            <input wire:model="status" type="checkbox" class="form-control" id="status" placeholder="Status">@error('status') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group col-sm-12 col-md-12 col-lg-8 col-xl-6">
                            <label for="nombre_para_mostrar">Nombre Para Mostrar</label>
                            <input wire:model="nombre_para_mostrar" type="text" class="form-control" id="nombre_para_mostrar" placeholder="Nombre Para Mostrar">@error('nombre_para_mostrar') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group col-sm-12 col-md-12 col-lg-8 col-xl-6">
                            <label for="campania">Campaña</label>
                            <input wire:model="campania" type="text" class="form-control" id="campania" placeholder="Campaña">@error('campania') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        {{-- <div class="form-group col-sm-12 col-md-12 col-lg-8 col-xl-6">
                            <label for="mes">Mes</label>
                            <input wire:model="mes" type="text" class="form-control" id="mes" placeholder="Mes">@error('mes') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group col-sm-12 col-md-12 col-lg-8 col-xl-6">
                            <label for="anio">Año</label>
                            <input wire:model="anio" type="text" class="form-control" id="anio" placeholder="Año">@error('anio') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div> --}}
                        <div class="form-group col-sm-12 col-md-12 col-lg-8 col-xl-6">
                            <label for="fecha_inicio">Fecha Inicio</label>
                            <input wire:model="fecha_inicio" type="date" class="form-control" id="fecha_inicio" placeholder="Fecha Inicio">@error('fecha_inicio') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group col-sm-12 col-md-12 col-lg-8 col-xl-6">
                            <label for="fecha_fin">Fecha Fin</label>
                            <input wire:model="fecha_fin" type="date" class="form-control" id="fecha_fin" placeholder="Fecha Fin">@error('fecha_fin') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group col-sm-12 col-md-12 col-lg-8 col-xl-6">
                            <label for="identificador">Identificador</label>
                            <input wire:model="identificador" type="text" class="form-control" id="identificador" placeholder="Identificador">@error('identificador') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        
                        <div class="form-group col-sm-12 col-md-12 col-lg-8 col-xl-6">
                            <label for="tipo_de_evaluacion_id">Tipo de Evaluación</label>
                            {{-- agregar select--}}
                            <select  

                           class="form-control" id="tipo_de_evaluacion_id" wire:model="tipo_de_evaluacion_id">
                                @foreach ($tipos as $tipo)
                                    <option value="{{ $tipo->id }}">{{ $tipo->name }}</option>
                                @endforeach
                            </select>
                            
                                {{-- <select wire:model="select_element" class="form-control" id="select_element">
                                @foreach ($tipos as $tipo)
                                    <option value="{{ $tipo->id }}">{{ $tipo->name }}</option>                                    
                                @endforeach
                                </select>                            
                            <input wire:model="tipo_de_evaluacion_id" type="text" class="form-control" id="tipo_de_evaluacion_id" placeholder="Tipo de Evaluación ID"> --}}
                            
                            @error('tipo_de_evaluacion_id') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>                      

                        <div    @if ($tipo_de_evaluacion_id == 2)
                                    class="col-12"
                                @else
                                    class="d-none"
                                @endif
                        >
                        Opciones de evaluación por resultados
                            <div class="row">
                                <div class="form-group col-sm-12 col-md-12 col-lg-8 col-xl-6">
                                    <label for="minimo">Mínimo</label>
                                    <input wire:model="minimo" type="text" class="form-control" id="minimo" placeholder="Mínimo">@error('minimo') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="form-group col-sm-12 col-md-12 col-lg-8 col-xl-6">
                                    <label for="maximo">Máximo</label>
                                    <input wire:model="maximo" type="text" class="form-control" id="maximo" placeholder="Máximo">@error('maximo') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="form-group col-sm-12 col-md-12 col-lg-8 col-xl-6">
                                    <label for="fecha_inicio_primera_fase_matricula">Fecha Inicio Primera Fase Matrícula</label>
                                    <input wire:model="fecha_inicio_primera_fase_matricula" type="date" class="form-control" id="fecha_inicio_primera_fase_matricula" placeholder="Fecha Inicio Primera Fase Matrícula">@error('fecha_inicio_primera_fase_matricula') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="form-group col-sm-12 col-md-12 col-lg-8 col-xl-6">
                                    <label for="fecha_fin_primera_fase_matricula">Fecha Fin Primera Fase Matrícula</label>
                                    <input wire:model="fecha_fin_primera_fase_matricula" type="date" class="form-control" id="fecha_fin_primera_fase_matricula" placeholder="Fecha Fin Primera Fase Matrícula">@error('fecha_fin_primera_fase_matricula') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="form-group col-sm-12 col-md-12 col-lg-8 col-xl-6">
                                    <label for="fecha_inicio_segunda_fase">Fecha Inicio Segunda Fase</label>
                                    <input wire:model="fecha_inicio_segunda_fase" type="date" class="form-control" id="fecha_inicio_segunda_fase" placeholder="Fecha Inicio Segunda Fase">@error('fecha_inicio_segunda_fase') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="form-group col-sm-12 col-md-12 col-lg-8 col-xl-6">
                                    <label for="fecha_fin_segunda_fase">Fecha Fin Segunda Fase</label>
                                    <input wire:model="fecha_fin_segunda_fase" type="date" class="form-control" id="fecha_fin_segunda_fase" placeholder="Fecha Fin Segunda Fase">@error('fecha_fin_segunda_fase') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
            
                    </fieldset>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" wire:click.prevent="cancel()" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" wire:click.prevent="update()" class="btn btn-primary">Guardar</button>
            </div>
       </div>
    </div>
</div>
