@section('title', __('Capacitacion Has Areas'))
<div class="container-fluid">
	<div class="row justify-content-center">
		<div class="col-md-12">
			<div class="card rounded-xl">
                <div class="text-white card-header bg-vanguard rounded-t-xl">
					<div style="display: flex; justify-content: space-between; align-items: center;">
						<div class="float-left">
							<h5 class="h5">Lista Capacitacion Has Area </h4>
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
						@can('crear-capacitacionHasArea')
						<div class="btn btn-sm btn-default" data-toggle="modal" data-target="#createDataModal">
						<i class="fa fa-plus"></i>  Nuevo
						</div>
						@endcan
					</div>
				</div>
				
				<div class="card-body">
						@can('crear-capacitacionHasArea')
						@include('livewire.capacitacionHasAreas.create')
						@endcan						
						@can('editar-capacitacionHasArea')
						@include('livewire.capacitacionHasAreas.update')
						@endcan
				<div class="table-responsive">
					<table class="table table-striped table-hover table-sm">
						<thead class="thead">
							<tr> 
								<th>#</th> 
								<th>Area Id</th>
								<th>Capacitacion Id</th>
																
								@can('editar-capacitacionHasArea','borrar-capacitacionHasArea')
								<th>ACCIONES</th>								
								@endcan
							</tr>
						</thead>
						<tbody>
							@foreach($capacitacionHasAreas as $row)
							<tr>
								<td>{{ $loop->iteration }}</td> 
								<td>{{ $row->area_id }}</td>
								<td>{{ $row->capacitacion_id }}</td>
																
								@can('editar-capacitacionHasArea','borrar-capacitacionHasArea')
								<td width="90">
								<div class="btn-group">
									@can('editar-capacitacionHasArea')
									<a data-toggle="modal" data-target="#updateModal" class="btn btn-sm btn-vanguard rounded-xl" wire:click="edit({{$row->id}})">Editar </a>
									@endcan
									@can('borrar-capacitacionHasArea')							 
									<a class="btn btn-sm btn-danger" onclick="confirm('Confirma borrar Capacitacion Has Area : {{$row->name}}? \nCapacitacion Has Areas borrados no pueden ser recuperados!')||event.stopImmediatePropagation()" wire:click="destroy({{$row->id}})"> Borrar </a> 
									@endcan  
								</div>
								</td>
								@endcan
							@endforeach
						</tbody>
					</table>						
					{{ $capacitacionHasAreas->links() }}
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
