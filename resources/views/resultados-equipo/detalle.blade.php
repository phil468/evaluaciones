@extends('adminlte::page')

@section('title', 'Detalle de Resultados')

@section('content_header')
    <h3 class="text-center h3">Resultados de Equipo</h3>
@stop

@section('content')
<div class="container-fluid">
    <!-- Cabecera con información del miembro -->
    <div class="mb-4 card">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h4 class="mb-0">{{ $miembro['nombre'] ?? 'José Aguilar' }}</h4>
                    <p class="mb-0 text-muted">{{ $miembro['cargo'] ?? 'Asistente de Producción' }}</p>
                </div>
                <div class="col-md-6 text-md-right">
                    <a href="{{ route('feedback.crear', ['empleado_id' => $miembro['id'] ?? 1]) }}" class="mr-2 btn rounded-xl btn-vanguard disabled">
                        Dar Feedback
                    </a>
                    <a href="{{ route('informe.descargar', ['empleado_id' => $miembro['id'] ?? 1]) }}" class="btn rounded-xl btn-secondary disabled">
                        Descargar informe
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Evaluación por Competencias -->
    <div class="mb-4 card">
        <div class="card-body">
            <h5 class="h5">Evaluación por Competencias</h5>
            
            <table class="table table-borderless">
                <tbody>
                    <!-- Evaluación 2025 -->
                    <tr>
                        <td style="width: 20%;">Evaluación 2025</td>
                        <td style="width: 50%;">
                            <div class="progress" style="height: 25px;">
                                <div class="progress-bar bg-secondary" role="progressbar" 
                                     style="width: {{ $competencias['2025']['progreso'] ?? 0 }}%"></div>
                            </div>
                        </td>
                        <td style="width: 10%;" class="text-center">
                            <span class="h5">
                                {{ $competencias['2025']['tieneResultados'] ? $competencias['2025']['progreso'].'%' : 'Resultados pendientes' }}
                            </span>
                        </td>
                        <td style="width: 20%;" class="text-right">
                            <button class="btn rounded-xl btn-vanguard btn-sm w-100" 
                                    {{ !isset($competencias['2025']['tieneResultados']) || !$competencias['2025']['tieneResultados'] ? 'disabled' : '' }}
                                    onclick="verDetalle('competencias', 2025)">
                                Ver detalle
                            </button>
                        </td>
                    </tr>
                    
                    <!-- Evaluación 2024 -->
                    <tr>
                        <td>Evaluación 2024</td>
                        <td>
                            <div class="progress" style="height: 25px;">
                                <div class="progress-bar" role="progressbar" 
                                     style="width: {{ $competencias['2024']['progreso'] ?? 67 }}%; background-color: #5bbfba;">
                                </div>
                            </div>
                        </td>
                        <td class="text-center">
                            <span class="h5">
                                {{ $competencias['2024']['progreso'] ?? 67 }}%
                            </span>
                        </td>
                        <td class="text-right">
                            <button class="btn rounded-xl btn-vanguard btn-sm w-100 disabled" 
                                    onclick="verDetalle('competencias', 2024)" disabled>
                                Ver detalle
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Evaluación por Objetivos -->
    <div class="mb-4 card">
        <div class="card-body">
            <h5 class="h5">Evaluación por Objetivos</h5>
            
            <table class="table table-borderless">
                <tbody>
                    <!-- Evaluación 2025 -->
                    <tr>
                        <td style="width: 20%;">Evaluación 2025</td>
                        <td style="width: 50%;">
                            <div class="progress" style="height: 25px;">
                                <div class="progress-bar bg-secondary" role="progressbar" 
                                     style="width: {{ $objetivos['2025']['progreso'] ?? 0 }}%"></div>
                            </div>
                        </td>
                        <td style="width: 10%;" class="text-center">
                            <span class="h5">
                                {{ $objetivos['2025']['tieneResultados'] ? $objetivos['2025']['progreso'].'%' : 'Resultados pendientes' }}
                            </span>
                        </td>
                        <td style="width: 20%;" class="text-right">
                            <button class="btn rounded-xl btn-vanguard btn-sm w-100 disabled" 
                                    {{ !isset($objetivos['2025']['tieneResultados']) || !$objetivos['2025']['tieneResultados'] ? 'disabled' : '' }}
                                    onclick="verDetalle('objetivos', 2025)" disabled>
                                Ver detalle
                            </button>
                        </td>
                    </tr>
                    
                    <!-- Evaluación 2024 -->
                    <tr>
                        <td>Evaluación 2024</td>
                        <td>
                            <div class="progress" style="height: 25px;">
                                <div class="progress-bar" role="progressbar" 
                                     style="width: {{ $objetivos['2024']['progreso'] ?? 75 }}%; background-color: #5bbfba;">
                                </div>
                            </div>
                        </td>
                        <td class="text-center">
                            <span class="h5">
                                {{ $objetivos['2024']['progreso'] ?? 75 }}%
                            </span>
                        </td>
                        <td class="text-right">
                            <button class="btn rounded-xl btn-vanguard btn-sm w-100 disabled" 
                                    onclick="verDetalle('objetivos', 2024)" disabled>
                                Ver detalle
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Plan de Desarrollo Individual -->
    <div class="mb-4 card">
        <div class="card-body">
            <h5 class="h5">Plan de Desarrollo Individual (PDI)</h5>
            
            <table class="table table-borderless">
                <tbody>
                    <!-- PDI 2025 -->
                    <tr>
                        <td style="width: 20%;">PDI 2025</td>
                        <td style="width: 50%;">
                            <div class="progress" style="height: 25px;">
                                <div class="progress-bar bg-secondary" role="progressbar" 
                                     style="width: {{ $pdi['2025']['progreso'] ?? 0 }}%"></div>
                            </div>
                        </td>
                        <td style="width: 10%;" class="text-center">
                            <span class="h5">
                                {{ $pdi['2025']['tieneResultados'] ? $pdi['2025']['progreso'].'%' : 'Resultados pendientes' }}
                            </span>
                        </td>
                        <td style="width: 20%;" class="text-right">
                            <button class="btn rounded-xl btn-vanguard btn-sm w-100 disabled" 
                                    {{ !isset($pdi['2025']['tieneResultados']) || !$pdi['2025']['tieneResultados'] ? 'disabled' : '' }}
                                    onclick="verDetalle('pdi', 2025)" disabled>
                                Ver detalle
                            </button>
                        </td>
                    </tr>
                    
                    <!-- PDI 2024 -->
                    <tr>
                        <td>PDI 2024</td>
                        <td>
                            <div class="progress" style="height: 25px;">
                                <div class="progress-bar" role="progressbar" 
                                     style="width: {{ $pdi['2024']['progreso'] ?? 60 }}%; background-color: #5bbfba;">
                                </div>
                            </div>
                        </td>
                        <td class="text-center">
                            <span class="h5">
                                {{ $pdi['2024']['progreso'] ?? 60 }}%
                            </span>
                        </td>
                        <td class="text-right">
                            <button class="btn rounded-xl btn-vanguard btn-sm w-100 disabled" 
                                    onclick="verDetalle('pdi', 2024)"
                                    disabled
                                    >
                                Ver detalle
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@stop

