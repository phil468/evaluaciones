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

                @if (count($planes) === 0)
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        No tienes planes de mejora validados disponibles para visualizar.
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-borderless">
                            <tbody>
                                @foreach ($planes as $campaniaId => $plan)
                                    <tr>
                                        <td style="width: 25%">
                                            <strong>Plan de Mejora Individual {{ $plan['nombre'] }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $plan['total_planes'] }} plan(es)
                                                validado(s)</small>
                                        </td>
                                        <td style="width: 45%">
                                            {{-- Barra de progreso - Datos dinámicos --}}
                                            <div class="progress" style="height: 25px;">
                                                <div class="progress-bar" role="progressbar"
                                                    style="width: {{ $plan['progreso'] }}%; background-color: #5bbfba;">
                                                    <span class="font-weight-bold">{{ $plan['progreso'] }}%</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td style="width: 15%" class="text-center">
                                            {{-- Porcentaje de progreso - Datos dinámicos --}}
                                            <span class="h5">
                                                {{ $plan['progreso'] }}%
                                            </span>
                                        </td>
                                        <td style="width: 15%" class="text-right">
                                            {{-- Botón de ver detalle --}}
                                            <a href="{{ route('plan.mejora.detalle', ['empleado_id' => $plan['empleado_id']]) }}?campania_id={{ $campaniaId }}"
                                                class="btn btn-vanguard rounded-xl btn-sm w-100">
                                                Ver detalle
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        /* Estilos personalizados */
        .card {
            border-radius: 15px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
        }

        .progress {
            border-radius: 10px;
            background-color: #f2f2f2;
        }

        .progress-bar {
            transition: width 0.6s ease;
        }

        .table tbody tr:hover {
            background-color: #f8f9fa;
        }
    </style>
@stop
