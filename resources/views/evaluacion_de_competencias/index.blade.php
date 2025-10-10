@extends('adminlte::page')

@section('title', 'Competencias')

@section('content_header')
    <h3 class="mb-0 h4 font-style-poppins font-weight-bold">
        Evaluación de Desempeño por <span class="text-vanguard" style="color: #568ba5;">Competencias</span>
    </h3>
@stop

@section('content')
<div class="container-fluid">

    <!-- Secciones principales en tarjetas -->
        {{-- Componente de evaluación por competencias --}}
        <div class="card">
            {{-- <div class="card-header">
                
            </div> --}}
            <div class="card-body">
                <h5 class="mb-4 font-weight-bold">Mis resultados</h5>

                <div class="table-responsive">
                    <table class="table table-borderless">
                        <tbody>
                            {{-- {{ dd($evaluaciones) }} --}}
                            @forelse($evaluaciones as $anio => $evaluacion)
                                <tr>                                    
                                    <td style="width: 20%;">Evaluación {{ $anio }}</td>
                                    <td style="width: 50%;">
                                        <div class="progress" style="height: 25px;">
                                            @if($evaluacion['estado'] === 'sin_resultados')
                                                {{-- Sin resultados - barra gris --}}
                                                <div class="progress-bar bg-secondary" 
                                                    role="progressbar" 
                                                    style="width: 0%;">
                                                </div>
                                            @elseif($evaluacion['estado'] === 'resultados_pendientes')
                                                {{-- Tiene resultados pero no puede verlos - barra completa gris --}}
                                                <div class="progress-bar bg-secondary" 
                                                    role="progressbar" 
                                                    style="width: 100%;">
                                                </div>
                                            @else
                                                {{-- Puede ver resultados - barra amarilla con progreso real --}}
                                                <div class="progress-bar" 
                                                    role="progressbar" 
                                                    style="width: {{ $evaluacion['progreso'] }}%; background-color: #5bbfba;">
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    {{-- <td style="width: 50%;">
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar {{ $evaluacion['tieneResultados'] ? '' : 'bg-secondary' }}" 
                                                role="progressbar" 
                                                style="width: {{ $evaluacion['progreso'] }}%; {{ $evaluacion['tieneResultados'] ? 'background-color: #FFD966;' : '' }}">
                                            </div>
                                        </div>
                                    </td> --}}
                                    <td style="width: 10%;" class="text-center">
                                        @if($evaluacion['estado'] === 'sin_resultados')
                                            <span class="h5 text-muted">Sin evaluar</span>
                                        @elseif($evaluacion['estado'] === 'resultados_pendientes')
                                            <span class="h5 text-muted">Resultados pendientes</span>
                                        @else
                                            <span class="h5">{{ $evaluacion['porcentaje'] }}%</span>
                                            <small class="d-block text-muted">{{ $evaluacion['puntajeObtenido'] }}/{{ $evaluacion['puntajeEsperado'] }}</small>
                                        @endif
                                    </td>
                                    <td style="width: 20%;" class="text-right">
                                        @if($evaluacion['estado'] === 'disponible')
                                            <button class="btn btn-vanguard rounded-xl btn-sm w-100" 
                                                    onclick="submitForm({{ $evaluacion['campania_id'] }})">
                                                Ver detalle
                                            </button>
                                        @else
                                            <button class="btn btn-vanguard rounded-xl btn-sm w-100" disabled>
                                                @if($evaluacion['estado'] === 'sin_resultados')
                                                    Sin evaluar
                                                @else
                                                    Ver detalle
                                                @endif
                                            </button>
                                        @endif
                                    </td>
                                    {{-- <td class="text-center">
                                        @if($evaluacion['tieneResultados'])
                                            <span class="h5">{{ number_format($evaluacion['puntaje'], 2) }}</span>
                                        @else
                                            <span class="h5">No registrados</span>
                                        @endif
                                    </td>
                                    <td class="text-right">
                                        @if (!$evaluacion['tieneResultados'])
                                            <button class="btn btn-vanguard rounded-xl btn-sm w-100" disabled>
                                                Ver detalle
                                            </button>
                                        @else
                                            <button class="btn btn-vanguard rounded-xl btn-sm w-100" 
                                                    onclick="submitForm({{ $evaluacion['campania_id'] }})">
                                                Ver detalle
                                            </button>                                          
                                        @endif
                                    </td> --}}
                                    {{-- <td>Evaluación {{ $anio }}</td>
                                    <td style="width: 50%;">
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar {{ $anio == '2024' ? '' : 'bg-secondary' }}" 
                                                role="progressbar" 
                                                style="width: {{ $evaluacion['progreso'] }}%; {{ $anio == '2024' ? 'background-color: #FFD966;' : '' }}">
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        @if($evaluacion['tieneResultados'])
                                            <span class="h5">{{ $evaluacion['puntaje'] }}</span>
                                        @else
                                            <span class="h5">Resultados pendientes</span>
                                        @endif
                                    </td>
                                    <td class="text-right">
                                        @if (!$evaluacion['tieneResultados'])
                                            <button>
                                                <button class="btn btn-vanguard rounded-xl btn-sm w-100" disabled>
                                                    Ver detalle
                                                </button>
                                            </button>
                                        
                                        @else
                                            <button class="btn btn-vanguard rounded-xl btn-sm w-100" 
                                            onclick="submitForm(1)">

                                                Ver detalle
                                            </button>                                          
                                        @endif
                                    </td> --}}
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-muted">
                                        No tienes evaluaciones de competencias registradas.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Botón de histórico, solo visible si hay datos de 2025 --}}
                {{-- @if(isset($evaluaciones['2025']) && $evaluaciones['2025']['tieneResultados'])
                    <div class="mt-4 text-center">
                        <button class="px-4 btn btn-vanguard rounded-xl btn-sm">Ver histórico</button>
                    </div>
                @else
                    <div class="mt-4 text-center">
                        <button class="px-4 btn btn-vanguard rounded-xl btn-sm" disabled
                        style="display: none;"
                        >Ver histórico</button>
                    </div>
                @endif --}}

                

            {{-- Botón de histórico - solo visible si hay al menos una evaluación disponible --}}
            @php
                $tieneEvaluacionDisponible = collect($evaluaciones)->contains('estado', 'disponible');
            @endphp
            
            {{-- @if($tieneEvaluacionDisponible)
                <div class="mt-4 text-center">
                    <button class="px-4 btn btn-vanguard rounded-xl btn-sm" onclick="verHistorico()">
                        Ver histórico
                    </button>
                </div>
            @else
                <div class="mt-4 text-center">
                    <button class="px-4 btn btn-vanguard rounded-xl btn-sm" disabled style="display: none;">
                        Ver histórico
                    </button>
                </div>
            @endif --}}

            {{-- Información adicional sobre fechas de resultados pendientes --}}
            @php
                $evaluacionesPendientes = collect($evaluaciones)->filter(fn($e) => $e['estado'] === 'resultados_pendientes');
            @endphp
            
            @if($evaluacionesPendientes->isNotEmpty())
                <div class="mt-3">
                    <small class="text-muted">
                        <i class="fas fa-info-circle"></i>
                        Los resultados se mostrarán según las fechas establecidas por la organización.
                    </small>
                </div>
            @endif

            </div>
        </div>

