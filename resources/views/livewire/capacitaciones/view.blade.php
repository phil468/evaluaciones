@section('title', __('Capacitaciones'))
<div class="container-fluid">
	<div class="row justify-content-center">
		<div class="col-md-12">
			<div class="card rounded-xl">
                <div class="text-white card-header bg-vanguard rounded-t-xl">
					<div style="display: flex; justify-content: space-between; align-items: center;">
						<div class="float-left">
							<h5 class="h5">Lista Capacitaciones </h4>
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
						@can('crear-capacitacion')
							<div class="ml-1 btn btn-md btn-default" wire:click="edit(0)" data-toggle="modal"
								data-target="#updateModal">
								<i class="fa fa-plus"></i>
							</div>

						{{-- <div class="btn btn-sm btn-default" data-toggle="modal" data-target="#createDataModal">
						<i class="fa fa-plus"></i>  Nuevo
						</div> --}}
						@endcan
					</div>
				</div>
				
				<div class="card-body">
						@can('crear-capacitacion')
						{{-- @include('livewire.capacitaciones.create') --}}
						@endcan						
						@can('editar-capacitacion')
						@include('livewire.capacitaciones.update')
						@endcan

						@livewire('capacitaciones-table')
			</div>
			<div wire:loading wire:target="importar,exportar,create,edit,destroy,store,update,agregar_tema">
				<x-loading-indicator />
			</div>
		</div>
	</div>

	@push('js')
	<script>
		document.addEventListener('livewire:load', function() {
			console.log('Inicializar Choice.js');

			const opciones = {
				removeItemButton: true,
				itemSelectText: 'Seleccione',
				
				searchPlaceholderValue: 'Buscar',
				placeholderValue: 'Selecciona una opción',
				noResultsText: 'Resultados no encontrados',
				
				placeholder: true,
    			placeholderValue: null,
				allowHTML: false,
				shouldSort: true,
				searchResultLimit: 5,
				searchFields: ['label'],
				renderChoiceLimit: 100
			}

			const opciones_expositor = {
				removeItemButton: true,
				itemSelectText: 'Seleccione',
				
				searchPlaceholderValue: 'Buscar',
				placeholderValue: 'Selecciona una opción',
				noResultsText: 'Resultados no encontrados',
				
				placeholder: true,
    			placeholderValue: null,
				allowHTML: false,
				shouldSort: true,
				searchResultLimit: 5,
				searchFields: ['label'],
				
				searchFloor: 1,
				renderChoiceLimit: 100
			}
			
			const placeholder = [{
				value: '',
				label: ''
			}, ];

			const empresa_id_select = new Choices('#empresa_id', opciones);
			empresa_id_select.passedElement.element.addEventListener('change', function(event) {
				if (empresa_id_select.getValue(true) !== undefined) {
					@this.set('empresa_id', empresa_id_select.getValue(true));
				} else {
					@this.set('empresa_id', null);
				}
			});

			const capacitaciones_tipo_id_select = new Choices('#capacitaciones_tipo_id', opciones);
			capacitaciones_tipo_id_select.passedElement.element.addEventListener('change', function(event) {
				if (capacitaciones_tipo_id_select.getValue(true) !== undefined) {
					@this.set('capacitaciones_tipo_id', capacitaciones_tipo_id_select.getValue(true));
				} else {
					@this.set('capacitaciones_tipo_id', null);
				}
			});

			const tema_id_select = new Choices('#tema_id', opciones);
			tema_id_select.passedElement.element.addEventListener('change', function(event) {
				if (tema_id_select.getValue(true) !== undefined) {
					@this.set('tema_id', tema_id_select.getValue(true));
				} else {
					@this.set('tema_id', null);
				}
			});

			const status_id_select = new Choices('#status_id', opciones);
			status_id_select.passedElement.element.addEventListener('change', function(event) {
				if (status_id_select.getValue(true) !== undefined) {
					@this.set('status_id', status_id_select.getValue(true));
				} else {
					@this.set('status_id', null);
				}
			});

			const sede_id_select = new Choices('#sede_id', opciones);
			sede_id_select.passedElement.element.addEventListener('change', function(event) {
				if (sede_id_select.getValue(true) !== undefined) {
					@this.set('sede_id', sede_id_select.getValue(true));
				} else {
					@this.set('sede_id', null);
				}
			});

			//agregar renderChoiceLimit: 100 a  opciones_expositor
			const expositor_id_select = new Choices('#expositor_id', opciones_expositor);
			expositor_id_select.passedElement.element.addEventListener('change', function(event) {
				// if (expositor_id_select.getValue(true) !== undefined) {
					@this.set('expositor_id', expositor_id_select.getValue(true));
					// console.log(expositor_id_select.getValue(true));
				// } else {
					// @this.set('expositor_id', null);
				// }
			});

			const cargo_expositor_id_select = new Choices('#cargo_expositor_id', opciones);
			cargo_expositor_id_select.passedElement.element.addEventListener('change', function(event) {
				// if (cargo_expositor_id_select.getValue(true) !== undefined) {
					// console.log(cargo_expositor_id_select.getValue(true));
					@this.set('cargo_expositor_id', cargo_expositor_id_select.getValue(true));
				// } else {
					// @this.set('cargo_expositor_id', null);
				// }
			});

			const registrador_id_select = new Choices('#registrador_id', opciones);
			registrador_id_select.passedElement.element.addEventListener('change', function(event) {
				// if (registrador_id_select.getValue(true) !== undefined) {
					@this.set('registrador_id', registrador_id_select.getValue(true));
				// } else {
					// @this.set('registrador_id', null);
				// }
			});

			const cargo_registrador_id_select = new Choices('#cargo_registrador_id', opciones);
			cargo_registrador_id_select.passedElement.element.addEventListener('change', function(event) {
				if (cargo_registrador_id_select.getValue(true) !== undefined) {
					@this.set('cargo_registrador_id', cargo_registrador_id_select.getValue(true));
				} else {
					@this.set('cargo_registrador_id', null);
				}
			});
			
			const area_id_select = new Choices('#area_id', opciones);
			area_id_select.passedElement.element.addEventListener('change', function(event) {
				if (area_id_select.getValue(true) !== undefined) {
					@this.set('area_id', area_id_select.getValue(true));
				} else {
					@this.set('area_id', null);
				}
			});

			const modalidad_id_select = new Choices('#modalidad_id', opciones);
			modalidad_id_select.passedElement.element.addEventListener('change', function(event) {
				if (area_id_select.getValue(true) !== undefined) {
					@this.set('modalidad_id', modalidad_id_select.getValue(true));
				} else {
					@this.set('modalidad_id', null);
				}
			});
			
			Livewire.on('listarTemas', function(
                    opciones,
                    id
                ) {
                    tema_id_select.disable();
                    tema_id_select.clearChoices();
                    tema_id_select.clearStore();
                    tema_id_select.setChoices(placeholder);
                    tema_id_select.enable();
                    tema_id_select.setChoices(opciones);
                    tema_id_select.setChoiceByValue(id ?? '');
                });
				

			Livewire.on('actualizarSelects', function(
				empresa_id,
				capacitaciones_tipo_id,
				tema_id,
				sede_id,
				expositor_id,
				cargo_expositor_id,
				registrador_id,
				cargo_registrador_id,
				status_id,
				area_id,
				modalidad_id,
			) {

				habilitarDatos();

				empresa_id_select.setChoiceByValue(empresa_id ?? '');
				capacitaciones_tipo_id_select.setChoiceByValue(capacitaciones_tipo_id ?? '');
				tema_id_select.setChoiceByValue(tema_id ?? '');
				status_id_select.setChoiceByValue(status_id ?? '');
				sede_id_select.setChoiceByValue(sede_id ?? '');
				expositor_id_select.setChoiceByValue(expositor_id ?? '');
				cargo_expositor_id_select.setChoiceByValue(cargo_expositor_id ?? '');
				registrador_id_select.setChoiceByValue(registrador_id ?? '');
				cargo_registrador_id_select.setChoiceByValue(cargo_registrador_id ?? '');
				area_id_select.setChoiceByValue(area_id ?? '');
				modalidad_id_select.setChoiceByValue(modalidad_id ?? '');
			});

			Livewire.on('actualizarDatosExpositor', function(
				cargo_expositor_id,
				expositor_id,
				expositor_externo
			) {

				if (expositor_externo) {
					cargo_expositor_id_select.setChoiceByValue('');
					expositor_id_select.setChoiceByValue('');
					cargo_expositor_id_select.disable();
					expositor_id_select.disable();
				} else {
					cargo_expositor_id_select.enable();
					expositor_id_select.enable();
					if(cargo_expositor_id) {
						cargo_expositor_id_select.setChoiceByValue(cargo_expositor_id);
						cargo_expositor_id_select.disable();
					}
					else {
						cargo_expositor_id_select.setChoiceByValue('');
						if(expositor_id) {
							cargo_expositor_id_select.enable();
						}
						else {
							cargo_expositor_id_select.disable();
						}
					}
				}
			});
			
			
			Livewire.on('actualizarDatosRegistrador', function(
				cargo_registrador_id,
				registrador_id
			) {
				if(cargo_registrador_id) {
					cargo_registrador_id_select.setChoiceByValue(cargo_registrador_id);
					cargo_registrador_id_select.disable();
				}
				else {
					cargo_registrador_id_select.setChoiceByValue('');
					if(registrador_id) {
						cargo_registrador_id_select.enable();
					}
					else {
						cargo_registrador_id_select.disable();
					}
				}
			});
			
			
			Livewire.on('listarSelects', function(
				// personal,
				empresas,
				capacitaciones_tipos,
				temas,
				sedes,
				expositors,
				cargos,
				registradors,
				cargo_registradors,
				statuss,
				areas,
				modalidades
				// ,responsables
			) {
				cargo_expositor_id_select.disable();
				cargo_registrador_id_select.disable();

				// @this.set('updateMode', false);
				// console.log(@this.set('updateMode',false));

				// @this.set('updateMode', true);

				deshabilitarDatos();

				limpiarDatos();

				// console.log(expositors);
				empresa_id_select.setChoices(empresas);
				capacitaciones_tipo_id_select.setChoices(capacitaciones_tipos);
				tema_id_select.setChoices(temas);
				status_id_select.setChoices(statuss);
				sede_id_select.setChoices(sedes);
				// personal_id_select.setChoices();
				expositor_id_select.setChoices(expositors);
				cargo_expositor_id_select.setChoices(cargos);
				registrador_id_select.setChoices(registradors);
				cargo_registrador_id_select.setChoices(cargos);
				area_id_select.setChoices(areas);
				modalidad_id_select.setChoices(modalidades);
				// @this.set('updateMode', true);
			});

			Livewire.on('limpiarDatos', function() {
				limpiarDatos();
			});

			// Livewire.on('listarCT', function(
			// 	cargadores_laptop,
			// 	cargo_registrador_id
			// ) {

			// 	cargo_registrador_id_select.disable();
			// 	cargo_registrador_id_select.clearChoices();
			// 	cargo_registrador_id_select.clearStore();
			// 	cargo_registrador_id_select.setChoices(placeholder);
			// 	cargo_registrador_id_select.enable();
			// 	cargo_registrador_id_select.setChoices(cargadores_laptop);
			// 	cargo_registrador_id_select.setChoiceByValue(cargo_registrador_id ?? '');

			// });


			const limpiarDatos = () => {

				empresa_id_select.clearChoices();
				capacitaciones_tipo_id_select.clearChoices();
				tema_id_select.clearChoices();
				status_id_select.clearChoices();
				sede_id_select.clearChoices();
				expositor_id_select.clearChoices();
				cargo_expositor_id_select.clearChoices();
				registrador_id_select.clearChoices();
				cargo_registrador_id_select.clearChoices();
				area_id_select.clearChoices();
				modalidad_id_select.clearChoices();

				empresa_id_select.clearStore();
				capacitaciones_tipo_id_select.clearStore();
				tema_id_select.clearStore();
				status_id_select.clearStore();
				sede_id_select.clearStore();
				expositor_id_select.clearStore();
				cargo_expositor_id_select.clearStore();
				registrador_id_select.clearStore();
				cargo_registrador_id_select.clearStore();
				area_id_select.clearStore();
				modalidad_id_select.clearStore();

				// empresa_id_select.setChoices(placeholder);
				// capacitaciones_tipo_id_select.setChoices(placeholder);
				// tema_id_select.setChoices(placeholder);
				// status_id_select.setChoices(placeholder);
				// sede_id_select.setChoices(placeholder);
				expositor_id_select.setChoices(placeholder);
				cargo_expositor_id_select.setChoices(placeholder);
				// registrador_id_select.setChoices(placeholder);
				cargo_registrador_id_select.setChoices(placeholder);

				// @this.set('updateMode', false);

				// empresa_id_select.unhighlightAll();
			}

			const deshabilitarDatos = () => {
				empresa_id_select.disable();
				capacitaciones_tipo_id_select.disable();
				tema_id_select.disable();
				status_id_select.disable();
				sede_id_select.disable();
				expositor_id_select.disable();
				// cargo_expositor_id_select.disable();
				registrador_id_select.disable();
				// cargo_registrador_id_select.disable();
				area_id_select.disable();
				modalidad_id_select.disable();
			};

			const habilitarDatos = () => {
				empresa_id_select.enable();
				capacitaciones_tipo_id_select.enable();
				tema_id_select.enable();
				status_id_select.enable();
				sede_id_select.enable();
				expositor_id_select.enable();
				// cargo_expositor_id_select.enable();
				registrador_id_select.enable();
				// cargo_registrador_id_select.enable();
				area_id_select.enable();
				modalidad_id_select.enable();
			};

		})
	</script>
@endpush

</div>
