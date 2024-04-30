@section('title', __('Asignaciones'))
<div class="container-fluid">
	<div class="row justify-content-center">
		<div class="col-md-12">
			<div class="card rounded-xl">
                <div class="text-white card-header bg-vanguard rounded-t-xl">
					<div style="display: flex; justify-content: space-between; align-items: center;">
						<div class="float-left h4">
							@if (!$createMode && !$updateMode && !$viewMode)
								<h4>Lista Entregas</h4>
							@else
							@if ($createMode)
								@if (!$pdfMode)									
								<h4>Nueva Entrega</h4>
								@else
								<h4>Ingreso de Firma</h4>									
								@endif
							@endif
							@if ($updateMode)
								<h4>Actualizar Entrega</h4>
							@endif
							@if ($viewMode)
								<h4>Entrega N° {{ $selected_id }}</h4>
							@endif
							@endif
						</div>
						{{--<div wire:poll.1s>
							<code><h5>{{ now()->format('H:i:s') }}</h5></code>
						</div>--}}
						@if (session()->has('message'))
						<div wire:poll.4s class="btn btn-lg btn-success" style="margin-top:0px; margin-bottom:0px;"> {{ session('message') }} </div>
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
						
						@if (!$createMode && !$updateMode && !$viewMode)
							<a class="btn btn-lg btn-default" wire:click="create()"><i class="fa fa-plus"></i>  Nuevo</a>						
						@endif

						@if ($createMode)
							@can('crear-entrega')
								@if (!$pdfMode)
								<a class="btn btn-lg btn-default" wire:click="pdfMode()" 
								{{-- wire:click="store()" --}}
								>
									{{-- <i class="fa fa-save"></i>  --}}
									Siguiente <i class="fas fa-angle-right"></i>
								</a> 
								@else
								<a class="btnbtn-default" wire:click="notPdfMode()" 
								{{-- wire:click="$set('pdfMode', 'false')" --}}
								>
									{{-- <i class="fa fa-save"></i>  --}}
									<i class="fas fa-angle-left"></i>
									Anterior
								</a>
								<a class="btn btn-lg btn-default" 
								wire:click="store()"
								{{-- wire:click="generarPdf({{$asignacion_guardada->id??''}})" --}}
								>
									<i class="fa fa-save"></i> Registrar, guardar y enviar entrega
								</a> 								
								@endif
							@endcan
						@endif
						@if ($updateMode)
						@can('editar-entrega')
							<a class="btn btn-lg btn-default" wire:click="update()">
								<i class="fa fa-save"></i> Actualizar
							</a> 
						@endcan
						@endif
						
						@if ($viewMode)
						@can('ver-entrega')
							<a class="btn btn-default" wire:click="cancel()">
								<i class="fas fa-arrow-left"></i> Regresar
							</a> 
						@endcan
						@endif
					</div>
				</div>
				
				<div class="card-body">
					
						{{-- @include('livewire.asignaciones.pdf') --}}
						@include('livewire.asignaciones.firma')
						@can('crear-entrega')
							{{-- @if ($createMode) --}}
								@include('livewire.asignaciones.create')
								@include('livewire.asignaciones.precargar_activos')
								@include('livewire.asignaciones.update_activo')
								@include('livewire.asignaciones.guardar_no_asignacion')
							{{-- @endif	 --}}
						@endcan						
						@can('editar-entrega')						
							@if ($updateMode)
								@include('livewire.asignaciones.create')
							@endif
						@endcan
				
						@if (!$createMode && !$updateMode && !$viewMode)					
							@livewire('asignacion-table')
						@endif
				</div>
				@if ($createMode || $updateMode)
					@if (!$viewMode)
					<div class="card-footer">														 
						<a class="btn btn-sm btn-secondary" 						
						onclick="confirm('¿Confirma que desea salir del registro de entrega? \n Se perderán los cambios realizados.')||event.stopImmediatePropagation()"
						wire:click="cancel()">
							Cancelar
						</a>
						@if ($createMode)
							@can('crear-entrega')
								@if (!$pdfMode)
								<a class="btn btn-lg btn-primary" wire:click="pdfMode()"
								 {{-- wire:click="store()" --}}
								 >
									{{-- <i class="fa fa-save"></i>  --}}
									Siguiente <i class="fas fa-angle-right"></i>
								</a> 
								@else
								<a class="btn btn-primary" wire:click="notPdfMode()" 
								{{-- wire:click="$set('pdfMode', 'false')" --}}
								>
									{{-- <i class="fa fa-save"></i>  --}}
									<i class="fas fa-angle-left"></i>
									Anterior
								</a>
								<a class="btn btn-lg btn-primary" 
								wire:click="store()"
								{{-- wire:click="generarPdf({{$asignacion_guardada->id??''}})" --}}
								>
									<i class="fa fa-save"></i> Registrar, guardar y enviar acta
								</a> 								
								@endif
							@endcan
						@endif
						@if ($updateMode)
						@can('editar-entrega')
							<a class="btn btn-primary btn-lg" wire:click="update()">
								<i class="fa fa-save"></i> Actualizar
							</a> 
						@endcan
						@endif
					</div>
					@endif
				@endif
				
				<div wire:loading wire:target="importar,exportar,create,edit,destroy,agregar,store,update,quitar_activo,edit_activo,update_activo,guardarFirma,generarPdf,descargarPDF,pdfMode,notPdfMode, personal_id, responsable_id, buscar_dni, render, listarSelects">
					<x-loading-indicator/>
				</div>

				{{-- @if ($mostrar_carga)
					<x-loading-indicator/>	
				@endif --}}
							
			</div>
		</div>
	</div>
	@push('js')
	<script>

	Livewire.on('alert-success', $q => {
		$(".alert-success").fadeIn("slow");
			window.setTimeout(function() {
		$(".alert-success").fadeTo(500, 0).slideUp(500, function(){
			$(this).remove(); 
		});
	}, 4000); 
	})

	
	Livewire.on('alert-danger', $q => {
		$(".alert-danger").fadeIn("slow");
			window.setTimeout(function() {
		$(".alert-danger").fadeTo(500, 0).slideUp(500, function(){
			$(this).remove(); 
		});
	}, 4000); 
	})

	Livewire.on('alert-warning', $q => {
		$(".alert-warning").fadeIn("slow");
			window.setTimeout(function() {
		$(".alert-warning").fadeTo(500, 0).slideUp(500, function(){
			$(this).remove(); 
		});
	}, 4000); 
	})

	Livewire.on('alert-info', $q => {
		$(".alert-info").fadeIn("slow");
			window.setTimeout(function() {
		$(".alert-info").fadeTo(500, 0).slideUp(500, function(){
			$(this).remove(); 
		});
	}, 4000); 
	})

	Livewire.on('confirm', e => {
				if (!confirm('Confirma borrar Salida de Pallet : '+ e.argv +'? \nPallets borrados no pueden ser recuperados!')) { return }
				@this[e.callback](...e.argv)
	});
	
	</script>

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
		}

		const personal_id_select = new Choices('#personal_id', opciones);
		personal_id_select.passedElement.element.addEventListener('change', function (event) {
				dato = personal_id_select.getValue(true) !== undefined ? personal_id_select.getValue(true) : '' ;
				@this.set('personal_id', dato );
				deshabilitarDatosPersonal();
		});
		
		const empresa_id_select = new Choices('#empresa_id', opciones);
		empresa_id_select.passedElement.element.addEventListener('change', function (event) {
			if (empresa_id_select.getValue(true) !== undefined) {
				@this.set('empresa_id', empresa_id_select.getValue(true));
			}
		});
		
		const gerencia_id_select = new Choices('#gerencia_id', opciones);
		gerencia_id_select.passedElement.element.addEventListener('change', function (event) {
			if (gerencia_id_select.getValue(true) !== undefined) {
				@this.set('gerencia_id', gerencia_id_select.getValue(true));
			}
		});
		
		const sede_id_select = new Choices('#sede_id', opciones);
		sede_id_select.passedElement.element.addEventListener('change', function (event) {
			if (sede_id_select.getValue(true) !== undefined) {
				@this.set('sede_id', sede_id_select.getValue(true));
			}
		});
		
		const area_id_select = new Choices('#area_id', opciones);
		area_id_select.passedElement.element.addEventListener('change', function (event) {
			if (area_id_select.getValue(true) !== undefined) {
				@this.set('area_id', area_id_select.getValue(true));
			}
		});
		
		const cargo_id_select = new Choices('#cargo_id', opciones);
		cargo_id_select.passedElement.element.addEventListener('change', function (event) {
			if (cargo_id_select.getValue(true) !== undefined) {
				@this.set('cargo_id', cargo_id_select.getValue(true));
			}
		});
		
		const responsable_id_select = new Choices('#responsable_id', opciones);
		responsable_id_select.passedElement.element.addEventListener('change', function (event) {
			dato = responsable_id_select.getValue(true) !== undefined ? responsable_id_select.getValue(true) : '' ;
				@this.set('responsable_id', dato );
				deshabilitarDatosResponsable();
		});
		
		const responsable_area_id_select = new Choices('#responsable_area_id', opciones);
		responsable_area_id_select.passedElement.element.addEventListener('change', function (event) {
			if (responsable_area_id_select.getValue(true) !== undefined) {
				@this.set('responsable_area_id', responsable_area_id_select.getValue(true));
			}
		});
		
		const responsable_cargo_id_select = new Choices('#responsable_cargo_id', opciones);
		responsable_cargo_id_select.passedElement.element.addEventListener('change', function (event) {
			if (responsable_cargo_id_select.getValue(true) !== undefined) {
				@this.set('responsable_cargo_id', responsable_cargo_id_select.getValue(true));
			}
		});

		Livewire.on('actualizarDatosP', function (personal_id,empresa_id,gerencia_id,sede_id,area_id,cargo_id) {
			habilitarDatosPersonal();

			personal_id_select.setChoiceByValue(personal_id ?? '');
			empresa_id_select.setChoiceByValue(empresa_id ?? '');
			gerencia_id_select.setChoiceByValue(gerencia_id ?? '');
			sede_id_select.setChoiceByValue(sede_id ?? '');
			area_id_select.setChoiceByValue(area_id ?? '');				
			cargo_id_select.setChoiceByValue(cargo_id ?? '');
		});
		
		Livewire.on('actualizarDatosR', function (personal_id,area_id,cargo_id) {
			habilitarDatosResponsable();

			responsable_id_select.setChoiceByValue(personal_id ?? '');
			responsable_area_id_select.setChoiceByValue(area_id ?? '');				
			responsable_cargo_id_select.setChoiceByValue(cargo_id ?? '');
		});

		Livewire.on('listar_selects', function (personal,empresas,gerencias,sedes,areas,cargos,responsables) {
			const placeholder = [
					{ value: '', label: ' - Seleccione - '},
				];

			deshabilitarDatosPersonal();
			deshabilitarDatosResponsable();
			
			personal_id_select.clearChoices();
			empresa_id_select.clearChoices();
			gerencia_id_select.clearChoices();
			sede_id_select.clearChoices();
			area_id_select.clearChoices();
			cargo_id_select.clearChoices();
			responsable_id_select.clearChoices();
			responsable_area_id_select.clearChoices();
			responsable_cargo_id_select.clearChoices();		
			
			personal_id_select.setChoices(placeholder);
			empresa_id_select.setChoices(placeholder);
			gerencia_id_select.setChoices(placeholder);
			sede_id_select.setChoices(placeholder);
			area_id_select.setChoices(placeholder);
			cargo_id_select.setChoices(placeholder);
			responsable_id_select.setChoices(placeholder);
			responsable_area_id_select.setChoices(placeholder);
			responsable_cargo_id_select.setChoices(placeholder);
			
			personal_id_select.setChoices(personal);
			empresa_id_select.setChoices(empresas);
			gerencia_id_select.setChoices(gerencias);
			sede_id_select.setChoices(sedes);
			area_id_select.setChoices(areas);
			cargo_id_select.setChoices(cargos);
			responsable_id_select.setChoices(responsables);
			responsable_area_id_select.setChoices(areas);
			responsable_cargo_id_select.setChoices(cargos);
			
		});

		const deshabilitarDatosPersonal = () => {
			personal_id_select.disable();
			empresa_id_select.disable();
			gerencia_id_select.disable();
			sede_id_select.disable();
			area_id_select.disable();
			cargo_id_select.disable();
		};
		
		const deshabilitarDatosResponsable = () => {
			responsable_id_select.disable();
			responsable_area_id_select.disable();
			responsable_cargo_id_select.disable();
		};
		
		const habilitarDatosPersonal = () => {
			personal_id_select.enable();
			empresa_id_select.enable();
			gerencia_id_select.enable();
			sede_id_select.enable();
			area_id_select.enable();
			cargo_id_select.enable();
		};
		
		const habilitarDatosResponsable = () => {
			responsable_id_select.enable();
			responsable_area_id_select.enable();
			responsable_cargo_id_select.enable();
		};
    })
