@section('title', __('Evaluaciones'))
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card rounded-xl">
                <div class="text-white card-header bg-vanguard rounded-t-xl">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <h4 class="h4">EVALUACIÓN DE COMPETENCIAS</h4>
                        </div>
                        @if ($errors->any())
                            <div wire:poll.4s class="btn btn-sm btn-danger" style="margin-top:0px; margin-bottom:0px;">
                                Debe responder todas las preguntas.
                            </div>
                        @endif

                        <div class="float-right">
                            <a type="button" class="btn btn-default rounded-xl" href="{{url('/evaluaciones-de-desempeno/1')}}" >Volver</a>
                        </div>
                    </div>
                </div>

                {{-- Hacer del card body algo transparente --}}
                <div class="card-body">
                    
						@include('livewire.evaluacion.indicaciones')
						@include('livewire.evaluacion.confirmacion')
						@include('livewire.evaluacion.gracias')

                        <div class="row">
                            <div class="col-md-6">
                                <h5 class='h5'>Evaluado:</h5>
                                <p>{{ $evaluado->name ?? 'No identificado' }}</p>
                            </div>
                            
                            <div class="col-md-6">
                                <h5 class='h5'>Cargo:</h5>
                                <p>{{ $evaluadorHasEvaluado->cargo_de_evaluado ?? 'No identificado' }}</p>
                            </div>
                        </div>
                        <br>
                        @if ($realizado)
                            <div class="alert alert-warning" role="alert">
                                <h4 class="alert-heading">Evaluación realizada</h4>
                                <p>La evaluación ya fue realizada, no se puede modificar.</p>
                                <br>
                                <p class="mb-0">Gracias por su participación.
                                    <button class="rounded-xl btn btn-default" wire:click="cancelar">Volver</button></p>
    
                            </div>
                        @else
                        <div class="progress" style="height: 35px; border-radius: 20px; background-color: #6ECBC9">
							<div class="progress-bar {{$class}}" role="progressbar" style="width: {{$porcentaje}}%; font-size: 18px; font-weight: bold; border-radius: 20px;" aria-valuenow="{{$porcentaje}}" aria-valuemin="0" aria-valuemax="100"> {{$label}} </div>
						</div>

                        <br>
                        <span class="p-2 text-white h5 d-block bg-vanguard rounded-xl">
                            {{ $secciones[$seccion_indexs[$seccion_index_select]]['name'] }}
                        </span>

                        <p class="mt-4 ml-4">
                            La escala contiene <u> 10 grados posibles</u> de calificación: el extremo más alto
                            y favorable es 10, mientras que el extremo más bajo y desfavorable es 1. 
                            Debes elegir uno de los 10 grados posibles. Considerando que 
                            las calificaciones más altas son 8, 9 y 10.
                            Las calificaciones más bajas son 1, 2 y 3. Cuando usted elige 4, 5, 6 y 7 está
                            indicando que la <b>afirmación</b> refleja de manera parcial o intermedia la conducta que
                            usted observa habitualmente en el <b>calificado (a)</b>.
                        </p>
                        <br>
                        <div class="ml-4">
                            <table class="table table-striped table-inverse table-responsive">

                                <tbody>
                            @foreach ($preguntas as $index => $item)
                                @if ($item['seccion_id'] == $secciones[$seccion_indexs[$seccion_index_select]]['id'])
                                    <tr>
                                        <td class="row">
                                            <div class="align-content-center col-12 col-sm-4 col-lg-3 col-xl-4">{{ $item['numero_orden'] . '. ' . $item['pregunta'] }}</div>
                                            <div class="align-content-center col-12 col-sm-8 col-lg-9 col-xl-8 rating-buttons">
                                                @for ($i = 1; $i <= 10; $i++)
                                                    <button
                                                        class="btn btn-md 
                                                    @if ($item['valor'] >= $i) btn-primary                                    
                                                    @else
                                                        btn-default @endif
                                                    {{ $i <= 10 ? 'm-1' : '' }}"
                                                        wire:click="marcarValor({{ $index }}, {{ $i }})">{{ $i }}</button>
                                                @endfor
                                                @error('preguntas.' . $index . '.valor')
                                                    <br><span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>                                                 
                                        </td>                                           
                                    </tr>
                                @endif
                            @endforeach
                            
                            </tbody>
                            </table>
                        </div>
                        <br>
                        {{-- boton de anterior y siguiente --}}
                        <div class="ml-sm-4">
                            <div>
                                @if ($seccion_index_select > 0)
                                    <button type="button" class="mx-1 rounded-xl btn btn-default" wire:click="anterior">Anterior</button>                                                    
                                @endif                                    
                                @if ($seccion_index_select < count($seccion_indexs) - 1)
                                    <button type="button" class="mx-1 rounded-xl btn btn-vanguard" wire:click="siguiente">Siguiente</button>                                                    
                                @else
                                    <button type="button" class="mx-1 rounded-xl btn btn-vanguard btn-lg" id="confirmarGuardado" data-toggle="modal" data-target="#confirmacionModal"
                                        wire:click="confirmarGuardado"
                                        >Guardar</button>
                                @endif
                            </div>
                        </div>
                    @endif

                </div>
                <div wire:loading wire:target="guardar,anterior,siguiente,volver">
                    <x-loading-indicator />
                </div>
            </div>
        </div>
    </div>

	@push('js')
        <script>
            document.addEventListener('livewire:load', function() {
                if (@this.aceptado) {
                    console.log('aceptado');
                } else {
                    console.log('no aceptado');
                    $('#indicacionesModal').modal('show');                    
                }
            })
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