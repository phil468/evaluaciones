@section('title', __('Evaluaciones'))
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card rounded-xl">
                <div class="text-white card-header bg-vanguard rounded-t-xl">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <h4 class="h4">EVALUACIÓN POR RESULTADOS</h4>
                        </div>
                        @if ($errors->any())
                            <div wire:poll.4s class="btn btn-sm btn-danger" style="margin-top:0px; margin-bottom:0px;">
                                Debe ingresar al menos un objetivo.
                            </div>
                        @endif

                        <div class="float-right">
                            	<a type="button" class="btn btn-default rounded-xl" href="{{url('/evaluaciones-de-desempeno/2')}}" >Volver</a>
                        </div>
                    </div>
                </div>

                {{-- Hacer del card body algo transparente --}}
                <div class="card-body">
                    
						{{-- @include('livewire.evaluacion.indicaciones')
						@include('livewire.evaluacion.confirmacion') --}}
						@include('livewire.evaluacion.gracias')

                        <div class="row">
                            <div class="col-md-6">
                                <h5 class='h5'>Evaluado:</h5>
                                <p>{{ $evaluado->name ?? 'No identificado' }}</p>
                            </div>
                            
                            <div class="col-md-6">
                                <h5 class='h5'>Cargo:</h5>
                                <p>{{ $evaluado->cargo->name ?? 'No identificado' }}</p>
                            </div>
                        </div>
                        <br>

                        {{-- <form wire:submit.prevent="guardar_objetivos"> --}}
                            <div class="row">                     
                                <!-- Campo para seleccionar el tipo de objetivo -->
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="descripcion1">Descripción de Objetivo* :</label>
                                        <input type="text" class="form-control" id="descripcion1" wire:model="descripcion1">
                                        @error('descripcion1')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="tipo_objetivo_id1">Tipo de Objetivo* :</label>
                                        <select class="form-control" id="tipo_objetivo_id1" wire:model="tipo_objetivo_id1">
                                            @foreach ($tipos_objetivo as $tipo)
                                                <option value="{{ $tipo->id }}">{{ $tipo->unidad.'('.$tipo->simbolo.')' }}</option>
                                            @endforeach
                                        </select>
                                        @error('tipo_objetivo_id1')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                {{-- <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="cantidad1">Meta* :</label>
                                        <input type="number" class="form-control" id="cantidad1" wire:model="cantidad1">
                                        @error('cantidad1')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>                                 --}}
                                
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="cantidad2">Resultado* :</label>
                                        <input type="number" class="form-control" id="resultado1" wire:model="resultado1" disabled>
                                        @error('cantidad2')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                {{--agregar un input tipo file de evidencias pero que este disabled--}}
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="evidencia1">Evidencia* (Máx: 10MB):</label>
                                        <input type="file" class="form-control" id="evidencia1" wire:model="evidencia1" disabled>
                                        @error('evidencia1')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                    
                                
                        
                                <!-- Segundo conjunto de campos para el segundo objetivo -->
                                <br>
                                <br>
                                <hr>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="descripcion2">Descripción de Objetivo:</label>
                                        <input type="text" class="form-control" id="descripcion2" wire:model="descripcion2">
                                        @error('descripcion2')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="tipo_objetivo_id2">Tipo de Objetivo:</label>
                                        <select class="form-control" id="tipo_objetivo_id2" wire:model="tipo_objetivo_id2">
                                            @foreach ($tipos_objetivo as $tipo)
                                            <option value="{{ $tipo->id }}">{{ $tipo->unidad.'('.$tipo->simbolo.')' }}</option>
                                            @endforeach
                                        </select>
                                        @error('tipo_objetivo_id2')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                
                                {{-- <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="cantidad2">Meta:</label>
                                        <input type="number" class="form-control" id="cantidad2" wire:model="cantidad2">
                                        @error('cantidad2')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div> --}}
                                
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="cantidad2">Resultado:</label>
                                        <input type="number" class="form-control" id="resultado2" wire:model="resultado2" disabled>
                                        @error('cantidad2')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                {{--agregar un input tipo file de evidencias pero que este disabled--}}
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="evidencia1">Evidencia (Máx: 10MB):</label>
                                        <input type="file" class="form-control" id="evidencia1" wire:model="evidencia1" disabled>
                                        @error('evidencia1')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                
                            </div>
                            <div>
                                <a type="button" href="{{url('/evaluaciones-de-desempeno/2')}}" class="rounded-full btn btn-outline-vanguard">Volver</a>
                                <button type="button" wire:click.prevent="guardar_objetivos()" class="mx-1 rounded-full btn btn-vanguard">Guardar</button>
                            </div>
                            <br>
                            <div>
                                <h1 class="h5">
                                    Ejemplos:
                                </h1>
                                <ol>
                                    <li>
                                        Reducir en un 10% las incidencias, por intrusión de personal no identificado, al establecimiento durante todo el periodo 24/25 vs 23/24.
                                    </li>
                                    <li>
                                        Cumplir con 145 inspecciones de actos y condiciones de seguridad durante todo el periodo 24/25
                                    </li>
                                </ol>
                                {{-- <div>
                                    Reducir en un 10% las incidencias, por intrusión de personal no identificado, al establecimiento durante todo el periodo 24/25 vs 23/24.
                                </div>
                                <div>
                                    Cumplir con 145 inspecciones de actos y condiciones de seguridad durante todo el periodo 24/25
                                </div> --}}
                            </div>
                </div>
                <div wire:loading wire:target="guardar,anterior,siguiente,volver,guardar_objetivos">
                    <x-loading-indicator />
                </div>
            </div>
        </div>
    </div>

	@push('js')
        <script>
            // document.addEventListener('livewire:load', function() {
            //     if (@this.aceptado) {
            //         console.log('aceptado');
            //     } else {
            //         console.log('no aceptado');
            //         $('#indicacionesModal').modal('show');                    
            //     }
            // })
        </script>
    @endpush

    @push('styles')
        <style>
            .rating-buttons button {
                border-color:#568BA5;
                border-radius: 50%;
                border-top-width:2px;
                border-bottom-width:2px;
                border-left-width:2px;
                border-right-width:2px;
                width: 42px;
                height: 42px;
                font-size: 0.90rem;
                font-weight: 500;
                padding: 6px;
            }

            .question {
                border: 1px solid #ccc;
                border-radius: 10px;
                padding: 10px;
                margin-bottom: 10px;
            }

        </style>
    @endpush    
</div>