</script>

	@endpush

	            
	@push('styles')
	<style type="text/css">
		html { font-family:Calibri, Arial, Helvetica, sans-serif; font-size:11pt; background-color:white }
		a.comment-indicator:hover + div.comment { background:#ffd; position:absolute; display:block; border:1px solid black; padding:0.5em }
		a.comment-indicator { background:red; display:inline-block; border:1px solid black; width:0.5em; height:0.5em }
		div.comment { display:none }
		table { border-collapse:collapse; page-break-after:always }
		.gridlines td { border:1px dotted black }
		.gridlines th { border:1px dotted black }
		.b { text-align:center }
		.e { text-align:center }
		.f { text-align:right }
		.inlineStr { text-align:left }
		.n { text-align:right }
		.s { text-align:left }
		td.style0 { vertical-align:bottom; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:white }
		th.style0 { vertical-align:bottom; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:white }
		td.style1 { vertical-align:top; text-align:left; padding-left:0px; border-bottom:none #000000; border-top:1px solid #000000 !important; border-left:1px solid #000000 !important; border-right:none #000000; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:white }
		th.style1 { vertical-align:top; text-align:left; padding-left:0px; border-bottom:none #000000; border-top:1px solid #000000 !important; border-left:1px solid #000000 !important; border-right:none #000000; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:white }
		td.style2 { vertical-align:middle; text-align:center; border-bottom:1px solid #000000 !important; border-top:1px solid #000000 !important; border-left:1px solid #000000 !important; border-right:1px solid #000000 !important; font-weight:bold; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:white }
		th.style2 { vertical-align:middle; text-align:center; border-bottom:1px solid #000000 !important; border-top:1px solid #000000 !important; border-left:1px solid #000000 !important; border-right:1px solid #000000 !important; font-weight:bold; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:white }
		td.style3 { vertical-align:top; text-align:left; padding-left:0px; border-bottom:none #000000; border-top:none #000000; border-left:1px solid #000000 !important; border-right:1px solid #000000 !important; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:white }
		th.style3 { vertical-align:top; text-align:left; padding-left:0px; border-bottom:none #000000; border-top:none #000000; border-left:1px solid #000000 !important; border-right:1px solid #000000 !important; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:white }
		td.style4 { vertical-align:middle; text-align:left; padding-left:0px; border-bottom:none #000000; border-top:none #000000; border-left:1px solid #000000 !important; border-right:none #000000; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:white }
		th.style4 { vertical-align:middle; text-align:left; padding-left:0px; border-bottom:none #000000; border-top:none #000000; border-left:1px solid #000000 !important; border-right:none #000000; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:white }
		td.style5 { vertical-align:middle; text-align:left; padding-left:0px; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:white }
		th.style5 { vertical-align:middle; text-align:left; padding-left:0px; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:white }
		td.style6 { vertical-align:middle; text-align:left; padding-left:0px; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:white }
		th.style6 { vertical-align:middle; text-align:left; padding-left:0px; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:white }
		td.style7 { vertical-align:middle; text-align:left; padding-left:0px; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:1px solid #000000 !important; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:white }
		th.style7 { vertical-align:middle; text-align:left; padding-left:0px; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:1px solid #000000 !important; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:white }
		td.style8 { vertical-align:middle; text-align:left; padding-left:0px; border-bottom:1px solid #000000 !important; border-top:none #000000; border-left:1px solid #000000 !important; border-right:1px solid #000000 !important; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:white }
		th.style8 { vertical-align:middle; text-align:left; padding-left:0px; border-bottom:1px solid #000000 !important; border-top:none #000000; border-left:1px solid #000000 !important; border-right:1px solid #000000 !important; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:white }
		td.style9 { vertical-align:middle; text-align:left; padding-left:0px; border-bottom:1px solid #000000 !important; border-top:1px solid #000000 !important; border-left:1px solid #000000 !important; border-right:1px solid #000000 !important; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:white }
		th.style9 { vertical-align:middle; text-align:left; padding-left:0px; border-bottom:1px solid #000000 !important; border-top:1px solid #000000 !important; border-left:1px solid #000000 !important; border-right:1px solid #000000 !important; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:white }
		td.style10 { vertical-align:top; text-align:left; padding-left:0px; border-bottom:1px solid #000000 !important; border-top:none #000000; border-left:1px solid #000000 !important; border-right:1px solid #000000 !important; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:white }
		th.style10 { vertical-align:top; text-align:left; padding-left:0px; border-bottom:1px solid #000000 !important; border-top:none #000000; border-left:1px solid #000000 !important; border-right:1px solid #000000 !important; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:white }
		td.style11 { vertical-align:middle; text-align:left; padding-left:0px; border-bottom:1px solid #000000 !important; border-top:none #000000; border-left:1px solid #000000 !important; border-right:none #000000; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:white }
		th.style11 { vertical-align:middle; text-align:left; padding-left:0px; border-bottom:1px solid #000000 !important; border-top:none #000000; border-left:1px solid #000000 !important; border-right:none #000000; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:white }
		td.style12 { vertical-align:middle; text-align:left; padding-left:0px; border-bottom:1px solid #000000 !important; border-top:none #000000; border-left:none #000000; border-right:none #000000; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:white }
		th.style12 { vertical-align:middle; text-align:left; padding-left:0px; border-bottom:1px solid #000000 !important; border-top:none #000000; border-left:none #000000; border-right:none #000000; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:white }
		td.style13 { vertical-align:middle; text-align:left; padding-left:0px; border-bottom:1px solid #000000 !important; border-top:none #000000; border-left:none #000000; border-right:1px solid #000000 !important; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:white }
		th.style13 { vertical-align:middle; text-align:left; padding-left:0px; border-bottom:1px solid #000000 !important; border-top:none #000000; border-left:none #000000; border-right:1px solid #000000 !important; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:white }
		td.style14 { vertical-align:middle; text-align:left; padding-left:0px; border-bottom:1px solid #000000 !important; border-top:1px solid #000000 !important; border-left:1px solid #000000 !important; border-right:1px solid #000000 !important; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:white }
		th.style14 { vertical-align:middle; text-align:left; padding-left:0px; border-bottom:1px solid #000000 !important; border-top:1px solid #000000 !important; border-left:1px solid #000000 !important; border-right:1px solid #000000 !important; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:white }
		td.style15 { vertical-align:top; text-align:right; padding-right:0px; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; font-weight:bold; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:#FFFFFF }
		th.style15 { vertical-align:top; text-align:right; padding-right:0px; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; font-weight:bold; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:#FFFFFF }
		td.style16 { vertical-align:top; text-align:left; padding-left:0px; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:#FFFFFF }
		th.style16 { vertical-align:top; text-align:left; padding-left:0px; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:#FFFFFF }
		td.style17 { vertical-align:top; text-align:center; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; font-weight:bold; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:#FFFFFF }
		th.style17 { vertical-align:top; text-align:center; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; font-weight:bold; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:#FFFFFF }
		td.style18 { vertical-align:top; text-align:left; padding-left:0px; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; font-weight:bold; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:#FFFFFF }
		th.style18 { vertical-align:top; text-align:left; padding-left:0px; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; font-weight:bold; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:#FFFFFF }
		td.style19 { vertical-align:top; text-align:left; padding-left:0px; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:#FFFFFF }
		th.style19 { vertical-align:top; text-align:left; padding-left:0px; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:#FFFFFF }
		td.style20 { vertical-align:top; text-align:left; padding-left:0px; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:#FFFFFF }
		th.style20 { vertical-align:top; text-align:left; padding-left:0px; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:#FFFFFF }
		td.style21 { vertical-align:bottom; text-align:left; padding-left:0px; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:white }
		th.style21 { vertical-align:bottom; text-align:left; padding-left:0px; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:white }
		td.style22 { vertical-align:top; text-align:left; padding-left:0px; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; color:#000000; font-family:'Times New Roman'; font-size:11pt; background-color:white }
		th.style22 { vertical-align:top; text-align:left; padding-left:0px; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; color:#000000; font-family:'Times New Roman'; font-size:11pt; background-color:white }
		td.style23 { vertical-align:top; text-align:left; padding-left:0px; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:white }
		th.style23 { vertical-align:top; text-align:left; padding-left:0px; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:white }
		td.style24 { vertical-align:top; text-align:left; padding-left:0px; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; font-weight:bold; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:white }
		th.style24 { vertical-align:top; text-align:left; padding-left:0px; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; font-weight:bold; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:white }
		td.style25 { vertical-align:top; text-align:left; padding-left:0px; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; font-weight:bold; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:white }
		th.style25 { vertical-align:top; text-align:left; padding-left:0px; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; font-weight:bold; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:white }
		td.style26 { vertical-align:top; text-align:left; padding-left:0px; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; font-weight:bold; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:white }
		th.style26 { vertical-align:top; text-align:left; padding-left:0px; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; font-weight:bold; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:white }
		td.style27 { vertical-align:top; text-align:left; padding-left:0px; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; font-weight:bold; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:white }
		th.style27 { vertical-align:top; text-align:left; padding-left:0px; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; font-weight:bold; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:white }
		td.style28 { vertical-align:top; text-align:left; padding-left:0px; border-bottom:1px solid #000000 !important; border-top:none #000000; border-left:none #000000; border-right:none #000000; font-weight:bold; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:#FFFFFF }
		th.style28 { vertical-align:top; text-align:left; padding-left:0px; border-bottom:1px solid #000000 !important; border-top:none #000000; border-left:none #000000; border-right:none #000000; font-weight:bold; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:#FFFFFF }
		td.style29 { vertical-align:top; text-align:left; padding-left:0px; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; font-weight:bold; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:#FFFFFF }
		th.style29 { vertical-align:top; text-align:left; padding-left:0px; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; font-weight:bold; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:#FFFFFF }
		td.style30 { vertical-align:middle; text-align:center; border-bottom:none #000000; border-top:1px solid #000000 !important; border-left:1px solid #000000 !important; border-right:none #000000; font-weight:bold; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:#D9D9D9 }
		th.style30 { vertical-align:middle; text-align:center; border-bottom:none #000000; border-top:1px solid #000000 !important; border-left:1px solid #000000 !important; border-right:none #000000; font-weight:bold; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:#D9D9D9 }
		td.style31 { vertical-align:middle; text-align:center; border-bottom:none #000000; border-top:1px solid #000000 !important; border-left:none #000000; border-right:none #000000; font-weight:bold; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:#D9D9D9 }
		th.style31 { vertical-align:middle; text-align:center; border-bottom:none #000000; border-top:1px solid #000000 !important; border-left:none #000000; border-right:none #000000; font-weight:bold; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:#D9D9D9 }
		td.style32 { vertical-align:middle; text-align:center; border-bottom:none #000000; border-top:1px solid #000000 !important; border-left:none #000000; border-right:1px solid #000000 !important; font-weight:bold; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:#D9D9D9 }
		th.style32 { vertical-align:middle; text-align:center; border-bottom:none #000000; border-top:1px solid #000000 !important; border-left:none #000000; border-right:1px solid #000000 !important; font-weight:bold; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:#D9D9D9 }
		td.style33 { vertical-align:middle; text-align:center; border-bottom:none #000000; border-top:1px solid #000000 !important; border-left:none #000000; border-right:1px solid #000000 !important; font-weight:bold; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:#D9D9D9 }
		th.style33 { vertical-align:middle; text-align:center; border-bottom:none #000000; border-top:1px solid #000000 !important; border-left:none #000000; border-right:1px solid #000000 !important; font-weight:bold; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:#D9D9D9 }
		td.style34 { vertical-align:middle; text-align:center; border-bottom:1px solid #000000 !important; border-top:1px solid #000000 !important; border-left:1px solid #000000 !important; border-right:1px solid #000000 !important; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:white }
		th.style34 { vertical-align:middle; text-align:center; border-bottom:1px solid #000000 !important; border-top:1px solid #000000 !important; border-left:1px solid #000000 !important; border-right:1px solid #000000 !important; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:white }
		td.style35 { vertical-align:middle; text-align:left; padding-left:0px; border-bottom:1px solid #000000 !important; border-top:1px solid #000000 !important; border-left:1px solid #000000 !important; border-right:1px solid #000000 !important; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:white }
		th.style35 { vertical-align:middle; text-align:left; padding-left:0px; border-bottom:1px solid #000000 !important; border-top:1px solid #000000 !important; border-left:1px solid #000000 !important; border-right:1px solid #000000 !important; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:white }
		td.style36 { vertical-align:middle; text-align:left; padding-left:0px; border-bottom:1px solid #000000 !important; border-top:1px solid #000000 !important; border-left:1px solid #000000 !important; border-right:1px solid #000000 !important; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:white }
		th.style36 { vertical-align:middle; text-align:left; padding-left:0px; border-bottom:1px solid #000000 !important; border-top:1px solid #000000 !important; border-left:1px solid #000000 !important; border-right:1px solid #000000 !important; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:white }
		td.style37 { vertical-align:top; text-align:left; padding-left:0px; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; font-weight:bold; font-style:italic; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:white }
		th.style37 { vertical-align:top; text-align:left; padding-left:0px; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; font-weight:bold; font-style:italic; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:white }
		td.style38 { vertical-align:top; text-align:left; padding-left:0px; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; font-weight:bold; color:#000000; font-family:'Times New Roman'; font-size:11pt; background-color:white }
		th.style38 { vertical-align:top; text-align:left; padding-left:0px; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; font-weight:bold; color:#000000; font-family:'Times New Roman'; font-size:11pt; background-color:white }
		td.style39 { vertical-align:bottom; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:white }
		th.style39 { vertical-align:bottom; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:white }
		td.style40 { vertical-align:top; text-align:center; border-bottom:none #000000; border-top:1px solid #000000 !important; border-left:none #000000; border-right:none #000000; font-weight:bold; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:#FFFFFF }
		th.style40 { vertical-align:top; text-align:center; border-bottom:none #000000; border-top:1px solid #000000 !important; border-left:none #000000; border-right:none #000000; font-weight:bold; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:#FFFFFF }
		td.style41 { vertical-align:top; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:#FFFFFF }
		th.style41 { vertical-align:top; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:#FFFFFF }
		td.style42 { vertical-align:top; text-align:center; border-bottom:none #000000; border-top:1px solid #000000 !important; border-left:none #000000; border-right:none #000000; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:#FFFFFF }
		th.style42 { vertical-align:top; text-align:center; border-bottom:none #000000; border-top:1px solid #000000 !important; border-left:none #000000; border-right:none #000000; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:#FFFFFF }
		td.style43 { vertical-align:top; text-align:center; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; font-weight:bold; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:#FFFFFF }
		th.style43 { vertical-align:top; text-align:center; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; font-weight:bold; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:#FFFFFF }
		td.style44 { vertical-align:top; text-align:left; padding-left:0px; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; color:#000000; font-family:'Times New Roman'; font-size:11pt; background-color:#FFFFFF }
		th.style44 { vertical-align:top; text-align:left; padding-left:0px; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; color:#000000; font-family:'Times New Roman'; font-size:11pt; background-color:#FFFFFF }
		td.style45 { vertical-align:top; text-align:left; padding-left:0px; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:#FFFFFF }
		th.style45 { vertical-align:top; text-align:left; padding-left:0px; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:#FFFFFF }
		td.style46 { vertical-align:bottom; text-align:center; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:#FFFFFF }
		th.style46 { vertical-align:bottom; text-align:center; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:#FFFFFF }
		td.style47 { vertical-align:bottom; text-align:center; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:#FFFFFF }
		th.style47 { vertical-align:bottom; text-align:center; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:#FFFFFF }
		table.sheet0 col.col0 { width:84.04444348pt }
		table.sheet0 col.col1 { width:62.35555484pt }
		table.sheet0 col.col2 { width:69.13333254pt }
		table.sheet0 col.col3 { width:31.85555519pt }
		table.sheet0 col.col4 { width:38.63333289pt }
		table.sheet0 col.col5 { width:45pt }
		table.sheet0 col.col6 { width:110pt }
		table.sheet0 tr { height:15pt }
		table.sheet0 tr.row1 { height:30pt }
		table.sheet0 tr.row17 { height:48.75pt }
		table.sheet0 tr.row18 { height:15pt }
		table.sheet0 tr.row19 { height:15pt }
		table.sheet0 tr.row27 { height:83.25pt }
		table.sheet0 tr.row36 { height:32.25pt }
		table.sheet0 tr.row37 { height:36.75pt }
		#canvas {
			border: 1px solid black;
		}
		#firma_responsable {
			border: 1px solid black;
		}
		#firma_personal {
			border: 1px solid black;
		}
		#firma {
			border: 1px solid black;
		}
	  </style>
		
	@endpush
</div>
