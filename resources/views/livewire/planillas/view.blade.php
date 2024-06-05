@section('title', __('Planillas'))
<div class="container-fluid">
	<div class="row justify-content-center">
		<div class="col-md-12">
			<div class="card rounded-xl">
                <div class="text-white card-header bg-vanguard rounded-t-xl">
					<div style="display: flex; justify-content: space-between; align-items: center;">
						<div class="float-left">
							<h5 class="h5">Lista Planilla </h4>
						</div>
						{{--<div wire:poll.1s>
							<code><h5>{{ now()->format('H:i:s') }}</h5></code>
						</div>--}}
						@if (session()->has('message'))
						<div wire:poll.4s class="btn btn-sm btn-success" style="margin-top:0px; margin-bottom:0px;"> {{ session('message') }} </div>
						@endif
						<div>
							<input wire:model='keyWord' type="text" class="form-control" name="search" id="search" placeholder="Buscar">
						</div>
						@can('crear-planilla')
						{{-- <div class="btn btn-sm btn-default" wire:click="edit(0)" data-toggle="modal" data-target="#updateModal">
						<i class="fa fa-plus"></i>  Nuevo
						</div> --}}
						@endcan
					</div>
				</div>
				
				<div class="card-body">
						@can('crear-planilla')
						{{-- @include('livewire.planillas.create') --}}
						@endcan						
						@can('editar-planilla')
						@include('livewire.planillas.update')
						@endcan
				<div class="table-responsive">
					<table class="table table-striped table-hover table-sm">
						<thead class="thead">
							<tr> 
								<th>ID</th> 
								<th>Name</th>
								<th>Estado</th>
								<th>Empresa</th>
								<th>Sede</th>
																
								@can('editar-planilla','borrar-planilla')
								<th>ACCIONES</th>								
								@endcan
							</tr>
						</thead>
						<tbody>
							@foreach($planillas as $row)
							<tr>
								<td>{{ $row->id }}</td> 
								<td>{{ $row->name }}</td>
								<td>
									<div>
										<livewire:toggle-button :model="$row" :field="'estado'" key="{{ $row->id }}">
									</div>
								</td>
								{{-- <td>{{ $row->estado }}</td> --}}
								<td>{{ $row->empresa->name??null }}</td>
								<td>{{ $row->sede->name??null }}</td>
																
								@can('editar-planilla','borrar-planilla')
								<td width="90">
								<div class="btn-group">
									@can('editar-planilla')
									<a data-toggle="modal" data-target="#updateModal" class="btn btn-sm btn-vanguard rounded-xl" wire:click="edit({{$row->id}})">Editar </a>
									@endcan
									@can('borrar-planilla')							 
									<a class="btn btn-sm btn-danger" onclick="confirm('Confirma borrar Planilla : {{$row->name}}? \nPlanillas borrados no pueden ser recuperados!')||event.stopImmediatePropagation()" wire:click="destroy({{$row->id}})"> Borrar </a> 
									@endcan  
								</div>
								</td>
								@endcan
							@endforeach
						</tbody>
					</table>						
					{{ $planillas->links() }}
					</div>
				</div>
				<div wire:loading wire:target="importar,exportar,create,edit,destroy">
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
		
			const sede_id_select = new Choices('#sede_id', opciones);
			sede_id_select.passedElement.element.addEventListener('change', function (event) {
				if (sede_id_select.getValue(true) !== undefined) {
					@this.set('sede_id', sede_id_select.getValue(true));
				} else {
					@this.set('sede_id', null);
				}
			});
			
			Livewire.on('actualizarDatosSelect', function (
				sede_id) {
				habilitarDatosSelect();
				sede_id_select.setChoiceByValue(sede_id ?? '');
			});
	
			Livewire.on('listar_selects', function (sedes) {
				deshabilitarDatosSelect();
				limpiarDatosSelect();
				sede_id_select.setChoices(sedes);				
			});

			Livewire.on('limpiarDatosP', function () {
					limpiarDatosSelect();
			});

			const limpiarDatosSelect = () => {				
				sede_id_select.clearChoices();
				sede_id_select.clearStore();
				sede_id_select.setChoices(placeholder);
			}
	
			const deshabilitarDatosSelect = () => {
				sede_id_select.disable();
			};
			
			const habilitarDatosSelect = () => {
				sede_id_select.enable();
			};
			
		})
	</script>
		
	@endpush
</div>
