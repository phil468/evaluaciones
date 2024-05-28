@section('title', __('Evaluaciones'))
<div class="container-fluid">
	<div class="row justify-content-center">
		<div class="col-md-12">
			<div class="card rounded-xl">
				<div class="card-header bg-vanguard rounded-t-xl">
					<div style="display: flex; justify-content: space-between; align-items: center;">
						<div class="float-left">
							<h4 class="text-white h4">Lista Evaluaciones </h4>
						</div>
						{{--<div wire:poll.1s>
							<code><h5>{{ now()->format('H:i:s') }}</h5></code>
						</div>--}}
						{{-- @if (session()->has('message'))
						<div wire:poll.4s class="btn btn-sm btn-success" style="margin-top:0px; margin-bottom:0px;"> {{ session('message') }} </div>
						@endif --}}
						{{-- <div>
							<input wire:model='keyWord' type="text" class="form-control" name="search" id="search" placeholder="Buscar">
						</div> --}}
						@can('crear-evaluacion')
						<div class="btn btn-sm btn-default" data-toggle="modal" data-target="#createDataModal">
						<i class="fa fa-plus"></i>  Nuevo
						</div>
						@endcan
					</div>
				</div>
				
				<div class="card-body">					
						@can('crear-evaluacion')
						{{-- @include('livewire.evaluaciones.create') --}}
						@endcan						
						@can('editar-evaluacion')
						@include('livewire.evaluaciones.update')
						@endcan
						
					@livewire('evaluacion-table')
				{{-- <div class="table-responsive">
					<table class="table table-striped table-hover table-sm">
						<thead class="thead">
							<tr> 
								<th>#</th> 
								<th>Eid</th>
								<th>Title</th>
								<th>Date</th>
								<th>Status</th>								
																
								@can('editar-evaluacion','borrar-evaluacion')
								<th>ACCIONES</th>								
								@endcan
							</tr>
						</thead>
						<tbody>
							@foreach($evaluaciones as $row)
							<tr>
								<td>{{ $loop->iteration }}</td> 
								<td>{{ $row->eid }}</td>
								<td>{{ $row->title }}</td>
								<td>{{ $row->date }}</td>
								<td>
									<div>
										<livewire:toggle-button :model="$row" :field="'status'" key="{{ $row->id }}">
									</div>
								</td>																
								@can('editar-evaluacion','borrar-evaluacion')
								<td width="90">
								<div class="btn-group">
									@can('editar-evaluacion')
									<a data-toggle="modal" data-target="#updateModal" class="btn btn-sm btn-vanguard" wire:click="edit({{$row->id}})">Editar </a>
									@endcan
									@can('borrar-evaluacion')							 
									<a class="btn btn-sm btn-danger" onclick="confirm('Confirma borrar Evaluacione : {{$row->name}}? \nEvaluaciones borrados no pueden ser recuperados!')||event.stopImmediatePropagation()" wire:click="destroy({{$row->id}})"> Borrar </a> 
									@endcan  
								</div>
								</td>
								@endcan
							@endforeach
						</tbody>
					</table>						
					{{ $evaluaciones->links() }}
					</div>
				</div> --}}
			</div>
		</div>
	</div>
	<div wire:loading wire:target="create,edit,crear_editar_usuarios,enviarCorreo,importar_objetivos,importar">
		<x-loading-indicator />
	</div>	
</div>
