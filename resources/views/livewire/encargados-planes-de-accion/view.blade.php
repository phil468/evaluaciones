@section('title', __('Encargados Planes De Accions'))
<div class="container-fluid">
	<div class="row justify-content-center">
		<div class="col-md-12">
            <div class="rounded-2xl card">
                <div class="text-white card-header bg-vanguard rounded-t-xl">
					<div style="display: flex; justify-content: space-between; align-items: center;">
						<div class="float-left">
							<h4 class="h5">Lista Encargados Planes De Accion </h4>
						</div>
						{{--<div wire:poll.1s>
							<code><h5>{{ now()->format('H:i:s') }}</h5></code>
						</div>--}}
						@if (session()->has('message'))
						<div wire:poll.4s class="btn btn-sm btn-success rounded-xl" style="margin-top:0px; margin-bottom:0px;"> {{ session('message') }} </div>
						@endif
						<div>
							<input wire:model='keyWord' type="text" class="form-control" name="search" id="search" placeholder="Buscar">
						</div>
						@can('crear-encargados-planes-de-accion')
						<div class="btn btn-sm btn-default rounded-xl" data-toggle="modal" data-target="#createDataModal">
						<i class="fa fa-plus"></i>  Nuevo
						</div>
						@endcan

					</div>
				</div>
				
				<div class="card-body">
						{{-- @can('crear-encargados-planes-de-accion')
						@include('livewire.encargados-planes-de-accion.create')
						@endcan						
						@can('editar-encargados-planes-de-accion')
						@include('livewire.encargados-planes-de-accion.create')
						@endcan --}}
						@include('livewire.encargados-planes-de-accion.create_plan')
						@include('livewire.encargados-planes-de-accion.update_plan')
				<div class="table-responsive">
					@isset($encargadosPlanesDeAccions)
					<table class="table table-striped table-hover table-sm">
						<thead class="thead">
							<tr> 
								{{-- <th>id</th> --}}
								@if ($ingreso)
									
								@else
								<th>Encargado Id</th>
								@endif
								<th>Empleado Id</th>
								{{-- <th>Evaluacion Id</th> --}}
								{{-- <th>Realizado</th> --}}
																
								@can('editar-encargados-planes-de-accion','borrar-encargados-planes-de-accion')
								<th>ACCIONES</th>								
								@endcan
							</tr>
						</thead>
						<tbody>
							@foreach($encargadosPlanesDeAccions as $row)
							<tr>
								{{-- <td>{{ $row->id }}</td>  --}}
								@if ($ingreso)
									
								@else
								<td>{{ $row->encargado->name }}</td>
								@endif
								<td>{{ $row->empleado->name }}</td>
								{{-- <td>{{ $row->evaluacion_id }}</td> --}}
								{{-- <td>{{ $row->realizado }}</td> --}}
																
								{{-- @can('editar-encargados-planes-de-accion','borrar-encargados-planes-de-accion') --}}
								<td width="90">
								<div class="btn-group">
									<button class="btn btn-vanguard" data-toggle="tooltip" data-placement="top" title="Ver" wire:click="ver({{$row->id}})"><i class="fa fa-eye"></i></button>
									@can('editar-encargados-planes-de-accion')
									<a data-toggle="modal" data-target="#updateModal" class="btn btn-sm btn-primary rounded-xl" wire:click="edit_plan({{$row->id}})">Editar </a>
									@endcan
									@can('borrar-encargados-planes-de-accion')							 
									<a class="btn btn-sm btn-danger rounded-xl" onclick="confirm('Confirma borrar Encargados Planes De Accion : {{$row->name}}? \nEncargados Planes De Accions borrados no pueden ser recuperados!')||event.stopImmediatePropagation()" wire:click="destroy_plan({{$row->id}})"> Borrar </a> 
									@endcan  
								</div>
								</td>
								{{-- @endcan --}}
							@endforeach
						</tbody>
					</table>						
					{{ $encargadosPlanesDeAccions->links() }}
						
					@endisset
					</div>

					@isset($dashboard)
						@if ($dashboard)
						<div style="display: flex; justify-content: space-between; align-items: center;" class="mb-4">
							<div class="float-left h5">
								PERSONAL: {{$nombreEmpleado}}
							</div>
								<a href="{{ route('planes-de-mejora.ingreso',[$ingreso=>'ingreso']) }}" class="btn btn-xl btn-vanguard rounded-xl">
									<i class="fa fa-arrow-left"></i> Volver
								</a>
						</div>
						
						<div class="table-responsive">
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
																		
										{{-- @can('editar-planes-de-accion','borrar-planes-de-accion') --}}						
										{{-- @endcan --}}
									</tr>
								</thead>
								<tbody>
									{{-- {{dd($planesDeAccions)}} --}}
									@foreach($planesDeAccions as $row)
									<tr>
										
										<td width="90">
											<div class="btn-group">
												<a data-toggle="modal" data-target="#updatePlanDataModal" class="btn btn-sm btn-primary rounded-xl" wire:click="edit_plan({{$row->id}})">Editar </a>
												<a class="btn btn-sm btn-danger rounded-xl" onclick="confirm('Confirma borrar Planes De Accion : {{$row->name}}? \nPlanes De Accion borrados no pueden ser recuperados!')||event.stopImmediatePropagation()" wire:click="destroy_plan({{$row->id}})"> Borrar </a> 
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
										<td>{{ $row->estado->name ?? '' }}</td>
										<td>{{ $row->avance }}%</td>
										<td>{{ $row->empleado->area->gerencia->name ?? '' }}</td>
										<td>{{ $row->empleado->area->name ?? '' }}</td>
																		
										{{-- @can('editar-planes-de-accion','borrar-planes-de-accion') --}}
										{{-- @endcan --}}
									@endforeach
								</tbody>
							</table>						
							{{-- {{ $planesDeAccions->links() }} --}}
							</div>
						</div>
						<div class="row">
								
						{{-- <div style="width: 800px"> --}}
							<canvas wire.ignore id="myChart"></canvas>
						{{-- </div> --}}
						</div>
						{{-- <div class="col-sm-6"> --}}
							
						{{-- </div> --}}
						</div>
						@endif
						
					@endisset
					
				</div>
                <div wire:loading wire:target="store,update,create,edit,destroy,store_plan,update_plan,create_plan,edit_plan,destroy_plan">
                    <x-loading-indicator />
                </div>	
			</div>
		</div>
	</div>
	@once
	@push('js')
		<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
	@endpush
