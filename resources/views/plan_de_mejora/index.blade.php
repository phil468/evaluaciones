@extends('adminlte::page')

@section('title', 'Plan de Mejora')

@section('content_header')
    <h1 class="text-center font-weight-bold">
        Plan de Mejora Individual
    </h1>
@stop

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <h5 class="mb-4 font-weight-bold">Mi Plan de Mejora Individual</h5>

            <div class="table-responsive">
                <table class="table table-borderless">
                    <tbody>
                        {{-- Fila para Plan de Mejora 2025 --}}
                        <tr>
                            <td>Plan de Mejora Individual 2025</td>
                            <td style="width: 50%">
                                {{-- Barra de progreso - Datos dinámicos --}}
                                <div class="progress" style="height: 25px;">
                                    <div class="progress-bar bg-secondary" role="progressbar" 
                                         style="width: {{ $planes['2025']['progreso'] ?? 0 }}%">
                                    </div>
                                </div>
                            </td>
                            <td style="width: 10%" class="text-center">
                                {{-- Porcentaje de progreso - Datos dinámicos --}}
                                <span class="h5">
                                    {{ $planes['2025']['tieneResultados'] ? $planes['2025']['progreso'].'%' : 'Resultados pendientes' }}
                                </span>
                            </td>
                            <td style="width: 15%" class="text-right">
                                {{-- Botón de ver detalle - Deshabilitar si no tiene resultados --}}
                                <button class="btn btn-vanguard rounded-xl btn-sm w-100" 
                                        {{ !($planes['2025']['tieneResultados'] ?? false) ? 'disabled' : '' }}
                                        onclick="verDetallePlan(2025)">
                                    Ver detalle
                                </button>
                            </td>
                        </tr>
                        
                        {{-- Fila para Plan de Mejora 2024 --}}
                        <tr>
                            <td>Plan de Mejora Individual 2024</td>
                            <td>
                                {{-- Barra de progreso con color personalizado - Datos dinámicos --}}
                                <div class="progress" style="height: 25px;">
                                    <div class="progress-bar" role="progressbar" 
                                         style="width: {{ $planes['2024']['progreso'] ?? 70 }}%; background-color: #5bbfba;">
                                    </div>
                                </div>
                            </td>
                            <td class="text-center">
                                {{-- Porcentaje de progreso - Datos dinámicos --}}
                                <span class="h5">
                                    {{ $planes['2024']['tieneResultados'] ? $planes['2024']['progreso'].'%' : 'Resultados pendientes' }}
                                </span>
                            </td>
                            <td class="text-right">
                                {{-- Botón de ver detalle --}}
                                <button class="btn btn-vanguard rounded-xl btn-sm w-100" onclick="verDetallePlan(2024)">
                                    Ver detalle
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
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
    
    .progress {
        border-radius: 10px;
        background-color: #f2f2f2;
    }
    
    .btn-info {
        background-color: #568ca5;
        border-color: #568ca5;
        color: white;
    }
    
    .btn-info:hover {
        background-color: #457891;
        border-color: #457891;
        color: white;
    }
</style>
@stop

@section('js')
<script>
    function verDetallePlan(anio) {
        // Redirigir a la página de detalles del plan
        window.location.href = "{{ route('plan.mejora.detalle') }}?anio=" + anio;
        
        // Alternativa mediante POST (similar a tu implementación actual)
        /*
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("plan.mejora.detalle") }}';
        
        const csrf = document.createElement('input');
        csrf.type = 'hidden';
        csrf.name = '_token';
        csrf.value = '{{ csrf_token() }}';
        
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'anio';
        input.value = anio;
        
        form.appendChild(csrf);
        form.appendChild(input);
        document.body.appendChild(form);
        form.submit();
        */
    }
</script>
@stop