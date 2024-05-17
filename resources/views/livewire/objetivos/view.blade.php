@section('title', __('Objetivos'))
<div class="container-fluid">
	<div class="row justify-content-center">
		<div class="col-md-12">
            <div class="card rounded-xl">
                <div class="text-white card-header bg-vanguard rounded-t-xl">
					<div style="display: flex; justify-content: space-between; align-items: center;">
						<div class="float-left">
							<h5 class="h5">EVALUACIÓN POR OBJETIVOS
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
							
							(Requeridos: {{$cantidad_requerida}} objetivos) <button class="mb-4 btn rounded-xl btn-vanguard" 
							wire:click="create()" 
							data-toggle="modal" 
							data-target="#createDataModal"
							@if ($objetivos->count() >= $cantidad_requerida)
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
					{{-- @if ($grupal) --}}
					@if (1)
						<table class="table table-striped table-hover table-sm">
							<thead class="thead">
								<tr>
									<th></th>
									<th class="text-center text-white bg-vanguard">Metas</th>
									<th class="text-center text-white bg-vanguard">% Participac.</th>
									<th class="text-center text-white bg-vanguard">Evidencias</th>
									<th class="text-center text-white bg-vanguard">Result. Anterior / Esperado</th>
									<th class="text-center text-white bg-vanguard">Mínimo 80%</th>
									<th class="text-center text-white bg-vanguard">Máximo 120%</th>
									<th class="text-center text-white bg-vanguard">Valor</th>
									<th class="text-center text-white bg-vanguard">% Logr. STI</th>
									<th class="text-center text-white bg-vanguard">Peso Pond.</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td class="bg-info">GRUPAL</td>
									<td>a-predefinida</td>
									<td>40%</td>
									<td>Resultados Financieros</td>
									<td>30%</td>
									<td>24%</td>
									<td>36%</td>
									<td>30%</td>
									<td>100%</td>
									<td>40.0%</td>
								</tr>
								<tr>
									<td class="bg-info">GRUPAL</td>
									<td>b-predefinida</td>
									<td>20%</td>
									<td>Forecast</td>
									<td>90%</td>
									<td>72%</td>
									<td>108%</td>
									<td>30%</td>
									<td>0.0%</td>
									<td>0.0%</td>
								</tr>
								<tr>
									<td class="bg-info">GRUPAL</td>
									<td>c-predefinida</td>
									<td>20%</td>
									<td>Estadísticas de producción</td>
									<td>4,228,416.00</td>
									<td>3,382,732.80</td>
									<td>5,074,099.20</td>
									<td>4228416.00</td>
									<td>100%</td>
									<td>20.0%</td>
								</tr>
								<tr>
									<td class="bg-primary">INDIVIDUAL</td>
									<td>Individual 1</td>
									<td>10%</td>
									<td></td>
									<td>85.00</td>
									<td>68.00</td>
									<td>102.00</td>
									<td>70.00</td>
									<td>82%</td>
									<td>8.235%</td>
								</tr>
								<tr>
									<td class="bg-primary">INDIVIDUAL</td>
									<td>Individual 2</td>
									<td>10%</td>
									<td></td>
									<td>85.00</td>
									<td>68.00</td>
									<td>102.00</td>
									<td>70.00</td>
									<td>82%</td>
									<td>8.20%</td>
								</tr>
								<tr>
									<td colspan="9" class="text-right">Subtotal</td>
									<td>76%</td>
								</tr>
								<tr>
									<td colspan="9" class="text-right">Total Real</td>
									<td>0.00%</td>
								</tr>
								<!-- Resto de las filas -->
							</tbody>
						</table>		
					@endif
					@if ($objetivos->count() == 0)
						{{-- <div class="alert alert-info" role="alert">
							No hay objetivos registrados
						</div>						 --}}
					@else
						
					<table class="table table-striped table-hover table-sm">
						<thead class="thead">
							<tr> 
								<th>#</th> 
								<th>Metas</th>
								<th>Tipo Objetivo</th>
								<th>Resultado</th>
								<th>Evidencia</th>
								<th>Fecha de creación</th>
								<th>Fecha de modificación</th>
								{{-- <th>Evaluado Id</th>
								<th>Evaluador Id</th> --}}
																
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
								<td>{{ $row->evidencia }}</td>
								<td>{{ date_format($row->created_at,'d-m-Y h:i:s a') }}</td>
								<td>{{ date_format($row->updated_at,'d-m-Y h:i:s a') }}</td>
								{{-- <td>{{ $row->evaluado_id }}</td>
								<td>{{ $row->evaluador_id }}</td> --}}
																
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
					@endif
					
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
