@extends('adminlte::page')

@section('title', 'Detalle de Plan de Mejora')

@section('content_header')
    <h1 class="text-center font-weight-bold">
        <a 
        href="{{ $esPropio ? route('plan.mejora.index') : route('resultados-de-equipo.detalle', ['id' => $empleado_id ?? 1]) }}" 
        class="mr-2 btn-link btn-light btn-sm">
            <i class="fas fa-arrow-left"></i>
            Volver
        </a>
        Plan de Mejora Individual - {{ $encargadoPlan->plan_de_mejora->campania->name ?? 'Detalle' }}
    </h1>
@stop

@section('content')
    <div class="container-fluid">
        <div class="card rounded-xl">
            <div class="text-white card-header bg-vanguard rounded-t-xl">
                <h4 class="mb-0 h5">Mis Planes de Mejora Individual</h4>
            </div>

            <div class="card-body">
                {{-- Información del Empleado y Encargado --}}
                <div class="mb-4 row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h6 class="font-weight-bold text-muted">EVALUADO</h6>
                                <p class="mb-0 h5">{{ $encargadoPlan->empleado->name ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h6 class="font-weight-bold text-muted">LÍDER/ENCARGADO</h6>
                                <p class="mb-0 h5">{{ $encargadoPlan->encargado->name ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Tabla de Planes --}}
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="text-white bg-vanguard">
                            <tr>
                                <th style="width: 5%">#</th>
                                <th style="width: 25%">Competencia</th>
                                <th style="width: 35%">Compromiso / Acción de Mejora</th>
                                <th style="width: 15%">Fecha de Revisión</th>
                                <th style="width: 10%" class="text-center">Avance</th>
                                <th style="width: 10%" class="text-center">Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($encargadoPlan->planesDeMejora->where('estado_aprobacion', 'validado') as $index => $plan)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>
                                        <strong>{{ $plan->competencia->competencia->name ?? 'N/A' }}</strong>
                                    </td>
                                    <td>
                                        {{ $plan->name }}

                                        {{-- Mostrar feedback si existe --}}
                                        @if ($plan->feedbacks->count() > 0)
                                            <div class="p-2 mt-2 bg-light border-left border-info">
                                                <small class="text-muted">
                                                    <i class="fas fa-comment-alt text-info"></i>
                                                    <strong>Feedback:</strong>
                                                </small>
                                                <p class="mb-0 small">{{ $plan->feedbacks->first()->feedback }}</p>
                                                <small class="text-muted">
                                                    {{ \Carbon\Carbon::parse($plan->feedbacks->first()->fecha_feedback)->format('d/m/Y') }}
                                                </small>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        {{ $plan->fecha_de_revision ? \Carbon\Carbon::parse($plan->fecha_de_revision)->format('d/m/Y') : 'N/A' }}
                                    </td>
                                    <td class="text-center">
                                        <div class="progress" style="height: 25px;">
                                            <div class="progress-bar" role="progressbar"
                                                style="width: {{ $plan->avance }}%; background-color: #5bbfba;">
                                                <span class="font-weight-bold">{{ $plan->avance }}%</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge badge-success">
                                            <i class="fas fa-check-circle"></i> Validado
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">
                                        No hay planes validados disponibles.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Promedio de Avance --}}
                <div class="mt-4 row">
                    <div class="col-md-12">
                        <div class="card bg-light">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-md-6">
                                        <h6 class="mb-0 font-weight-bold">PROMEDIO DE AVANCE</h6>
                                    </div>
                                    <div class="col-md-6">
                                        @php
                                            $promedioAvance = round(
                                                $encargadoPlan->planesDeMejora
                                                    ->where('estado_aprobacion', 'validado')
                                                    ->avg('avance'),
                                                2,
                                            );
                                        @endphp
                                        <div class="progress" style="height: 30px;">
                                            <div class="progress-bar" role="progressbar"
                                                style="width: {{ $promedioAvance }}%; background-color: #5bbfba;">
                                                <span class="mb-0 font-weight-bold h6">{{ $promedioAvance }}%</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Evidencias (si las hay) --}}
                @if ($encargadoPlan->planesDeMejora->flatMap->evidencias->count() > 0)
                    <div class="mt-4 row">
                        <div class="col-md-12">
                            <h5 class="mb-3 font-weight-bold">Evidencias Adjuntas</h5>
                            <div class="list-group">
                                @foreach ($encargadoPlan->planesDeMejora as $plan)
                                    @foreach ($plan->evidencias as $evidencia)
                                        <a href="{{ route('download_evidencia_plan', $evidencia->id) }}"
                                            class="list-group-item list-group-item-action">
                                            <i class="fas fa-file-pdf text-danger"></i>
                                            {{ $evidencia->name }}
                                            <span class="float-right badge badge-secondary">
                                                {{ \Carbon\Carbon::parse($evidencia->created_at)->format('d/m/Y') }}
                                            </span>
                                        </a>
                                    @endforeach
                                @endforeach
                            </div>
                        </div>
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
        }

        .progress {
            border-radius: 10px;
        }

        .table-bordered th,
        .table-bordered td {
            border-color: #dee2e6;
        }

        thead.thead-light th {
            background-color: #f8f9fa;
            font-weight: 600;
        }

        .badge {
            font-size: 0.85rem;
            padding: 0.4em 0.8em;
        }
    </style>
@stop
