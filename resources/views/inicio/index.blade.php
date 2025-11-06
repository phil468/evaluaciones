@extends('adminlte::page')

@section('title', '¡Bienvenido!')

@section('content_header')
    <h1 class="font-style-class">¡Hola, {{ Auth::user()->name }}!</h1>
@stop

@section('content')
<div class="container-fluid">
    <!-- Sección de evaluaciones pendientes -->
    {{-- @if(isset($evaluacionesPendientes) && count($evaluacionesPendientes) > 0) --}}
    {{-- @if(Auth::user()->hasPendingEvaluations()) --}}
    @if($evaluacionesPendientes)
     {{-- Simulación de evaluación pendiente --}}
    <div class="row">
        <div class="col-12">
            <div class="shadow card rounded-xl">
                <div class="card-body justify-content-between align-items-center">
                    <div class=" d-flex">
                        <h5 class="m-0 h5">¡Tienes pendientes por realizar!</h5>
                        <div class="d-flex align-items-center">
                            <div class="ml-3 notification-bell">
                                <i class="fas fa-bell fa-lg text-muted"></i>
                            </div>
                        </div>
                    </div>
                        <a href="{{ route('pendientes') }}"
                        class="mt-4 shadow rounded-xl btn btn-vanguard">
                            Ingresar a Pendientes
                        </a>
                </div>
                {{-- <div class="bg-white card-footer text-muted rounded-b-xl">
                    <small>*Sección solo aparece cuando se tengan pendientes</small>
                </div> --}}
            </div>
        </div>
    </div>
    @endif

    {{-- <div class="mt-2 mb-0 h4 font-style-poppins font-weight-bold">Resultados de Evaluaciones 2024</div> --}}
    
    {{-- <div class="mt-2 mb-0 h4 font-style-poppins font-weight-bold">
        @if(isset($campaniaAnterior) && $campaniaAnterior)
            Resultados de Evaluaciones {{ $campaniaAnterior->anio_mostrar }}
        @else
            No existe campaña anterior
        @endif
    </div> --}}
    <!-- Secciones principales en tarjetas -->
    <div class="row">
        <!-- Evaluación por Competencias -->
        <div class="mt-3 col-md-4">
            <div class="shadow card h-100">
                <div class="card-header">
                    {{-- {{ --indicar el año -- }} --}}
                    <div class="mb-0 h5">Evaluación por Competencias</div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="mb-4 col-12">
                            <p>Evalúa el desarrollo de habilidades blandas, alineadas a nuestros valores y cultura.</p>
                        </div>
                        @if(isset($competenciasAnio) && $competenciasAnio)
                            <small class="text-info">
                                <i class="fas fa-info-circle"></i>
                                Mostrando resultados de: <strong>{{ $competenciasAnio }}</strong>
                                @if($competenciasCampania && $competenciasCampania->es_campania_actual)
                                    <span class="text-success">(Actual)</span>
                                @else
                                    <span class="text-muted">(Anterior)</span>
                                @endif
                            </small>
                        @endif
        <div class="mt-0 mb-6 text-center col-12">
            <div class="mb-3 puntaje-medio">
                @if(!empty($tieneResultadosCompetencias) && $tieneResultadosCompetencias)
                    <h2 class="font-weight-bold h2">{{ $promedioGeneral }}</h2>
                @else
                    <h2 class="font-weight-bold">--</h2>
                    <br>
                @endif

                @if(isset($puntajeEsperado) && $puntajeEsperado !== null)
                    <span class="text-warning d-block">Puntaje esperado: {{ $puntajeEsperado }}</span>
                @else
                    <span class="text-muted d-block">{{ $mensajeEsperadoCompetencias ?? 'No se cuenta con puntaje esperado.' }}</span>
                @endif
            </div>
            <p class="text-sm">Resultados de última evaluación</p>
            @if (empty($tieneResultadosCompetencias) || !$tieneResultadosCompetencias)
                <span class="text-muted small">{{ $mensajeResultadosCompetencias ?? 'No hay resultados disponibles.' }}</span>
            @endif
        </div>
                        {{-- <div class="mt-0 mb-6 text-center col-12">
                            <div class="mb-3 puntaje-medio">

                                @if(!empty($tieneResultados) && $tieneResultados)
                                    <h2 class="font-weight-bold h2">{{ ($promedioGeneral) }}</h2>
                                @else
                                <h2 class="font-weight-bold">--</h2>
                                <br>
                                @endif

                                @if(isset($puntajeEsperado) && $puntajeEsperado !== null)
                                    <span class="text-warning d-block">Puntaje esperado: {{ ($puntajeEsperado) }}</span>
                                @else
                                    <span class="text-muted d-block">{{ $mensajeEsperado ?? 'No se cuenta con puntaje esperado.' }}</span>
                                @endif
                            </div>
                            <p class="text-sm">Resultados de última evaluación</p>
                            @if (empty($tieneResultados) || !$tieneResultados)
                                <span class="text-muted small">{{ $mensajeResultados ?? 'No hay resultados para la campaña anterior.' }}</span>
                            @endif
                            
                        </div> --}}
                    </div>
                </div>
{{-- En el botón "Ver Resultados" de Competencias: --}}
<div class="card-footer rounded-b-xl">
    @php $btnDisabledComp = !($competenciasCampania && $tieneResultadosCompetencias); @endphp
    <a href="{{ route('evaluacion_de_competencias') }}"
       class="shadow rounded-xl btn btn-vanguard btn-block {{ $btnDisabledComp ? 'disabled' : '' }}"
       {{ $btnDisabledComp ? 'aria-disabled=true' : '' }}>
        Ver Resultados
        {{-- @if($competenciasAnio)
            ({{ $competenciasAnio }})
        @endif --}}
    </a>
