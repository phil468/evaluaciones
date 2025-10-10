
{{-- filepath: c:\laragon\www\evaluaciones\resources\views\evaluacion_de_competencias\resultados.blade.php --}}
@php
    // Valores por defecto para evitar errores
    $esVistaEquipo = $esVistaEquipo ?? false;
    $datosPersonal = $datosPersonal ?? ['nombre' => 'Usuario', 'cargo' => ''];
    $competencias = $competencias ?? [];
    $puntajes = $puntajes ?? [];
    $promedioGeneral = $promedioGeneral ?? null;
    $puntajeEsperado = $puntajeEsperado ?? null;
    $campania = $campania ?? null;
@endphp

@extends('adminlte::page')

@section('title', 'Resultados por Competencias')

@section('content_header')

    <h1 class="m-0 text-center text-dark font-weight-bold ">
        {{-- botón de atrás alineado a la izquierda --}}
        <div class="font-style-poppins font-weight-bold">
                    <a href="{{ url()->previous() }}" class="mr-2 btn-link btn-light btn-sm">
            <i class="fas fa-arrow-left"></i>
            Atrás
        </a>
            
            @if($esVistaEquipo ?? false)
                Resultados de <span style="color: #568ba5;">{{ $datosPersonal['nombre'] }}</span>
            @else
                Evaluación de Desempeño por <span style="color: #568ba5;">Competencias</span>
            @endif          
        </div>
    </h1>

@stop

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <h4 class="mb-4 font-weight-bold">
            <div class="mb-4 d-flex justify-content-between align-items-center">
                <h4 class="mb-0 font-weight-bold">
                    Resultados Evaluación {{ $campania->anio_mostrar ?? ($campania->name ?? '') }}
                </h4>
                
                @if($esVistaEquipo ?? false)
                    <div class="text-right">
                        <p class="mb-0 font-weight-bold">{{ $datosPersonal['nombre'] }}</p>
                        <small class="text-muted">{{ $datosPersonal['cargo'] }}</small>
                    </div>
                @endif
            </div>
            
            <div class="row">
                {{-- Sección de promedio general --}}
                <div class="col-md-4">
                    <div class="mb-4 text-center">
                        <h5 class="font-weight-bold">Promedio general</h5>
                        <div class="h1 font-weight-bold">
                            {{ $promedioGeneral !== null ? ($promedioGeneral) : '—' }}
                        </div>
                        <div class="mt-2" style="font-weight: bold; color:#FFD966;">
                            @if($puntajeEsperado !== null)
                                Valor esperado: {{ ($puntajeEsperado) }}
                            @else
                                <span class="text-muted">No se cuenta con “valor esperado” para esta campaña.</span>
                            @endif
                        </div>

                        @if(!($esVistaEquipo ?? false))
                            {{-- Solo mostrar el botón en vista personal --}}
                            <div class="mt-4 text-center">
                                <a href="{{ route('plan.mejora') ?? '#' }}"
                                   class="btn btn-vanguard disabled" aria-disabled="true">
                                    Ir a Plan de Mejora
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
                
                {{-- Sección de promedio por competencia --}}
                <div class="col-md-8">
                    <h5 class="mb-3 font-weight-bold">Promedio por competencia</h5>
                    
                    @if(count($competencias) > 0)
                        {{-- Contenedor para el gráfico de barras horizontales --}}
                        <div style="height:840px;">
                            <canvas id="chartCompetencias"></canvas>
                        </div>
                    @else
                        <div class="text-center alert alert-info">
                            <i class="mb-3 fas fa-info-circle fa-2x"></i>
                            <h5>Sin datos de competencias</h5>
                            <p>{{ $esVistaEquipo ? 'Este empleado no tiene' : 'No tienes' }} evaluaciones de competencias registradas para esta campaña.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
<style>
    /* Estilos personalizados */
    .card {
        border-radius: 15px;
        box-shadow: 0 0 10px rgba(0,0,0,0.05);
    }
    
    .btn-vanguard {
        background-color: #568ca5;
        border-color: #568ca5;
        color: white;
        border-radius: 20px;
        padding: 8px 20px;
    }
    
    .btn-vanguard:hover {
        background-color: #457891;
        border-color: #457891;
        color: white;
    }
</style>
@stop

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const competencias = @json($competencias ?? []);
            const puntajes = @json($puntajes ?? []);
            const promedioGeneral = @json($promedioGeneral);
            const valorEsperado = @json($puntajeEsperado);

            if (competencias.length === 0) {
                return; // No crear gráfico si no hay datos
            }

            // Dataset secundario: barra fina del promedio general por cada etiqueta
            const barrasPromedio = competencias.map(() => promedioGeneral ?? 0);

            // Plugin simple para dibujar la línea vertical del valor esperado
            const lineaEsperado = {
                id: 'lineaEsperado',
                afterDatasetsDraw(chart, args, pluginOptions) {
                    if (valorEsperado == null) return;
                    const {ctx, chartArea: {top, bottom}, scales: {x}} = chart;
                    const xPos = x.getPixelForValue(valorEsperado);
                    ctx.save();
                    ctx.beginPath();
                    ctx.moveTo(xPos, top);
                    ctx.lineTo(xPos, bottom);
                    ctx.lineWidth = 2;
                    ctx.strokeStyle = '#ff6b6b';
                    ctx.setLineDash([6,4]);
                    ctx.stroke();
                    ctx.restore();

                    // Etiqueta
                    ctx.fillStyle = '#ff6b6b';
                    ctx.font = '12px sans-serif';
                    ctx.fillText(`Esperado: ${valorEsperado.toFixed(2)}`, xPos + 6, top + 14);
                }
            };

            const ctx = document.getElementById('chartCompetencias').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: competencias,
                    datasets: [
                        {
                            label: 'Promedio por competencia',
                            data: puntajes,
                            backgroundColor: '#5bbfba',
                            borderRadius: 5,
                            barPercentage: 0.7,
                            maxBarThickness: 22
                        },
                        {
                            label: 'Promedio general',
                            data: barrasPromedio,
                            backgroundColor: '#FFD966',
                            borderRadius: 5,
                            barPercentage: 0.2,
                            maxBarThickness: 6
                        }
                    ]
                },
                options: {
                    indexAxis: 'y',
                    plugins: {
                        legend: { display: true },
                        tooltip: {
                            callbacks: {
                                label: (ctx) => `${ctx.dataset.label}: ${Number(ctx.parsed.x).toFixed(2)}`
                            }
                        }
                    },
                    scales: {
                        x: {
                            beginAtZero: true,
                            max: 10,
                            ticks: {
                                callback: (v) => Number(v).toFixed(1)
                            }
                        },
                        y: { grid: { display: false } }
                    },
                    maintainAspectRatio: false,
                    responsive: true
                },
                plugins: [lineaEsperado]
            });
        });
    </script>
@stop