@section('title', __('Cargos'))
<div class="container-fluid">
	<div class="row justify-content-center">
		<div class="col-md-12">
			<div class="card rounded-xl">
                <div class="text-white card-header bg-vanguard rounded-t-xl">
					<div style="display: flex; justify-content: space-between; align-items: center;">
						<div class="float-left">
							<h5 class="h5">Lista Cargo </h4>
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
							@can('crear-cargo')
							<div class="btn btn-sm btn-default" data-toggle="modal" data-target="#createDataModal">
							<i class="fa fa-plus"></i>  Nuevo
							</div>
							@endcan
							@can('importar-cargo')
							<div class="btn btn-sm btn-default" data-toggle="modal" data-target="#importDataModal">
							<i class="fa fa-file-import"></i>  Importar
							</div>
							@endcan
							<div class="btn btn-sm btn-default" wire:click="exportar" wire:loading.attr="disabled">
								<i class="fas fa-file-export"></i>
								Exportar
							</div>
						</div>
						<div class="d-block d-sm-none float-right">
							@can('crear-cargo')
							<div class="btn btn-sm btn-default float-right" wire:click="create()" data-toggle="modal" data-target="#createDataModal">
							<i class="fa fa-plus"></i>  
							</div>
							@endcan
							@can('importar-cargo')
							<div class="btn btn-sm btn-default float-right" data-toggle="modal" data-target="#importDataModal">
							<i class="fa fa-file-import"></i>  
							</div>
							@endcan
							<div class="btn btn-sm btn-default float-right" wire:click="exportar" wire:loading.attr="disabled">
								<i class="fas fa-file-export"></i>								
							</div>
						</div>
					</div>
				</div>
				
				<div class="card-body">
						@can('crear-cargo')
						@include('livewire.cargos.create')
						@endcan						
						@can('editar-cargo')
						@include('livewire.cargos.update')
						@endcan
						@can('importar-cargo')
						@include('livewire.cargos.importar')
						@endcan
				<div class="table-responsive">
					<table class="table table-striped table-hover table-sm">
						<thead class="thead">
							<tr> 
								<th>ID</th> 
								<th>Name</th>
								<th>Estado</th>
								<th>Idcargo Nisira</th>
								<th>Fechacreacion Nisira</th>
																
								@can('editar-cargo','borrar-cargo')
								<th>ACCIONES</th>								
								@endcan
							</tr>
						</thead>
						<tbody>
							@foreach($cargos as $row)
							<tr>
								<td>{{ $row->id }}</td> 
								<td>{{ $row->name }}</td>
								<td>
									<div>
										<livewire:toggle-button :model="$row" :field="'estado'" key="{{ $row->id }}">
									</div>
								</td>
								<td>{{ $row->idcargo_nisira }}</td>
								<td>{{ $row->fechacreacion_nisira }}</td>																
								@can('editar-cargo','borrar-cargo')
								<td width="90">
								<div class="btn-group">
									@can('editar-cargo')
									<a data-toggle="modal" data-target="#updateModal" class="btn btn-sm btn-vanguard rounded-xl" wire:click="edit({{$row->id}})">Editar </a>
									@endcan
									@can('borrar-cargo')							 
									<a class="btn btn-sm btn-danger" onclick="confirm('Confirma borrar Cargo : {{$row->name}}? \nCargos borrados no pueden ser recuperados!')||event.stopImmediatePropagation()" wire:click="destroy({{$row->id}})"> Borrar </a> 
									@endcan  
								</div>
								</td>
								@endcan
							@endforeach
						</tbody>
					</table>						
					{{ $cargos->links() }}
					</div>
				</div>
				<div wire:loading wire:target="importar,exportar,create,edit,destroy">
					<x-loading-indicator/>
				</div>
			</div>
		</div>
	</div>
</div>
