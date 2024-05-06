@section('title', __('Evaluador Has Evaluados'))

<div class="container-fluid">
	@push('styles')
	<style>
		.table-bordered td, .table-bordered th {
			border: 5px solid #ffffff;
		}
	</style>
	@endpush
	<div class="row justify-content-center">
		<div class="col-md-12">
			<div class="card rounded-xl">
				<div class="text-white card-header bg-vanguard rounded-t-xl">
					<div style="display: flex; justify-content: space-between; align-items: center;">
						<div class="float-left">
							<h5 class="h5">EVALUACIONES A REALIZAR </h5>
						</div>
						{{--<div wire:poll.1s>
							<code><h5>{{ now()->format('H:i:s') }}</h5></code>
						</div>--}}
						@if (session()->has('message'))
						<div wire:poll.4s class="rounded-xl btn btn-sm btn-success " style="margin-top:0px; margin-bottom:0px;"> {{ session('message') }} </div>
						@endif
						{{-- <div>
							<input wire:model='keyWord' type="text" class="form-control" name="search" id="search" placeholder="Buscar">
						</div> --}}
						@can('crear-evaluadorHasEvaluado')
						<div class="rounded-xl btn btn-sm btn-default" data-toggle="modal" data-target="#createDataModal">
						<i class="fa fa-plus"></i>  Nuevo
						</div>
						@endcan

						{{-- colocar boton para cambiar vista, cambia el valor de la variable $view_alternative de trua afalse y viceversa --}}

						<div class="rounded-xl btn btn-sm btn-default " wire:click="changeView()">
							<i class="fas fa-eye"></i>  Vista Alternativa
						</div>

					</div>
				</div>
				
				<div class="card-body">
						{{-- @can('crear-evaluadorHasEvaluado')
						@include('livewire.evaluadorHasEvaluados.create')
						@endcan						
						@can('editar-evaluadorHasEvaluado')
						@include('livewire.evaluadorHasEvaluados.update')
						@endcan --}}
						@isset($evaluadorHasEvaluados)
							@if ($evaluadorHasEvaluados->count() > 0)
							{{-- dd(evaluadorHasEvaluados) --}}
							<div class="rounded-full progress" style="height: 35px; background-color: #6ECBC9">
								<div class="progress-bar {{$class}}" role="progressbar" style="width: {{$porcentaje}}%; font-size: 18px; font-weight: bold; border-radius: 20px;" aria-valuenow="{{$porcentaje}}" aria-valuemin="0" aria-valuemax="100"> {{$label}} </div>
							</div>
							<br>

							@if ($view_alternative == false)
							<div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-xl-4">
								@foreach($evaluadorHasEvaluados as $row)
								<div class="mb-4 col">
									<div class="h-full bg-gray-100 card rounded-2xl" 
									{{-- style="border-radius: 15px; background-color: rgb(238 243 246);" --}}
									>
										<div class="text-center align-content-top card-body bg-default">
											<div class="mb-2 h-3/4 align-content-end">
												<p class="align-content-end" >
													<i class="fas fa-user bg-primary rounded-circle"
													style="
														width: 40px;
														height: 40px;
														font-size: x-large;
														align-content: center;
													"
													></i>
												<h5 class="mb-2 text-center"
												style="
													font-size: 1.40rem;
													font-weight: 700;
													margin: 0;
												}"
												>{{$row->evaluado->name}}
													<hr class="" style="
														border-top-width: 3px;
														border-color: #3c4651;
													">
												</h5>									
												</p>
												
												<div class="mb-2 card-subtitle text-muted">{{ $row->evaluado->cargo->name }}</div>
												<p class="mb-1 card-text">
													{{ ucfirst(strtolower($row->evaluacion->nombre_para_mostrar))}}
													{{-- Evaluación {{ str_replace('EVALUACIÓN DE DESEMPEÑO ', '', $row->evaluacion->title) }} --}}
												</p>
											</div>
											
										{{-- <div> --}}
											{{--Primero evaluamos estado de evaluacion --}}
											@if ($row->realizado)
												@if ($tipo_de_evaluacion_id == 2)
													<a href="{{ route('evaluacion.show', $row->id) }}"><span class="badge badge-secondary badge-pill" style="width: 8rem; height: 2rem; font-size: 90%; line-height: inherit;">EDITAR <i class="far fa-hand-point-up"></i></span> </a>									
												@endif
												@if ($tipo_de_evaluacion_id == 1)
													<span class="badge badge-secondary badge-pill" style="width: 8rem; height: 2rem; font-size: 90%; line-height: inherit;">FINALIZADO</span>
												@endif
											@else
												<a href="{{ route('evaluacion.show', $row->id) }}"><span class="badge badge-primary badge-pill" style="width: 8rem; height: 2rem; font-size: 90%; line-height: inherit;">PENDIENTE <i class="far fa-hand-point-up"></i></span> </a>
											@endif
										{{-- </div> --}}
										{{-- <a href="#" class="card-link">Card link</a> --}}
										{{-- <a href="#" class="card-link">Another link</a> --}}
										</div>
									</div>
								</div>
								@endforeach					
							</div>
							@else
								<div class="table-responsive">
									<table class="table table-striped table-bordered table-sm">
										<thead class="thead">
											<tr> 
												{{-- <th class="text-center">ID</th>  --}}
												{{-- <th>Evaluador</th> --}}
												<th class="text-center">EVALUADO</th>
												<th class="text-center">CARGO</th>
												<th class="text-center">TIPO DE EVALUACIÓN</th>
																				
												{{-- @can('editar-evaluadorHasEvaluado','borrar-evaluadorHasEvaluado') --}}
												<th class="text-center">ESTADO</th>								
												{{-- @endcan --}}
											</tr>
										</thead>
										<tbody>
											@foreach($evaluadorHasEvaluados as $row)
											<tr>
												{{-- <td class="text-center">{{ $row->id}}</td>  --}}
												{{-- <td>{{ $row->evaluador->name }}</td> --}}
												<td class="text-center">{{ $row->evaluado->name }}</td>
												<td class="text-center">{{ $row->evaluado->cargo->name }}</td>
												<td class="text-center">
													{{-- {{ str_replace('EVALUACIÓN DE DESEMPEÑO ', '', $row->evaluacion->title) }} --}}
													{{ ucfirst(strtolower($row->evaluacion->nombre_para_mostrar))}}
												</td>
												
												<td class="text-center" width="90">
													{{--Primero evaluamos estado de evaluacion --}}
													@if ($row->realizado)
														@if ($tipo_de_evaluacion_id == 2)
															<a href="{{ route('evaluacion.show', $row->id) }}"><span class="badge badge-secondary badge-pill" style="width: 8rem; height: 2rem; font-size: 90%; line-height: inherit;">EDITAR <i class="far fa-hand-point-up"></i></span> </a>									
														@endif
														@if ($tipo_de_evaluacion_id == 1)
															<span class="badge badge-secondary badge-pill" style="width: 8rem; height: 2rem; font-size: 90%; line-height: inherit;">FINALIZADO</span>
														@endif
													@else
														<a href="{{ route('evaluacion.show', $row->id) }}"><span class="badge badge-primary badge-pill" style="width: 8rem; height: 2rem; font-size: 90%; line-height: inherit;">PENDIENTE <i class="far fa-hand-point-up"></i></span> </a>
													@endif
												</td>

											@endforeach
										</tbody>
									</table>						
									{{ $evaluadorHasEvaluados->links() }}
									</div>
								</div>					
							@endif
							@else
								<div class="alert alert-default" role="alert">
									No tiene objetivos pendientes de ingresar.
								</div>
							@endif
						@endisset
				
                <div wire:loading wire:target="changeView">
                    <x-loading-indicator />
                </div>			
			</div>
		</div>
	</div>
</div>
