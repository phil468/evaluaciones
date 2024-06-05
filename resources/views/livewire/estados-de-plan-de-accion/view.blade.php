@section('title', __('Estados De Plan De Accions'))
<div class="container-fluid">
	<div class="row justify-content-center">
		<div class="col-md-12">
            <div class="card rounded-xl">
                <div class="text-white card-header bg-vanguard rounded-t-xl">
					<div style="display: flex; justify-content: space-between; align-items: center;">
						<div class="float-left">
							<h5 class="h5">Lista Estados De Plan De Accion </h4>
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
						@can('crear-estados-de-plan-de-accion')
						<div class="btn btn-sm btn-default rounded-xl" data-toggle="modal" data-target="#createDataModal">
						<i class="fa fa-plus"></i>  Nuevo
						</div>
						@endcan
					</div>
				</div>
				
				<div class="card-body">
						@can('crear-estados-de-plan-de-accion')
						@include('livewire.estados-de-plan-de-accion.create')
						@endcan						
						@can('editar-estados-de-plan-de-accion')
						@include('livewire.estados-de-plan-de-accion.update')
						@endcan
				<div class="table-responsive">
					<table class="table table-striped table-hover table-sm">
						<thead class="thead">
							<tr> 
								<th>Id</th> 
								<th>Name</th>
								<th>Color</th>
								<th>Estado</th>
								<th>Rango</th>
																
								@can('editar-estados-de-plan-de-accion','borrar-estados-de-plan-de-accion')
								<th>ACCIONES</th>								
								@endcan
							</tr>
						</thead>
						<tbody>
							@foreach($estadosDePlanDeAccions as $row)
							<tr>
								<td>{{ $row->id }}</td> 
								<td>{{ $row->name }}</td>
								<td><span class="text-white badge badge-pill" style="background-color: {{ $row->color }}">{{ $row->color }}</span></td>

								{{-- <td>{{ $row->color }}</td> --}}
								<td>
									<div>
										<livewire:toggle-button :model="$row" :field="'estado'" key="{{ $row->id }}">
									</div>
								</td>

								<td>
									{{ $row->rango }}
								</td>
								
																
								@can('editar-estados-de-plan-de-accion','borrar-estados-de-plan-de-accion')
								<td width="90">
								<div class="btn-group">
									@can('editar-estados-de-plan-de-accion')
									<a data-toggle="modal" data-target="#updateModal" class="btn btn-sm btn-vanguard rounded-xl" wire:click="edit({{$row->id}})">Editar </a>
									@endcan
									@can('borrar-estados-de-plan-de-accion')							 
									<a class="btn btn-sm btn-danger rounded-xl" onclick="confirm('Confirma borrar Estados De Plan De Accion : {{$row->name}}? \nEstados De Plan De Accions borrados no pueden ser recuperados!')||event.stopImmediatePropagation()" wire:click="destroy({{$row->id}})"> Borrar </a> 
									@endcan  
								</div>
								</td>
								@endcan
							@endforeach
						</tbody>
					</table>						
					{{ $estadosDePlanDeAccions->links() }}
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