</div>
                {{-- <div class="card-footer rounded-b-xl">
                    @php $btnDisabled = !($campaniaAnterior && $tieneResultados); @endphp
                    <a href="{{ route('evaluacion_de_competencias') }}"
                       class="shadow rounded-xl btn btn-vanguard btn-block {{ $btnDisabled ? 'disabled' : '' }}"
                       {{ $btnDisabled ? 'aria-disabled=true' : '' }}>
                        Ver Resultados
                    </a>
                </div> --}}
            </div>
        </div>

        <!-- Evaluación por Objetivos -->
        <div class="mt-3 col-md-4" style="display: none;">
            <div class="shadow card h-100">
                <div class="card-header">
                    <div class="mb-0 h5">Evaluación por Objetivos</div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="mb-4 col-12">
                            <p>Evalúa el cumplimiento de metas claras y medibles, establecidas previamente.</p>
                        </div>
                        <div class="mb-4 text-center col-12">
                            <div class="mb-4 progress-chart">
                                <canvas id="chartObjetivos"></canvas>
                                <div class="progress-percent">
                                    <span>--</span>
                                </div>
                            </div>
                            <p class="text-sm">Resultados de última evaluación</p>
                            @if(!empty($objetivosMensaje))
                                <p class="text-muted small">{{ $objetivosMensaje }}</p>
                            @endif
                        </div>
                        {{-- <div class="mb-4 text-center col-12">
                            <div class="mb-4 progress-chart">
                                <canvas id="chartObjetivos"></canvas>
                                <div class="progress-percent">
                                    <span>80%</span>
                                </div>
                            </div>
                            <p class="text-sm">Resultados de última evaluación</p>
                        </div> --}}
                    </div>
                </div>
                <div class="card-footer rounded-b-xl">
                    <a href="{{ url('evaluaciones-de-desempeno/2') }}"
                    class="shadow rounded-xl btn btn-vanguard btn-block disabled">
                        Ver Resultados
                    </a>
                </div>
            </div>
        </div>
    {{-- </div>

    <div class="row"> --}}
        <!-- Feedback y Retroalimentación  - ocultar -->
        <div class="mt-3 col-md-4" style="display: none;">
            <div class="shadow card h-100">
                <div class="card-header">
                    <h5 class="mb-0 h5">Feedback y Retroalimentación</h5>
                </div>
                <div class="card-body">
                    <p>Consulta y gestiona tus evaluaciones recibidas</p>
                </div>
                <div class="card-footer rounded-b-xl">
                    <a href="#"
                    {{-- "{{ route('evaluaciones.feedback') }}"  --}}
                    class="shadow rounded-xl btn btn-vanguard btn-block">
                        Ingresar a Feedback
                    </a>
                </div>
            </div>
        </div>

        <!-- Plan de Mejora Individual -->
        <div class="mt-3 col-md-4">
            <div class="shadow card h-100">
                <div class="card-header">
                    <div class="mb-0 h5">Plan de Mejora Individual</div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="mb-4 col-12">
                            <p>Crea y sigue metas para tu crecimiento profesional</p>
                        </div>
                        <div class="mb-4 text-center col-12">
                            <div class="mb-4 progress-chart">
                                <canvas id="chartDesarrollo"></canvas>
                                <div class="progress-percent">
                                    <span>--</span>
                                </div>
                                {{-- <h2 class="font-weight-bold">--</h2> --}}
                                <br>
                            </div>
                            
                            {{-- <div class="mb-3 puntaje-medio">
                                <h2 class="font-weight-bold">--</h2>
                                <br>
                            </div> --}}
                            
                            <br>
                            <p class="text-sm">Resultados de última evaluación</p>
                        </div>
                    </div>
                </div>
                <div class="card-footer rounded-b-xl">
                    <a href=
                    {{ route('plan.mejora.index') }}
                    {{-- "#" --}}
                    {{-- "{{ route('evaluaciones.desarrollo') }}"  --}}
                    class="shadow rounded-xl btn btn-vanguard btn-block disabled" disabled>
                        Ver Resultados
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Botón para descargar informe -->
    <div class="mt-3 row justify-content-center"
    style="display: none;"
    >
        <div class="col-md-4">
            <a href=
            "#"
            {{-- "{{ route('evaluaciones.descargar-informe') }}"  --}}
            class="shadow rounded-xl btn btn-vanguard btn-block disabled"
            
            >
                <i class="mr-2 fas fa-download"></i> Descargar informe
            </a>
        </div>
    </div>
