@section('title', __('Personals'))
<div class="container-fluid">
	<div class="row justify-content-center">
		<div class="col-md-12">
			@php
				$campaniaActual = \App\Models\Campania::where('es_campania_actual', true)->first();
				$evaluaciones = $campaniaActual
					? \App\Models\Evaluacione::where('campania_id', $campaniaActual->id)->get()
					: collect();
			@endphp


			@if($campaniaActual)
				{{-- Mostrar información de la campaña actual --}}
				{{-- Si no hay campaña actual, no se muestra nada --}}
				{{-- Si no hay evaluaciones, no se muestra la lista --}}
				{{-- Si hay evaluaciones, se muestra la lista --}}
				<div class="mb-3 card">
					<div class="text-white card-header bg-primary">
						<strong>Campaña Actual:</strong> {{ $campaniaActual->name }}
					</div>
					<div class="card-body">
						<p><strong>Fecha de creación:</strong> {{ $campaniaActual->created_at }}</p>
						<p><strong>Estado:</strong> {{ $campaniaActual->status ? 'Activo' : 'Inactivo' }}</p>
						<h6>Evaluaciones relacionadas:</h6>
						<ul>
							@foreach($evaluaciones as $ev)
								<li>
									<strong>{{ $ev->nombre_para_mostrar }}</strong>
									(Fecha de corte: {{ $ev->fecha_corte }})
								</li>
							@endforeach
						</ul>
					</div>
				</div>
			@endif

			<div class="card rounded-xl">
                <div class="text-white card-header bg-vanguard rounded-t-xl">
					<div style="display: flex; justify-content: space-between; align-items: center;">
						<div class="float-left">
							<h5 class="h5">Lista Personal </h4>
						</div>
						{{--<div wire:poll.1s>
							<code><h5>{{ now()->format('H:i:s') }}</h5></code>
						</div>--}}
						@if (session()->has('message'))
						<div wire:poll.4s class="btn btn-sm btn-success" style="margin-top:0px; margin-bottom:0px;"> {{ session('message') }} </div>
						@endif
						
						<div class="bottom-0 right-0 p-3 position-fixed z-index-3" style="z-index: 1; right: 0; bottom: 6em; opacity: 0.90;">
							@if (session()->has('message-success'))
							<div class="alert alert-success alert-dismissible fade show">
								<span>{{ session('message-success') }}</span>
								<button type="button" class="close" data-dismiss="alert" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							@endif
						</div>

						<div class="bottom-0 right-0 p-3 position-fixed z-index-3" style="z-index: 1; right: 0; bottom: 6em; opacity: 0.90;">
							@if (session()->has('message-warning'))
							<div class="alert alert-warning alert-dismissible fade show">
								<span>{{ session('message-warning') }}</span>
								<button type="button" class="close" data-dismiss="alert" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							@endif
						</div>
						
						<div class="bottom-0 right-0 p-3 position-fixed z-index-3" style="z-index: 1; right: 0; bottom: 6em; opacity: 0.90;">
							@if (session()->has('message-danger'))
							<div class="alert alert-danger alert-dismissible fade show">
								<span>{{ session('message-danger') }}</span>
								<button type="button" class="close" data-dismiss="alert" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							@endif
						</div>

						<div class="bottom-0 right-0 p-3 position-fixed z-index-3" style="z-index: 1; right: 0; bottom: 6em; opacity: 0.90;">
							@if (session()->has('message-info'))
							<div class="alert alert-info alert-dismissible fade show">
								<span>{{ session('message-info') }}</span>
								<button type="button" class="close" data-dismiss="alert" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							@endif
						</div>

						{{-- <div>
							<input wire:model='keyWord' type="text" class="form-control" name="search" id="search" placeholder="Buscar">
						</div> --}}
						<div class="d-none d-sm-block">
							@can('crear-personal')
							<a title="Nuevo" data-toggle="modal" data-target="#updateModal" class="btn btn-sm btn-default" wire:click="edit(0)">
								<i class="fa fa-plus"></i>Nuevo </a>
							{{-- <div class="btn btn-sm btn-default" wire:click="create()" data-toggle="modal" data-target="#createDataModal">
							<i class="fa fa-plus"></i>  Nuevo
							</div> --}}
							@endcan
							@can('editar-personal')
								@if (count($selectedFromPersonalTable))
									{{-- <a title="Edición Masiva" data-toggle="modal" data-target="#edicionMasivaModal" class="btn btn-sm btn-default" wire:click="editMasiva()">
									<i class="fa fa-plus"></i>Editar Seleccionados </a>								 --}}
								@endif
							@endcan
							@can('importar-personal')
							{{-- <div class="btn btn-sm btn-default" data-toggle="modal" data-target="#importDataModal">
							<i class="fa fa-file-import"></i>Importar
							</div> --}}
							@endcan						
							{{-- <div class="btn btn-sm btn-default" wire:click="exportar" wire:loading.attr="disabled">
								<i class="fas fa-file-export"></i>
								Exportar Todo
							</div> --}}
						</div>
						<div class="float-right d-block d-sm-none">
							
							@can('crear-personal')
							<a title="Nuevo" data-toggle="modal" data-target="#updateModal" class="float-right btn btn-sm btn-default" wire:click="edit(0)">
								<i class="fa fa-plus"></i> </a>
							{{-- <div class="float-right btn btn-sm btn-default" wire:click="create()" data-toggle="modal" data-target="#updateModal">
							<i class="fa fa-plus"></i>  
							</div> --}}
							@endcan
							@can('importar-personal')
							{{-- <div title="Importar"  class="float-right btn btn-sm btn-default" data-toggle="modal" data-target="#importDataModal">
							<i class="fa fa-file-import"></i>  
							</div> --}}
							@endcan						
							{{-- <div title="Exportar Todo"  class="float-right btn btn-sm btn-default" wire:click="exportar" wire:loading.attr="disabled">
								<i class="fas fa-file-export"></i>
								
							</div> --}}
						</div>
					</div>
				</div>
				
				<div class="card-body">
						@can('crear-personal')
							{{-- @include('livewire.personals.create') --}}
						@endcan
						@can('editar-personal')
							@include('livewire.personals.update')
						@endcan
						@can('importar-personal')
							@include('livewire.personals.importar')
						@endcan
					<div class="mb-2">
						<button class="btn btn-success" id="exportarSeleccionadosCampania">
							<i class="fas fa-upload"></i> Exportar seleccionados a Campaña Actual
						</button>
					</div>
					@livewire('personal-table')
					{{-- @livewire('personal-table-preseleccionados') --}}
				</div>
				
				<div wire:loading wire:target="importar,exportar,create,edit,destroy,buscar_dni">
					<x-loading-indicator/>
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
			
			const empresa_id_select = new Choices('#empresa_id', opciones);
			empresa_id_select.passedElement.element.addEventListener('change', function (event) {
				// console.log(empresa_id_select.getValue(true));
				if (empresa_id_select.getValue(true) !== undefined) {
					@this.set('empresa_id', empresa_id_select.getValue(true));
				} else {
					@this.set('empresa_id', null);
				}
			});
			
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
			
			const cargo_id_select = new Choices('#cargo_id', opciones);
			cargo_id_select.passedElement.element.addEventListener('change', function (event) {
				if (cargo_id_select.getValue(true) !== undefined) {
					@this.set('cargo_id', cargo_id_select.getValue(true));
				} else {
					@this.set('cargo_id', null);
				}
			});

			const reporta_a_select = new Choices('#reporta_a', opciones);
			reporta_a_select.passedElement.element.addEventListener('change', function (event) {
				if (reporta_a_select.getValue(true) !== undefined) {
					@this.set('reporta_a', reporta_a_select.getValue(true));
				} else {
					@this.set('reporta_a', null);
				}
			});
			
			Livewire.on('actualizarDatosP', function (
				// personal_id,
				empresa_id, gerencia_id, sede_id, area_id, cargo_id, reporta_a) {
				habilitarDatosPersonal();
	
				// personal_id_select.setChoiceByValue(personal_id ?? '');
				empresa_id_select.setChoiceByValue(empresa_id ?? '');
				gerencia_id_select.setChoiceByValue(gerencia_id ?? '');
				sede_id_select.setChoiceByValue(sede_id ?? '');
				area_id_select.setChoiceByValue(area_id ?? '');				
				cargo_id_select.setChoiceByValue(cargo_id ?? '');
    			reporta_a_select.setChoiceByValue(reporta_a ?? '');
			});
			
			// Livewire.on('actualizarDatosR', function (personal_id,area_id,cargo_id) {
			// 	habilitarDatosResponsable();
	
			// 	responsable_id_select.setChoiceByValue(personal_id ?? '');
			// 	responsable_area_id_select.setChoiceByValue(area_id ?? '');				
			// 	responsable_cargo_id_select.setChoiceByValue(cargo_id ?? '');
			// });
	
			Livewire.on('listar_selects', function (
				// personal,
			empresas,gerencias,sedes,areas,cargos, personales
			// ,responsables
			) {

				deshabilitarDatosPersonal();	
				
				limpiarDatosPersonal();
				
				// personal_id_select.setChoices(personal);
				empresa_id_select.setChoices(empresas);
				gerencia_id_select.setChoices(gerencias);
				sede_id_select.setChoices(sedes);
				area_id_select.setChoices(areas);
				cargo_id_select.setChoices(cargos);
    			reporta_a_select.setChoices(personales);
				// personal_id_select.setChoices(personales);
				// responsable_id_select.setChoices(responsables);
				// responsable_area_id_select.setChoices(areas);
				// responsable_cargo_id_select.setChoices(cargos);
				
			});

			Livewire.on('limpiarDatosP', function () {
					limpiarDatosPersonal();
			});

			const limpiarDatosPersonal = () => {
				
				empresa_id_select.clearChoices();
				gerencia_id_select.clearChoices();
				sede_id_select.clearChoices();
				area_id_select.clearChoices();
				cargo_id_select.clearChoices();
				reporta_a_select.clearChoices();

				empresa_id_select.clearStore();
				gerencia_id_select.clearStore();
				sede_id_select.clearStore();
				area_id_select.clearStore();
				cargo_id_select.clearStore();
				reporta_a_select.clearStore();
				
				empresa_id_select.setChoices(placeholder);
				gerencia_id_select.setChoices(placeholder);
				sede_id_select.setChoices(placeholder);
				area_id_select.setChoices(placeholder);
				cargo_id_select.setChoices(placeholder);
				reporta_a_select.setChoices(placeholder);

				// empresa_id_select.unhighlightAll();
			}
	
			const deshabilitarDatosPersonal = () => {
				// personal_id_select.disable();
				empresa_id_select.disable();
				gerencia_id_select.disable();
				sede_id_select.disable();
				area_id_select.disable();
				cargo_id_select.disable();
				reporta_a_select.disable();
			};
			
			// const deshabilitarDatosResponsable = () => {
			// 	responsable_id_select.disable();
			// 	responsable_area_id_select.disable();
			// 	responsable_cargo_id_select.disable();
			// };
			
			const habilitarDatosPersonal = () => {
				// personal_id_select.enable();
				empresa_id_select.enable();
				gerencia_id_select.enable();
				sede_id_select.enable();
				area_id_select.enable();
				cargo_id_select.enable();
				reporta_a_select.enable();
			};
			
			// const habilitarDatosResponsable = () => {
			// 	responsable_id_select.enable();
			// 	responsable_area_id_select.enable();
			// 	responsable_cargo_id_select.enable();
			// };
			
			$('#exportarSeleccionadosCampania').on('click', function () {
				// Obtén los IDs seleccionados desde Livewire
				console.log('Exportar seleccionados a campaña actual');
				let ids = @this.selectedFromPersonalTable ?? [];
				console.log('IDs seleccionados:', ids);
				if (!ids.length) {
					Swal.fire('Atención', 'No hay personal seleccionado.', 'warning');
					return;
				}
				Swal.fire({
					title: '¿Está seguro?',
					text: `Se exportarán ${ids.length} personas a la campaña actual.`,
					icon: 'question',
					showCancelButton: true,
					confirmButtonText: 'Sí, exportar',
					cancelButtonText: 'Cancelar'
				}).then((result) => {
					if (result.isConfirmed) {
						$.ajax({
							url: {{ 'route("campanias.exportarPersonalACampaniaActua")' }},
							method: 'POST',
							data: {
								ids: ids,
								_token: '{{ csrf_token() }}'
							},
							success: function (resp) {
								Swal.fire('¡Listo!', resp.message, 'success');
							},
							error: function (xhr) {
								Swal.fire('Error', xhr.responseJSON?.message || 'Error al exportar', 'error');
							}
						});
					}
				});
			});
		})
	</script>
	@endpush

	@push('css')
	<style>
		.bg-red-100 td {
			background-color: rgba(254, 202, 202, 0.8) !important; /* Un rojo claro */
		}

		.bg-red-100:hover td {
			background-color: rgba(254, 202, 202, 1) !important; /* Un rojo más intenso al hover */
		}
		
	</style>
	@endpush
</div>
