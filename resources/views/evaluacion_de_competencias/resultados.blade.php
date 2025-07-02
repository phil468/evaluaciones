@extends('adminlte::page')

@section('title', 'Resultados por Competencias')

@section('content_header')
    <h1 class="m-0 text-center text-dark font-weight-bold">
        {{-- botón de atrás alineado a la izquierda --}}
        
        <a href="{{ url()->previous() }}" class="mr-2 btn-link btn-light btn-sm">
            <i class="fas fa-arrow-left"></i>
            Atrás
        </a>

        Evaluación de Desempeño por <span style="color: #568ba5;">Competencias</span>
    </h1>
    {{-- <h3 class="h3">
    </h3> --}}
@stop

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <h4 class="mb-4 font-weight-bold">Resultados Evaluación 2024</h4>
            
            <div class="row">
                {{-- Sección de promedio general --}}
                <div class="col-md-4">
                    <div class="mb-4 text-center">
                        <h5 class="font-weight-bold">Promedio general</h5>
                        
                        {{-- Valor del promedio general (dinámico) --}}
                        <div class="h1 font-weight-bold">
                            {{ $promedioGeneral ?? '7.36' }} {{-- Valor dinámico --}}
                        </div>
                        
                        {{-- Etiqueta de puntaje medio --}}
                        <div style="color: #FFD966; font-weight: bold;">
                            Puntaje medio
                        </div>
                        
                        <div class="mt-4 text-center">
                            <p>
                                Muestra los comportamientos esperados
                                en situaciones simples, con
                                oportunidades de mejora.
                            </p>
                            
                            {{-- Botón para ir al plan de mejora --}}
                            <a href="{{ route('plan.mejora') ?? '#' }}" class="btn btn-vanguard">
                                Ir a Plan de Mejora
                            </a>
                        </div>
                    </div>
                </div>
                
                {{-- Sección de promedio por competencia --}}
                <div class="col-md-8">
                    <h5 class="mb-3 font-weight-bold">Promedio por competencia</h5>
                    
                    {{-- Contenedor para el gráfico de barras horizontales --}}
                    <div style="height: 400px;">
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
    // Datos para el gráfico - Estos deberían venir del backend
    // Reemplaza estos datos con los valores reales de tu controlador
    const competencias = 
    [
        'Liderazgo',
        'Comunicación asertiva',
        'Gestión y Organización',
        'Trabajo en equipo',
        'Toma de decisiones considerando impactos',
        'Planificación Efectiva',
        'Análisis Estratégico',
        'Aprendizaje Continuo',
        'Gestión de recursos',
        'Compromiso',
        'Innovación'
    ];
    
    const puntajes = 
    [
        7.8, 7.6, 7.4, 7.9, 7.5, 8.0, 7.7, 7.5, 7.3, 7.6, 7.2
    ];
    
    // Crear el gráfico de barras horizontales
    const ctx = document.getElementById('chartCompetencias').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: competencias,
            datasets: [{
                data: puntajes,
                backgroundColor: '#5bbfba', // Color verde similar al de la imagen
                borderRadius: 5,
                barPercentage: 0.7,
                maxBarThickness: 20
            }]
        },
        options: {
            indexAxis: 'y', // Barras horizontales
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.parsed.x.toFixed(2);
                        }
                    }
                },
                datalabels: {
                    anchor: 'end',
                    align: 'end',
                    formatter: function(value) {
                        return value.toFixed(1);
                    },
                    color: '#333',
                    font: {
                        weight: 'bold'
                    }
                }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    max: 10, // Escala máxima
                    ticks: {
                        callback: function(value) {
                            return value.toFixed(1);
                        }
                    }
                },
                y: {
                    grid: {
                        display: false
                    }
                }
            },
            maintainAspectRatio: false,
            responsive: true
        }
    });
});
</script>
@stop