</div>
@stop

@section('css')
<style nonce="{{ $nonce }}">
    /* Estilos personalizados */
    .font-style-class {
        font-family: "poppins", sans-serif;
        font-weight: 700;
        font-style: normal;
        font-size: 2.5em;
    }

    .btn-vanguard {
        background-color: #568ca5;
        color: white;
    }

    .btn-vanguard:hover {
        background-color: #3c7286;
        color: white;
    }

    .card {
        border-radius: 10px;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.05);
    }

    .progress-chart {
        position: relative;
        width: 100px;
        height: 100px;
        margin: 0 auto;
    }

    .progress-percent {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-size: 20px;
        font-weight: bold;
    }

    .puntaje-medio {
        padding: 15px 0;
    }

    .notification-bell {
        position: relative;
    }

    .notification-bell::after {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 10px;
        height: 10px;
        background-color: #ff6b6b;
        border-radius: 50%;
    }
</style>
@stop

@section('js')
{{-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Configuración común para gráficos
        const chartConfig = {
            cutout: '75%',
            plugins: {
                legend: {
                    display: false
                }
            },
            elements: {
                arc: {
                    borderRadius: 10  // Aquí se aplica el borde redondeado
                }
            }
        };


        // === Objetivos: usa valor del backend ===
        const totalObjetivosRaw = @json($objetivosTotal); // puede ser null o >100
        const tieneTotalObjetivos = totalObjetivosRaw !== null && !Number.isNaN(Number(totalObjetivosRaw));
        const totalObjetivos = tieneTotalObjetivos ? Number(totalObjetivosRaw) : 0;
        const fillObjetivos = Math.max(0, Math.min(100, totalObjetivos)); // clamp 0..100 para el anillo
        const restanteObjetivos = 100 - fillObjetivos;

        // Actualiza el texto centrado (muestra el real, aunque sea >100)
        const objetivosPercentEl = document.querySelector('#chartObjetivos')
            .closest('.progress-chart')
            .querySelector('.progress-percent span');
        objetivosPercentEl.textContent = tieneTotalObjetivos ? `${Math.round(totalObjetivos)}%` : '--';

        const ctxObjetivos = document.getElementById('chartObjetivos').getContext('2d');
        new Chart(ctxObjetivos, {
            type: 'doughnut',
            data: {
                datasets: [{
                    data: [fillObjetivos, restanteObjetivos],
                    backgroundColor: ['#5bbfba', '#f2f2f2'],
                    borderColor: 'f2f2f2',
                    borderWidth: 0,
                    borderRadius: [10, 0]
                }]
            },
            options: chartConfig
        });
        
        // // Datos para el gráfico de Objetivos (80%)
        // const ctxObjetivos = document.getElementById('chartObjetivos').getContext('2d');
        // new Chart(ctxObjetivos, {
        //     type: 'doughnut',
        //     data: {
        //         datasets: [{
        //             data: [80, 20],
        //             backgroundColor: [
        //                 '#5bbfba',
        //                 '#f2f2f2'
        //             ],
        //             borderColor: 'f2f2f2',
        //             borderWidth: 0,
        //             // Aplicar borderRadius solo al primer segmento (80%)
        //             // borderRadius: 10,
        //             borderRadius: [10, 0]
        //         }]
        //     },
        //     options: chartConfig
        // });

        // Datos para el gráfico de Plan de Desarrollo (80%)
        const ctxDesarrollo = document.getElementById('chartDesarrollo').getContext('2d');
        new Chart(ctxDesarrollo, {
            type: 'doughnut',
            data: {
                datasets: [{
                    data: [0, 100],
                    backgroundColor: [
                        '#5bbfba',
                        '#f2f2f2'
                    ],
                    borderColor: 'f2f2f2',
                    borderWidth: 0,
                    // Aplicar borderRadius solo al primer segmento (80%)
                    // borderRadius: 10, 
                    borderRadius: [10, 0]
                }]
            },
            options: chartConfig
        });
    });
</script>
@stop