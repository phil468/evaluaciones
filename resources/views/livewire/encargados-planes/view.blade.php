@section('title', __('Evaluador Has Evaluados'))
<div class="container-fluid">
	<div class="row justify-content-center">
		<div class="col-md-12">
			<div class="card rounded-xl">
                <div class="text-white card-header bg-vanguard rounded-t-xl">
					<div style="display: flex; justify-content: space-between; align-items: center;">
						<div class="float-left">
							<h5 class="h5">Encargado de Planes de Acción</h5>
						</div>

						{{-- @if (session()->has('message'))
						<div wire:poll.4s class="btn btn-sm btn-success" style="margin-top:0px; margin-bottom:0px;"> {{ session('message') }} </div>
						@endif --}}
						{{-- @if (session()->has('message'))
							<div wire:poll.5s class="alert alert-success">
								{{ session('message') }}
							</div>
						@endif --}}
						@can('crear-evaluacion')
						<div class="float-right">
							{{-- <div class="mb-1 btn btn-sm btn-default" data-toggle="modal" data-target="#createDataModal">
								<a title="Nuevo" data-toggle="modal" data-target="#updateModal" wire:click="edit(0)" accesskey="n">
									<i class="fa fa-plus"></i> Nuevo (n)
								</a>
							</div>

							<div class="mb-1 btn btn-sm btn-default" data-toggle="modal" data-target="#createEditUsersModal">
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

			<div wire:loading wire:target="edit,crear_editar_usuarios,enviarCorreo,importar_objetivos,store,importar,update">
				<x-loading-indicator />
			</div>	
		</div>
	</div>

	<script>
		document.addEventListener('livewire:load', function () {
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
				
				searchFloor: 1,
				renderChoiceLimit: 100
			}
	
			const evaluador_id_select = new Choices('#evaluador_id', opciones);
			evaluador_id_select.passedElement.element.addEventListener('change', function (event) {
					dato = evaluador_id_select.getValue(true) !== undefined ? evaluador_id_select.getValue(true) : '' ;
					@this.set('evaluador_id', dato );
			});
			
			const evaluado_id_select = new Choices('#evaluado_id', opciones);
			evaluado_id_select.passedElement.element.addEventListener('change', function (event) {
				if (evaluado_id_select.getValue(true) !== undefined) {
					@this.set('evaluado_id', evaluado_id_select.getValue(true));
				}
			});
			
			const evaluacion_id_select = new Choices('#evaluacion_id', opciones);
			evaluacion_id_select.passedElement.element.addEventListener('change', function (event) {
				if (evaluacion_id_select.getValue(true) !== undefined) {
					@this.set('evaluacion_id', evaluacion_id_select.getValue(true));
				}
			});
				
			Livewire.on('actualizarDatosP', function (evaluador_id,evaluado_id,evaluacion_id) {
				habilitarDatosPersonal();
	
				evaluador_id_select.setChoiceByValue(evaluador_id ?? '');
				evaluado_id_select.setChoiceByValue(evaluado_id ?? '');
				evaluacion_id_select.setChoiceByValue(evaluacion_id ?? '');
			});
				
			Livewire.on('listar_selects', function (evaluadores,evaluados,evaluaciones) {
				const placeholder = [
						{ value: '', label: ' - Seleccione - '},
					];
	
				deshabilitarDatosPersonal();
				
				evaluador_id_select.clearChoices();
				evaluado_id_select.clearChoices();
				evaluacion_id_select.clearChoices();
				
				evaluador_id_select.setChoices(placeholder);
				evaluado_id_select.setChoices(placeholder);
				evaluacion_id_select.setChoices(placeholder);
				
				evaluador_id_select.setChoices(evaluadores);
				evaluado_id_select.setChoices(evaluados);
				evaluacion_id_select.setChoices(evaluaciones);
				
			});
	
			const deshabilitarDatosPersonal = () => {
				evaluador_id_select.disable();
				evaluado_id_select.disable();
				evaluacion_id_select.disable();
			};
			
		
			const habilitarDatosPersonal = () => {
				evaluador_id_select.enable();
				evaluado_id_select.enable();
				evaluacion_id_select.enable();
			};

		})
	</script>
	

</div>
