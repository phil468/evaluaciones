@section('title', __('Evaluador Has Evaluados'))
<div class="container-fluid">
	<div class="row justify-content-center">
		<div class="col-md-12">
			<div class="card rounded-xl">
                <div class="text-white card-header bg-vanguard rounded-t-xl">
					<div style="display: flex; justify-content: space-between; align-items: center;">
						<div class="float-left">
							<h5 class="h5">Encargado de Planes de Mejora</h5>
						</div>

						{{-- @if (session()->has('message'))
						<div wire:poll.4s class="btn btn-sm btn-success" style="margin-top:0px; margin-bottom:0px;"> {{ session('message') }} </div>
						@endif --}}
						@if (session()->has('messagePlanes'))
							<div wire:poll.5s class="alert alert-success">
								{{ session('messagePlanes') }}
							</div>
						@endif
						@can('crear-evaluacion')
						<div class="float-right">
							<div class="mb-1 btn btn-sm btn-default" data-toggle="modal" data-target="#updateEncargadosPlanesModal">
								<a title="Nuevo" data-toggle="modal" data-target="#updateEncargadosPlanesModal" wire:click="edit(0)" accesskey="n">
									<i class="fa fa-plus"></i> Nuevo (n)
								</a>
							</div>

							{{-- <div class="mb-1 btn btn-sm btn-default" data-toggle="modal" data-target="#createEditUsersModal">
								<a title="Crear/Editar Usuarios" wire:click="crear_editar_usuarios" accesskey="u">
									<i class="fa fa-users"></i> Crear/Editar Usuarios (u)
								</a>
							</div>							 --}}

							{{-- <div class="mb-1 btn btn-sm btn-default">
								<a title="Enviar correo masivo" accesskey="e" wire:click="enviarCorreo" accesskey="e">
									<i class="fa fa-envelope"></i> Enviar correo masivo (e)
								</a>
							</div>
							<br> --}}
							<div class="mb-1 btn btn-sm btn-default" data-toggle="modal" data-target="#importEncargadosPlanesDataModal">
								<a title="Importar" data-toggle="modal" data-target="#importEncargadosPlanesDataModal" accesskey="p" wire:click="abrirImportar">
									<i class="fa fa-file-import"></i> Importar Encargados de Planes (p)
								</a>
							</div>

							{{-- <div class="mb-1 btn btn-sm btn-default">
								<a title="Eliminar Evaluaciones por Desempeño no iniciadas" accesskey="x" wire:click="eliminarEvaluacionPorDesempeno">
									<i class="fa fa-trash"></i> Eliminar Eval. por Competencias no iniciadas (x)
								</a>
							</div> --}}
							
						</div>
						@endcan
					</div>
				</div>
				
				<div class="card-body">
					@livewire('encargados-planes-table')
				</div>
			</div>
			
			@can('crear-evaluacion')
			@include('livewire.encargados-planes.importar')
			@endcan						
			@can('editar-evaluacion')
			@include('livewire.encargados-planes.update')
			@endcan

			<div wire:loading wire:target="crear_editar_usuarios,enviarCorreo,importar_objetivos,importar">
				<x-loading-indicator />
			</div>	
		</div>
	</div>
	
	@push('js')
	<script>
		document.addEventListener('livewire:load', function () {
			console.log('Inicializar Choice.js');
			
			const placeholderEncargadoPlanes = [
						{ value: '', label: ' - Seleccione - '},
					];

			const opcionesPlanes = {
				removeItemButton: true,
				itemSelectText: 'Seleccione',
				noChoicesText: 'No hay opciones para elegir',
				
				searchPlaceholderValue: 'Buscar',
				placeholderValue: 'Selecciona una opción',
				noResultsText: 'Resultados no encontrados',
				
				placeholder: true,
    			placeholderValue: null,
				allowHTML: false,
				shouldSort: true,
				searchResultLimit: 10,
				searchFields: ['label'],
				
				searchFloor: 1,
				renderChoiceLimit: 15
			}
		
			const encargado_id_select = new Choices('#encargado_id', opcionesPlanes);
			const empleado_id_select = new Choices('#empleado_id', opcionesPlanes);
			const planes_de_accion_configuracion_id_select = new Choices('#planes_de_accion_configuracion_id', opcionesPlanes);
			
			encargado_id_select.setChoices(@json($evaluadores), 'value', 'label', true);
			empleado_id_select.setChoices(@json($evaluados), 'value', 'label', true);
			planes_de_accion_configuracion_id_select.setChoices(@json($evaluaciones), 'value', 'label', true);

			encargado_id_select.passedElement.element.addEventListener('change', function (event) {
				@this.set('encargado_id', encargado_id_select.getValue(true));
			});
			empleado_id_select.passedElement.element.addEventListener('change', function (event) {
				@this.set('empleado_id', empleado_id_select.getValue(true));
			});
			planes_de_accion_configuracion_id_select.passedElement.element.addEventListener('change', function (event) {
				@this.set('planes_de_accion_configuracion_id', planes_de_accion_configuracion_id_select.getValue(true));
			});
			
			Livewire.on('actualizarDatosEncargadosPlanes', function (evaluador_id,evaluado_id,evaluacion_id) {
				encargado_id_select.setChoices(@json($evaluadores), 'value', 'label', true);
				empleado_id_select.setChoices(@json($evaluados), 'value', 'label', true);
				planes_de_accion_configuracion_id_select.setChoices(@json($evaluaciones), 'value', 'label', true);
			
				encargado_id_select.setChoiceByValue(evaluador_id ?? '');
				empleado_id_select.setChoiceByValue(evaluado_id ?? '');
				planes_de_accion_configuracion_id_select.setChoiceByValue(evaluacion_id ?? '');
				
			});
			
			Livewire.on('limpiarDatosEncargadosPlanes', function (areas) {
				encargado_id_select.removeActiveItems();
				empleado_id_select.removeActiveItems();
				planes_de_accion_configuracion_id_select.removeActiveItems();
			});
			
		});
	</script>
	@endpush
</div>
