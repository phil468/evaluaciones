@section('title', __('Evaluaciones'))
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <h4 class="h4">EVALUACIÓN</h4>
                        </div>
                        @if ($errors->any())
                            <div wire:poll.4s class="btn btn-sm btn-danger" style="margin-top:0px; margin-bottom:0px;">
                                Debe responder todas las preguntas.
                            </div>
                        @endif

                        <div class="float-right">
                            {{-- <button class="btn btn-default btn-lg" wire:click="guardar">Guardar</button> --}}
							@if ($evaluadorHasEvaluado->realizado)
								<button class="btn btn-default" wire:click="cancelar">Aceptar</button>
							@else
                            	<button class="btn btn-secondary" wire:click="cancelar">Cancelar</button>
							@endif
                        </div>
                    </div>
                </div>

                {{-- HAcer del card body algo transparente --}}
                <div class="card-body">
                    @if ($evaluadorHasEvaluado->realizado)
                        <div class="alert alert-success" role="alert">
                            <h4 class="alert-heading">Evaluación realizada</h4>
                            <p>La evaluación ya fue realizada, no se puede modificar.</p>
                            <hr>
                            <p class="mb-0">Gracias por su participación.</p>
                        </div>
                    @else
                        <div class="row">
                            <div class="col-md-6">
                                <h5 class='h5'>Evaluador:</h5>
                                <p>{{ $evaluador->name ?? 'No identificado' }}</p>
                                <h5 class='h5'>Cargo:</h5>
                                <p>{{ $evaluador->cargo->name ?? 'No identificado' }}</p>
                            </div>
                            <div class="col-md-6">
                                <h5 class='h5'>Evaluado:</h5>
                                <p>{{ $evaluado->name ?? 'No identificado' }}</p>
                                <h5 class='h5'>Cargo:</h5>
                                <p>{{ $evaluado->cargo->name ?? 'No identificado' }}</p>
                            </div>
                        </div>
                        <br>
                        <h5 class="h5">
                            EVALUACIÓN DE COMPETENCIAS</h5>

                        <p>En la siguiente sección deberás calificar (del 1 al 10) las competencias que se brindan.
                            En todo momento, necesitamos que respondas de la manera más neutral y sincera posible.
                            <b> Por favor, sigue las instrucciones antes de completar el formulario.</b>
                        </p>
                        <br>
                        <h4 class="h2"><span class="badge text-white"
                                style="background-color:
							{{ $secciones[$seccion_indexs[$seccion_index_select]]['color'] }} ">
                                {{ $secciones[$seccion_indexs[$seccion_index_select]]['name'] }}
                            </span></h4>

                        {{-- <h5 class="h5"></h5> --}}
                        <p class="ml-4">
                            La escala contiene <u> 10 grados posibles</u> de calificación: el extremo más alto
                            y favorable es 10, mientras que el extremo más bajo y desfavorable es 1. Usted
                            debe elegir uno de los 10 grados posibles.

                            Considere que las calificaciones más altas son 8, 9 y 10.
                            Las calificaciones más bajas son 1, 2 y 3. Cuando usted elige 4, 5, 6 y 7 está
                            indicando que la <b>afirmación</b> refleja de manera parcial o intermedia la conducta que
                            usted observa habitualmente en el <b>calificado (a)</b>.
                        </p>
                        <br>
                        <div class="ml-4">
                            @foreach ($preguntas as $index => $item)
                                {{-- {{dd($index, $item)}} --}}
                                @if ($item['seccion_id'] == $secciones[$seccion_indexs[$seccion_index_select]]['id'])
                                    <div class="question d-block" {{-- style="background-color: {{$item['seccion']['color']}}" --}}>
                                        {{-- <span class="badge badge-pill text-white" style="background-color: {{$item['seccion']['color']}}">{{$item['seccion']['name']}}</span> --}}

                                        {{-- <sub  style="background-color: {{$item['seccion']['color']}}">{{$item['seccion']['name']}}</sub> --}}
                                        <h5>{{ $item['numero_orden'] . '. ' . $item['pregunta'] }}</h5>
                                        <div class="rating-buttons ml-4 mb-2">
                                            @for ($i = 1; $i <= 10; $i++)
                                                <button
                                                    class="btn btn-md 
												@if ($item['valor'] == $i) btn-primary                                    
												@else
													btn-default @endif
												{{ $i <= 10 ? 'ml-1' : '' }}"
                                                    wire:click="marcarValor({{ $index }}, {{ $i }})">{{ $i }}</button>
                                            @endfor
                                            @error('preguntas.' . $index . '.valor')
                                                <br><span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                @else
                                    <div class="question d-none" {{-- style="background-color: {{$item['seccion']['color']}}" --}}>
                                        <span class="badge badge-pill text-white"
                                            style="background-color: {{ $item['seccion']['color'] }}">{{ $item['seccion']['name'] }}</span>
                                        {{-- <sub  style="background-color: {{$item['seccion']['color']}}">{{$item['seccion']['name']}}</sub> --}}
                                        <h5>{{ $item['numero_orden'] . '. ' . $item['pregunta'] }}</h5>
                                        <div class="rating-buttons ml-4 mb-2">
                                            @for ($i = 1; $i <= 10; $i++)
                                                <button
                                                    class="btn btn-md 
												@if ($item['valor'] == $i) btn-primary                                    
												@else
													btn-default @endif
												{{ $i <= 10 ? 'ml-1' : '' }}"
                                                    wire:click="marcarValor({{ $index }}, {{ $i }})">{{ $i }}</button>
                                            @endfor
                                            @error('preguntas.' . $index . '.valor')
                                                <br><span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                        <br>
                        {{-- boton de anterior y siguiente --}}
                        <div class="row ml-sm-4">
							{{-- <button class="btn btn-default" wire:click="anterior">Anterior</button>
							<button class="btn btn-primary" wire:click="siguiente">Siguiente</button> --}}

                            {{-- <div class="col-md-6 col-sm-12"> --}}
                                <div class="row">
                                    {{-- <div class="col-xs-6"> --}}
                                        @if ($seccion_index_select > 0)
                                            {{-- <div class="text-sm-right"> --}}
                                                <button class="btn btn-default" wire:click="anterior">Anterior</button>
                                            {{-- </div> --}}
                                        @else
										{{-- <button class="btn btn-default d-none" wire:click="anterior">Anterior</button> --}}
                                        @endif
                                    </div>
                                    {{-- <div class="col-xs-6"> --}}
                                        @if ($seccion_index_select < count($seccion_indexs) - 1)
                                            {{-- <div class="text-sm-left"> --}}
                                                <button class="btn btn-primary" wire:click="siguiente">Siguiente</button>
                                            {{-- </div> --}}
                                        @else
                                            {{-- <div class="text-sm-left"> --}}
                                                <button class="btn btn-primary" id="confirmarGuardado" wire:click="confirmarGuardado">Guardar</button>
                                            {{-- </div> --}}
                                        @endif
                                    </div>
                                </div>
                            {{-- </div> --}}
                        </div>
                    @endif

                </div>
                <div wire:loading wire:target="guardar,anterior,siguiente">
                    <x-loading-indicator />
                </div>
            </div>
        </div>
    </div>
</div>
