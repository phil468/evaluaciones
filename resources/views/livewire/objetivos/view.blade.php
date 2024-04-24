@section('title', __('Objetivos'))
<div class="container-fluid">
	<div class="row justify-content-center">
		<div class="col-md-12">
            <div class="rounded-2xl card">
                <div class="text-white card-header bg-vanguard rounded-t-xl">
					<div style="display: flex; justify-content: space-between; align-items: center;">
						<div class="float-left">
							<h4 class="h4">EVALUACIÓN POR OBJETIVOS
							</h4>
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
						<div class="float-right">
							
							{{-- @can('ver-evaluaciones-de-desempeno')
							<div class="btn btn-default rounded-xl" data-toggle="modal" data-target="#createDataModal">
							<i class="fa fa-plus"></i>  Nuevo
							</div>
							@endcan --}}
                            {{-- <button class="btn btn-default btn-lg" wire:click="guardar">Guardar</button> --}}
							{{-- @if ($evaluadorHasEvaluado->realizado) --}}
								{{-- <a class="btn btn-default" href="{{url('/evaluaciones-de-desempeno')}}" >Volver</a> --}}
							{{-- @else --}}
                            	<a type="button" class="btn btn-default rounded-xl" href="{{url('/evaluaciones-de-desempeno/2')}}" >Volver</a>
							{{-- @endif --}}
                        </div>
					</div>
				</div>
				
				<div class="card-body">
						@can('ver-evaluaciones-de-desempeno')
						@include('livewire.objetivos.create')
						@endcan						
						@can('ver-evaluaciones-de-desempeno')
						@include('livewire.objetivos.update')
						@endcan
						
						{{-- @include('livewire.evaluacion.gracias') --}}

                        <div class="row">
                            <div class="col-md-6">
                                <h5 class='h5'>Evaluado:</h5>
                                <p>{{ $evaluado->name ?? 'No identificado' }}</p>
                            </div>
                            
                            <div class="col-md-6">
                                <h5 class='h5'>Cargo:</h5>
                                <p>{{ $evaluado->cargo->name ?? 'No identificado' }}</p>
                            </div>
                        </div>
						@can('ver-evaluaciones-de-desempeno')
						<div class="float-right">
							
							(Máx.: 2 objetivos) <button class="btn rounded-xl btn-vanguard" 
							wire:click="create()" 
							data-toggle="modal" 
							data-target="#createDataModal"
							@if ($objetivos->count() == 2)
								disabled
							@endif
							>
							<i class="fa fa-plus"></i>  Nuevo
							</button>
							<br>
						</div>
						@endcan

						<br>
				<div class="table-responsive">
					<table class="table table-striped table-hover table-sm">
						<thead class="thead">
							<tr> 
								<th>#</th> 
								<th>Descripción</th>
								<th>Tipo Objetivo</th>
								<th>Resultado</th>
								{{-- <th>Evaluado Id</th>
								<th>Evaluador Id</th> --}}
								<th>Evidencia</th>
																
								@can('ver-evaluaciones-de-desempeno','borrar-objetivo')
								<th>ACCIONES</th>								
								@endcan
							</tr>
						</thead>
						<tbody>
							@foreach($objetivos as $row)
							<tr>
								<td>{{ $loop->iteration }}</td> 
								<td>{{ $row->descripcion }}</td>
								<td>{{ $row->tipo_objetivo->unidad.'('.$row->tipo_objetivo->simbolo.')' }}</td>
								<td>{{ $row->resultado }}</td>
								{{-- <td>{{ $row->evaluado_id }}</td>
								<td>{{ $row->evaluador_id }}</td> --}}
								<td>{{ $row->evidencia }}</td>
																
								@can('ver-evaluaciones-de-desempeno','borrar-objetivo')
								<td width="90">
								<div class="btn-group">
									@can('ver-evaluaciones-de-desempeno')
									<a data-toggle="modal" data-target="#updateModal" class="btn rounded-xl btn-sm btn-vanguard" wire:click="edit({{$row->id}})">Editar </a>
									@endcan
									@can('ver-evaluaciones-de-desempeno')							 
									<a class="btn rounded-xl btn-sm btn-danger" onclick="confirm('Confirma borrar Objetivo : {{$row->descripcion}}? \nObjetivos borrados no pueden ser recuperados!')||event.stopImmediatePropagation()" wire:click="destroy({{$row->id}})"> Borrar </a> 
									@endcan  
								</div>
								</td>
								@endcan
							@endforeach
						</tbody>
					</table>
					
					<br>
					<div>
						<h1 class="h5">
							Ejemplos:
						</h1>
						<ol>
							<li>
								- Reducir en un 10% las incidencias, por intrusión de personal no identificado, al establecimiento durante todo el periodo 24/25 vs 23/24.
							</li>
							<li>
								- Cumplir con 145 inspecciones de actos y condiciones de seguridad durante todo el periodo 24/25
							</li>
						</ol>
					</div>
					{{-- {{ $objetivos->links() }} --}}
					</div>
				</div>
                <div wire:loading wire:target="store,update,create,edit,destroy">
                    <x-loading-indicator />
                </div>	
			</div>
		</div>
	</div>
</div>