</div>
@stop

@section('css')
    <style>
        .card {
            border-radius: 15px;
            box-shadow: 0 0 10px rgba(0,0,0,0.05);
        }
        
        .progress {
            border-radius: 10px;
            background-color: #f2f2f2;
        }
        
        .btn-info {
            background-color: #5b9bd5;
            border-color: #5b9bd5;
        }
        
        .text-info {
            color: #5b9bd5 !important;
        }
    </style>
@stop

@section('js')
<script>
    function submitForm(campaniaId) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("evaluacion_de_competencias.resultados") }}';
        
        const csrf = document.createElement('input');
        csrf.type = 'hidden';
        csrf.name = '_token';
        csrf.value = '{{ csrf_token() }}';

        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'campania_id';
        input.value = campaniaId;
        
        form.appendChild(csrf);
        form.appendChild(input);
        document.body.appendChild(form);
        form.submit();
    }

    function verHistorico() {
        // Implementar la lógica para ver histórico
        alert('Funcionalidad de histórico por implementar');
    }
    // document.addEventListener('DOMContentLoaded', function() {
        // function submitForm(campaniaId) {
        //     const form = document.createElement('form');
        //     form.method = 'POST';
        //     form.action = '{{ route("evaluacion_de_competencias.resultados") }}';
            
        //     const csrf = document.createElement('input');
        //     csrf.type = 'hidden';
        //     csrf.name = '_token';
        //     csrf.value = '{{ csrf_token() }}';

        //     const input = document.createElement('input');
        //     input.type = 'hidden';
        //     input.name = 'campania_id';
        //     input.value = campaniaId;
            
        //     form.appendChild(csrf);
        //     form.appendChild(input);
        //     document.body.appendChild(form);
        //     form.submit();
        // }
    // });
</script>
@stop