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
                            @forelse($evaluaciones as $anio => $evaluacion)
                                <tr>                                    
                                    <td>Evaluación {{ $anio }}</td>
                                    <td style="width: 50%;">
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar {{ $evaluacion['tieneResultados'] ? '' : 'bg-secondary' }}" 
                                                role="progressbar" 
                                                style="width: {{ $evaluacion['progreso'] }}%; {{ $evaluacion['tieneResultados'] ? 'background-color: #FFD966;' : '' }}">
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
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
                                    </td>
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
                                    <td colspan="4" class="text-muted">No existe campaña anterior.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Botón de histórico, solo visible si hay datos de 2025 --}}
                @if(isset($evaluaciones['2025']) && $evaluaciones['2025']['tieneResultados'])
                    <div class="mt-4 text-center">
                        <button class="px-4 btn btn-vanguard rounded-xl btn-sm">Ver histórico</button>
                    </div>
                @else
                    <div class="mt-4 text-center">
                        <button class="px-4 btn btn-vanguard rounded-xl btn-sm" disabled
                        style="display: none;"
                        >Ver histórico</button>
                        {{-- <div class="mt-2 text-muted small">*Botón habilitado cuando se tengan resultados 2025</div> --}}
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
    // document.addEventListener('DOMContentLoaded', function() {
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
    // });
</script>
@stop