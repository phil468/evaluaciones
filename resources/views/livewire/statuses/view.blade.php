@section('title', __('Statuss'))
<div class="container-fluid">
	<div class="row justify-content-center">
		<div class="col-md-12">
			<div class="card rounded-xl">
                <div class="text-white card-header bg-vanguard rounded-t-xl">
					<div style="display: flex; justify-content: space-between; align-items: center;">
						<div class="float-left">
							<h5 class="h5">Lista Estados </h4>
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
						@can('crear-estado')
						<div class="btn btn-sm btn-default"  wire:click="create()" data-toggle="modal" data-target="#createDataModal">
						<i class="fa fa-plus"></i>  Nuevo
						</div>
						@endcan
					</div>
				</div>
				
				<div class="card-body">
						@can('crear-estado')
						@include('livewire.statuses.create')
						@endcan						
						@can('editar-estado')
						@include('livewire.statuses.update')
						@endcan
				<div class="table-responsive">
					<table class="table table-striped table-hover table-sm">
						<thead class="thead">
							<tr> 
								<th>ID</th> 
								<th>Name</th>
								{{-- <th>Color</th> --}}
								<th>Estado</th>
																
								@can('editar-estado','borrar-estado')
								<th>ACCIONES</th>								
								@endcan
							</tr>
						</thead>
						<tbody>
							@foreach($statuses as $row)
							<tr>
								<td>{{ $row->id }}</td> 
								<td>{{ $row->name }}</td>
								{{-- <td>{{ $row->color }}</td> --}}
								<td>
									<div>
										<livewire:toggle-button :model="$row" :field="'estado'" key="{{ $row->id }}">
									</div>
								</td>
																
								@can('editar-estado','borrar-estado')
								<td width="90">
								<div class="btn-group">
									@can('editar-estado')
									<a data-toggle="modal" data-target="#updateModal" class="btn btn-sm btn-vanguard rounded-xl" wire:click="edit({{$row->id}})">Editar </a>
									@endcan
									@can('borrar-estado')							 
									<a class="btn btn-sm btn-danger" onclick="confirm('Confirma borrar Estados : {{$row->name}}? \nStatuss borrados no pueden ser recuperados!')||event.stopImmediatePropagation()" wire:click="destroy({{$row->id}})"> Borrar </a> 
									@endcan  
								</div>
								</td>
								@endcan
							@endforeach
						</tbody>
					</table>						
					{{ $statuses->links() }}
					</div>
				</div>
				<div wire:loading wire:target="importar,exportar,create,edit,destroy">
					<x-loading-indicator/>
				</div>
			</div>
		</div>
	</div>
</div>
