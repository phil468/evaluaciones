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
            Evaluación de Desempeño por <span style="color: #568ba5;">Competencias</span>            
        </div>
    </h1>
    {{-- <h3 class="h3">
    </h3> --}}
@stop

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <h4 class="mb-4 font-weight-bold">
                Resultados Evaluación {{ $campania->anio_mostrar ?? ($campania->name ?? '') }}
            </h4>
            {{-- <h4 class="mb-4 font-weight-bold">Resultados Evaluación 2024</h4> --}}
            
            <div class="row">
                {{-- Sección de promedio general --}}
                <div class="col-md-4">
                    <div class="mb-4 text-center">
                        <h5 class="font-weight-bold">Promedio general</h5>
                        <div class="h1 font-weight-bold">
                            {{ $promedioGeneral !== null ? number_format($promedioGeneral, 2) : '—' }}
                        </div>
                        <div class="mt-2" style="font-weight: bold; color:#FFD966;">
                            @if($puntajeEsperado !== null)
                                Valor esperado: {{ number_format($puntajeEsperado, 2) }}
                            @else
                                <span class="text-muted">No se cuenta con “valor esperado” para esta campaña.</span>
                            @endif
                        </div>           
                        <div class="mt-4 text-center">
                            <a href="{{ route('plan.mejora') ?? '#' }}"
                               class="btn btn-vanguard disabled" aria-disabled="true">Ir a Plan de Mejora</a>
                        </div>
                        
{{--              
                        <h5 class="font-weight-bold">Promedio general</h5>
                        
                        <div class="h1 font-weight-bold">
                            {{ $promedioGeneral ?? '7.36' }} 
                        </div>
                        
                        <div style="color: #FFD966; font-weight: bold;">
                        </div>
                        
                        <div class="mt-4 text-center">
                            <p
                            style="display:none;"
                            >
                                Muestra los comportamientos esperados
                                en situaciones simples, con
                                oportunidades de mejora.
                            </p>
                            
                            <a href="{{ route('plan.mejora') ?? '#' }}" class="btn btn-vanguard">
                                Ir a Plan de Mejora
                            </a>
                        </div> --}}
                    </div>
                </div>
                
                {{-- Sección de promedio por competencia --}}
                <div class="col-md-8">
                    <h5 class="mb-3 font-weight-bold">Promedio por competencia</h5>
                    
                    {{-- Contenedor para el gráfico de barras horizontales --}}
                    <div style="height:840px;">
                        <canvas id="chartCompetencias"></canvas>
                    </div>
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
{{-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const competencias = @json($competencias ?? []);
    const puntajes = @json($puntajes ?? []);
    const promedioGeneral = @json($promedioGeneral);
    const valorEsperado = @json($puntajeEsperado);

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
// document.addEventListener('DOMContentLoaded', function() {
//     // Datos para el gráfico - Estos deberían venir del backend
//     // Reemplaza estos datos con los valores reales de tu controlador
//     const competencias = 
//     [
//         'Liderazgo',
//         'Comunicación asertiva',
//         'Gestión y Organización',
//         'Trabajo en equipo',
//         'Toma de decisiones considerando impactos',
//         'Planificación Efectiva',
//         'Análisis Estratégico',
//         'Aprendizaje Continuo',
//         'Gestión de recursos',
//         'Compromiso',
//         'Innovación'
//     ];
    
//     const puntajes = 
//     [
//         7.8, 7.6, 7.4, 7.9, 7.5, 8.0, 7.7, 7.5, 7.3, 7.6, 7.2
//     ];
    
//     // Crear el gráfico de barras horizontales
//     const ctx = document.getElementById('chartCompetencias').getContext('2d');
//     new Chart(ctx, {
//         type: 'bar',
//         data: {
//             labels: competencias,
//             datasets: [{
//                 data: puntajes,
//                 backgroundColor: '#5bbfba', // Color verde similar al de la imagen
//                 borderRadius: 5,
//                 barPercentage: 0.7,
//                 maxBarThickness: 20
//             }]
//         },
//         options: {
//             indexAxis: 'y', // Barras horizontales
//             plugins: {
//                 legend: {
//                     display: false
//                 },
//                 tooltip: {
//                     callbacks: {
//                         label: function(context) {
//                             return context.parsed.x.toFixed(2);
//                         }
//                     }
//                 },
//                 datalabels: {
//                     anchor: 'end',
//                     align: 'end',
//                     formatter: function(value) {
//                         return value.toFixed(1);
//                     },
//                     color: '#333',
//                     font: {
//                         weight: 'bold'
//                     }
//                 }
//             },
//             scales: {
//                 x: {
//                     beginAtZero: true,
//                     max: 10, // Escala máxima
//                     ticks: {
//                         callback: function(value) {
//                             return value.toFixed(1);
//                         }
//                     }
//                 },
//                 y: {
//                     grid: {
//                         display: false
//                     }
//                 }
//             },
//             maintainAspectRatio: false,
//             responsive: true
//         }
//     });
// });
</script>
@stop