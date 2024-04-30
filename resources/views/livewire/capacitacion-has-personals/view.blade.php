@section('title', __('Capacitacion Has Personals'))
<div class="container-fluid">
	<div class="row justify-content-center">
		<div class="col-md-12">
			<div class="card rounded-xl">
                <div class="text-white card-header bg-vanguard rounded-t-xl">
					<div style="display: flex; justify-content: space-between; align-items: center;">
						<div class="float-left">
							<h5 class="h5">PERSONAL INSCRITO</h4>
						</div>
						{{--<div wire:poll.1s>
							<code><h5>{{ now()->format('H:i:s') }}</h5></code>
						</div>--}}
						@if (session()->has('message'))
						<div wire:poll.4s class="btn btn-sm btn-success" style="margin-top:0px; margin-bottom:0px;"> {{ session('message') }} </div>
						@endif
						{{-- <div class="btn btn-sm btn-default" data-toggle="modal" data-target="#createDataModal">
							<i class="fa fa-plus"></i>  Nuevo
						</div> --}}
						<a class="btn btn-sm btn-default" href={{route('capacitaciones')}}>
							<i class="fa fa-arrow-left"></i>
							Volver a la lista
						</a>
						{{-- <div>
							<input wire:model='keyWord' type="text" class="form-control" name="search" id="search" placeholder="Buscar">
						</div> --}}
						@can('crear-capacitacionHasPersonal')
						<div class="btn btn-sm btn-default" data-toggle="modal" data-target="#createDataModal">
						<i class="fa fa-plus"></i>  Nuevo
						</div>
						@endcan
					</div>
				</div>
				
				<div class="card-body">
						{{-- @can('crear-capacitacionHasPersonal')
						@include('livewire.capacitacionHasPersonals.create')
						@endcan --}}

												
						@can('editar-capacitacion')
						@include('livewire.capacitacion-has-personals.update-registro')
						@endcan

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
						<div class="h4">PERSONAS INSCRITAS</div>
						
				{{-- @livewire('registros-table', ['exportable' => false,'listaParaAgregar' => true]) --}}
				@livewire('registros-table', ['exportable' => false,'capacitacion_id' => $capacitacion_id])
						{{-- {{dd(count($capacitacionHasPersonals))}} --}}
						{{-- @if (isset($capacitacionHasPersonals) && count($capacitacionHasPersonals))
						<div class="table-responsive">
							<table class="table table-striped table-hover table-sm">
								<thead class="thead">
									<tr> 
										<th>#</th> 
										<th>Personal</th>
										@can('editar-capacitacion')
											<th>Botón Quitar</th>
										@endauth
									</tr>
								</thead>
								<tbody>
									
									@foreach($capacitacionHasPersonals as $row)
									<tr>
										<td>{{ $loop->iteration }}</td> 
										<td>{{ $row->personal->name}}</td>
										@can('editar-capacitacion')

										<td>
										<a class="btn btn-sm btn-danger" 
											onclick="confirm('Confirma quitar Personal : {{$row->personal->name}}? \nPersonal borrados no pueden ser recuperados!')||event.stopImmediatePropagation()" wire:click="quitarAsistente({{$row->id}})"> 
											Quitar 
										</a>
										</td>
										@endcan
									@endforeach
								</tbody>
							</table>						
						</div>
						@endif --}}

						@can('editar-capacitacion')
				<br>
				<div class="h4">SELECCIONE PERSONAS PARA AGREGAR A LA CAPACITACIÓN</div>
				<button title="Agregar Seleccionados" 
				class="btn btn-primary"
				@if (count($selectedFromPersonalTable) == 0)
				disabled
				@endif
				wire:click="agregarSeleccionados()">
					<i class="fa fa-plus"></i> Agregar Seleccionados 
					
					@if (count($selectedFromPersonalTable) != 0)
					<span class="badge badge-light">{{count($selectedFromPersonalTable)}}</span>

					@endif
				</button>
				<br>
				<br>
		@endcan
						{{-- <div class="h5">SELECCIONE LAS PERSONAS A AGREGAR</div> 							 --}}
						@can('editar-capacitacion')

				@livewire('personal-table', ['exportable' => false,'listaParaAgregar' => true])
				@endcan
				
				</div>				


			</div>
		</div>
	</div>
	@push('js')
	<script>
		document.addEventListener('livewire:load', function () {
			console.log('Inicializar Choice.js');
			
			const opciones = {
				removeItemButton: true,
				itemSelectText: 'Seleccione',
				searchPlaceholderValue: 'Buscar',
				placeholderValue: 'Selecciona una opción',
				placeholder: true, // Activa el placeholder			
				allowHTML: false,
				shouldSort: false,
				noResultsText: 'Resultados no encontrados',
    			searchResultLimit: 10,
    			searchFields: ['label'],
			}

			const placeholder = [
						{ value: '', label: ' - Seleccione - '},
					];
	
			// const personal_id_select = new Choices('#personal_id', opciones);
			// personal_id_select.passedElement.element.addEventListener('change', function (event) {
			// 		dato = personal_id_select.getValue(true) !== undefined ? personal_id_select.getValue(true) : '' ;
			// 		@this.set('personal_id', dato );
			// 		deshabilitarDatosPersonal();
			// });
			
			// const empresa_id_select = new Choices('#empresa_id', opciones);
			// empresa_id_select.passedElement.element.addEventListener('change', function (event) {
			// 	// console.log(empresa_id_select.getValue(true));
			// 	if (empresa_id_select.getValue(true) !== undefined) {
			// 		@this.set('empresa_id', empresa_id_select.getValue(true));
			// 	} else {
			// 		@this.set('empresa_id', null);
			// 	}
			// });
			
			const gerencia_id_select = new Choices('#gerencia_id', opciones);
			gerencia_id_select.passedElement.element.addEventListener('change', function (event) {
				if (gerencia_id_select.getValue(true) !== undefined) {
					@this.set('gerencia_id', gerencia_id_select.getValue(true));
				} else {
					@this.set('gerencia_id', null);
				}
			});
			
			const sede_id_select = new Choices('#sede_id', opciones);
			sede_id_select.passedElement.element.addEventListener('change', function (event) {
				if (sede_id_select.getValue(true) !== undefined) {
					@this.set('sede_id', sede_id_select.getValue(true));
				} else {
					@this.set('sede_id', null);
				}
			});
			
			const area_id_select = new Choices('#area_id', opciones);
			area_id_select.passedElement.element.addEventListener('change', function (event) {
				if (area_id_select.getValue(true) !== undefined) {
					@this.set('area_id', area_id_select.getValue(true));
				} else {
					@this.set('area_id', null);
				}
			});
			
			// const cargo_id_select = new Choices('#cargo_id', opciones);
			// cargo_id_select.passedElement.element.addEventListener('change', function (event) {
			// 	if (cargo_id_select.getValue(true) !== undefined) {
			// 		@this.set('cargo_id', cargo_id_select.getValue(true));
			// 	} else {
			// 		@this.set('cargo_id', null);
			// 	}
			// });
			
			Livewire.on('actualizarDatosP', function (
				// personal_id,
				// empresa_id,
				gerencia_id,sede_id,area_id,
				// cargo_id
				) 
				{
				habilitarDatosPersonal();
	// console.log(sede_id);
				// personal_id_select.setChoiceByValue(personal_id ?? '');
				// empresa_id_select.setChoiceByValue(empresa_id ?? '');
				gerencia_id_select.setChoiceByValue(gerencia_id ?? '');
				sede_id_select.setChoiceByValue(sede_id ?? '');
				area_id_select.setChoiceByValue(area_id ?? '');				
				// cargo_id_select.setChoiceByValue(cargo_id ?? '');
			}
			);
			
			// Livewire.on('actualizarDatosR', function (personal_id,area_id,cargo_id) {
			// 	habilitarDatosResponsable();
	
			// 	responsable_id_select.setChoiceByValue(personal_id ?? '');
			// 	responsable_area_id_select.setChoiceByValue(area_id ?? '');				
			// 	responsable_cargo_id_select.setChoiceByValue(cargo_id ?? '');
			// });
	
			Livewire.on('listar_selects', function (
				// personal,
			// empresas,
			gerencias,sedes,areas,
			// cargos
			// ,responsables
			) {

				deshabilitarDatosPersonal();	
				
				limpiarDatosPersonal();
				
				// personal_id_select.setChoices(personal);
				// empresa_id_select.setChoices(empresas);
				gerencia_id_select.setChoices(gerencias);
				sede_id_select.setChoices(sedes);
				area_id_select.setChoices(areas);
				// cargo_id_select.setChoices(cargos);
				// responsable_id_select.setChoices(responsables);
				// responsable_area_id_select.setChoices(areas);
				// responsable_cargo_id_select.setChoices(cargos);
				
			});

			Livewire.on('limpiarDatosP', function () {
					limpiarDatosPersonal();
			});

			const limpiarDatosPersonal = () => {
				
				// empresa_id_select.clearChoices();
				gerencia_id_select.clearChoices();
				sede_id_select.clearChoices();
				area_id_select.clearChoices();
				// cargo_id_select.clearChoices();

				// empresa_id_select.clearStore();
				gerencia_id_select.clearStore();
				sede_id_select.clearStore();
				area_id_select.clearStore();
				// cargo_id_select.clearStore();
				
				// empresa_id_select.setChoices(placeholder);
				gerencia_id_select.setChoices(placeholder);
				sede_id_select.setChoices(placeholder);
				area_id_select.setChoices(placeholder);
				// cargo_id_select.setChoices(placeholder);

				// empresa_id_select.unhighlightAll();
			}
	
			const deshabilitarDatosPersonal = () => {
				// personal_id_select.disable();
				// empresa_id_select.disable();
				gerencia_id_select.disable();
				sede_id_select.disable();
				area_id_select.disable();
				// cargo_id_select.disable();
			};
			
			// const deshabilitarDatosResponsable = () => {
			// 	responsable_id_select.disable();
			// 	responsable_area_id_select.disable();
			// 	responsable_cargo_id_select.disable();
			// };
			
			const habilitarDatosPersonal = () => {
				// personal_id_select.enable();
				// empresa_id_select.enable();
				gerencia_id_select.enable();
				sede_id_select.enable();
				area_id_select.enable();
				// cargo_id_select.enable();
			};
			
			// const habilitarDatosResponsable = () => {
			// 	responsable_id_select.enable();
			// 	responsable_area_id_select.enable();
			// 	responsable_cargo_id_select.enable();
			// };
		})
	</script>
	@endpush
</div>