@section('css')
<style>
    .btn-vanguard {
        background-color: #568ca5;
        border-color: #568ca5;
        color: white;
    }
    
    .btn-vanguard:hover {
        background-color: #457891;
        border-color: #457891;
        color: white;
    }
    
    .card {
        border-radius: 15px;
        box-shadow: 0 0 10px rgba(0,0,0,0.05);
    }
    
    .progress {
        border-radius: 10px;
        background-color: #f2f2f2;
    }
</style>
@stop

@section('js')
<script>
    function verDetalle(tipo, anio) {
        // Función para redirigir a la página de detalle
        const empleadoId = {{ $miembro['id'] ?? 1 }};
        
        // Rutas según el tipo de evaluación
        const rutas = {
            'competencias': `{{ route('evaluacion-competencias.detalle') }}?empleado_id=${empleadoId}&anio=${anio}`,
            'objetivos': `{{ route('evaluacion-objetivos.detalle') }}?empleado_id=${empleadoId}&anio=${anio}`,
            'pdi': `{{ route('pdi.detalle') }}?empleado_id=${empleadoId}&anio=${anio}`,
        };
        
        window.location.href = rutas[tipo];
    }

    document.addEventListener('DOMContentLoaded', function() {
        console.log('Detalle de Resultados de Miembro cargado');
    });
</script>
@stop