@endonce

@push('js')

	{{-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> --}}

	<script>
        var ctx = document.getElementById('myChart').getContext('2d');
        var labels = {!! json_encode($this->secciones->pluck('nombre')) !!};
        var data = {!! json_encode($this->secciones->pluck('promedio')) !!};
        var seccion_ids = {!! json_encode($this->secciones->pluck('seccion_id')) !!};
		// var data = ["2.0000","2.0000","9.0000","7.5000","8.0000","4.0000","6.0000","9.5000","3.5000","7.0000","9.5000","2.0000","3.0000","4.0000","5.0000","5.0909"];

		var backgroundColors = data.map((value) => 'rgba(75, 192, 192, 0.2)');
        var borderColors = data.map((value) => 'rgba(75, 192, 192, 1)');

        var sortedData = [...data].sort((a, b) => a - b);
        var lowestValues = sortedData.slice(0, 2);

        data.forEach((value, index) => {
            if (lowestValues.includes(value)) {
                backgroundColors[index] = 'rgba(255, 99, 132, 0.2)';
                borderColors[index] = 'rgba(255, 99, 132, 1)';
            }
        });

        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Promedio de valor numérico',
                    data: data,
                    data_id: seccion_ids,
                    backgroundColor: backgroundColors,
                    borderColor: borderColors,
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                onClick: function(event, array) {
                    if (array.length > 0) {
                        var index = array[0].index;
                        // if (lowestValues.includes(data[index])) {
                        //     window.location.href = '/ruta/a/tu/enlace';
                        // }
						if (lowestValues.includes(data[index])) {

							var seccion_id = this.data.datasets[0].data_id[index];
							console.log(seccion_id);
							//tengo una varibale en 
                    		Livewire.emit('setCompetenciaId', seccion_id);
                    		Livewire.emit('setEstadoId', 1);
                    		Livewire.emit('setAvance', 0);
								var myModal = new bootstrap.Modal(document.getElementById('createPlanDataModal'), {});
							myModal.show();
						}
                    }
                }
            }
        });
    </script>
	@endpush
</div>
