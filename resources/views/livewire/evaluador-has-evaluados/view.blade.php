<div class="container-fluid">
    @push('styles')
        <style>
            .table-bordered td,
            .table-bordered th {
                border: 5px solid #ffffff;
            }
        </style>
    @endpush
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card rounded-xl">
                <div class="text-white card-header bg-vanguard rounded-t-xl">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            @if ($tipo_de_evaluacion_id == 2)
                                <h5 class="h5">Evaluación de Desempeño por Resultados {{$campania}} de personal a cargo</h5>
                                @section('title', __('Evaluación de Desempeño por Resultados'))
                            @endif
                            @if ($tipo_de_evaluacion_id == 1)
                                <h5 class="h5">Evaluación de Desempeño por Competencia {{$campania}} </h5>
                                @section('title', __('Evaluación de Desempeño por Competencia'))
                            @endif

                        </div>
                        @if (session()->has('message'))
                            <div wire:poll.4s class="rounded-xl btn btn-sm btn-success "
                                style="margin-top:0px; margin-bottom:0px;"> {{ session('message') }} </div>
                        @endif
                        @if (session()->has('error'))
                            <div wire:poll.4s class="rounded-xl btn btn-sm btn-danger "
                                style="margin-top:0px; margin-bottom:0px;"> {{ session('error') }} </div>
                        @endif
                        @can('crear-evaluadorHasEvaluado')
                            <div class="rounded-xl btn btn-sm btn-default" data-toggle="modal"
                                data-target="#createDataModal">
                                <i class="fa fa-plus"></i> Nuevo
                            </div>
                        @endcan
                        {{-- colocar boton para cambiar vista, cambia el valor de la variable $view_alternative de trua afalse y viceversa --}}
                        <div class="rounded-xl btn btn-sm btn-default " wire:click="changeView()">
                            <i class="fas fa-eye"></i> Vista Alternativa
                        </div>

                    </div>
                </div>

                <div class="card-body">
                    @isset($evaluadorHasEvaluados)
                        @if ($evaluadorHasEvaluados->count() > 0)
                            <div class="rounded-full progress" style="height: 35px; background-color: #6ECBC9">
                                <div class="progress-bar {{ $class }}" role="progressbar"
                                    style="width: {{ $porcentaje }}%; font-size: 18px; font-weight: bold; border-radius: 20px;"
                                    aria-valuenow="{{ $porcentaje }}" aria-valuemin="0" aria-valuemax="100">
                                    {{ $label }} 
                                </div>
                            </div>
                            <br>

                            @if ($view_alternative == false)
                                <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-xl-4">
                                    @foreach ($evaluadorHasEvaluados as $row)
                                        <div class="mb-4 col">
                                            <div class="h-full bg-gray-100 card rounded-2xl">
                                                <div class="text-center align-content-top card-body bg-default">
                                                    <div class="mb-2 h-3/4 align-content-end">
                                                        <p class="align-content-end">
                                                            <i class="fas fa-user bg-primary rounded-circle"
                                                                style=
                                                                "width: 40px;
                                                                height: 40px;
                                                                font-size: x-large;
                                                                align-content: center;">
                                                            </i>
                                                            <h5 class="mb-2 text-center"
                                                                style=
                                                                "font-size: 1.40rem;
                                                                font-weight: 700;
                                                                margin: 0;">
                                                                {{ $row->evaluado->name }}
                                                                <hr class=""
                                                                    style="
                                                                    border-top-width: 3px;
                                                                    border-color: #3c4651;">
                                                            </h5>
                                                        </p>

                                                        <div class="mb-2 card-subtitle text-muted">
                                                            {{ $row->cargo_de_evaluado }}
                                                        </div>
                                                        <p class="mb-1 card-text">
                                                            {{ ucfirst(strtolower($row->evaluacion->nombre_para_mostrar)) }}
                                                        </p>
                                                    </div>

                                                    {{-- Primero evaluamos estado de evaluacion --}}

                                                    @if ($tipo_de_evaluacion_id == 1)
                                                        @if ($row->evaluacion->activa)
                                                            @if ($row->realizado)
                                                                <span class="badge badge-secondary badge-pill"
                                                                    style="width: 9rem; height: 2rem; font-size: 90%; line-height: inherit;">REALIZADO</span>
                                                            @else
                                                                <a
                                                                href="{{ route('evaluacion.show', [$tipo_de_evaluacion_id, $row->id]) }}"><span
                                                                    class="badge badge-primary badge-pill"
                                                                    style="width: 11rem; height: 2rem; font-size: 90%; line-height: inherit;">
                                                                    PENDIENTE
                                                                        <i class="far fa-hand-point-up"></i>
                                                                    </span> 
                                                                </a>
                                                            @endif
                                                        @else
                                                            <span 
                                                            class="badge badge-light badge-pill"
                                                            style="height: 2rem; font-size: 90%; line-height: inherit;"
                                                            >EVALUACIÓN NO VIGENTE</span>
                                                        @endif                                                        
                                                    @endif
                                                    
                                                    @if ($tipo_de_evaluacion_id == 2)
                                                        @if ($row->realizado)
                                                            <a href="{{ route('evaluacion.show', [$tipo_de_evaluacion_id, $row->id]) }}">
                                                                <span
                                                                    class="badge badge-secondary badge-pill"
                                                                    style="width: 9rem; height: 2rem; font-size: 90%; line-height: inherit;">
                                                                    REALIZADO
                                                                    ({{ $row->cantidad_de_objetivos . '/' . $row->cantidad_requerida }})
                                                                    <i class="far fa-hand-point-up"></i>
                                                                </span>
                                                            </a>
                                                        @else
                                                            <a href="{{ route('evaluacion.show', [$tipo_de_evaluacion_id, $row->id]) }}">
                                                                <span
                                                                    @if ($row->evaluacion->primera_fase_activa)
                                                                        @if ($row->cantidad_de_objetivos_registrados == $row->cantidad_de_objetivos )
                                                                            class="text-white badge badge-info badge-pill"
                                                                            style="background-color: #6ECBC9; width: 11rem; height: 2rem; font-size: 90%; line-height: inherit;"
                                                                        @else
                                                                            class="badge badge-primary badge-pill"
                                                                            style="width: 11rem; height: 2rem; font-size: 90%; line-height: inherit;"
                                                                        @endif
                                                                    @else
                                                                        @if ($row->evaluacion->segunda_fase_activa)
                                                                            @if ($row->cantidad_de_objetivos_completados == $row->cantidad_de_objetivos )
                                                                                class="text-white badge badge-info badge-pill"
                                                                                style="background-color: #6ECBC9; width: 11rem; height: 2rem; font-size: 90%; line-height: inherit;"
                                                                            @else
                                                                                class="badge badge-primary badge-pill"
                                                                                style="width: 11rem; height: 2rem; font-size: 90%; line-height: inherit;"
                                                                            @endif
                                                                        @else
                                                                            class="text-white badge badge-default badge-pill"
                                                                            style="width: 11rem; height: 2rem; font-size: 90%; line-height: inherit;"
                                                                        @endif
                                                                    @endif
                                                                    >
                                                                        @if ($row->evaluacion->primera_fase_activa)
                                                                            @if ($row->cantidad_de_objetivos_registrados == $row->cantidad_de_objetivos )
                                                                                <i class="fa fa-check"></i>
                                                                                FINALIZADO
                                                                            @else
                                                                                <i class="far fa-hand-point-up"></i>
                                                                                PENDIENTE
                                                                            @endif
                                                                        @else
                                                                            @if ($row->evaluacion->segunda_fase_activa)
                                                                            
                                                                                @if ($row->cantidad_de_objetivos_completados == $row->cantidad_de_objetivos )
                                                                                    <i class="fa fa-check"></i>
                                                                                    FINALIZADO
                                                                                @else
                                                                                    <i class="far fa-hand-point-up"></i>
                                                                                    PENDIENTE
                                                                                @endif
                                                                            @else
                                                                            OBJETIVOS
                                                                                ({{ $row->cantidad_de_objetivos }})
                                                                            @endif
                                                                        @endif
                                                                </span> 
                                                            </a>
                                                        @endif
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-sm">
                                        <thead class="thead">
                                            <tr>
                                                <th class="text-center">EVALUADO</th>
                                                <th class="text-center">CARGO</th>
                                                <th class="text-center">TIPO DE EVALUACIÓN</th>

                                                <th class="text-center">ESTADO</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($evaluadorHasEvaluados as $row)
                                                <tr>
                                                    <td class="text-center">{{ $row->evaluado->name }}</td>
                                                    <td class="text-center">
                                                        {{ $row->cargo_de_evaluado }}
                                                        {{-- {{ $row->evaluado->cargo->name }} --}}
                                                    </td>
                                                    <td class="text-center">
                                                        {{ ucfirst(strtolower($row->evaluacion->nombre_para_mostrar)) }}
                                                    </td>

                                                    <td class="text-center" width="90">
                                                        {{-- Primero evaluamos estado de evaluacion --}}

                                                        @if ($tipo_de_evaluacion_id == 1)
                                                            @if ($row->evaluacion->activa)
                                                                @if ($row->realizado)
                                                                    <span class="badge badge-secondary badge-pill"
                                                                        style="width: 9rem; height: 2rem; font-size: 90%; line-height: inherit;">REALIZADO</span>
                                                                @else
                                                                    <a
                                                                    href="{{ route('evaluacion.show', [$tipo_de_evaluacion_id, $row->id]) }}"><span
                                                                        class="badge badge-primary badge-pill"
                                                                        style="width: 11rem; height: 2rem; font-size: 90%; line-height: inherit;">
                                                                        PENDIENTE
                                                                            <i class="far fa-hand-point-up"></i>
                                                                        </span> 
                                                                    </a>
                                                                @endif
                                                            @else
                                                                <span 
                                                                class="badge badge-light badge-pill"
                                                                style="height: 2rem; font-size: 90%; line-height: inherit;"
                                                                >EVALUACIÓN NO VIGENTE</span>
                                                            @endif                                                              
                                                        @endif
                                                        
                                                        @if ($tipo_de_evaluacion_id == 2)
                                                            @if ($row->realizado)
                                                                <a href="{{ route('evaluacion.show', [$tipo_de_evaluacion_id, $row->id]) }}">
                                                                    <span
                                                                        class="badge badge-secondary badge-pill"
                                                                        style="width: 9rem; height: 2rem; font-size: 90%; line-height: inherit;">
                                                                        REALIZADO
                                                                        ({{ $row->cantidad_de_objetivos . '/' . $row->cantidad_requerida }})
                                                                        <i class="far fa-hand-point-up"></i>
                                                                    </span>
                                                                </a>
                                                            @else
                                                                <a href="{{ route('evaluacion.show', [$tipo_de_evaluacion_id, $row->id]) }}">
                                                                    <span
                                                                        @if ($row->evaluacion->primera_fase_activa)
                                                                            @if ($row->cantidad_de_objetivos_registrados == $row->cantidad_de_objetivos )
                                                                                class="text-white badge badge-info badge-pill"
                                                                                style="background-color: #6ECBC9; width: 11rem; height: 2rem; font-size: 90%; line-height: inherit;"
                                                                            @else
                                                                                class="badge badge-primary badge-pill"
                                                                                style="width: 11rem; height: 2rem; font-size: 90%; line-height: inherit;"
                                                                            @endif
                                                                        @else
                                                                            @if ($row->evaluacion->segunda_fase_activa)
                                                                            
                                                                                @if ($row->cantidad_de_objetivos_completados == $row->cantidad_de_objetivos )
                                                                                    class="text-white badge badge-info badge-pill"
                                                                                    style="background-color: #6ECBC9; width: 11rem; height: 2rem; font-size: 90%; line-height: inherit;"
                                                                                @else
                                                                                    class="badge badge-primary badge-pill"
                                                                                    style="width: 11rem; height: 2rem; font-size: 90%; line-height: inherit;"
                                                                                @endif
                                                                            @else
                                                                                class="text-white badge badge-default badge-pill"
                                                                                style="width: 11rem; height: 2rem; font-size: 90%; line-height: inherit;"
                                                                            @endif
                                                                        @endif
                                                                        >
                                                                        
                                                                            @if ($row->evaluacion->primera_fase_activa)
                                                                                @if ($row->cantidad_de_objetivos_registrados == $row->cantidad_de_objetivos )
                                                                                    <i class="fa fa-check"></i>
                                                                                    FINALIZADO
                                                                                @else
                                                                                    <i class="far fa-hand-point-up"></i>
                                                                                    PENDIENTE
                                                                                @endif
                                                                            @else
                                                                                @if ($row->evaluacion->segunda_fase_activa)
                                                                                
                                                                                    @if ($row->cantidad_de_objetivos_completados == $row->cantidad_de_objetivos )
                                                                                        <i class="fa fa-check"></i>
                                                                                        FINALIZADO
                                                                                    @else
                                                                                        <i class="far fa-hand-point-up"></i>
                                                                                        PENDIENTE
                                                                                    @endif
                                                                                @else
                                                                                OBJETIVOS
                                                                                    ({{ $row->cantidad_de_objetivos }})
                                                                                @endif
                                                                            @endif
                                                                    </span> 
                                                                </a>
                                                            @endif

                                                        @endif
                                                    </td>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    {{-- {{ $evaluadorHasEvaluados->links() }} --}}
                                </div>
                            @endif
                
                    @else
                    <div class="alert alert-default" role="alert">
                        No tiene objetivos pendientes de ingresar.
                    </div>
                    @endif
                @endisset
                </div>

                <div wire:loading wire:target="changeView">
                    <x-loading-indicator />
                </div>
            </div>
        </div>
    </div>
</div>
