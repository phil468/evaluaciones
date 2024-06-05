@section('title', __('Objetivos Precargados'))
<div class="container-fluid">
	<div class="row justify-content-center">
		<div class="col-md-12">
            <div class="rounded-2xl card">
                <div class="text-white card-header bg-vanguard rounded-t-xl">
					<div style="display: flex; justify-content: space-between; align-items: center;">
						<div class="float-left">
							<h4 class="h5">Lista Objetivos Precargado </h4>
						</div>
						{{--<div wire:poll.1s>
							<code><h5>{{ now()->format('H:i:s') }}</h5></code>
						</div>--}}
						@if (session()->has('message'))
						<div wire:poll.4s class="btn btn-sm btn-success rounded-xl" style="margin-top:0px; margin-bottom:0px;"> {{ session('message') }} </div>
						@endif
						{{-- <div>
							<input wire:model='keyWord' type="text" class="form-control" name="search" id="search" placeholder="Buscar">
						</div> --}}
						@can('crear-objetivos-precargados')
						<a title="Nuevo" data-toggle="modal" data-target="#updateModal" class="btn btn-sm btn-default rounded-xl" wire:click="edit(0)">

						{{-- <a class="btn btn-sm btn-default rounded-xl" data-toggle="modal" data-target="#createDataModal" wire:click="create()"> --}}
						<i class="fa fa-plus"></i>  Nuevo
						</a>
						@endcan
					</div>
				</div>
				
				<div class="card-body">
						{{-- @can('crear-objetivos-precargados')
						@include('livewire.objetivos-precargados.create')
						@endcan						 --}}
						@can('editar-objetivos-precargados')
							@include('livewire.objetivos-precargados.update')
						@endcan
				<div class="table-responsive">
					<table class="table table-striped table-hover table-sm">
						<thead class="thead">
							<tr class="text-center"> 
								<th>#</th> 
								<th>Tipo de Jerarquia</th>
								<th>Grupal</th>
								<th>Meta</th>
								<th>% De Participación</th>
								{{-- <th>Evidencias</th> --}}
								<th>Tipo de Objetivo</th>
								<th>Resultado Anterior/Esperado</th>
								<th>% Mínimo</th>
								<th>Mínimo</th>
								<th>% Máximo</th>
								<th>Máximo</th>
								{{-- <th>Valor</th> --}}
								{{-- <th>Porcentaje De Logro Sti</th> --}}
								{{-- <th>Peso Ponderado</th> --}}
								<th>Evaluación</th>
																
								@can('editar-objetivos-precargados','borrar-objetivos-precargados')
								<th>ACCIONES</th>								
								@endcan
							</tr>
						</thead>
						<tbody>
							@foreach($objetivosPrecargados as $row)
							<tr class="text-center">
								<td>{{ $loop->iteration }}</td> 
								<td>{{ 'TIPO '.$row->tipo_de_jerarquia_id  }}</td>
								<td>{{ $row->grupal? 'Sí' : 'No' }}</td>
								<td @class(['table-secondary' => !($row->grupal)])>{{ $row->meta }}</td>
								<td>{{ $row->porcentaje_de_participacion}}%</td>
								{{-- <td>{{ $row->evidencias }}</td> --}}
								<td @class(['table-secondary' => !($row->grupal)])>
									{{ $row->tipo_objetivo ? $row->tipo_objetivo->unidad.'('.$row->tipo_objetivo->simbolo.')' : '' }}
								</td>
								<td @class(['table-secondary' => !($row->grupal)])>
									{{ 
										$row->tipo_objetivo ? 
											($row->tipo_objetivo->id == 2 ? 
												($row->resultado_anterior_o_esperado).'%' 
											: 	($row->tipo_objetivo->id == 1 ? 
													number_format($row->resultado_anterior_o_esperado, 2, '.', ',')
												: $row->resultado_anterior_o_esperado)
												)
										: $row->resultado_anterior_o_esperado 
									}}
								</td>
								<td>{{ $row->evaluacion->minimo }} %</td>
								<td @class(['table-secondary' => !($row->grupal)])>
																	{{ 
										$row->tipo_objetivo ? 
											($row->tipo_objetivo->id == 2 ? 
												($row->minimo).'%' 
											: 	($row->tipo_objetivo->id == 1 ? 
													number_format($row->minimo, 2, '.', ',')
												: $row->minimo)
												)
										: $row->minimo 
									}}
									
								</td>
								<td>{{ $row->evaluacion->maximo }} %</td>
								<td @class(['table-secondary' => !($row->grupal)])>
									{{ 
										$row->tipo_objetivo ? 
											($row->tipo_objetivo->id == 2 ? 
												($row->maximo).'%' 
											: 	($row->tipo_objetivo->id == 1 ? 
													number_format($row->maximo, 2, '.', ',')
												: $row->maximo)
												)
										: $row->maximo 
									}}
									
									{{-- {{ ($row->maximo).'%' }} --}}
									</td>
								{{-- <td>{{ $row->valor }}</td> --}}
								{{-- <td>{{ $row->porcentaje_de_logro_STI }}</td> --}}
								{{-- <td>{{ $row->peso_ponderado }}</td> --}}
								<td>{{ $row->evaluacion->title }}</td>
																
								@can('editar-objetivos-precargados','borrar-objetivos-precargados')
								<td width="90">
								<div class="btn-group">
									@can('editar-objetivos-precargados')
									<a data-toggle="modal" data-target="#updateModal" class="btn btn-sm btn-vanguard rounded-xl" wire:click="edit({{$row->id}})">Editar </a>
									@endcan
									@can('borrar-objetivos-precargados')							 
									<a class="btn btn-sm btn-danger rounded-xl" onclick="confirm('Confirma borrar Objetivos Precargado : {{$row->name}}? \nObjetivos Precargados borrados no pueden ser recuperados!')||event.stopImmediatePropagation()" wire:click="destroy({{$row->id}})"> Borrar </a> 
									@endcan  
								</div>
								</td>
								@endcan
							@endforeach
						</tbody>
					</table>						
					{{ $objetivosPrecargados->links() }}
					</div>
				</div>
                <div wire:loading wire:target="destroy,save">
                    <x-loading-indicator />
                </div>	
			</div>
		</div>
	</div>
</div>
