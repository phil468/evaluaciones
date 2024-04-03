@section('title', __('Sesiones'))
<div class="container-fluid">
	<div class="row justify-content-center">
		<div class="col-md-12">
			<div class="card">
				<div class="card-header bg-primary">
					<div style="display: flex; justify-content: space-between; align-items: center;">
						<div class="float-left">
							<h4>Lista Sesione </h4>
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
						@can('crear-sesione')
						<div class="btn btn-sm btn-default" data-toggle="modal" data-target="#createDataModal">
						<i class="fa fa-plus"></i>  Nuevo
						</div>
						@endcan
					</div>
				</div>
				
				<div class="card-body">
						@can('crear-sesione')
						@include('livewire.sesiones.create')
						@endcan						
						@can('editar-sesione')
						@include('livewire.sesiones.update')
						@endcan
				<div class="table-responsive">
					<table class="table table-striped table-hover table-sm">
						<thead class="thead">
							<tr> 
								<th>#</th> 
								<th>Capacitacion Id</th>
								<th>Numero De Sesion</th>
								<th>Fecha</th>
								<th>Hora Inicio</th>
								<th>Hora Fin</th>
																
								@can('editar-sesione','borrar-sesione')
								<th>ACCIONES</th>								
								@endcan
							</tr>
						</thead>
						<tbody>
							@foreach($sesiones as $row)
							<tr>
								<td>{{ $loop->iteration }}</td> 
								<td>{{ $row->capacitacion_id }}</td>
								<td>{{ $row->numero_de_sesion }}</td>
								<td>{{ $row->fecha }}</td>
								<td>{{ $row->hora_inicio }}</td>
								<td>{{ $row->hora_fin }}</td>
																
								@can('editar-sesione','borrar-sesione')
								<td width="90">
								<div class="btn-group">
									@can('editar-sesione')
									<a data-toggle="modal" data-target="#updateModal" class="btn btn-sm btn-primary" wire:click="edit({{$row->id}})">Editar </a>
									@endcan
									@can('borrar-sesione')							 
									<a class="btn btn-sm btn-danger" onclick="confirm('Confirma borrar Sesione : {{$row->name}}? \nSesiones borrados no pueden ser recuperados!')||event.stopImmediatePropagation()" wire:click="destroy({{$row->id}})"> Borrar </a> 
									@endcan  
								</div>
								</td>
								@endcan
							@endforeach
						</tbody>
					</table>						
					{{ $sesiones->links() }}
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
