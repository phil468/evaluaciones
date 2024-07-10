{{-- @section('title', __('Objetivos')) --}}
<div class="container-fluid">
	<div class="row justify-content-center">
		<div class="col-md-12">
			<div class="accordion" id="accordion">
				
				<div class="card rounded-xl">
					<div class="text-white card-header bg-vanguard rounded-t-xl">
						<div style="display: flex; justify-content: space-between; align-items: center;">
							<div class="float-left">
								@if ($readOnly)
										<button class="text-left btn btn-vanguard btn-block" data-toggle="collapse" data-target="#collapseOne{{$evaluador_has_evaluado->id}}" aria-expanded="true" aria-controls="collapseOne">
											<div class="h5">
												Evaluación de Desempeño por Resultados {{$evaluador_has_evaluado->evaluacion->campania}} <i class="fas fa-caret-down"></i>
											</div>
										</button>
								@else
									<h5 class="h5">Evaluación de Desempeño por Resultados</h5>
								@endif
							</div>

							@if (session()->has('message'))
							<div wire:poll.4s class="btn btn-sm btn-success" style="margin-top:0px; margin-bottom:0px;"> {{ session('message') }} </div>
							@endif

							@if (!$readOnly)
								<div class="float-right">
										<a type="button" class="btn btn-default rounded-xl" href="{{url('/evaluaciones-de-desempeno/2')}}" >Volver</a>
								</div>
							@endif
						</div>
					</div>
					<div id="collapseOne{{$evaluador_has_evaluado->id}}" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">

						<div class="card-body">
							@can('ver-evaluaciones-de-desempeno')
								@include('livewire.objetivos.create')
							@endcan						
							@can('ver-evaluaciones-de-desempeno')
								@include('livewire.objetivos.update_v2')
								@include('livewire.objetivos.updateValor')
								@include('livewire.objetivos.evidencias')
							@endcan
							
							<div class="row">
								<div class="col-md-6">
									<h5 class='h5'>Personal:</h5>
									<p>{{ $evaluado->name ?? 'No identificado' }}</p>
								</div>
								
								<div class="col-md-6">
									<h5 class='h5'>Cargo:</h5>
									<p>{{ $evaluador_has_evaluado->cargo_de_evaluado ?? 'No identificado' }}</p>
								</div>
							</div>
							@can('ver-evaluaciones-de-desempeno')

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
													@if ($primera_fase_activa && !$readOnly)
														<th class="text-white bg-vanguard">Editar</th>
													@endif
													@endcan
													
													<th class="text-center text-white bg-vanguard">Metas</th>
													<th class="text-center text-white bg-vanguard">% Participac.</th>
													<th class="text-center text-white bg-vanguard">Tipo de Objetivo</th>
													<th class="text-center text-white bg-vanguard">Result. Anterior / Esperado</th>
													{{-- <th class="text-center text-white bg-vanguard">Mínimo {{ $evaluador_has_evaluado->evaluacion->minimo}}%</th> --}}
													{{-- <th class="text-center text-white bg-vanguard">Máximo {{ $evaluador_has_evaluado->evaluacion->maximo}}%</th> --}}
													{{-- <th class="text-center text-white bg-vanguard">Evaluación</th> --}}
													<th class="text-center text-white bg-vanguard">Valor</th>
													<th class="text-center text-white bg-vanguard">% Logr. STI</th>
													<th class="text-center text-white bg-vanguard">Peso Pond.</th>
													<th class="text-center text-white bg-vanguard">Evidencias</th>
													<th class="text-center text-white bg-vanguard">Estado</th>
													<th class="text-center text-white bg-vanguard">Evaluación</th>
													
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
																				
													@can('ver-evaluaciones-de-desempeno','borrar-objetivo')
														@if ($primera_fase_activa && !$readOnly)
															<td width="90" class="">
																@if (!$row->grupal)
																	<div class="btn-group">
																		@can('ver-evaluaciones-de-desempeno')
																			<a data-toggle="modal" data-target="#updateModal" class="btn rounded-xl btn-vanguard" wire:click="edit({{$row->id}})">
																				<i class="fa fa-edit"></i>
																			</a>
																		@endcan
																	</div>
																@else
																	
																@endif
															</td>
														@endif
													@endcan
												
												<td 
												>{{ $row->meta }}</td>
												<td>{{ $row->porcentaje_de_participacion}}%</td>

												<td>
													{{ $row->tipo_objetivo ? $row->tipo_objetivo->unidad.'('.$row->tipo_objetivo->simbolo.')' : '' }}
												</td>
												<td>
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
												{{-- <td>
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
												</td> --}}
												
												<td>
													{{-- @if ($segunda_fase_activa && !$readOnly) --}}
													{{-- QUE SE LEVANTE UN MODAL PARA INGRESAR EL VALOR --}}
													
													@if ($segunda_fase_activa && !$readOnly)
														<button 
														type="button" 
														class="btn btn-link" 
														data-toggle="modal" 
														data-target="#actualizarValorModal" 
														wire:click="openModadActualizarValor({{$row->id}})">
													@endif
														{{ 
															$row->valor ?
																( $row->tipo_objetivo ? 
																	($row->tipo_objetivo->id == 2 ? 
																		($row->valor).'%' 
																	: 	($row->tipo_objetivo->id == 1 ? 
																			number_format($row->valor, 2, '.', ',')
																		: $row->valor)
																	)
																: $row->valor )
															: (($segunda_fase_activa && !$readOnly) ? 'Ingresar Valor' : '')
														}}
														
													@if ($segunda_fase_activa && !$readOnly)
														</button>
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
															@if ($segunda_fase_activa && !$readOnly)
																<button class="btn btn-danger" wire:click="deleteEvidencia({{$evidencia->id}})" 
																	onclick="confirm('¿Confirma borrar Evidencia : {{$row->name}}? \n¡Las Evidencias eliminadas no pueden ser recuperadas!')||event.stopImmediatePropagation()"
																	>
																	<i class="fa fa-trash"></i>
																</button>
															@endif
														</div> 
														<br>
													@endforeach

													@if ($segunda_fase_activa && !$readOnly)
														<button 
														class="rounded-full btn btn-vanguard" 
														wire:click="openModalEvidencias({{$row->id}})"										
														data-toggle="modal" 
														data-target="#evidenciaModal">
															<i class="fa fa-plus"></i>
														</button>
													@else
														@if ($primera_fase_activa && !$readOnly)
															<button 
															disabled
															class="rounded-full btn btn-vanguard">
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
											{{-- {{dd($row->evaluacion->segunda_fase_iniciada)}} --}}
											@if ($row->evaluacion->segunda_fase_iniciada)
												<tfoot>
													<tr>
														<td colspan="7" class="text-right">Subtotal</td>
														<td class="text-center text-white bg-vanguard">{{ $subtotal.'%' }}</td>
														<td colspan="3"></td>
													</tr>
													
													<tr>
														<td colspan="7" class="text-right">Total Real</td>
														<td class="text-center text-white bg-vanguard">{{ $total.'%' }}</td>
														<td colspan="3"></td>
													</tr>
												</tfoot>
											@endif
										</table>
									@endif		
								@endif
								
								@if (!$readOnly)
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
								@endif
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
</div>
