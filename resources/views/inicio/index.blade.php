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
            <div class="card rounded-xl">
                <div class="card-body justify-content-between align-items-center">
                    <div class=" d-flex">
                        <h5 class="m-0 h5">¡Tienes evaluaciones pendientes por realizar!</h5>
                        <div class="d-flex align-items-center">
                            <div class="ml-3 notification-bell">
                                <i class="fas fa-bell fa-lg text-muted"></i>
                            </div>
                        </div>
                    </div>
                        <a href="{{ route('pendientes') }}"
                        {{-- "#" --}}                         
                        class="mt-4 rounded-xl btn btn-vanguard">
                            Ingresar a Pendientes
                        </a>
                </div>
                <div class="bg-white card-footer text-muted rounded-b-xl">
                    <small>*Sección solo aparece cuando se tengan pendientes</small>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Secciones principales en tarjetas -->
    <div class="row">
        <!-- Evaluación por Competencias -->
        <div class="mt-3 col-md-6">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="h5">Evaluación por Competencias</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-8">
                            <p>Evalúa el desarrollo de habilidades blandas, alineadas a nuestros valores y cultura.</p>
                        </div>
                        <div class="text-center col-4">
                            <div class="puntaje-medio">
                                <h2 class="font-weight-bold">7.34</h2>
                                <span class="text-warning">Puntaje medio</span>
                                <p class="text-sm">Resultados de última evaluación</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer rounded-b-xl">
                    <a href="#"
                    {{-- "{{ route('evaluaciones.competencias') }}"  --}}
                    class="rounded-xl btn btn-vanguard btn-block">
                        Ingresar a Competencias
                    </a>
                </div>
            </div>
        </div>

        <!-- Evaluación por Objetivos -->
        <div class="mt-3 col-md-6">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="h5">Evaluación por Objetivos</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-8">
                            <p>Evalúa el cumplimiento de metas claras y medibles, establecidas previamente.</p>
                        </div>
                        <div class="text-center col-4">
                            <div class="progress-chart">
                                <canvas id="chartObjetivos"></canvas>
                                <div class="progress-percent">
                                    <span>80%</span>
                                </div>
                            </div>
                            <p class="text-sm">Resultados de última evaluación</p>
                        </div>
                    </div>
                </div>
                <div class="card-footer rounded-b-xl">
                    <a href="#"
                    {{-- "{{ route('evaluaciones.objetivos') }}"  --}}
                    class="rounded-xl btn btn-vanguard btn-block">
                        Ingresar a Objetivos
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Feedback y Retroalimentación -->
        <div class="mt-3 col-md-6">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="h5">Feedback y Retroalimentación</h5>
                </div>
                <div class="card-body">
                    <p>Consulta y gestiona tus evaluaciones recibidas</p>
                </div>
                <div class="card-footer rounded-b-xl">
                    <a href="#"
                    {{-- "{{ route('evaluaciones.feedback') }}"  --}}
                    class="rounded-xl btn btn-vanguard btn-block">
                        Ingresar a Feedback
                    </a>
                </div>
            </div>
        </div>

        <!-- Plan de Desarrollo Individual -->
        <div class="mt-3 col-md-6">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="h5">Plan de Desarrollo Individual</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-8">
                            <p>Crea y sigue metas para tu crecimiento profesional</p>
                        </div>
                        <div class="text-center col-4">
                            <div class="progress-chart">
                                <canvas id="chartDesarrollo"></canvas>
                                <div class="progress-percent">
                                    <span>80%</span>
                                </div>
                            </div>
                            <p class="text-sm">Resultados de última evaluación</p>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <a href=
                    "#"
                    {{-- "{{ route('evaluaciones.desarrollo') }}"  --}}
                    class="rounded-xl btn btn-vanguard btn-block">
                        Ingresar a Plan de Desarrollo
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Botón para descargar informe -->
    <div class="mt-3 row justify-content-center">
        <div class="col-md-4">
            <a href=
            "#"
            {{-- "{{ route('evaluaciones.descargar-informe') }}"  --}}
            class="rounded-xl btn btn-vanguard btn-block">
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

        // Datos para el gráfico de Objetivos (80%)
        const ctxObjetivos = document.getElementById('chartObjetivos').getContext('2d');
        new Chart(ctxObjetivos, {
            type: 'doughnut',
            data: {
                datasets: [{
                    data: [80, 20],
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

        // Datos para el gráfico de Plan de Desarrollo (80%)
        const ctxDesarrollo = document.getElementById('chartDesarrollo').getContext('2d');
        new Chart(ctxDesarrollo, {
            type: 'doughnut',
            data: {
                datasets: [{
                    data: [80, 20],
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