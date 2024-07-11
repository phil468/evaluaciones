
<div class="container-fluid">
	@section('title', __('Evaluaciones'))
	<div class="row justify-content-center">
		<div class="col-md-12">
			<div class="card rounded-xl">
				<div class="card-header bg-vanguard rounded-t-xl">
					<div style="display: flex; justify-content: space-between; align-items: center;">
						<div class="float-left">
							<h5 class="text-white h5">Planes de Mejora Configuración</h5>
						</div>
						@can('crear-evaluacion')
						<div title="Nuevo" data-toggle="modal" 
						class="btn btn-sm btn-default rounded-xl" wire:click="edit(0)">
						<i class="fa fa-plus"></i>  Nuevo
						</div>
						@endcan
					</div>
				</div>
				
				<div class="card-body">					
					@can('editar-evaluacion')
						@include('livewire.planes-configuracion.update')
					@endcan
						
					@livewire('planes-configuracion-table')
				</div>
			</div>
		</div>
	</div>
	<div wire:loading wire:target="crear_editar_usuarios,enviarCorreo,importar_objetivos,importar">
		<x-loading-indicator />
	</div>	
</div>
