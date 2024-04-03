@section('title', __('Evaluador Has Evaluados'))
<div class="container-fluid">
	<div class="row justify-content-center">
		<div class="col-md-12">
			<div class="card">
				<div class="card-header bg-primary">
					<div style="display: flex; justify-content: space-between; align-items: center;">
						<div class="float-left">
							<h4 class="h4">Evaluaciones a realizar </h4>
						</div>
						{{--<div wire:poll.1s>
							<code><h5>{{ now()->format('H:i:s') }}</h5></code>
						</div>--}}
						@if (session()->has('message'))
						<div wire:poll.4s class="btn btn-sm btn-success" style="margin-top:0px; margin-bottom:0px;"> {{ session('message') }} </div>
						@endif
						{{-- <div>
							<input wire:model='keyWord' type="text" class="form-control" name="search" id="search" placeholder="Buscar">
						</div> --}}
						@can('crear-evaluadorHasEvaluado')
						<div class="btn btn-sm btn-default" data-toggle="modal" data-target="#createDataModal">
						<i class="fa fa-plus"></i>  Nuevo
						</div>
						@endcan
					</div>
				</div>
				
				<div class="card-body">
						@can('crear-evaluadorHasEvaluado')
						@include('livewire.evaluadorHasEvaluados.create')
						@endcan						
						@can('editar-evaluadorHasEvaluado')
						@include('livewire.evaluadorHasEvaluados.update')
						@endcan
				<div class="table-responsive">
					<table class="table table-striped table-hover table-sm">
						<thead class="thead">
							<tr> 
								<th>#</th> 
								<th>Evaluador</th>
								<th>Evaluado</th>
								<th>Evaluación</th>
																
								{{-- @can('editar-evaluadorHasEvaluado','borrar-evaluadorHasEvaluado') --}}
								<th>ACCIONES</th>								
								{{-- @endcan --}}
							</tr>
						</thead>
						<tbody>
							@foreach($evaluadorHasEvaluados as $row)
							<tr>
								<td>{{ $loop->iteration }}</td> 
								<td>{{ $row->evaluador->name }}</td>
								<td>{{ $row->evaluado->name }}</td>
								<td>{{ $row->evaluacion->title }}</td>
								
								<td width="90">
									{{--Primero evaluamos estado de evaluacion --}}
									@if ($row->realizado)
										Evaluación realizada
									@else										
										<a href="{{ route('evaluacion.show', $row->id) }}" class="btn btn-sm btn-primary">Evaluar </a>
									@endif
								</td>

								{{-- @can('editar-evaluadorHasEvaluado','borrar-evaluadorHasEvaluado')
								<td width="90">
								<div class="btn-group">
									@can('editar-evaluadorHasEvaluado')
									<a data-toggle="modal" data-target="#updateModal" class="btn btn-sm btn-primary" wire:click="edit({{$row->id}})">Editar </a>
									@endcan
									@can('borrar-evaluadorHasEvaluado')							 
									<a class="btn btn-sm btn-danger" onclick="confirm('Confirma borrar Evaluador Has Evaluado : {{$row->name}}? \nEvaluador Has Evaluados borrados no pueden ser recuperados!')||event.stopImmediatePropagation()" wire:click="destroy({{$row->id}})"> Borrar </a> 
									@endcan  
								</div>
								</td>
								@endcan --}}
							@endforeach
						</tbody>
					</table>						
					{{ $evaluadorHasEvaluados->links() }}
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
