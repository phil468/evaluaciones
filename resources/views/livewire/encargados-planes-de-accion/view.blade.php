{{-- @section('title', __('Encargados Planes De Mejora')) --}}
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card rounded-xl">
                <div class="text-white card-header bg-vanguard rounded-t-xl">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <h4 class="h5">Planes De Mejora de personal a cargo </h4>
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
                                <a href="{{ route('planes-de-mejora.ingreso', [$ingreso => 'ingreso']) }}"
                                    class="btn btn-xl btn-default rounded-xl">
                                    <i class="fa fa-arrow-left"></i> Volver
                                </a>
                            </div>
                            @endif
                        @endisset
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
	                                                @if ($ingreso)
                                                    @else
                                                        <th>Encargado</th>
                                                    @endif
                                                    <th>Personal</th>
                                                    <th>Planes</th>
	                                                <th>ACCIONES</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($encargadosPlanesDeAccions as $row)
                                                    <tr>
                                                        @if ($ingreso)
                                                        @else
                                                            <td>{{ $row->encargado->name }}</td>
                                                        @endif
                                                        <td>{{ $row->empleado->name }}</td>
                                                        <td>    
                                                            {{ $row->planes_de_accion_empleado->count() }} / {{ $row->cantidad_requerida }}
                                                        </td>
 														<td width="90">
                                                            <div class="btn-group">
                                                                <button class="btn btn-vanguard" data-toggle="tooltip"
                                                                    data-placement="top" title="Ver"
                                                                    wire:click="ver({{ $row->id }})"><i
                                                                        class="fa fa-eye"></i></button>
                                                            </div>
                                                        </td>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        {{ $encargadosPlanesDeAccions->links() }}
                                    @endif
                                @endisset
                            @endif
                        @endisset
                    </div>

                    @isset($dashboard)
                        @if ($dashboard)
                            <div style="display: flex; justify-content: space-between; align-items: center;" class="mb-2">
                                <div class="float-left h5">
                                    PERSONAL: {{ $nombreEmpleado }}
                                </div>
                                {{-- <a href="{{ route('planes-de-mejora.ingreso', [$ingreso => 'ingreso']) }}"
                                    class="btn btn-xl btn-default rounded-xl">
                                    <i class="fa fa-arrow-left"></i> Volver
                                </a> --}}
                            </div>

                            <div class="float-right mb-2">
                                <p class="text-right align">
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
                                </p>
                                <p>
                                    (Requeridos: {{$cantidad_requerida}} planes)
                                </p>
                            </div>
                            {{-- <div class="float-right mb-2">
                                (Requeridos: {{$cantidad_requerida}} planes)
                            </div> --}}
                            

                            <div class="table-responsive">

                                @if ($planesDeAccions->count() == 0)
                                    <div class="alert alert-default rounded-2xl" role="alert">
                                        No hay registro de planes de acción ingresados
                                    </div>
                                @else
                                    <table class="table table-striped table-hover table-sm">
                                        <thead class="thead">
                                            <tr>
                                                <th>ACCIONES</th>
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

                                                    <td width="90">
                                                        <div class="btn-group">
                                                            <a data-toggle="modal" data-target="#updatePlanDataModal"
                                                                class="btn btn-sm btn-vanguard rounded-xl"
                                                                wire:click="edit_plan({{ $row->id }})">Editar </a>
                                                            <a class="btn btn-sm btn-danger rounded-xl"
                                                                onclick="confirm('Confirma borrar Planes De Mejora : {{ $row->name }}? \nPlanes De Mejora borrados no pueden ser recuperados!')||event.stopImmediatePropagation()"
                                                                wire:click="destroy_plan({{ $row->id }})"> Borrar </a>
                                                        </div>
                                                    </td>

                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $row->name }}</td>
                                                    <td>{{ $row->tipo_de_proceso->name ?? '' }}</td>
                                                    <td>{{ $row->proceso->name ?? '' }}</td>
                                                    <td>{{ $row->encargado->name ?? '' }}</td>
                                                    <td>{{ $row->empleado->name ?? '' }}</td>
                                                    <td>{{ $row->competencia->name ?? '' }}</td>
                                                    <td>{{ $row->fecha_de_revision ?? '' }}</td>
                                                    <td style="
                                                    background-color: {{ $row->estado->color ?? '' }};
                                                    " >{{ $row->estado->name ?? '' }}</td>
                                                    <td>{{ $row->avance }}%</td>
                                                    <td>{{ $row->empleado->area->gerencia->name ?? '' }}</td>
                                                    <td>{{ $row->empleado->area->name ?? '' }}</td>
                                                    <td>{{ date_format($row->created_at, 'd-m-Y h:i:s a') }}</td>
                                                    <td>{{ date_format($row->updated_at, 'd-m-Y h:i:s a') }}</td>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @endif
                                
                            </div>
                        @endif
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
   
    @once
        @push('js')
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
        @endpush
    @endonce

	@push('js')
		@if ($dashboard)
		<script>
			Chart.defaults.font.size = 16;

			var ctx = document.getElementById('myChart').getContext('2d');
			var labels = {!! json_encode($this->secciones->pluck('nombre')) !!};
			var data = {!! json_encode($this->secciones->pluck('promedio')) !!};
			var valor_esperado_data = {!! json_encode($this->secciones->pluck('valor_esperado')) !!};
			var seccion_ids = {!! json_encode($this->secciones->pluck('seccion_id')) !!};

			// Add a line with the value from Livewire
			
			var backgroundColors = {!! json_encode($this->secciones->pluck('color')) !!};
            
			var borderColors = data.map((value) => 'rgba(75, 192, 192, 1)');

			var sortedData = [...data].sort((a, b) => a - b);
			var lowestValues = sortedData.slice(0, 2);
            var secciones_bajas=[];

			data.forEach((value, index) => {
				if (lowestValues.includes(value)) {
					borderColors[index] = 'rgba(255, 99, 132, 1)';
					labels[index] = labels[index] + ' (Bajo)';
                    // hacer un array
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
						borderWidth: 1,
						order:1,
						usePointStyle: false,
						pointStyle: 'rect',
					},{
						type: 'line',
						borderWidth: 2,
						label: 'Valor mínimo esperado ({{ $this->valor_esperado }})',//
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
						order:2
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
					layout:{
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
							if (lowestValues.includes(data[index])) {
								var seccion_id = this.data.datasets[0].data_id[index];
								Livewire.emit('setValues', seccion_id);
							}
						}
					},
                    plugins : {
						legend: {
							display: true,
							position: 'top',
							labels: {
								usePointStyle: true,
							},
						},
                        tooltip : {
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
			
			Livewire.on('dataUpdated', () => {
				myChart.update();
			});
		
		</script>
			
		@endif
    @endpush  
    
</div>