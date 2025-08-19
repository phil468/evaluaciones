@section('title', __('Areas'))
<div class="container-fluid">
	<div class="row justify-content-center">
		<div class="col-md-12">
			<div class="card rounded-xl">
                <div class="text-white card-header bg-vanguard rounded-t-xl">
					<div style="display: flex; justify-content: space-between; align-items: center;">
						<div class="float-left">
							<h5 class="h5">Lista Area </h4>
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
						<div class="d-none d-sm-block">
							@can('crear-area')
							<div class="btn btn-sm btn-default" data-toggle="modal" data-target="#createDataModal">
							<i class="fa fa-plus"></i>  Nuevo
							</div>
							@endcan
							@can('importar-area')
							<div class="btn btn-sm btn-default" data-toggle="modal" data-target="#importDataModal">
							<i class="fa fa-file-import"></i>  Importar
							</div>
							@endcan						
							<div class="btn btn-sm btn-default" wire:click="exportar" wire:loading.attr="disabled">
								<i class="fas fa-file-export"></i>
								Exportar
							</div>
						</div>
						<div class="float-right d-block d-sm-none">
							
							@can('crear-area')
							<div class="float-right btn btn-sm btn-default" wire:click="create()" data-toggle="modal" data-target="#createDataModal">
							<i class="fa fa-plus"></i>  
							</div>
							@endcan
							@can('importar-area')
							<div class="float-right btn btn-sm btn-default" data-toggle="modal" data-target="#importDataModal">
							<i class="fa fa-file-import"></i>  
							</div>
							@endcan						
							<div class="float-right btn btn-sm btn-default" wire:click="exportar" wire:loading.attr="disabled">
								<i class="fas fa-file-export"></i>
								
							</div>
						</div>
					</div>
				</div>
				
				<div class="card-body">
						@can('crear-area')
						@include('livewire.areas.create')
						@endcan						
						@can('editar-area')
						@include('livewire.areas.update')
						@endcan
						@can('importar-area')
						@include('livewire.areas.importar')
						@endcan
				<div class="table-responsive">
					<table class="table table-striped table-hover table-sm">
						<thead class="thead">
							<tr> 
								<th>ID</th> 
								@can('editar-area','borrar-area')
								<th>ACCIONES</th>								
								@endcan
								<th>Nombre</th>
								<th>Tipo</th>
								<th>Area Superior</th>
								{{-- <th>Gerencia</th> --}}
								{{-- <th>Subgerencia</th> --}}
								<th>Estado</th>
								{{-- <th>Idempresa Nisira</th> --}}
								{{-- <th>Idarea Nisira</th> --}}
								{{-- <th>Fechacreacion Nisira</th> --}}
							</tr>
						</thead>
						<tbody>
							@foreach($areas as $row)
							<tr>
								<td>{{ $row->id }}</td> 
								@can('editar-area','borrar-area')
								<td width="150">
								<div class="btn-group">
									@can('editar-area')
									<a data-toggle="modal" data-target="#updateModal" class="btn btn-warning rounded-xl" wire:click="edit({{$row->id}})">
										<i class="fa fa-edit"></i> 
									</a>
									@endcan
									@can('borrar-area')							 
									<a class="btn btn-danger rounded-xl" 
									onclick="confirm('Confirma borrar Area : {{$row->name}}? \nAreas borrados no pueden ser recuperados!')||event.stopImmediatePropagation()" wire:click="destroy({{$row->id}})">
										<i class="fa fa-trash"></i> 
									</a> 
									@endcan  
								</div>
								</td>
								@endcan
								<td>{{ $row->name }}</td>
								<td>{{ $row->tipo->name ?? '' }}</td>
								<td>{{ $row->superior->name ?? '' }}</td>
								{{-- <td>{{ $row->gerencia->name ?? '' }}</td> --}}
								{{-- <td>{{ $row->subgerencia->name ?? '' }}</td> --}}
								<td>
									<div>
										<livewire:toggle-button :model="$row" :field="'estado'" key="{{ $row->id }}">
									</div>
								</td>
								{{-- <td>{{ $row->idempresa_nisira }}</td> --}}
								{{-- <td>{{ $row->idarea_nisira }}</td> --}}
								{{-- <td>{{ $row->fechacreacion_nisira }}</td> --}}
							</tr>
							@endforeach
						</tbody>
					</table>						
					{{ $areas->links() }}
					</div>
				</div>
				<div wire:loading wire:target="importar,exportar,create,edit,destroy">
					<x-loading-indicator/>
				</div>
			</div>
		</div>
	</div>
</div>
