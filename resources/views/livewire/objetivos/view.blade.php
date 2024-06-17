@section('title', __('Objetivos'))
<div class="container-fluid">
	<div class="row justify-content-center">
		<div class="col-md-12">
            <div class="card rounded-xl">
                <div class="text-white card-header bg-vanguard rounded-t-xl">
					<div style="display: flex; justify-content: space-between; align-items: center;">
						<div class="float-left">
							<h5 class="h5">EVALUACIÓN POR RESULTADOS</h5>
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
						{{-- @include('livewire.objetivos.update') --}}
						@include('livewire.objetivos.update_v2')
						@include('livewire.objetivos.updateValor')
						@include('livewire.objetivos.evidencias')

						@endcan
						
						{{-- @include('livewire.evaluacion.gracias') --}}

                        <div class="row">
                            <div class="col-md-6">
                                <h5 class='h5'>Evaluado:</h5>
                                <p>{{ $evaluado->name ?? 'No identificado' }}</p>
                            </div>
                            
                            <div class="col-md-6">
                                <h5 class='h5'>Cargo:</h5>
                                <p>{{ $evaluador_has_evaluado->cargo_de_evaluado ?? 'No identificado' }}</p>
                            </div>
                        </div>
						@can('ver-evaluaciones-de-desempeno')
						{{-- <div class="float-right">
							@if ($evaluador_has_evaluado->jerarquia == 1 && $primera_fase_activa)
								(Requeridos: {{$cantidad_requerida}} objetivos) 
								
								<button
								title="Nuevo"
								class="mb-4 btn rounded-xl btn-vanguard" 
								wire:click="edit(0)" 
								data-toggle="modal" 
								data-target="#updateModal"
								@if ($objetivos->count() >= $cantidad_requerida)
									disabled
								@endif
								>

								<i class="fa fa-plus"></i> Nuevo
								</button>
								<br>
							@endif
						</div> --}}
						@endcan

						<br>
				<div class="table-responsive">
					@if (1)
						@if ($objetivos->count() == 0)
							<div class="alert alert-info" role="alert">
								No hay objetivos registrados
							</div>
						@else
							
						<table class="table table-striped table-hover table-sm">
							<thead class="thead">
								<tr>
									{{-- <th class="text-center text-white bg-vanguard">#</th>  --}}
									<th class="text-center text-white bg-vanguard">Tipo</th>
									@can('ver-evaluaciones-de-desempeno','borrar-objetivo')
									@if ($primera_fase_activa)
										<th class="text-white bg-vanguard">Editar</th>
									@endif
									@endcan
									{{-- <th>Meta</th> --}}
									{{-- <th>% De Participación</th> --}}
									{{-- <th>Evidencias</th> --}}
									{{-- <th>Tipo de Objetivo</th> --}}
									{{-- <th>Resultado Anterior/Esperado</th> --}}
									{{-- <th>Mínimo</th> --}}
									{{-- <th>Máximo</th> --}}
									{{-- <th>Valor</th> --}}
									{{-- <th>Porcentaje De Logro Sti</th> --}}
									{{-- <th>Peso Ponderado</th> --}}
									{{-- <th>Evaluación</th> --}}
									
									<th class="text-center text-white bg-vanguard">Metas</th>
									<th class="text-center text-white bg-vanguard">% Participac.</th>
									<th class="text-center text-white bg-vanguard">Tipo de Objetivo</th>
									<th class="text-center text-white bg-vanguard">Result. Anterior / Esperado</th>
									<th class="text-center text-white bg-vanguard">Mínimo {{ $evaluador_has_evaluado->evaluacion->minimo}}%</th>
									<th class="text-center text-white bg-vanguard">Máximo {{ $evaluador_has_evaluado->evaluacion->maximo}}%</th>
									{{-- <th class="text-center text-white bg-vanguard">Evaluación</th> --}}
									<th class="text-center text-white bg-vanguard">Valor</th>
									<th class="text-center text-white bg-vanguard">% Logr. STI</th>
									<th class="text-center text-white bg-vanguard">Peso Pond.</th>
									<th class="text-center text-white bg-vanguard">Evidencias</th>
									<th class="text-center text-white bg-vanguard">Estado</th>
									<th class="text-center text-white bg-vanguard">Evaluación</th>
									
									{{-- <th>#</th> 
									<th>Metas</th>
									<th>Tipo Objetivo</th>
									<th>Resultado</th>
									<th>Evidencia</th> --}}
									<th class="text-center text-white bg-vanguard">Fecha de creación</th>
									<th class="text-center text-white bg-vanguard">Fecha de modificación</th>
																	
								</tr>
							</thead>
							<tbody>
								@foreach($objetivos as $index => $row)
								<div wire:key="objetivoss-field-{{ $row->id }}">

								<tr class="text-center">
									

									@if ($row->grupal)
										<td class="bg-info">GRUPAL</td>
									@else
										<td class="bg-primary">INDIVIDUAL</td>
									@endif
									{{-- <td>{{ $loop->iteration }}</td> --}}
									{{-- <td>{{ $row->grupal? 'Sí' : 'No' }}</td> --}}
								
																	
									@can('ver-evaluaciones-de-desempeno','borrar-objetivo')
									@if ($primera_fase_activa)
										<td width="90" class="">
											@if (!$row->grupal)
												<div class="btn-group">
													@can('ver-evaluaciones-de-desempeno')
													<a data-toggle="modal" data-target="#updateModal" class="btn rounded-xl btn-vanguard" wire:click="edit({{$row->id}})">
														{{--icono--}}
														<i class="fa fa-edit"></i>
														{{-- Editar  --}}
													</a>
													@endcan
													{{-- @can('ver-evaluaciones-de-desempeno')							 
													<a class="btn rounded-xl btn-sm btn-danger" onclick="confirm('Confirma borrar Objetivo : {{$row->descripcion}}? \nObjetivos borrados no pueden ser recuperados!')||event.stopImmediatePropagation()" wire:click="destroy({{$row->id}})"> Borrar </a> 
													@endcan   --}}
												</div>
											@else
												
											@endif
										</td>
									@endif
									@endcan
								
								<td 
								{{-- @class(['table-secondary' => !($row->grupal)]) --}}
								>{{ $row->meta }}</td>
								<td>{{ $row->porcentaje_de_participacion}}%</td>

								{{-- @if($isOpen)
								<div wire:ignore.self class="modal fade" id="evidenciaModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="evidenciaModalLabel" aria-hidden="true">
									<div class="modal-dialog modal-xl" role="document">
										<div class="text-white modal-header bg-vanguard rounded-t-2xl">
											<h5 class="h5 modal-title" id="updateModalLabel">
												Cargar Evidencia
											</h5>
											<button type="button" class="text-white close" data-dismiss="modal" aria-label="Close">
												<span wire:click.prevent="cancel()" aria-hidden="true">×</span>
											</button>
										</div>
										<div class="modal-body">
											
											<div class="rounded-2xl modal-content">
												<h2>Cargar Evidencia</h2>
												<form wire:submit.prevent="uploadEvidencia">
													<input type="file" wire:model="evidencia_subir">
													<button type="submit">Subir</button>
												</form>
											</div>
										</div>
									</div>
								</div>
								@endif --}}

								<td 
								{{-- @class(['table-secondary' => !($row->grupal)]) --}}
								>
									{{ $row->tipo_objetivo ? $row->tipo_objetivo->unidad.'('.$row->tipo_objetivo->simbolo.')' : '' }}
								</td>
								<td 
								{{-- @class(['table-secondary' => !($row->grupal)]) --}}
								>
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
								<td>
									{{-- {{ ($row->minimo).'%' }} --}}

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
								<td>
									{{-- {{ ($row->maximo).'%' }} --}}

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
									
								</td>
								
								<td>
									@if ($segunda_fase_activa)
									{{-- QUE SE LEVANTE UN MODAL PARA INGRESAR EL VALOR --}}
									<a type="button" class="btn btn-link" data-toggle="modal" data-target="#actualizarValorModal" wire:click="openModadActualizarValor({{$row->id}})">
										{{ 
										
										
											$row->valor
											?

											($row->tipo_objetivo ? 
											($row->tipo_objetivo->id == 2 ? 
												($row->valor).'%' 
											: 	($row->tipo_objetivo->id == 1 ? 
													number_format($row->valor, 2, '.', ',')
												: $row->valor)
												)
											: $row->valor )


											
											:'Ingresar Valor' 
										}}
									</a>

									@else
										@if ($primera_fase_activa)
											<div class="form-group">
												<input disabled type="number" class="form-control" id="valor" placeholder="Valor" value="{{ $row->valor }}">
											</div>
										@else
												{{ $row->valor }}
										@endif
									@endif
									<div wire:loading wire:target="store_valor({{$row->id}})">
										Actualizando
									</div>
								</td>
								<td>{{ $row->porcentaje_de_logro_STI.'%' }}</td>
								<td>{{ $row->peso_ponderado.'%' }}</td>
								<td>

									@foreach ($row->evidencias()->get() as $evidencia)
										<div class="mb-2 btn-group" role="group" aria-label="Basic example">
											<a href="{{ route('download', $evidencia->id) }}" class="btn btn-link">
												{{ $evidencia->name }}
											</a>
											@if ($segunda_fase_activa)
												<button class="btn btn-danger" wire:click="deleteEvidencia({{$evidencia->id}})" 
													onclick="confirm('Confirma borrar Evidencia : {{$row->name}}? \nLas Evidencias eliminadas no pueden ser recuperados!')||event.stopImmediatePropagation()"
													>
													<i class="fa fa-trash"></i>
												</button>
											@endif
										</div> 
										<br>
									@endforeach

									@if ($segunda_fase_activa)
										<button 
										class="rounded-full btn btn-vanguard" 
										wire:click="openModalEvidencias({{$row->id}})"										
										data-toggle="modal" 
										data-target="#evidenciaModal"
										>
											<i class="fa fa-plus"></i>
										</button>
									@else
										@if ($primera_fase_activa)
											<button 
											disabled
											class="rounded-full btn btn-vanguard" 
											>
												<i class="fa fa-plus"></i>
											</button>
										@endif
									@endif
																		
								</td>

								<td>
										@if (!$row->estado_id)
											<span class="badge badge-danger">No Registrado</span>
										@endif
										@if ($row->estado_id == 1)
											<span class="badge badge-warning">Registrado</span>
										@endif
										@if ($row->estado_id == 2)
											<span class="badge badge-success">Realizado</span>
										@endif
									
								</td>

								<td>{{ $row->evaluacion->title ?? '' }}</td>
								<td>{{ date_format($row->created_at,'d-m-Y h:i:s a') }}</td>
								<td>{{ date_format($row->updated_at,'d-m-Y h:i:s a') }}</td>

								</div>

								@endforeach
							</tbody>
							{{--footer con promedio total que es la suma de promedios--}}
							@if ($segunda_fase_activa)
								<tfoot>
									<tr>
										<td colspan="9" class="text-right">Subtotal</td>
										<td class="text-center text-white bg-vanguard">{{ $subtotal.'%' }}</td>
										<td colspan="5"></td>
									</tr>
									
									<tr>
										<td colspan="9" class="text-right">Total Real</td>
										<td class="text-center text-white bg-vanguard">{{ $total.'%' }}</td>
										<td colspan="5"></td>
									</tr>
								</tfoot>
							@endif
						</table>
						@endif		
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
					</div>
				</div>
                <div wire:loading wire:target="store,update,create,edit,destroy">
                    <x-loading-indicator />
                </div>	
			</div>
		</div>
	</div>
</div>
