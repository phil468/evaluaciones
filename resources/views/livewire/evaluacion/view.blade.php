@section('title', __('Evaluaciones'))
<div class="container-fluid">
    <!-- Agrega esto justo después de la apertura del div container-fluid -->
    <div id="escala-tooltip" style="display: none; position: absolute; background: transparent; padding: 2px 8px; font-weight: 600; font-size: 0.8rem; text-align: center; pointer-events: none; z-index: 1000;"></div>
    
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card rounded-xl">

                {{-- Hacer del card body algo transparente --}}
                <div class="card-body">

                    @include('livewire.evaluacion.indicaciones')
                    @include('livewire.evaluacion.indicaciones-view')
                    @include('livewire.evaluacion.confirmacion')
                    @include('livewire.evaluacion.gracias')

                    <div class="row">
                        <div class="col-md-5">
                            <h5 class='h5'>Evaluado:</h5>
                            <p>{{ $evaluado->name ?? 'No identificado' }}</p>
                        </div>

                        <div class="col-md-5">
                            <h5 class='h5'>Cargo:</h5>
                            <p>{{ $evaluadorHasEvaluado->cargo_nombre_evaluado ?? 'No identificado' }}</p>
                        </div>
                        
                        <div class="col-md-2">
                            <div class="text-right">
                                
                                <button type="button"  
                                style="
                                background-color: #6ECBC9;
                                border-color: #6ECBC9;
                                
                                    border-radius: 50%; 
                                    "
                                class="btn btn-lg btn-vanguard" data-toggle="modal" data-target="#indicacionesModalView">
                                    <i class="fas fa-bell"></i>
                                </button>
                            </div>
                        </div>
                        
                        
                    </div>
                    <br>
                    @if ($realizado)
                        <div class="alert alert-warning" role="alert">
                            <h4 class="alert-heading">Evaluación realizada</h4>
                            <p>La evaluación ya fue realizada, no se puede modificar.</p>
                            <br>
                            <p class="mb-0">Gracias por su participación.
                                <button class="rounded-xl btn btn-default" wire:click="cancelar">Volver</button>
                            </p>

                        </div>
                    @else
                        <div class="progress" style="height: 35px; border-radius: 20px; background-color: #6ECBC9">
                            <div class="progress-bar {{ $class }}" role="progressbar"
                                style="width: {{ $porcentaje }}%; font-size: 18px; font-weight: bold; border-radius: 20px;"
                                aria-valuenow="{{ $porcentaje }}" aria-valuemin="0" aria-valuemax="100">
                                {{ $label }} </div>
                        </div>
                        
                        <br>
                        <span class="p-2 text-white h5 d-block rounded-xl"
                        style="background-color: {{ $secciones[$seccion_indexs[$seccion_index_select]]['color'] ?? '#568BA5' }} " 
                        >
                            {{ $secciones[$seccion_indexs[$seccion_index_select]]['name'] }}
                        </span>

                        <p class="mt-4 ml-4">
                            {{ $secciones[$seccion_indexs[$seccion_index_select]]['descripcion'] }}
                        </p>
                        <br>
                        <div class="ml-4">
                            <table class="table table-striped table-inverse table-responsive">

                                <tbody>
                                    @foreach ($preguntas as $index => $item)
                                        @if ($item['seccion_id'] == $secciones[$seccion_indexs[$seccion_index_select]]['id'])
                                            <tr>
                                                <td class="row">
                                                    <div class="align-content-center col-12 col-sm-4 col-lg-3 col-xl-4">
                                                        {{ $item['numero_orden'] . '. ' . $item['pregunta'] }}</div>
                                                    <div class="align-content-center col-12 col-sm-8 col-lg-9 col-xl-8 rating-buttons">
                                                        @for ($i = 1; $i <= 10; $i++)
                                                            <button class="btn btn-md 
                                                                @if ($item['valor'] == $i) btn-primary 
                                                                @else btn-outline-{{ isset($escalasArray[$i]) ? $escalasArray[$i]['color'] : 'secondary' }} @endif
                                                                {{ $i <= 10 ? 'm-1' : '' }}"
                                                                wire:click="marcarValor({{ $index }}, {{ $i }})"
                                                                data-toggle="tooltip"
                                                                title="{{ isset($escalasArray[$i]) ? $escalasArray[$i]['name'] : '' }}">
                                                                {{ $i }}
                                                            </button>
                                                        @endfor
                                                        @error('preguntas.' . $index . '.valor')
                                                            <br><span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </td>
                                            </tr>
                                        @endif

                                        @if ($item['campania_has_competencia_id'] == $secciones[$seccion_indexs[$seccion_index_select]]['id'])
                                            <tr>
                                                <td class="row">
                                                    <div class="mt-3 align-content-center col-sm-12 col-lg-3 col-xl-4">
                                                        {{ $item['numero_orden'] . '. ' . $item['pregunta'] }}</div>
                                                    <div class="mt-3 align-content-center col-sm-12 col-lg-9 col-xl-8 rating-buttons">
                                                        @for ($i = 1; $i <= 10; $i++)
                                                            <button class="btn btn-md rating-btn"
                                                                wire:click="marcarValor({{ $index }}, {{ $i }})"
                                                                data-escala="{{ isset($escalasArray[$i]) ? $escalasArray[$i]['name'] : '' }}"
                                                                data-escala-color="{{ isset($escalasArray[$i]) ? $escalasArray[$i]['color'] : '#6ECBC9' }}"

                                                                @if ($item['valor'] === $i)
                                                                    style="background-color: {{ isset($escalasArray[$i]) ? $escalasArray[$i]['color'] : '#6ECBC9' }};
                                                                    border-color: {{ isset($escalasArray[$i]) ? $escalasArray[$i]['color'] : '#6ECBC9' }};
                                                                    color: white; font-weight: bold;
                                                                    "
                                                                @else 
                                                                    style="border-color: {{ isset($escalasArray[$i]) ? $escalasArray[$i]['color'] : '#6ECBC9' }};
                                                                    color: {{ isset($escalasArray[$i]) ? $escalasArray[$i]['color'] : '#6ECBC9' }};
                                                                    "
                                                                @endif
                                                                >
                                                                {{ $i }}
                                                            </button>
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
                                    <button type="button" class="mx-1 rounded-xl btn btn-default"
                                        wire:click="anterior">Anterior</button>
                                @endif
                                @if ($seccion_index_select < count($seccion_indexs) - 1)
                                    <button type="button" class="mx-1 rounded-xl btn btn-vanguard"
                                        wire:click="siguiente">Siguiente</button>
                                @else
                                    <button type="button" class="mx-1 rounded-xl btn btn-vanguard btn-lg"
                                        id="confirmarGuardado" data-toggle="modal" data-target="#confirmacionModal"
                                        wire:click="confirmarGuardado">Guardar</button>
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

                // Tooltip flotante personalizado
                const tooltip = document.getElementById('escala-tooltip');
                
                function initializeTooltips() {
                    // Elimina los tooltips de Bootstrap para evitar conflictos
                    $('[data-toggle="tooltip"]').tooltip('dispose');
                    
                    // Selecciona todos los botones de calificación
                    const ratingButtons = document.querySelectorAll('.rating-btn');
                    
                    ratingButtons.forEach(button => {
                        // Elimina eventos previos para evitar duplicados
                        button.removeEventListener('mouseenter', showTooltip);
                        button.removeEventListener('mouseleave', hideTooltip);
                        
                        // Agrega nuevos event listeners
                        button.addEventListener('mouseenter', showTooltip);
                        button.addEventListener('mouseleave', hideTooltip);
                    });
                }
                
                function showTooltip(e) {
                    const button = e.target;
                    const escala = button.getAttribute('data-escala');
                    const color = button.getAttribute('data-escala-color');
                    
                    if (escala) {
                        tooltip.textContent = escala;
                        tooltip.style.color = color;
                        tooltip.style.display = 'block';
                        
                        // Posicionar el tooltip encima del botón
                        const rect = button.getBoundingClientRect();
                        tooltip.style.left = (rect.left + (rect.width / 2) - (tooltip.offsetWidth / 2)) + 'px';
                        tooltip.style.top = (rect.top - tooltip.offsetHeight - 5) + window.scrollY + 'px';
                    }
                }
                
                function hideTooltip() {
                    tooltip.style.display = 'none';
                }
                
                // Inicializar tooltips
                initializeTooltips();
                
                // Reinicializar tooltips después de actualizaciones de Livewire
                Livewire.hook('message.processed', () => {
                    setTimeout(initializeTooltips, 100);
                });
            })
        </script>
    @endpush

    @push('styles')
        <style nonce="{{ $nonce }}">
            .rating-buttons button {
                border-color: #568BA5;
                border-radius: 50%;
                border-top-width: 2px;
                border-bottom-width: 2px;
                border-left-width: 2px;
                border-right-width: 2px;
                width: 42px;
                height: 42px;
                font-size: 0.90rem;
                font-weight: 500;
                padding: 6px;
            }

            /* Nuevos estilos para los colores de las escalas */
            .btn-outline-alto {
                color: #28a745;
                border-color: #28a745;
            }
            
            .btn-outline-medio-alto {
                color: #88c34a;
                border-color: #88c34a;
            }
            
            .btn-outline-medio {
                color: #ffc107;
                border-color: #ffc107;
            }
            
            .btn-outline-bajo {
                color: #dc3545;
                border-color: #dc3545;
            }

            /* Estilo para la lista de escala */
            .escala-lista {
                counter-reset: item;
                list-style-position: inside;
                padding-left: 20px;
            }
            
            .escala-lista li {
                margin-bottom: 10px;
                list-style-type: decimal;
            }
    
            /* Añade estos estilos */
            .rating-btn {
                transition: transform 0.2s, box-shadow 0.2s;
                position: relative;
            }
            
            .rating-btn:hover {
                transform: scale(1.1);
                z-index: 50;
                box-shadow: 0 0 8px rgba(0,0,0,0.3);
            }
            
            #escala-tooltip {
                border-radius: 3px;
                transition: all 0.2s;
                white-space: nowrap;
                /* transform: translateY(-5px); */
                /* font-weight: bold; */
            }

            /* .question {
                border: 1px solid #ccc;
                border-radius: 10px;
                padding: 10px;
                margin-bottom: 10px;
            } */
        </style>
    @endpush
</div>
