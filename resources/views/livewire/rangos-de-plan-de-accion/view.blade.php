@section('title', __('Rangos De Plan De Accions'))
<div class="container-fluid">
	<div class="row justify-content-center">
		<div class="col-md-12">
            <div class="rounded-2xl card">
                <div class="text-white card-header bg-vanguard rounded-t-xl">
					<div style="display: flex; justify-content: space-between; align-items: center;">
						<div class="float-left">
							<h4 class="h5">Lista Rangos De Plan De Accion </h4>
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
						@can('crear-rangos-de-plan-de-accion')
						<div class="btn btn-sm btn-default rounded-xl" data-toggle="modal" data-target="#createDataModal">
						<i class="fa fa-plus"></i>  Nuevo
						</div>
						@endcan
					</div>
				</div>
				
				<div class="card-body">
						@can('crear-rangos-de-plan-de-accion')
						@include('livewire.rangos-de-plan-de-accion.create')
						@endcan						
						@can('editar-rangos-de-plan-de-accion')
						@include('livewire.rangos-de-plan-de-accion.update')
						@endcan
				<div class="table-responsive">
					<table class="table table-striped table-hover table-sm">
						<thead class="thead">
							<tr> 
								<th>#</th> 
								<th>Name</th>
								<th>Color</th>
								<th>Estado</th>
								<th>Nombre Para Mostrar</th>
								<th>Descripción</th>
								<th>Rango Mayor</th>
																
								@can('editar-rangos-de-plan-de-accion','borrar-rangos-de-plan-de-accion')
								<th>ACCIONES</th>								
								@endcan
							</tr>
						</thead>
						<tbody>
							@foreach($rangosDePlanDeAccions as $row)
							<tr>
								<td>{{ $loop->iteration }}</td> 
								<td>{{ $row->name }}</td>
								<td>{{ $row->color }}</td>
								<td>{{ $row->estado }}</td>
								<td>{{ $row->nombre_para_mostrar }}</td>
								<td>{{ $row->descripción }}</td>
								<td>{{ $row->rango_mayor }}</td>
																
								@can('editar-rangos-de-plan-de-accion','borrar-rangos-de-plan-de-accion')
								<td width="90">
								<div class="btn-group">
									@can('editar-rangos-de-plan-de-accion')
									<a data-toggle="modal" data-target="#updateModal" class="btn btn-sm btn-vanguard rounded-xl" wire:click="edit({{$row->id}})">Editar </a>
									@endcan
									@can('borrar-rangos-de-plan-de-accion')							 
									<a class="btn btn-sm btn-danger rounded-xl" onclick="confirm('Confirma borrar Rangos De Plan De Accion : {{$row->name}}? \nRangos De Plan De Accions borrados no pueden ser recuperados!')||event.stopImmediatePropagation()" wire:click="destroy({{$row->id}})"> Borrar </a> 
									@endcan  
								</div>
								</td>
								@endcan
							@endforeach
						</tbody>
					</table>						
					{{ $rangosDePlanDeAccions->links() }}
					</div>
				</div>
                <div wire:loading wire:target="store,update,create,edit,destroy">
                    <x-loading-indicator />
                </div>	
			</div>
		</div>
	</div>
</div>
