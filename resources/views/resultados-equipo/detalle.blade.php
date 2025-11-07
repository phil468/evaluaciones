@extends('adminlte::page')

@section('title', 'Detalle de Resultados')

@section('content_header')
    {{-- agregar botn de atrás --}}
    {{-- <div class="font-style-poppins font-weight-bold"> --}}
    {{-- <a href="{{ url()->previous() }}" class="mr-2 btn-link btn-light btn-sm">
            <i class="fas fa-arrow-left"></i>
            Atrás
        </a>
    {{-- </div> --}}
    {{-- <h3 class="text-center h3">Resultados de Equipo</h3> --}}


    <h1 class="m-0 text-center text-dark font-weight-bold">
        <a href="{{ route('resultados-de-equipo.index') }}" class="mr-2 btn-link btn-light btn-sm">
            <i class="fas fa-arrow-left"></i>
            Atrás
        </a>
        Resultados de Equipo
    </h1>
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
                        {{-- <a href="{{ route('feedback.crear', ['empleado_id' => $miembro['id'] ?? 1]) }}" class="mr-2 btn rounded-xl btn-vanguard disabled">
                        Dar Feedback
                    </a>
                    <a href="{{ route('informe.descargar', ['empleado_id' => $miembro['id'] ?? 1]) }}" class="btn rounded-xl btn-secondary disabled">
                        Descargar informe
                    </a> --}}
                    </div>
                </div>
            </div>
        </div>

        <!-- Evaluación por Competencias -->
        @if (count($competencias) > 0)
            <div class="mb-4 card">
                <div class="card-body">
                    <h5 class="h5">Evaluación por Competencias</h5>

                    <table class="table table-borderless">
                        <tbody>
                            @foreach ($competencias as $anio => $datos)
                                <tr>
                                    <td style="width: 20%;">Evaluación {{ $anio }}</td>
                                    <td style="width: 50%;">
                                        <div class="progress" style="height: 25px;">
                                            @if ($datos['estado'] === 'sin_resultados')
                                                <div class="progress-bar bg-secondary" role="progressbar"
                                                    style="width: 0%;"></div>
                                            @elseif($datos['estado'] === 'resultados_pendientes')
                                                <div class="progress-bar bg-secondary" role="progressbar"
                                                    style="width: 100%;"></div>
                                            @else
                                                <div class="progress-bar" role="progressbar"
                                                    style="width: {{ $datos['progreso'] }}%; background-color: #5bbfba;">
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td style="width: 10%;" class="text-center">
                                        @if ($datos['estado'] === 'sin_resultados')
                                            <span class="h5 text-muted">Sin evaluar</span>
                                        @elseif($datos['estado'] === 'resultados_pendientes')
                                            <span class="h5 text-muted">Resultados pendientes</span>
                                        @else
                                            <span class="h5">{{ $datos['progreso'] }}%</span>
                                            <small
                                                class="d-block text-muted">{{ $datos['puntajeObtenido'] }}/{{ $datos['puntajeEsperado'] }}</small>
                                        @endif
                                    </td>
                                    <td style="width: 20%;" class="text-right">
                                        <button class="btn rounded-xl btn-vanguard btn-sm w-100"
                                            {{ $datos['estado'] !== 'disponible' ? 'disabled' : '' }}
                                            onclick="verDetalle('competencias', {{ $anio }})">
                                            Ver detalle
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <!-- Evaluación por Objetivos -->

        @if (count($objetivos) > 0)
            <div class="mb-4 card" style="display: none;">
                <div class="card-body">
                    <h5 class="h5">Evaluación por Objetivos</h5>

                    <table class="table table-borderless">
                        <tbody>
                            @foreach ($objetivos as $anio => $datos)
                                <tr>
                                    <td style="width: 20%;">Evaluación {{ $anio }}</td>
                                    <td style="width: 50%;">
                                        <div class="progress" style="height: 25px;">
                                            @if ($datos['estado'] === 'sin_resultados')
                                                <div class="progress-bar bg-secondary" role="progressbar"
                                                    style="width: 0%;"></div>
                                            @elseif($datos['estado'] === 'resultados_pendientes')
                                                <div class="progress-bar bg-secondary" role="progressbar"
                                                    style="width: 100%;"></div>
                                            @else
                                                <div class="progress-bar" role="progressbar"
                                                    style="width: {{ $datos['progreso'] }}%; background-color: #5bbfba;">
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td style="width: 10%;" class="text-center">
                                        @if ($datos['estado'] === 'sin_resultados')
                                            <span class="h5 text-muted">Sin evaluar</span>
                                        @elseif($datos['estado'] === 'resultados_pendientes')
                                            <span class="h5 text-muted">Resultados pendientes</span>
                                        @else
                                            <span class="h5">{{ $datos['progreso'] }}%</span>
                                            <small
                                                class="d-block text-muted">{{ $datos['puntajeObtenido'] }}/{{ $datos['puntajeEsperado'] }}</small>
                                        @endif
                                    </td>
                                    <td style="width: 20%;" class="text-right">
                                        <button class="btn rounded-xl btn-vanguard btn-sm w-100"
                                            {{ $datos['estado'] !== 'disponible' ? 'disabled' : '' }}
                                            onclick="verDetalle('objetivos', {{ $anio }})">
                                            Ver detalle
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <!-- Plan de Mejora Individual -->

        {{-- @dd(count($pdi), $competencias, $objetivos) --}}
        @if (count($pdi) > 0)
            {{-- @dd(count($pdi)) --}}
            <div class="mb-4 card">
                <div class="card-body">
                    <h5 class="h5">Plan de Mejora Individual (PMI)</h5>

                    <table class="table table-borderless">
                        <tbody>
                            {{-- @dd($pdi) --}}
                            @foreach ($pdi as $anio => $datos)
                                {{-- @dd($datos) --}}
                                <tr>
                                    <td style="width: 20%;">PMI {{ $anio }}</td>
                                    <td style="width: 50%;">
                                        <div class="progress" style="height: 25px;">
                                            @if ($datos['estado'] === 'sin_resultados')
                                                <div class="progress-bar bg-secondary" role="progressbar"
                                                    style="width: 0%;"></div>
                                            @elseif($datos['estado'] === 'resultados_pendientes')
                                                <div class="progress-bar bg-secondary" role="progressbar"
                                                    style="width: 100%;"></div>
                                            @else
                                                <div class="progress-bar" role="progressbar"
                                                    style="width: {{ $datos['progreso'] }}%; background-color: #5bbfba;">
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td style="width: 10%;" class="text-center">
                                        @if ($datos['estado'] === 'sin_resultados')
                                            <span class="h5 text-muted">Sin plan</span>
                                        @elseif($datos['estado'] === 'resultados_pendientes')
                                            <span class="h5 text-muted">En progreso</span>
                                        @else
                                            <span class="h5">{{ $datos['progreso'] }}%</span>
                                        @endif
                                    </td>
                                    <td style="width: 20%;" class="text-right">
                                        <a class="btn rounded-xl btn-vanguard btn-sm w-100"
                                            {{ $datos['estado'] !== 'disponible' ? 'disabled' : '' }}
                                            onclick="verDetalle('pdi', {{ $anio }})"
                                            href="{{ route('plan.mejora.detalle', ['empleado_id' => $datos['empleado_id']]) }}?campania_id={{ $datos['campania_id'] }}">
                                            Ver detalle
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <!-- Mensaje cuando no hay datos -->
        @if (count($competencias) === 0 && count($objetivos) === 0 && count($pdi) === 0)
            <div class="card">
                <div class="py-5 text-center card-body">
                    <div class="alert alert-info">
                        <i class="mb-3 fas fa-info-circle fa-2x"></i>
                        <h4>Sin datos disponibles</h4>
                        <p>Este miembro del equipo no tiene evaluaciones registradas.</p>
                    </div>
                </div>
            </div>
        @endif
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
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
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
            const empleadoId = {{ $miembro['id'] }};

            if (tipo === 'competencias') {
                // Buscar la campaña por año
                const campanias = @json($competencias);
                const campaniaData = campanias[anio];

                if (campaniaData) {
                    // Usar la misma ruta de evaluacion_de_competencias.resultados
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '{{ route('evaluacion_de_competencias.resultados') }}';

                    const csrf = document.createElement('input');
                    csrf.type = 'hidden';
                    csrf.name = '_token';
                    csrf.value = '{{ csrf_token() }}';

                    const inputCampania = document.createElement('input');
                    inputCampania.type = 'hidden';
                    inputCampania.name = 'campania_id';
                    inputCampania.value = campaniaData.campania_id;

                    const inputEmpleado = document.createElement('input');
                    inputEmpleado.type = 'hidden';
                    inputEmpleado.name = 'empleado_id';
                    inputEmpleado.value = empleadoId;

                    form.appendChild(csrf);
                    form.appendChild(inputCampania);
                    form.appendChild(inputEmpleado);
                    document.body.appendChild(form);
                    form.submit();
                }
            } else if (tipo === 'objetivos') {
                // Implementar para objetivos
                alert('Detalle de objetivos por implementar');
            } else if (tipo === 'pdi') {
                // Redirigir al PMI del subordinado
                // const url = '{{ route('plan.mejora.detalle', ':empleado_id') }}'.replace(':empleado_id', empleadoId);
                // window.location.href = url;
            }
            // const rutas = {
            //     'competencias': `{{ route('evaluacion-competencias.detalle') }}?empleado_id=${empleadoId}&anio=${anio}`,
            //     'objetivos': `{{ route('evaluacion-objetivos.detalle') }}?empleado_id=${empleadoId}&anio=${anio}`,
            //     'pdi': `{{ route('pdi.detalle') }}?empleado_id=${empleadoId}&anio=${anio}`,
            // };

            // if (rutas[tipo]) {
            //     window.location.href = rutas[tipo];
            // }
        }

        // document.addEventListener('DOMContentLoaded', function() {
        //     console.log('Detalle de Resultados de Miembro cargado');
        // });
    </script>
@stop
