@section('title', __('Planes De Mejora Individual'))
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card rounded-xl">
                <div class="text-white card-header bg-vanguard rounded-t-xl">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <h4 class="h5">Planes de Mejora Individual</h4>
                        </div>

                        @if (session()->has('message'))
                            <div wire:poll.4s class="btn btn-sm btn-success rounded-xl"
                                style="margin-top:0px; margin-bottom:0px;"> {{ session('message') }}
                            </div>
                        @endif
                        {{-- <div>
                            <input wire:model='keyWord' type="text" class="form-control" name="search"
                                id="search" placeholder="Buscar">
                        </div> --}}
                        @can('crear-encargados-planes-de-accion')
                            <div class="btn btn-sm btn-default rounded-xl" data-toggle="modal"
                                data-target="#createDataModal">
                                <i class="fa fa-plus"></i> Nuevo
                            </div>
                        @endcan
                        @isset($dashboard)
                            @if ($dashboard)
                                <div style="display: flex; justify-content: space-between; align-items: center;">
                                    <a href="{{ route('pendientes') }}" class="btn btn-xl btn-default rounded-xl">
                                        <i class="fa fa-arrow-left"></i> Volver
                                    </a>
                                </div>
                            @endif
                        @endisset

                        {{-- Selector de campaña --}}
                        {{-- <div class="mb-2">
                            <label class="small font-weight-bold">Campaña:</label>
                            <select wire:model="campaniaFiltro" class="form-control form-control-sm" style="max-width:240px;">
                                @foreach ($campaniasDisponibles as $cid => $cname)
                                    <option value="{{ $cid }}">{{ $cname }}</option>
                                @endforeach
                            </select>
                        </div> --}}
                    </div>
                </div>


                <div class="card-body">
                    @include('livewire.encargados-planes-de-accion.create_plan')
                    @include('livewire.encargados-planes-de-accion.update_plan')
                    <div class="table-responsive">
                        @isset($ingreso)
                            @if ($ingreso)
                                @isset($encargadosPlanesDeAccions)
                                    <div class="h5">Planes De Mejora</div>
                                    @if ($encargadosPlanesDeAccions->count() == 0)
                                        <div class="alert alert-default" role="alert">
                                            No tiene registro de planes de acción pendientes de ingresar.
                                        </div>
                                    @else
                                        <table class="table table-striped table-hover table-sm">
                                            <thead class="thead">
                                                <tr>
                                                    <th>Campaña</th>
                                                    @if ($ingreso)
                                                    @else
                                                        <th>Encargado</th>
                                                    @endif
                                                    <th>Personal</th>
                                                    <th>Planes ingresados</th>
                                                    <th>ACCIONES</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($encargadosPlanesDeAccions as $row)
                                                    <tr>
                                                        <td>{{ $row->encargados_planes_de_accion->plan_de_mejora->campania->name ?? '' }}
                                                        </td>
                                                        @if ($ingreso)
                                                        @else
                                                            <td>{{ $row->encargado->name }}</td>
                                                        @endif
                                                        <td>{{ $row->empleado->name }}</td>
                                                        <td>
                                                            {{ $row->planes_de_accion_empleado->count() }} /
                                                            {{ $row->cantidad_requerida }}
                                                        </td>
                                                        <td width="90">
                                                            @if ($evaluacionPorCompetenciasFinalizada)
                                                                <div class="btn-group">
                                                                    <button class="rounded-xl btn btn-vanguard"
                                                                        data-toggle="tooltip" data-placement="top"
                                                                        title="Ver" wire:click="ver({{ $row->id }})">
                                                                        <i class="fa fa-eye"></i>
                                                                    </button>
                                                                </div>
                                                            @else
                                                                <button class="rounded-xl btn btn-vanguard"
                                                                    data-toggle="tooltip" data-placement="top"
                                                                    title="Evaluaciones aun no están finalizadas" disabled>
                                                                    <i class="fa fa-eye"></i>
                                                                </button>
                                                                <br>
                                                            @endif
                                                        </td>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        {{ $encargadosPlanesDeAccions->links() }}
                                    @endif
                                @endisset
                            @endif
                        @endisset

                        @isset($vistaAprobacionDePlanes)
                            @if ($vistaAprobacionDePlanes)
                                {{-- @foreach ($planesDeAccions->groupBy('empleado_id') as $empleadoId => $planesPersona)
                                    <h6 class="mt-3">{{ $planesPersona->first()->empleado->name }} (Campaña:
                                        {{ $planesPersona->first()->encargados_planes_de_accion->plan_de_mejora->campania->name ?? '' }})</h6>
                                    <table class="table table-sm table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Descripción</th>
                                                <th>Competencia</th>
                                                <th>Estado Aprobación</th>
                                                <th>%</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($planesPersona as $plan)
                                            <tr>
                                                <td>{{ $plan->name }}</td>
                                                <td>{{ $plan->competencia->name ?? '' }}</td>
                                                <td>{{ strtoupper(str_replace('_',' ',$plan->estado_aprobacion)) }}</td>
                                                <td>{{ $plan->porcentaje_cumplimiento ?? '-' }}</td>
                                                <td>
                                                    <button class="btn btn-xs btn-vanguard" wire:click="edit_plan({{ $plan->id }})" data-toggle="modal" data-target="#updatePlanDataModal">Ver</button>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                @endforeach --}}
                            @endif
                        @endisset
                    </div>

                    @isset($dashboard)
                        @if ($dashboard)
                            <div style="display: flex; justify-content: space-between; align-items: center;" class="mb-2">
                                <div class="float-left h5">
                                    <b>Evaluado</b>
                                    {{-- <br> --}}
                                    <p class="mx-2">{{ $nombreEmpleado }}</p>

                                </div>
                                <div class="float-left h5">
                                    <b>Líder</b>
                                    {{-- <br>  --}}
                                    <p class="mx-2">
                                        {{ $evaluador_has_evaluado->encargado->name }}
                                    </p>
                                </div>
                                {{-- <a href="{{ route('planes-de-mejora.ingreso', [$ingreso => 'ingreso']) }}"
                                    class="btn btn-xl btn-default rounded-xl">
                                    <i class="fa fa-arrow-left"></i> Volver
                                </a> --}}
                            </div>

                            {{-- <div class="float-right mb-2"> --}}
                            {{-- <p class="text-right align">
                                    <button class="btn rounded-xl btn-vanguard" 
                                    wire:click="openModal()" 
                                    data-toggle="modal" 
                                    data-target="#createPlanDataModal"
                                    @if ($planesDeAccions->count() >= $cantidad_requerida)
                                        disabled
                                    @endif
                                    >
                                        <i class="fa fa-plus"></i>  Nuevo
                                    </button>
                                </p> --}}
                            {{-- <p>
                                    (Requeridos: {{ $cantidad_requerida }} planes)
                                </p> --}}
                            {{-- </div> --}}
                            @if ($planesDeAccions->count() < $cantidad_requerida)
                                <p class="mb-2 h6 text-bold">Construye el PMI, eligiendo las dos (02) competencias más bajas
                                    a continuación: </p>
                                @foreach ($secciones_ordenadas as $row)
                                    @if ($row->bajo)
                                        @if ($row->obligatorio)
                                            @if ($row->ingresado)
                                            @else
                                                <p class="mb-2">
                                                    <button type="button"
                                                        class="rounded-xl btn btn-outline-danger btn-block"
                                                        wire:click='setValues({{ $row->seccion_id }})'
                                                        onclick="mostrarLoading()">
                                                        <div class="h6"> {{ $row->nombre }} (Obligatorio) </div>
                                                    </button>
                                                </p>
                                            @endif
                                        @else
                                            @if ($row->ingresado)
                                            @else
                                                @if ($row->visible)
                                                    <p class="mb-2">
                                                        <button type="button"
                                                            class="rounded-xl btn btn-outline-warning btn-block"
                                                            wire:click='setValues({{ $row->seccion_id }})'
                                                            onclick="mostrarLoading()">
                                                            <div class="h6"> {{ $row->nombre }} (Opcional) </div>
                                                        </button>
                                                    </p>
                                                @else
                                                @endif
                                            @endif
                                        @endif
                                    @endif
                                @endforeach
                            @endif

                            {{-- <div class="float-right mb-2">
                                (Requeridos: {{$cantidad_requerida}} planes)
                            </div> --}}

                            <div class="table-responsive">

                                @if ($planesDeAccions->count() == 0)
                                    <div class="alert alert-default rounded-2xl" role="alert">
                                        No hay registro de planes de acción ingresados
                                    </div>
                                @else
                                    <table class="table table-bordered table-striped table-hover table-sm"
                                        id="table-planes">
                                        <thead class="text-white thead bg-vanguard">
                                            <tr>
                                                <th style="min-width: 100px;">Estado de aprobación</th>
                                                {{-- <th>Observación de Validación</th> --}}
                                                {{-- <th>Campaña</th> --}}
                                                <th>Edición</th>
                                                {{-- <th>#</th> --}}
                                                {{-- <th>Tipo De Proceso</th> --}}
                                                {{-- <th>Proceso</th> --}}
                                                <th>Competencia</th>
                                                <th>Feedback</th>
                                                <th style="min-width: 105px;">Fecha de feedback</th>
                                                <th>Compromiso SMART</th>
                                                <th>Objetivo medible</th>
                                                <th>Tipo Objetivo</th>
                                                <th>Fecha de revisión de compromiso</th>
                                                @if ($segunda_fase_activa)
                                                    <th>Evidencias</th>
                                                @endif
                                                <th>Fecha de registro de compromiso</th>
                                                {{-- <th>Descripción</th> --}}
                                                {{-- <th>Evaluado</th>
                                                <th>Líder</th>
                                                <th>Avance</th>
                                                <th>Evidencias</th> --}}
                                                {{-- <th>Gerencia</th> --}}
                                                {{-- <th>Area</th> --}}
                                                {{-- <th>Fecha de Creación</th> --}}
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($planesDeAccions as $row)
                                                {{-- {{dd($row, $row->puedeEditarse())}} --}}
                                                <tr>
                                                    {{-- <td>{{ $row->encargados_planes_de_accion->plan_de_mejora->campania->name ?? '' }}</td> --}}
                                                    <td>
                                                        {{-- {{ strtoupper(str_replace('_', ' ', $row->estado_aprobacion)) }} --}}
                                                        @if ($row->estado_aprobacion == 'validado')
                                                            <span
                                                                class="px-2 py-1 text-xs font-medium rounded-full bg-success">
                                                                Validado
                                                            </span>
                                                        @elseif($row->estado_aprobacion == 'no_validado')
                                                            <span
                                                                class="px-2 py-1 text-xs font-medium rounded-full bg-danger">
                                                                No validado
                                                            </span>
                                                        @elseif($row->estado_aprobacion == 'pendiente')
                                                            <span
                                                                class="px-2 py-1 text-xs font-medium rounded-full bg-gray">
                                                                Pendiente
                                                            </span>
                                                        @endif
                                                    </td>
                                                    {{-- <td>{{ $row->estado_aprobacion == "no_validado" ? $row->observacion_validacion : '' }}</td> --}}
                                                    <td width="90">
                                                        <div class="btn-group">
                                                            @if ($row->puedeEditarse())
                                                                <a data-toggle="modal" data-target="#updatePlanDataModal"
                                                                    class="btn btn-sm btn-vanguard rounded-xl"
                                                                    wire:click="edit_plan({{ $row->id }})">
                                                                    Editar
                                                                </a>
                                                            @else
                                                                <span class="font-weight-bold font-italic">
                                                                    Edición no disponible
                                                                </span>
                                                            @endif
                                                            @if ($primera_fase_activa)
                                                                {{-- <a class="btn btn-sm btn-danger rounded-xl"
                                                                    onclick="confirm('Confirma borrar Planes De Mejora : {{ $row->name }}? \nPlanes De Mejora borrados no pueden ser recuperados!')||event.stopImmediatePropagation()"
                                                                    wire:click="destroy_plan({{ $row->id }})"> Borrar
                                                                </a> --}}
                                                            @endif
                                                        </div>
                                                    </td>

                                                    {{-- <td>{{ $loop->iteration }}</td> --}}
                                                    {{-- <td>{{ $row->tipo_de_proceso->name ?? '' }}</td> --}}
                                                    {{-- <td>{{ $row->proceso->name ?? '' }}</td> --}}
                                                    <td>{{ $row->competencia->name ?? '' }}</td>
                                                    <td>{{ $row->feedbacks->first()->feedback ?? '' }}</td>
                                                    <td>
                                                        {{-- <span class="px-2 py-1 text-xs font-medium rounded-full bg-success"> --}}
                                                        {{ $row->feedbacks->first() ? $row->feedbacks->first()->fecha_feedback->format('d-m-Y') : '' }}
                                                        {{-- </span> --}}
                                                    </td>
                                                    {{-- <td>{{ $row->empleado->name ?? '' }}</td>
                                                    <td>{{ $row->encargado->name ?? '' }}</td> --}}
                                                    <td>{{ $row->name }}</td>
                                                    <td>{{ $row->objetivo }}</td>
                                                    <td>{{ ucfirst(str_replace('_', ' ', $row->tipo_objetivo)) }}</td>
                                                    <td>{{ $row->fecha_de_revision->format('d-m-Y') ?? '' }}</td>
                                                    {{-- <td style=" background-color: {{ $row->estado->color ?? '' }};">
                                                        {{ $row->estado->name ?? '' }}</td> --}}
                                                    {{-- observaciones en caso de no validado --}}
                                                    {{-- <td>{{ $row->avance }}%</td> --}}
                                                    @if ($segunda_fase_activa)
                                                        <td>
                                                            @foreach ($row->evidencias()->get() as $evidencia)
                                                                <div class="mb-2 btn-group" role="group"
                                                                    aria-label="Basic example">
                                                                    <a href="{{ route('download_evidencia_plan', $evidencia->id) }}"
                                                                        class="btn btn-link">
                                                                        {{ $evidencia->name }}
                                                                    </a>
                                                                </div>
                                                                <br>
                                                            @endforeach
                                                        </td>
                                                    @endif
                                                    {{-- 
                                                    <td>{{ '' }}</td>
                                                    <td>{{ '' }}</td> --}}
                                                    {{-- <td>{{ date_format($row->created_at, 'd-m-Y h:i:s a') }}</td> --}}
                                                    <td>
                                                        @if ($row->estado_aprobacion == 'validado')
                                                            {{ date_format($row->updated_at, 'd-m-Y') }}
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @endif

                            </div>
                        @endif

                        {{-- media pantalla cuando es grande y completa de mediana para abajo --}}
                        {{-- <div class="col-md-12 col-lg-6 col-xl-6"> --}}
                        <canvas id="myChart"></canvas>
                        {{-- <div class="mb-4 border-0 shadow card card-body">
                                <div class="mb-3 d-flex align-items-center justify-content-between">
                                    <h2 class="mb-0 h5">Promedio General de Desempeño</h2>
                                    <span class="badge rounded-pill bg-soft-vanguard text-vanguard">{{ round($this->secciones->avg(function($item) {
                                        return $item['promedio'];  // Acceso como array
                                    }), 2) }}%</span>
                                </div>
                                <p class="mb-0 text-sm text-gray-700">
                                    El promedio general de desempeño se calcula como el promedio de los promedios de cada sección evaluada. Este valor proporciona una visión general del rendimiento global en todas las áreas evaluadas.
                                </p>
                            </div> --}}
                        {{-- </div> --}}

                    @endisset
                </div>
            </div>

            <div wire:loading
                wire:target="store,update,create,edit,destroy,store_plan,update_plan,create_plan,edit_plan,destroy_plan">
                <x-loading-indicator />
            </div>
        </div>

        @isset($ingreso)
            @if ($ingreso)
                <div class="col-md-12">
                    <div class="card rounded-xl">
                        <div class="text-white card-header bg-vanguard rounded-t-xl">
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <div class="float-left">
                                    <h4 class="h5">Planes De Mejora propios</h4>
                                </div>

                                @if (session()->has('message'))
                                    <div wire:poll.4s class="btn btn-sm btn-success rounded-xl"
                                        style="margin-top:0px; margin-bottom:0px;"> {{ session('message') }} </div>
                                @endif
                                {{-- <div>
                                    <input wire:model='keyWord' type="text" class="form-control" name="search"
                                        id="search" placeholder="Buscar">
                                </div> --}}
                                @can('crear-encargados-planes-de-accion')
                                    <div class="btn btn-sm btn-default rounded-xl" data-toggle="modal"
                                        data-target="#createDataModal">
                                        <i class="fa fa-plus"></i> Nuevo
                                    </div>
                                @endcan

                            </div>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                @isset($ingreso)
                                    @if ($ingreso)
                                        @isset($planesDeAccions)
                                            {{-- {{ dd($planesDeAccions) }} --}}
                                            <div class="h5">Planes De Mejora
                                            </div>
                                            @if ($planesDeAccions->count() == 0)
                                                <div class="alert alert-default" role="alert">
                                                    No tiene registro de planes de acción asignados a usted.
                                                </div>
                                            @else
                                                <table class="table table-striped table-hover table-sm">
                                                    <thead class="thead">
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Descripción</th>
                                                            <th>Tipo De Proceso</th>
                                                            <th>Proceso</th>
                                                            <th>Encargado</th>
                                                            <th>Personal</th>
                                                            <th>Competencia</th>
                                                            <th>Fecha De Revision</th>
                                                            <th>Estado</th>
                                                            <th>Avance</th>
                                                            <th>Gerencia</th>
                                                            <th>Area</th>
                                                            <th>Fecha de Creación</th>
                                                            <th>Fecha de Modificación</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($planesDeAccions as $row)
                                                            <tr>
                                                                <td>{{ $loop->iteration }}</td>
                                                                <td>{{ $row->name }}</td>
                                                                <td>{{ $row->tipo_de_proceso->name ?? '' }}</td>
                                                                <td>{{ $row->proceso->name ?? '' }}</td>
                                                                <td>{{ $row->encargado->name ?? '' }}</td>
                                                                <td>{{ $row->empleado->name ?? '' }}</td>
                                                                <td>{{ $row->competencia->name ?? '' }}</td>
                                                                <td>{{ $row->fecha_de_revision ?? '' }}</td>
                                                                <td>{{ $row->estado->name ?? '' }}</td>
                                                                <td>{{ $row->avance }}%</td>
                                                                <td>{{ $row->empleado->area->gerencia->name ?? '' }}</td>
                                                                <td>{{ $row->empleado->area->name ?? '' }}</td>
                                                                <td>{{ date_format($row->created_at, 'd-m-Y h:i:s a') }}</td>
                                                                <td>{{ date_format($row->updated_at, 'd-m-Y h:i:s a') }}</td>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                                {{ $planesDeAccions->links() }}
                                            @endif
                                        @endisset
                                    @endif
                                @endisset
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endisset

    </div>

    {{-- @once
        @push('js')
        @endpush
    @endonce --}}

    @push('js')

        {{-- <script nonce="{{ $nonce }}" src="https://cdn.jsdelivr.net/npm/chart.js"></script> --}}
        {{-- <script nonce="{{ $nonce }}" src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script> --}}
        @if ($dashboard)
            <script>
                // SweetAlert de Indicaciones al cargar la página
                //  window.livewire.on( () => {
                // console.log('Mostrando indicaciones...');
                // Obtener datos del líder
                // const liderNombre = "{{ auth()->user()->name }}";
                // const evaluadoNombre = "{{ $nombreEmpleado }}";

                //lanzar una vez que termine de cargar la ventana
                window.addEventListener('load', () => {
                    // console.log('Mostrando indicaciones...');

                    // Solo mostrar indicaciones si aún no se ha alcanzado la cantidad requerida
                    const planesIngresados = {{ $planesDeAccions->count() }};
                    const cantidadRequerida = {{ $cantidad_requerida }};

                    if (planesIngresados >= cantidadRequerida) {
                        // console.log('Ya se completaron los planes requeridos. No se muestra alerta de indicaciones.');
                        return;
                    }

                    Swal.fire({
                        title: 'Indicaciones',
                        html: `
                            <div class="text-left">
                                <p class="mb-2">En esta fase, como líder, deberá seleccionar las <strong>dos (02) competencias más bajas del evaluado</strong>, brindarle <strong>feedback</strong> y, de manera conjunta, elaborar un <strong>plan de mejora individual</strong> basado en los resultados de su Evaluación de Desempeño por Competencias.</p>
                            </div>
                        `,
                        icon: 'info',
                        iconColor: '#17a2b8',
                        showCancelButton: true,
                        confirmButtonText: 'Aceptar',
                        confirmButtonColor: '#568ca5',
                        cancelButtonText: 'Volver',
                        cancelButtonColor: '#6c757d',
                        customClass: {
                            popup: 'rounded-xl',
                            confirmButton: 'rounded-xl',
                            cancelButton: 'rounded-xl'
                        },
                        allowOutsideClick: false
                    }).then((result) => {
                        if (!result.isConfirmed) {
                            // Si hace clic en "Volver", redirigir a la página anterior
                            window.history.back();
                        }
                    });
                });
                // });

                Chart.defaults.font.size = 16;

                var ctx = document.getElementById('myChart').getContext('2d');
                var labels = {!! json_encode($secciones_ordenadas->pluck('nombre')) !!};
                var data = {!! json_encode($secciones_ordenadas->pluck('promedio')) !!};
                var valor_esperado_data = {!! json_encode($secciones_ordenadas->pluck('valor_esperado')) !!};
                var seccion_ids = {!! json_encode($secciones_ordenadas->pluck('seccion_id')) !!};
                var bajo_flags = {!! json_encode($secciones_ordenadas->pluck('bajo')) !!};
                var visible_flags = {!! json_encode($secciones_ordenadas->pluck('visible')) !!};
                var ingresado_flags = {!! json_encode($secciones_ordenadas->pluck('ingresado')) !!};
                var cantidad_requerida = {{ $cantidad_requerida }};
                var planes_ingresados = {{ $planesDeAccions->count() }};

                // console.log('Datos cargados:');
                // console.log('Labels:', labels);
                // console.log('Bajo flags:', bajo_flags);
                // console.log('Visible flags:', visible_flags);
                // console.log('Ingresado flags:', ingresado_flags);
                // console.log('Planes ingresados:', planes_ingresados, '/ Requeridos:', cantidad_requerida);

                var backgroundColors = {!! json_encode($secciones_ordenadas->pluck('color')) !!};

                // Asignar color gris al promedio si no tiene color
                backgroundColors = backgroundColors.map((color, index) => {
                    return color || 'rgba(200, 200, 200, 0.5)';
                });

                var borderColors = [];
                var secciones_bajas = [];

                // Procesar cada sección
                data.forEach((value, index) => {
                    // Marcar con borde rojo solo si es bajo=true
                    if (bajo_flags[index] === true) {
                        borderColors[index] = 'rgba(255, 99, 132, 1)';
                        labels[index] = labels[index] + ' (Bajo)';
                        secciones_bajas.push(seccion_ids[index]);
                    } else {
                        borderColors[index] = 'rgba(0, 0, 0, 0.1)';
                    }
                });

                Livewire.emit('setSeccionesBajas', secciones_bajas);

                // @this.set('secciones_bajas', secciones_bajas);

                var myChart = new Chart(ctx, {
                    data: {
                        labels: labels,
                        datasets: [{
                            type: 'bar',
                            label: 'Promedio de competencia',
                            data: data,
                            data_id: seccion_ids,
                            backgroundColor: backgroundColors,
                            borderColor: borderColors,
                            borderWidth: 4,
                            order: 1,
                            usePointStyle: false,
                            pointStyle: 'rect',
                        }, {
                            type: 'line',
                            borderWidth: 2,
                            label: 'Valor mínimo esperado ({{ $this->valor_esperado }})', //
                            data: valor_esperado_data,
                            datalabels: {
                                display: false,
                            },
                            borderColor: '#b3b3b3',
                            backgroundColor: 'transparent',
                            borderDash: [5, 5],
                            usePointStyle: true,
                            pointStyle: 'line',
                            pointRadius: 2,
                            order: 2
                        }]
                    },
                    plugins: [ChartDataLabels],
                    options: {
                        legend: {
                            labels: {
                                usePointStyle: true,
                            }
                        },
                        scales: {
                            y: {
                                title: {
                                    display: true,
                                    text: 'Competencias',
                                },
                            },
                            x: {
                                title: {
                                    display: true,
                                    text: 'Resultado'
                                },
                                min: 0,
                                max: 10,
                                ticks: {
                                    stepSize: 1
                                },
                            }
                        },
                        layout: {
                            padding: {
                                left: 20,
                                right: 80,
                                top: 20,
                                bottom: 20
                            }
                        },
                        indexAxis: 'y',
                        onClick: function(event, array) {
                            if (array.length > 0) {
                                var index = array[0].index;
                                var seccion_id = this.data.datasets[0].data_id[index];

                                // console.log('Click en barra:', index, 'seccion_id:', seccion_id);
                                // console.log('bajo:', bajo_flags[index], 'visible:', visible_flags[index], 'ingresado:',
                                //     ingresado_flags[index]);

                                // Verificar primero si ya se alcanzó la cantidad requerida
                                if (planes_ingresados >= cantidad_requerida) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Planes completos',
                                        text: 'Ya has completado la cantidad de planes requeridos (' +
                                            cantidad_requerida + ')',
                                        iconColor: '#28a745',
                                        confirmButtonColor: '#568ca5',
                                        confirmButtonText: 'Entendido',
                                        customClass: {
                                            popup: 'rounded-xl',
                                            confirmButton: 'rounded-xl'
                                        }
                                    });
                                    return;
                                }

                                // Verificar si la sección es clickeable (bajo=true, visible=true, ingresado=false)
                                if (bajo_flags[index] === true &&
                                    visible_flags[index] === true &&
                                    ingresado_flags[index] === false) {
                                    // console.log('Emitiendo setValues con seccion_id:', seccion_id);

                                    // Mostrar loading
                                    Swal.fire({
                                        title: 'Cargando...',
                                        text: 'Preparando el formulario',
                                        allowOutsideClick: false,
                                        allowEscapeKey: false,
                                        didOpen: () => {
                                            Swal.showLoading();
                                        }
                                    });

                                    Livewire.emit('setValues', seccion_id);
                                } else {
                                    // Mostrar mensaje con SweetAlert si no es clickeable
                                    if (ingresado_flags[index] === true) {
                                        Swal.fire({
                                            icon: 'info',
                                            title: 'Competencia ya ingresada',
                                            text: 'Esta competencia ya tiene un plan ingresado',
                                            iconColor: '#17a2b8',
                                            confirmButtonColor: '#568ca5',
                                            confirmButtonText: 'Entendido',
                                            customClass: {
                                                popup: 'rounded-xl',
                                                confirmButton: 'rounded-xl'
                                            }
                                        });
                                    } else if (visible_flags[index] === false) {
                                        Swal.fire({
                                            icon: 'warning',
                                            title: 'Competencia no disponible',
                                            text: 'Esta competencia no está disponible para selección',
                                            iconColor: '#ffc107',
                                            confirmButtonColor: '#568ca5',
                                            confirmButtonText: 'Entendido',
                                            customClass: {
                                                popup: 'rounded-xl',
                                                confirmButton: 'rounded-xl'
                                            }
                                        });
                                    } else if (bajo_flags[index] === false) {
                                        Swal.fire({
                                            icon: 'info',
                                            title: 'Selección no permitida',
                                            text: 'Solo puedes seleccionar las competencias más bajas',
                                            iconColor: '#17a2b8',
                                            confirmButtonColor: '#568ca5',
                                            confirmButtonText: 'Entendido',
                                            customClass: {
                                                popup: 'rounded-xl',
                                                confirmButton: 'rounded-xl'
                                            }
                                        });
                                    }
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                display: true,
                                position: 'top',
                                labels: {
                                    usePointStyle: true,
                                },
                            },
                            tooltip: {
                                enabled: true,
                            },
                            datalabels: {
                                align: 'end',
                                anchor: 'end',
                            },
                            title: {
                                display: true,
                                text: 'Evaluación de Desempeño por Competencias',
                                html: true,
                                font: {
                                    size: 18
                                }
                            }

                        },

                    }
                });

                Livewire.on('dataUpdated', async () => {
                    // console.log('Evento dataUpdated recibido - Actualizando gráfico...');

                    // Obtener datos actualizados desde Livewire usando @this
                    const secciones_actualizadas = await @this.get('secciones_ordenadas');
                    const planes_count = await @this.get('planesDeAccions');

                    // Actualizar contador de planes ingresados
                    planes_ingresados = planes_count.length || 0;

                    // console.log('Secciones actualizadas recibidas:', secciones_actualizadas);
                    // console.log('Planes ingresados actualizados:', planes_ingresados);

                    // Procesar los datos
                    var labels_updated = [];
                    var data_updated = [];
                    var seccion_ids_updated = [];
                    var bajo_flags_updated = [];
                    var visible_flags_updated = [];
                    var ingresado_flags_updated = [];
                    var backgroundColors_updated = [];
                    var borderColors_updated = [];

                    secciones_actualizadas.forEach((seccion, index) => {
                        labels_updated.push(seccion.nombre + (seccion.bajo === true ? ' (Bajo)' : ''));
                        data_updated.push(seccion.promedio);
                        seccion_ids_updated.push(seccion.seccion_id);
                        bajo_flags_updated.push(seccion.bajo);
                        visible_flags_updated.push(seccion.visible);
                        ingresado_flags_updated.push(seccion.ingresado);
                        backgroundColors_updated.push(seccion.color || 'rgba(200, 200, 200, 0.5)');
                        borderColors_updated.push(seccion.bajo === true ? 'rgba(255, 99, 132, 1)' :
                            'rgba(0, 0, 0, 0.1)');
                    });

                    // Actualizar las variables globales
                    bajo_flags = bajo_flags_updated;
                    visible_flags = visible_flags_updated;
                    ingresado_flags = ingresado_flags_updated;

                    // Actualizar los datos del gráfico
                    myChart.data.labels = labels_updated;
                    myChart.data.datasets[0].data = data_updated;
                    myChart.data.datasets[0].data_id = seccion_ids_updated;
                    myChart.data.datasets[0].backgroundColor = backgroundColors_updated;
                    myChart.data.datasets[0].borderColor = borderColors_updated;

                    // Refrescar el gráfico
                    myChart.update();

                    // console.log('Gráfico actualizado correctamente', {
                    //     bajo_flags: bajo_flags_updated,
                    //     visible_flags: visible_flags_updated,
                    //     ingresado_flags: ingresado_flags_updated,
                    //     planes_ingresados: planes_ingresados
                    // });
                });

                // Listener cuando se cierra el modal después de guardar
                Livewire.on('closeModal', () => {
                    // console.log('Modal cerrado - Solicitando actualización de datos...');
                    // Emitir evento para que Livewire recargue los datos
                    @this.call('$refresh');
                });

                function confirmarGuardado(tipo) {
                    Swal.fire({
                        // title: '¿Está seguro de guardar el Plan de Mejora?',
                        html: `
                            <div class="text-center">
                                <p class="mb-0">Este compromiso será enviado a validación por GTH para verificar que cumple con la metodología SMART.</p>
                                <p class="mb-2">Una vez enviado, no podrá realizarse modificaciones.</p>
                                <p class="mb-2 text-bold">¿Está seguro de que desea continuar?</p>
                            </div>
                        `,
                        icon: 'question',
                        iconColor: '#568ca5',
                        showCancelButton: true,
                        confirmButtonText: 'Sí, continuar',
                        confirmButtonColor: '#568ca5',
                        cancelButtonText: 'Cancelar',
                        cancelButtonColor: '#6c757d',
                        customClass: {
                            popup: 'rounded-xl',
                            confirmButton: 'rounded-xl',
                            cancelButton: 'rounded-xl'
                        },
                        allowOutsideClick: false,
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Llamar al método de Livewire según el tipo
                            if (tipo === 'nuevo') {
                                @this.call('store_plan');
                            } else if (tipo === 'actualizar') {
                                @this.call('update_plan');
                            }
                        }
                    });
                }

                // Listener para abrir el modal cuando se hace clic en el gráfico o botones
                Livewire.on('openUpdatePlanDataModal', () => {
                    // console.log('Evento openUpdatePlanDataModal recibido');
                    // Cerrar el loading si está activo
                    Swal.close();
                    // Abrir el modal
                    $('#updatePlanDataModal').modal('show');
                });

                // Función para mostrar loading cuando se hace clic en botones
                function mostrarLoading() {
                    Swal.fire({
                        title: 'Cargando...',
                        text: 'Preparando el formulario',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                }
            </script>
        @endif

    @endpush

</div>
