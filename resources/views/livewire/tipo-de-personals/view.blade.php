@section('title', __('Tipo De Personals'))
<div class="container-fluid">
	<div class="row justify-content-center">
		<div class="col-md-12">
			<div class="card rounded-xl">
                <div class="text-white card-header bg-vanguard rounded-t-xl">
					<div style="display: flex; justify-content: space-between; align-items: center;">
						<div class="float-left">
							<h5 class="h5">Lista Tipo De Personal </h4>
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
						@can('crear-tipoDePersonal')
						<div class="btn btn-sm btn-default" data-toggle="modal" data-target="#createDataModal">
						<i class="fa fa-plus"></i>  Nuevo
						</div>
						@endcan
					</div>
				</div>
				
				<div class="card-body">
						@can('crear-tipoDePersonal')
						@include('livewire.tipoDePersonals.create')
						@endcan						
						@can('editar-tipoDePersonal')
						@include('livewire.tipoDePersonals.update')
						@endcan
				<div class="table-responsive">
					<table class="table table-striped table-hover table-sm">
						<thead class="thead">
							<tr> 
								<th>#</th> 
								<th>Idtipopersonal Nisira</th>
								<th>Name</th>
								<th>Estado</th>
								<th>Empresa Id</th>
																
								@can('editar-tipoDePersonal','borrar-tipoDePersonal')
								<th>ACCIONES</th>								
								@endcan
							</tr>
						</thead>
						<tbody>
							@foreach($tipoDePersonals as $row)
							<tr>
								<td>{{ $loop->iteration }}</td> 
								<td>{{ $row->idtipopersonal_nisira }}</td>
								<td>{{ $row->name }}</td>
								<td>{{ $row->estado }}</td>
								<td>{{ $row->empresa_id }}</td>
																
								@can('editar-tipoDePersonal','borrar-tipoDePersonal')
								<td width="90">
								<div class="btn-group">
									@can('editar-tipoDePersonal')
									<a data-toggle="modal" data-target="#updateModal" class="btn btn-sm btn-vanguard rounded-xl" wire:click="edit({{$row->id}})">Editar </a>
									@endcan
									@can('borrar-tipoDePersonal')							 
									<a class="btn btn-sm btn-danger" onclick="confirm('Confirma borrar Tipo De Personal : {{$row->name}}? \nTipo De Personals borrados no pueden ser recuperados!')||event.stopImmediatePropagation()" wire:click="destroy({{$row->id}})"> Borrar </a> 
									@endcan  
								</div>
								</td>
								@endcan
							@endforeach
						</tbody>
					</table>						
					{{ $tipoDePersonals->links() }}
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
