@section('title', __('Planes De Accions'))
<div class="container-fluid">
	<div class="row justify-content-center">
		<div class="col-md-12">
            <div class="card rounded-xl">
                <div class="text-white card-header bg-vanguard rounded-t-xl">
					<div style="display: flex; justify-content: space-between; align-items: center;">
						<div class="float-left">
							<h5 class="h5">Planes De Mejora </h4>
						</div>
						@if (session()->has('message'))
						<div wire:poll.4s class="btn btn-sm btn-success rounded-xl" style="margin-top:0px; margin-bottom:0px;"> {{ session('message') }} </div>
						@endif
						<div>
							<input wire:model='keyWord' type="text" class="form-control" name="search" id="search" placeholder="Buscar">
						</div>
						@can('crear-planes-de-accion')
						<div class="btn btn-sm btn-default rounded-xl" data-toggle="modal" data-target="#createDataModal">
						<i class="fa fa-plus"></i>  Nuevo
						</div>
						@endcan
					</div>
				</div>
				
				<div class="card-body">
					
					
						@can('crear-planes-de-accion')
						@include('livewire.planes-de-accion.create')
						@endcan						
						@can('editar-planes-de-accion')
						@include('livewire.planes-de-accion.update')
						@endcan
						
					@livewire('planes-de-accion-table')
				{{-- <div class="table-responsive">
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
																
								@can('editar-planes-de-accion','borrar-planes-de-accion')
								<th>ACCIONES</th>								
								@endcan
							</tr>
						</thead>
						<tbody>
							@foreach($planesDeAccions as $row)
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
																
								@can('editar-planes-de-accion','borrar-planes-de-accion')
								<td width="90">
								<div class="btn-group">
									@can('editar-planes-de-accion')
									<a data-toggle="modal" data-target="#updateModal" class="btn btn-sm btn-vanguard rounded-xl" wire:click="edit({{$row->id}})">Editar </a>
									@endcan
									@can('borrar-planes-de-accion')							 
									<a class="btn btn-sm btn-danger rounded-xl" onclick="confirm('Confirma borrar Planes De Mejora : {{$row->name}}? \nPlanes De Mejora borrados no pueden ser recuperados!')||event.stopImmediatePropagation()" wire:click="destroy({{$row->id}})"> Borrar </a> 
									@endcan  
								</div>
								</td>
								@endcan
							@endforeach
						</tbody>
					</table>						
					{{ $planesDeAccions->links() }}
					</div>
				</div> --}}
			</div>
		</div>
	</div>
</div>
