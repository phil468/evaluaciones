<!-- Modal -->
<div wire:ignore.self class="modal fade" id="updateModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="rounded-2xl modal-content">
            <div class="text-white modal-header bg-vanguard rounded-t-2xl">
                <h5 class="modal-title h5" id="updateModalLabel" wire:loading.remove wire:target="edit"> 
                    @if ($this->selected_id == 0)                    
                        Nueva
                    @else
                        Actualizar
                    @endif Evaluacion
                </h5>
                <button type="button" class="text-white close" data-dismiss="modal" aria-label="Close">
                    <span wire:click.prevent="cancel()" aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <fieldset class="row" wire:target="edit,store,update" wire:loading.attr="disabled"
                        @if (!$this->updateMode)                    
                            disabled
                        @endif >
                    
                        @if (!$this->updateMode)
                        <div class="col-12 alert alert-warning" role="alert">
                            Cargando ...
                        </div>
                        @endif

					    <input type="hidden" wire:model="selected_id">
                        {{-- <div class="form-group col-sm-12 col-md-12 col-lg-6 col-xl-6">
                            <label for="eid">Eid</label>
                            <input wire:model="eid" type="text" class="form-control" id="eid" placeholder="Eid">@error('eid') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div> --}}
                        <div class="form-group col-sm-12 col-md-12 col-lg-6 col-xl-6">
                            <label for="title">Título*</label>
                            <input wire:model="title" type="text" class="form-control" id="title" placeholder="Título">@error('title') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        {{-- <div class="form-group col-sm-12 col-md-12 col-lg-6 col-xl-6">
                            <label for="date">Date</label>
                            <input wire:model="date" type="date" class="form-control" id="date" placeholder="Date">@error('date') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div> --}}
                        <div class="form-group col-sm-12 col-md-12 col-lg-6 col-xl-6">
                            <label for="status">Estado*</label>
                            <label class="mb-4 checkbox-container">
                                <input wire:model="status" type="checkbox" class="custom-checkbox" id="status" style="display: none;">
                                <span class="checkmark"></span>
                            </label>
                            
                            {{-- <input wire:model="status" type="checkbox" class="custom-checkbox form-control" id="status"> --}}
                            @error('status') <span class="error text-danger">{{ $message }}</span> @enderror
                            {{-- <input wire:model="status" type="checkbox" class="form-control custom-checkbox" id="status" placeholder="Status">@error('status') <span class="error text-danger">{{ $message }}</span> @enderror --}}
                        </div>

                        <div class="form-group col-sm-12 col-md-12 col-lg-6 col-xl-6">
                            <label for="nombre_para_mostrar">Nombre Para Mostrar*</label>
                            <input wire:model="nombre_para_mostrar" type="text" class="form-control" id="nombre_para_mostrar" placeholder="Nombre Para Mostrar">@error('nombre_para_mostrar') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group col-sm-12 col-md-12 col-lg-6 col-xl-6">
                            <label for="campania">Campaña*</label>
                            <input wire:model="campania" type="text" class="form-control" id="campania" placeholder="Campaña">@error('campania') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        {{-- <div class="form-group col-sm-12 col-md-12 col-lg-6 col-xl-6">
                            <label for="mes">Mes</label>
                            <input wire:model="mes" type="text" class="form-control" id="mes" placeholder="Mes">@error('mes') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group col-sm-12 col-md-12 col-lg-6 col-xl-6">
                            <label for="anio">Año</label>
                            <input wire:model="anio" type="text" class="form-control" id="anio" placeholder="Año">@error('anio') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div> --}}
                        <div class="form-group col-sm-12 col-md-12 col-lg-6 col-xl-6">
                            <label for="fecha_inicio">Fecha Inicio*</label>
                            <input wire:model="fecha_inicio" type="datetime-local" class="form-control" id="fecha_inicio" placeholder="Fecha Inicio">@error('fecha_inicio') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group col-sm-12 col-md-12 col-lg-6 col-xl-6">
                            <label for="fecha_fin">Fecha Fin*</label>
                            <input wire:model="fecha_fin" type="datetime-local" class="form-control" id="fecha_fin" placeholder="Fecha Fin">@error('fecha_fin') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group col-sm-12 col-md-12 col-lg-6 col-xl-6">
                            <label for="identificador">Identificador*</label>
                            <input wire:model="identificador" type="text" class="form-control" id="identificador" placeholder="Identificador">@error('identificador') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        
                        <div class="form-group col-sm-12 col-md-12 col-lg-6 col-xl-6">
                            <label for="tipo_de_evaluacion_id">Tipo de Evaluación*</label>
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
                                @endif >
                            <hr>
                            <div class="mt-2 h5">
                                Opciones de evaluación por resultados:
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-12 col-md-12 col-lg-6 col-xl-6">
                                    <label for="minimo">Mínimo*</label>
                                    <input wire:model="minimo" type="text" class="form-control" id="minimo" placeholder="Mínimo">@error('minimo') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="form-group col-sm-12 col-md-12 col-lg-6 col-xl-6">
                                    <label for="maximo">Máximo*</label>
                                    <input wire:model="maximo" type="text" class="form-control" id="maximo" placeholder="Máximo">@error('maximo') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="form-group col-sm-12 col-md-12 col-lg-6 col-xl-6">
                                    <label for="fecha_inicio_primera_fase_matricula">Fecha Inicio Primera Fase Matrícula*</label>
                                    <input wire:model="fecha_inicio_primera_fase_matricula" type="datetime-local" class="form-control" id="fecha_inicio_primera_fase_matricula" placeholder="Fecha Inicio Primera Fase Matrícula">@error('fecha_inicio_primera_fase_matricula') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="form-group col-sm-12 col-md-12 col-lg-6 col-xl-6">
                                    <label for="fecha_fin_primera_fase_matricula">Fecha Fin Primera Fase Matrícula*</label>
                                    <input wire:model="fecha_fin_primera_fase_matricula" type="datetime-local" class="form-control" id="fecha_fin_primera_fase_matricula" placeholder="Fecha Fin Primera Fase Matrícula">@error('fecha_fin_primera_fase_matricula') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="form-group col-sm-12 col-md-12 col-lg-6 col-xl-6">
                                    <label for="fecha_inicio_segunda_fase">Fecha Inicio Segunda Fase*</label>
                                    <input wire:model="fecha_inicio_segunda_fase" type="datetime-local" class="form-control" id="fecha_inicio_segunda_fase" placeholder="Fecha Inicio Segunda Fase">@error('fecha_inicio_segunda_fase') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="form-group col-sm-12 col-md-12 col-lg-6 col-xl-6">
                                    <label for="fecha_fin_segunda_fase">Fecha Fin Segunda Fase*</label>
                                    <input wire:model="fecha_fin_segunda_fase" type="datetime-local" class="form-control" id="fecha_fin_segunda_fase" placeholder="Fecha Fin Segunda Fase">@error('fecha_fin_segunda_fase') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="form-group col-sm-12 col-md-12 col-lg-6 col-xl-6">
                                    <label for="fecha_para_mostrar_resultados">Fecha para mostrar resultados*</label>
                                    <input wire:model="fecha_para_mostrar_resultados" type="datetime-local" class="form-control" id="fecha_para_mostrar_resultados" placeholder="Fecha Fin Segunda Fase">@error('fecha_para_mostrar_resultados') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>
                                
                            </div>
                        </div>
            
                    </fieldset>
                </form>
            </div>
            <div class="modal-footer">
                <button
                type="button" 
                wire:target="edit,store,update,tipo_de_evaluacion_id" 
                wire:loading.attr="disabled" 
                wire:click.prevent="cancel()" 
                class="btn btn-secondary rounded-xl" 
                data-dismiss="modal"
                >
                    Cerrar
                </button>
                @if ($this->selected_id == 0)                    
                    <button 
                    type="button" 
                    wire:target="edit,store,update,tipo_de_evaluacion_id" 
                    wire:loading.attr="disabled" 
                    wire:click.prevent="store()" 
                    class="btn btn-lg btn-vanguard rounded-xl close-modal"
                    >Guardar</button>
                @else
                    <button 
                    type="button" 
                    wire:target="edit,store,update,tipo_de_evaluacion_id" 
                    wire:loading.attr="disabled" 
                    wire:click.prevent="update()" 
                    class="btn btn-lg btn-vanguard rounded-xl"
                    >Guardar</button>
                @endif
            </div>
       </div>
    </div>
    {{--agregar css--}}
    <style>

        .checkbox-container {
            display: block;
            position: relative;
            padding-left: 35px;
            cursor: pointer;
            font-size: 22px;
            user-select: none;
        }

        .checkbox-container input {
            position: absolute;
            opacity: 0;
            cursor: pointer;
            height: 0;
            width: 0;
        }

        .checkmark {
            position: absolute;
            top: 0;
            left: 0;
            height: 40px;
            width: 40px;
            background-color: #eee;
            border-radius: 50%;
            border: 5px solid #568ba5; /* Agrega un borde */

        }

        .checkbox-container:hover input ~ .checkmark {
            background-color: #ccc;
        }

        .checkbox-container input:checked ~ .checkmark {
            background-color: white;
        }

        .checkmark:after {
            content: "";
            position: absolute;
            display: none;
        }

        .checkbox-container input:checked ~ .checkmark:after {
            display: block;
        }

        .checkbox-container .checkmark:after {
            left: 10.2px;
            top: 4px;
            width: 10px;
            height: 20px;
            border: solid #568ba5;
            border-width: 0 5px 5px 0;
            transform: rotate(45deg);
        }
    </style>
</div>
