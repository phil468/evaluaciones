@section('title', __('Asistenciums'))
<div class="container-fluid">
	<div class="row justify-content-center">
		<div class="col-md-12">
			<div class="card rounded-xl">
                <div class="text-white card-header bg-vanguard rounded-t-xl">
					<div style="display: flex; justify-content: space-between; align-items: center;">
						<div class="float-left">
							<h5 class="h5">
								Asistencia
							</h4>
						</div>
						{{--<divwire:poll.1s>
							<code><h5>{{ now()->format('H:i:s') }}</h5></code>
						</divwire:poll.1s>--}}
						@if (session()->has('message'))
						<div wire:poll.4s class="btn btn-sm btn-success" style="margin-top:0px; margin-bottom:0px;"> {{ session('message') }} </div>
						@endif
						
						<a class="btn btn-sm btn-default" href={{route('capacitaciones')}}>
							<i class="fa fa-arrow-left"></i>
							Volver a la lista
						</a>
						
						{{-- <div>
							<input wire:model='keyWord' type="text" class="form-control" name="search" id="search" placeholder="Buscar">
						</div> --}}
						{{-- @can('crear-asistencia')
						<div class="btn btn-sm btn-default" data-toggle="modal" data-target="#createDataModal">
						<i class="fa fa-plus"></i>  Nuevo
						</div>
						@endcan --}}
					</div>
				</div>
				
				<div class="card-body">
					<div class="table-responsive">
						<table class="table table-striped table-hover table-sm">
							<thead class="thead">
								<tr> 
									{{-- <th>ID</th>  --}}
									<th>TIPO</th>
									<th>FECHA</th>
									<th>EMPRESA</th>
									<th>MODALIDAD</th>
									<th>EXPOSITOR</th>
									<th>SEDE</th>
									<th>REGISTRADOR</th>
									<th>SESIONES</th>
								</tr>
							</thead>
							<tbody>
								@foreach($capacitacion as $row)
								<tr>
									{{-- {{dd($capacitacion)}} --}}
									{{-- <td>{{ $row['id'] }}</td> --}}
									<td>{{ $row['tipo_capacitacion']['name'] }}</td>
									<td>{{ $row['fecha_capacitacion'] }}</td>
									<td>{{ $row['empresa']['name'] }}</td>
									<td>{{ $row['modalidad']['name'] }}</td>
									<td>
										@if ($row['expositor_externo'])
											{{$row['nombre_expositor_externo']}}
										@else
											{{ $row['expositor']['name'] ??'' }}
										@endif											
									 </td>
									<td>{{ $row['sede']['name'] }}</td>
									<td>{{ $row['registrador']['name'] }}</td>
									<td>{{ $row['cantidad_de_sesiones'] }}</td>
								</tr>
								@endforeach
							</tbody>
						</table>						
					</div>

					<div class="row">
						<div class="form-group col-sm-8 col-md-6 col-lg-6 col-xl-4">
							<label class="" for="numero_de_sesion">Sesión</label>
							<div class="input-group">
								<select class="form-control" name="numero_sesion_id" id="numero_sesion_id" wire:model="numero_sesion_id">
									<option value="0">Seleccione</option>
									@for ($i = 1; $i <= $capacitacion[0]['cantidad_de_sesiones']; $i++)
										<option value="{{$i}}">{{$i}}</option>
									@endfor
								</select>								
								<a wire:click="agregarSesion()" type="button" class="btn btn-primary"
								onclick="confirm('Confirma agregar Sesion? \nSesiones agregadas no pueden ser eliminadas!')||event.stopImmediatePropagation()"
								>
									<i class="fa fa-plus"></i>Agregar Sesion
								</a>
							</div>
						</div>
						@if ($numero_sesion_id>0)
							<div class="form-group col-sm-4 col-md-3 col-lg-3 col-xl-2">
								<label for="fecha">Fecha</label>
								<input wire:model="fecha" type="date" class="form-control" id="fecha" placeholder="Fecha">@error('fecha') <span class="error text-danger">{{ $message }}</span> @enderror
							</div>
							<div class="form-group col-sm-4 col-md-3 col-lg-3 col-xl-2">
								<label for="hora_inicio">Hora Inicio</label>
								<input wire:model.defer="hora_inicio" type="time" class="form-control" id="hora_inicio" placeholder="Hora Inicio">@error('hora_inicio') <span class="error text-danger">{{ $message }}</span> @enderror
							</div>
							<div class="form-group col-sm-4 col-md-3 col-lg-3 col-xl-2">
								<label for="hora_fin">Hora Fin</label>
								<input wire:model.defer="hora_fin" type="time" class="form-control" id="hora_fin" placeholder="Hora Fin">@error('hora_fin') <span class="error text-danger">{{ $message }}</span> @enderror
							</div>
						@endif
					</div>
					@if ($numero_sesion_id>0)
							<div class="row">
							<div class="form-group col-xs-12">
								<label for="dni_search">Buscar por DNI</label>
								<div>
									<div class="input-group">
									<input type="text"
									name="dni_search" 
									wire:model.defer="dni_search"
									inputmode="numeric" 
									wire:keydown.enter='buscar_dni' 
									wire:keydown.tab="buscar_dni"
									wire:keydown.arrow-right="buscar_dni"
									id="dni_search" class="form-control"
									placeholder="DNI" 
									autofocus>
									<a wire:click="buscar_dni()" type="button" class="btn btn-primary"><i class="fas fa-search"></i></a>
									</div>								
								</div>
								@error('dni_search') <span class="error text-danger">{{ $message }}</span> @enderror
							</div>

							<div class="form-group col-sm-4 col-md-3 col-lg-3 col-xl-2">
								<label for="">Filtro Asistencia</label>
								<div class="form-row">
									<a type="" class="btn btn-md {{$filtro_asistencia ? 'btn-success text-white' : 'btn-default text-success' }}" wire:click="filtro_asistencia()">
										<i class="fas fa-check-circle"></i>
									</a>
									<a type="" class="btn btn-md {{$filtro_no_asistencia ? 'btn-success' : 'btn-default text-success' }}" wire:click="filtro_no_asistencia()">
										<i class="far fa-circle"></i>
									</a>
								</div>
							</div>
							
							<div class="table-responsive">
								<table class="table table-striped table-hover table-sm">
									<thead class="thead">
										<tr>
											{{-- <th>#</th>  --}}
											{{--<th>Sesion</th>--}}
											<th class="border-0"></th>
											<th>DNI</th>
											<th>Personal</th>
											<th>Empresa</th>
											<th>Sede</th>
											<th>Gerencia</th>
											<th>Area</th>
											<th>Cargo</th>
											<th>Planilla</th>
											<th>Tipo De Trabajador</th>
											<th>Tipo De Personal</th>
											{{-- <th>Capacitacion</th> --}}
											{{-- <th>Observaciones</th> --}}
																			
											{{-- @can('editar-asistencia','borrar-asistencia')
											<th>ACCIONES</th>								
											@endcan --}}
										</tr>
									</thead>
									<tbody>
										@foreach($asistencia as $row)
										{{-- {{dd($row->capacitacion_has_personal->personal->dni)}} --}}
										<tr
										@if (($filtro_asistencia == $filtro_no_asistencia))
										@else
											@if ( 	(!($row->active) && $filtro_asistencia) 
													|| 
													(($row->active) && $filtro_no_asistencia)
												)
												style="display: none;"
											@endif
											
										@endif
										
										> 
											{{-- <td>{{ $loop->iteration }}</td>  --}}
											{{-- <td>{{ $row->sesion->numero_de_sesion }}</td>  --}}
											
											
											<td class="bg-white border-0">
												@if ($row->active)
												<span class="text-success">										
													<i class="fas fa-check-circle"></i> 
												</span>
												@else
												<span class="text-success">
													<i class="far fa-circle"></i>  
												</span>
												@endif
											</td>
											<td>{{ $row->capacitacion_has_personal->personal->dni??'' }}</td>
											<td>{{ $row->capacitacion_has_personal->personal->name??'' }}</td>
											<td>{{ $row->capacitacion_has_personal->empresa->name??'' }}</td>
											<td>{{ $row->capacitacion_has_personal->sede->name??'' }}</td>
											<td>{{ $row->capacitacion_has_personal->gerencia->name??'' }}</td>
											<td>{{ $row->capacitacion_has_personal->area->name??'' }}</td>
											<td>{{ $row->capacitacion_has_personal->cargo->name??'' }}</td>
											<td>{{ $row->capacitacion_has_personal->planilla->name??'' }}</td>
											<td>{{ $row->capacitacion_has_personal->tipo_de_trabajador->name??'' }}</td>
											<td>{{ $row->capacitacion_has_personal->tipo_de_personal->name??'' }}</td>
											{{-- <td>{{ $row->capacitacion->name??'' }}</td> --}}
											{{-- <td>{{ $row->observaciones }}</td> --}}
																			
											{{-- @can('editar-asistencia','borrar-asistencia')
											<td width="90">
											<div class="btn-group">
												@can('editar-asistencia')
												<a data-toggle="modal" data-target="#updateModal" class="btn btn-sm btn-vanguard rounded-xl" wire:click="edit({{$row->id}})">Editar </a>
												@endcan
												@can('borrar-asistencia')							 
												<a class="btn btn-sm btn-danger" onclick="confirm('Confirma borrar Asistencium : {{$row->name}}? \nAsistenciums borrados no pueden ser recuperados!')||event.stopImmediatePropagation()" wire:click="destroy({{$row->id}})"> Borrar </a> 
												@endcan  
											</div>
											</td>
											@endcan --}}
											
										@endforeach
									</tbody>
								</table>
							</div>
						@endif
					</div>
					
						{{-- @can('crear-asistencia')
						@include('livewire.asistencia.create')
						@endcan						
						@can('editar-asistencia')
						@include('livewire.asistencia.update')
						@endcan --}}					
						{{-- @can('editar-asistencia') --}}
							@include('livewire.asistenciums.confirmarIngresoDNI')
						{{-- @endcan --}}
				</div>
			
				<div wire:loading wire:target="importar,exportar,create,edit,destroy,generarPdf,store,update,buscar_dni,numero_sesion_id,agregarSesion">
					<x-loading-indicator />
				</div>
			</div>
		</div>
	</div>
</div>
