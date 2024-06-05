@section('title', __('Tipo De Procesos'))
<div class="container-fluid">
	<div class="row justify-content-center">
		<div class="col-md-12">
            <div class="card rounded-xl">
                <div class="text-white card-header bg-vanguard rounded-t-xl">
					<div style="display: flex; justify-content: space-between; align-items: center;">
						<div class="float-left">
							<h5 class="h5">Lista Tipo De Proceso </h4>
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
						@can('crear-tipo-de-proceso')
						<div class="btn btn-sm btn-default rounded-xl" data-toggle="modal" data-target="#createDataModal">
						<i class="fa fa-plus"></i>  Nuevo
						</div>
						@endcan
					</div>
				</div>
				
				<div class="card-body">
						@can('crear-tipo-de-proceso')
						@include('livewire.tipo-de-proceso.create')
						@endcan						
						@can('editar-tipo-de-proceso')
						@include('livewire.tipo-de-proceso.update')
						@endcan
				<div class="table-responsive">
					<table class="table table-striped table-hover table-sm">
						<thead class="thead">
							<tr> 
								<th>#</th> 
								<th>Name</th>
								<th>Estado</th>
																
								@can('editar-tipo-de-proceso','borrar-tipo-de-proceso')
								<th>ACCIONES</th>								
								@endcan
							</tr>
						</thead>
						<tbody>
							@foreach($tipoDeProcesos as $row)
							<tr>
								<td>{{ $loop->iteration }}</td> 
								<td>{{ $row->name }}</td>
								<td>{{ $row->estado }}</td>
																
								@can('editar-tipo-de-proceso','borrar-tipo-de-proceso')
								<td width="90">
								<div class="btn-group">
									@can('editar-tipo-de-proceso')
									<a data-toggle="modal" data-target="#updateModal" class="btn btn-sm btn-vanguard rounded-xl" wire:click="edit({{$row->id}})">Editar </a>
									@endcan
									@can('borrar-tipo-de-proceso')							 
									<a class="btn btn-sm btn-danger rounded-xl" onclick="confirm('Confirma borrar Tipo De Proceso : {{$row->name}}? \nTipo De Procesos borrados no pueden ser recuperados!')||event.stopImmediatePropagation()" wire:click="destroy({{$row->id}})"> Borrar </a> 
									@endcan  
								</div>
								</td>
								@endcan
							@endforeach
						</tbody>
					</table>						
					{{ $tipoDeProcesos->links() }}
					</div>
				</div>
                <div wire:loading wire:target="store,update,create,edit,destroy">
                    <x-loading-indicator />
                </div>	
			</div>
		</div>
	</div>
</div>
