@section('title', __('Preguntas'))
<div class="container-fluid">
	<div class="row justify-content-center">
		<div class="col-md-12">
			<div class="card rounded-xl">
                <div class="text-white card-header bg-vanguard rounded-t-xl">
					<div style="display: flex; justify-content: space-between; align-items: center;">
						<div class="float-left">
							<h5 class="h5">Lista Pregunta </h4>
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
						@can('crear-pregunta')
						<div class="btn btn-sm btn-default" data-toggle="modal" data-target="#createDataModal">
						<i class="fa fa-plus"></i>  Nuevo
						</div>
						@endcan
					</div>
				</div>
				
				<div class="card-body">
						@can('crear-pregunta')
						@include('livewire.preguntas.create')
						@endcan						
						@can('editar-pregunta')
						@include('livewire.preguntas.update')
						@endcan
				<div class="table-responsive">
					<table class="table table-striped table-hover table-sm">
						<thead class="thead">
							<tr> 
								<th>#</th> 
								<th>Seccion</th>
								<th>Evaluacion</th>
								<th>Qid</th>
								<th>Pregunta</th>
								<th>Tipo</th>
								<th>Opciones</th>
								<th>Numero Orden</th>
																
								@can('editar-pregunta','borrar-pregunta')
								<th>ACCIONES</th>								
								@endcan
							</tr>
						</thead>
						<tbody>
							@foreach($preguntas as $row)
							<tr>
								<td>{{ $loop->iteration }}</td> 
								<td>{{ $row->seccion->name }}</td>
								<td>{{ $row->evaluacion->title }}</td>
								<td>{{ $row->qid }}</td>
								<td>{{ $row->pregunta }}</td>
								<td>{{ $row->tipo }}</td>
								<td>{{ $row->opciones }}</td>
								<td>{{ $row->numero_orden }}</td>
																
								@can('editar-pregunta','borrar-pregunta')
								<td width="90">
								<div class="btn-group">
									@can('editar-pregunta')
									<a data-toggle="modal" data-target="#updateModal" class="btn btn-sm btn-vanguard rounded-xl" wire:click="edit({{$row->id}})">Editar </a>
									@endcan
									@can('borrar-pregunta')							 
									<a class="btn btn-sm btn-danger" onclick="confirm('Confirma borrar Pregunta : {{$row->name}}? \nPreguntas borrados no pueden ser recuperados!')||event.stopImmediatePropagation()" wire:click="destroy({{$row->id}})"> Borrar </a> 
									@endcan  
								</div>
								</td>
								@endcan
							@endforeach
						</tbody>
					</table>						
					{{ $preguntas->links() }}
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
