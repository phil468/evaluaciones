@section('title', __('Asignacion Has Activos'))
<div class="container-fluid">
	<div class="row justify-content-center">
		<div class="col-md-12">
			<div class="card rounded-xl">
                <div class="text-white card-header bg-vanguard rounded-t-xl">
					<div style="display: flex; justify-content: space-between; align-items: center;">
						<div class="float-left">
							<h5 class="h5">Lista Asignacion Has Activo </h4>
						</div>
						{{--<div wire:poll.1s>
							<code><h5>{{ now()->format('H:i:s') }}</h5></code>
						</div>--}}
						@if (session()->has('message'))
						<div wire:poll.4s class="btn btn-sm btn-success" style="margin-top:0px; margin-bottom:0px;"> {{ session('message') }} </div>
						@endif
						<div>
							<input wire:model='keyWord' type="text" class="form-control" name="search" id="search" placeholder="Buscar">
						</div>
						@can('crear-asignacionHasActivo')
						<div class="btn btn-sm btn-default" data-toggle="modal" data-target="#createDataModal">
						<i class="fa fa-plus"></i>  Nuevo
						</div>
						@endcan
					</div>
				</div>
				
				<div class="card-body">
						@can('crear-asignacionHasActivo')
						@include('livewire.asignacionHasActivos.create')
						@endcan						
						@can('editar-asignacionHasActivo')
						@include('livewire.asignacionHasActivos.update')
						@endcan
				<div class="table-responsive">
					<table class="table table-striped table-hover table-sm">
						<thead class="thead">
							<tr> 
								<th>ID</th> 
								<th>Activo Id</th>
								<th>Asignacion Id</th>
								<th>Accesorios Entregados</th>
								<th>Accesorios Devueltos</th>
								<th>Performance Id</th>
								<th>Vigencia Id</th>
								<th>Fecha De Vigencia</th>
								<th>Devuelto</th>
								<th>Fecha De Devolucion</th>
								<th>Observaciones</th>
																
								@can('editar-asignacionHasActivo','borrar-asignacionHasActivo')
								<th>ACCIONES</th>								
								@endcan
							</tr>
						</thead>
						<tbody>
							@foreach($asignacionHasActivos as $row)
							<tr>
								<td>{{ $row->id }}</td> 
								<td>{{ $row->activo_id }}</td>
								<td>{{ $row->asignacion_id }}</td>
								<td>{{ $row->accesorios_entregados }}</td>
								<td>{{ $row->accesorios_devueltos }}</td>
								<td>{{ $row->performance_id }}</td>
								<td>{{ $row->vigencia_id }}</td>
								<td>{{ $row->fecha_de_vigencia }}</td>
								<td>{{ $row->devuelto }}</td>
								<td>{{ $row->fecha_de_devolucion }}</td>
								<td>{{ $row->observaciones }}</td>
																
								@can('editar-asignacionHasActivo','borrar-asignacionHasActivo')
								<td width="90">
								<div class="btn-group">
									@can('editar-asignacionHasActivo')
									<a data-toggle="modal" data-target="#updateModal" class="btn btn-sm btn-vanguard rounded-xl" wire:click="edit({{$row->id}})">Editar </a>
									@endcan
									@can('borrar-asignacionHasActivo')							 
									<a class="btn btn-sm btn-danger" onclick="confirm('Confirma borrar Asignacion Has Activo : {{$row->name}}? \nAsignacion Has Activos borrados no pueden ser recuperados!')||event.stopImmediatePropagation()" wire:click="destroy({{$row->id}})"> Borrar </a> 
									@endcan  
								</div>
								</td>
								@endcan
							@endforeach
						</tbody>
					</table>						
					{{ $asignacionHasActivos->links() }}
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
