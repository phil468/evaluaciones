@section('title', __('Evaluador Has Evaluados'))
<div class="container-fluid">
	<div class="row justify-content-center">
		<div class="col-md-12">
			<div class="card rounded-xl">
                <div class="text-white card-header bg-vanguard rounded-t-xl">
					<div style="display: flex; justify-content: space-between; align-items: center;">
						<div class="float-left">
							<h5 class="h5">Evaluadores de Evaluación por Competencias</h5>
						</div>

						@if (session()->has('messageEvaluadoresCompetencias'))
						<div wire:poll.4s class="btn btn-sm btn-success" style="margin-top:0px; margin-bottom:0px;"> {{ session('messageEvaluadoresCompetencias') }} </div>
						@endif

						@can('crear-evaluacion')
						<div class="float-right">
							<div class="mb-1 btn btn-sm btn-default" data-toggle="modal" data-target="#createDataModal">
								<a title="Nuevo" data-toggle="modal" data-target="#updateModal" wire:click="edit(0,1)" accesskey="n">
									<i class="fa fa-plus"></i> Nuevo (n)
								</a>
							</div>
							{{-- //boton para crear_editar_usuarios --}}
							<div class="mb-1 btn btn-sm btn-default" data-toggle="modal" data-target="#createEditUsersModal">
								<a title="Crear/Editar Usuarios" wire:click="crear_editar_usuarios" accesskey="u">
									<i class="fa fa-users"></i> Crear/Editar Usuarios (u)
								</a>
							</div>							

							{{-- <div class="mb-1 btn btn-sm btn-default">
								<a title="Enviar correo masivo" accesskey="e" wire:click="enviarCorreo" accesskey="e">
									<i class="fa fa-envelope"></i> Enviar correo masivo (e)
								</a>
							</div>
							<br> --}}
							<div class="mb-1 btn btn-sm btn-default" data-toggle="modal" data-target="#importDataModal">
								<a title="Importar" data-toggle="modal" data-target="#importDataModal" accesskey="d">
									<i class="fa fa-file-import"></i> Importar Eval. por Competencias (d)
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
					@livewire('evaluaciones-evaluadores-competencias-table')
				</div>
			</div>
			
			
			<div class="card rounded-xl">
                <div class="text-white card-header bg-vanguard rounded-t-xl">
					<div style="display: flex; justify-content: space-between; align-items: center;">
						<div class="float-left">
							<h5 class="h5">Evaluadores de Evaluación por Resultados</h5>
						</div>

						@if (session()->has('messageEvaluadoresResultados'))
						<div wire:poll.4s class="btn btn-sm btn-success" style="margin-top:0px; margin-bottom:0px;"> {{ session('messageEvaluadoresResultados') }} </div>
						@endif

						@can('crear-evaluacion')
						<div class="float-right">
							<div class="mb-1 btn btn-sm btn-default" data-toggle="modal" data-target="#createDataModal">
								<a title="Nuevo" data-toggle="modal" data-target="#updateModal" wire:click="edit(0,2)" accesskey="n">
									<i class="fa fa-plus"></i> Nuevo (n)
								</a>
							</div>

							<div class="mb-1 btn btn-sm btn-default" data-toggle="modal" data-target="#createEditUsersModal">
								<a title="Crear/Editar Usuarios" wire:click="crear_editar_usuarios" accesskey="u">
									<i class="fa fa-users"></i> Crear/Editar Usuarios (u)
								</a>
							</div>
							<br>
							<div class="mb-1 btn btn-sm btn-default" data-toggle="modal" data-target="#importObjetivosDataModal">
								<a title="Importar Objetivos" data-toggle="modal" data-target="#importObjetivosDataModal" accesskey="o">
									<i class="fa fa-file-import"></i> Importar Eval. de Desempeño por Resultados (o)
								</a>
							</div>

							{{-- <div class="mb-1 btn btn-sm btn-default">
								<a title="Eliminar Evaluaciones por Objetivos no iniciadas" accesskey="x" wire:click="eliminarObjetivos">
									<i class="fa fa-trash"></i> Eliminar Eval. por Resultados no iniciadas (x)
								</a>
							</div> --}}
							
						</div>
						@endcan
					</div>
				</div>
				
				<div class="card-body">
					@livewire('evaluaciones-evaluadores-resultados-table')
				</div>
			</div>
			
			@can('crear-evaluacion')
			@include('livewire.evaluadores.importar')
			@include('livewire.evaluadores.importar_objetivos')
			@endcan						
			@can('editar-evaluacion')
			@include('livewire.evaluadores.update')
			@endcan

			<div wire:loading wire:target="crear_editar_usuarios,enviarCorreo">
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
		
			const evaluador_id_select = new Choices('#evaluador_id', opcionesPlanes);
			const evaluado_id_select = new Choices('#evaluado_id', opcionesPlanes);
			const evaluacion_id_select = new Choices('#evaluacion_id', opcionesPlanes);
			
			// evaluador_id_select.setChoices(@json($evaluadores), 'value', 'label', true);
			// evaluado_id_select.setChoices(@json($evaluados), 'value', 'label', true);
			// evaluacion_id_select.setChoices(@json($evaluaciones), 'value', 'label', true);

			evaluador_id_select.disable();
			evaluado_id_select.disable();
			evaluacion_id_select.disable();

			evaluador_id_select.passedElement.element.addEventListener('change', function (event) {
				@this.set('evaluador_id', evaluador_id_select.getValue(true));
			});
			evaluado_id_select.passedElement.element.addEventListener('change', function (event) {
				@this.set('evaluado_id', evaluado_id_select.getValue(true));
			});
			evaluacion_id_select.passedElement.element.addEventListener('change', function (event) {
				@this.set('evaluacion_id', evaluacion_id_select.getValue(true));
			});
			
			Livewire.on('actualizarDatosEvaluadores', function (
				evaluador_id,
				evaluado_id,
				evaluacion_id,
				
				evaluadores,
				evaluados,
				evaluaciones,
				
				disable
			) {
				evaluador_id_select.hideDropdown();
				evaluado_id_select.hideDropdown();
				evaluacion_id_select.hideDropdown();
					
				evaluador_id_select.setChoices(evaluadores, 'value', 'label', true);
				evaluado_id_select.setChoices(evaluados, 'value', 'label', true);
				evaluacion_id_select.setChoices(evaluaciones, 'value', 'label', true);

				evaluador_id_select.setChoiceByValue(evaluador_id ?? '');
				evaluado_id_select.setChoiceByValue(evaluado_id ?? '');
				evaluacion_id_select.setChoiceByValue(evaluacion_id ?? '');

				if(disable) {
					evaluador_id_select.disable();
					evaluado_id_select.disable();
					evaluacion_id_select.disable();
				} else {
					evaluador_id_select.enable();
					evaluado_id_select.enable();
					evaluacion_id_select.enable();
				}
			});
			
			Livewire.on('limpiarDatosEvaluadores', function (areas) {
				evaluador_id_select.removeActiveItems();
				evaluado_id_select.removeActiveItems();
				evaluacion_id_select.removeActiveItems();
				
				evaluador_id_select.disable();
				evaluado_id_select.disable();
				evaluacion_id_select.disable();
			});
			
		});
	</script>
	@endpush
	

</div>
