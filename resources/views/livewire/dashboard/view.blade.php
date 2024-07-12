{{-- @section('title', __('Dashboard')) --}}
<div class="container-fluid">
	<div class="row justify-content-center">
		<div class="col-md-12">
            <div class="card rounded-xl">
                <div class="text-white card-header bg-vanguard rounded-t-xl"
					{{-- style="background-image: linear-gradient(90deg, #568ba5 0%, #500aa0 100%);" --}}
					@if (!$showHeader)
					hidden					
					@endif				
					>
					<div style="display: flex; justify-content: space-between; align-items: center;">
						<div class="float-left">
							<h4 class='h5'>
								{{$title}}
								{{-- Dashboard								 --}}
							</h4>
						</div>
						<div class="bottom-0 right-0 p-3 position-fixed z-index-3" style="z-index: 0; right: 0; bottom: 6em; opacity: 0.90;">
							@if (session()->has('message'))
							<div class="alert alert-success alert-dismissible fade show">
								<span>{{ session('message') }}</span>
								<button type="button" class="close" data-dismiss="alert" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							@endif
						</div>
					</div>
				</div>
				
				<div class="card-body">
					
					@if (!$vista_personal)
						<div class="row">
							<div class="form-group col-md-4 col-sm-4" wire:ignore>
								<label for="gerencia_sub_gerencia_de_evaluado">Gerencia/Subgerencia</label>
								<select name="gerencia_sub_gerencia_de_evaluado" class="form-control" id="gerencia_sub_gerencia_de_evaluado" placeholder="Gerencias/Sugerencias">
									<option value="">Seleccione</option>
									@foreach ($gerencia_sub_gerencia_de_evaluados as $id => $name)
										<option value="{{ $id }}">{{ $name }}</option>
									@endforeach											
								</select>
							</div>
							
							<div class="form-group col-md-4 col-sm-4" wire:ignore>
								<label for="area_de_evaluado">Área</label>
								<select name="area_de_evaluado" class="form-control" multiple id="area_de_evaluado" placeholder="Areas">
									<option value="">Seleccione</option>
									{{-- @foreach ($area_de_evaluados as $label => $value)
										<option value="{{ $label }}">{{ $value }}</option>
									@endforeach											 --}}
								</select>
							</div>
							
							{{-- <div class="form-group col-md-4 col-sm-4" wire:ignore>
								<label for="evaluado">Personal</label>
								<select name="evaluado" class="form-control" multiple class="form-control" id="evaluado" placeholder="Areas">
									<option value="">Seleccione</option>
								</select>
							</div> --}}
							
							{{-- <div class="form-group col-md-4 col-sm-4" wire:ignore>
								<label for="area_id">Área</label>
								<select name="area_id" class="form-control" wire:model.defer="area_id" multiple class="form-control" id="area_id" placeholder="Área">
									<option value="">Seleccione</option>
									@foreach ($areas as $id => $name)
										<option value="{{ $id }}">{{ $name }}</option>
									@endforeach											
								</select>
							</div> --}}
							{{-- <div class="form-group col-md-4 col-sm-4" wire:ignore>
								<label for="status_id">Estado</label>
								<select name="status_id" class="form-control" wire:model.defer="status_id" multiple class="form-control" id="status_id" placeholder="Estado">
									<option value="">Seleccione</option>
									@foreach ($estados as $id => $name)
										<option value="{{ $id }}">{{ $name }}</option>
									@endforeach											
								</select>
							</div>

							<div class="form-group col-md-4 col-sm-4">
								<label for="fecha_inicio">Fecha Inicio </label>
								<input name="fecha_inicio"								
								@if ($fecha_inicio)										
									style="   
									border: 2px solid;
									border-color: #ffc107;"
								@endif
								wire:model.defer="fecha_inicio" type="date" class="form-control" id="fecha_inicio" placeholder="Fecha Inicio">@error('fecha_inicio') <span class="error text-danger">{{ $message }}</span> @enderror							
							</div>
							<div class="form-group col-md-4 col-sm-4">
								<label for="fecha_final" >Fecha Final </label>
									<input name="fecha_final"									
									@if ($fecha_final)										
										style="   
										border: 2px solid;
										border-color: #ffc107;"
									@endif
									wire:model.defer="fecha_final" type="date" class="form-control" id="fecha_final" placeholder="Fecha Final">@error('fecha_final') <span class="error text-danger">{{ $message }}</span> @enderror							
							</div> --}}
							{{-- <div class="form-group col-md-4 col-sm-4">
								<button type="button" wire:click.prevent="generar_grafica()" class="align-bottom btn btn-primary close-modal w-100 h-100">Actualizar Gráfica</button>
							</div> --}}
						</div>
					@else

					@endif

					<div class="row">
						<div class="col-sm-12">

							<div style="width: 100%; height: 600px;"> <!-- Ajusta la altura según necesites -->
								<canvas wire.ignore id="chart"
									@if (!$mostrar_grafica)
										style="display:none;"
									@endif>
								</canvas>
							</div>						
							
							@if ($mostrar_grafica === true)
								<div class="d-flex justify-content-end">
									<div class="col-sm-6">
										@include('components.progress-bar-rango')										
									</div>
								</div>
							@endif
							
							@if ($mostrar_grafica === false)
								<p class="mb-2 h5">
									Promedio total por competencia										
								</p>

								@if (!$this->evaluacionPorCompetenciasFinalizada)
									<div class="alert alert-default rounded-2xl" role="alert">
										Una vez finalizada la evaluación de desempeño por competencias, se mostrarán los resultados obtenidos en la gráfica.
									</div>
								@else
									<div class="alert alert-default rounded-2xl" role="alert">
										No se encontró información
									</div>
								@endif
								
							@endif
						</div>
					</div>
					
					<div wire:loading.delay.long wire:target="generar_grafica">
						<x-loading-indicator/>
					</div>

				</div>
			</div>
		</div>
	</div>	
   
    @once
        @push('js')
            <script src="{{ asset('js/chart.js')}}"></script>
            <script src="{{ asset('js/chartjs-plugin-datalabels@2.js')}}"></script>
        @endpush
    @endonce
    
	@push('js')

    <script>
		Chart.defaults.font.size = 12;

		var ctx = document.getElementById('chart');
		var labels = {!! json_encode($this->secciones->pluck('nombre')) !!};
		var data = {!! json_encode($this->secciones->pluck('promedio')) !!};
		var valor_esperado_data = {!! json_encode($this->secciones->pluck('valor_esperado')) !!};
		var seccion_ids = {!! json_encode($this->secciones->pluck('seccion_id')) !!};
		var accion_de_click = null;

		labels.forEach((value, index) => {
			labels[index] = labels[index] + ' ('+data[index]+')';
		});

		// Add a line with the value from Livewire

		var backgroundColors = {!! json_encode($this->secciones->pluck('color')) !!};

		var borderColors = data.map((value) => 'rgba(0, 0, 0, 0.0)');
		var borderWidths = data.map((value) => 0);

		var sortedData = [...data].sort((a, b) => a - b);
		var lowestValues = sortedData.slice(0, 2);
        var secciones_bajas = [];

		if (!{!!json_encode($this->ingresar_plan)!!}) {
		    accion_de_click = null;
		} else {
		    accion_de_click = function(event, array) {
					if (array.length > 0) {
						var index = array[0].index;
						if (lowestValues.includes(data[index])) {
							var seccion_id = this.data.datasets[0].data_id[index];
							Livewire.emit('setValues', seccion_id);
						}
					}
				};
				data.forEach((value, index) => {
					if (lowestValues.includes(value)) {
						borderColors[index] = 'rgba(255, 99, 132, 1)';
						borderWidths[index] = 2;
						labels[index] = labels[index] + ' (Bajo)';
						
						// hacer un array de las secciones más bajas
						secciones_bajas.push(seccion_ids[index]);
					} else {
						borderColors[index] = 'rgba(0, 0, 0, 0.0)';
						borderWidths[index] = 0;
					}
				});
		}

		const chart = new Chart(ctx, {
			data: {
				labels: labels,
				datasets: [
					{
						type: 'bar',
						label: 'Promedio de competencia',
						data: data,
						data_id: seccion_ids,
						backgroundColor: backgroundColors,
						borderColor: borderColors,
						borderWidth: borderWidths,
						order:1,
						usePointStyle: false,
						pointStyle: 'rect',
					}
					// ,{
					// 	type: 'line',
					// 	borderWidth: 2,
					// 	label: 'Valor mínimo esperado ({{ number_format($this->valor_esperado,2) }})',//
					// 	data: valor_esperado_data,
					// 	datalabels: {
					// 		display: false,
					// 	},
					// 	borderColor: '#b3b3b3',
					// 	backgroundColor: 'transparent',
					// 	borderDash: [5, 5],
					// 	usePointStyle: true,
					// 	pointStyle: 'line',
					// 	pointRadius: 0,
					// 	order:2
					// }
				]
			},
			plugins: [ChartDataLabels],
			options: {
				responsive: true,
				maintainAspectRatio: false, // Esto permite que el gráfico se estire en altura. Ajusta según necesidad.
				legend: {
					labels: {
						usePointStyle: true,
					}
				},
				scales: {
						y: {
							title: {
								display: true,
								text: 'Competencias',
							},
						},
						x: {
							title: {
								display: true,
								text: 'Resultado'
							},
							min: 0.00,
							max: 10.00,
							ticks: {
								stepSize: 1.00
							},
						}
						},
				layout:{
					padding: {
						left: 20,
						right: 80,
						top: 20,
						bottom: 20
					}
				},
				indexAxis: 'y',
				onClick: accion_de_click,
				plugins : {
					legend: {
						display: true,
						position: 'top',
						labels: {
							usePointStyle: true,
						},
					},
					tooltip : {
						enabled: true,
					},
					datalabels: {
						align: 'end',
						anchor: 'end',
					},
					title: {
						display: true,
						text: 'Promedio total por competencia',
						// text: document.querySelector('.chart-footer').innerHTML,
						html: true,
						font: {
							size: 18
						}
					}

				},
				
			}
		});

        Livewire.on('dataUpdated', (promedios,nombres,colores) => {
            chart.data.datasets[0].data = promedios;
            chart.data.datasets[0].backgroundColor = colores;
			nombres.forEach((value, index) => {
				nombres[index] = nombres[index] + ' ('+promedios[index]+')';
			});
            chart.data.labels = nombres;
			chart.update();
        });

    </script>

	<script>
		Livewire.on('alert', $q => {
			$(".alert").fadeIn("slow");
				window.setTimeout(function() {
			$(".alert").fadeTo(500, 0).slideUp(500, function(){
				$(this).remove(); 
			});
		}, 4000); 
		})
	</script>

	<script>
		document.addEventListener('livewire:load', function () {
			console.log('Inicializar Choice.js');
			
			const opciones = {
				removeItemButton: true,
				itemSelectText: 'Seleccione',
				noChoicesText: 'No hay opciones para elegir'
			}
			
			const select = new Choices('#area_de_evaluado', {
				removeItemButton: true,
				itemSelectText: 'Seleccione',
				noChoicesText: 'No hay opciones para elegir',
			});

			select.setChoices(@json($area_de_evaluados), 'value', 'label', true);

			select.passedElement.element.addEventListener('change', function (event) {
				@this.set('area_de_evaluado', select.getValue(true));
				@this.call('generar_grafica')
			});
			
			const select2 = new Choices('#gerencia_sub_gerencia_de_evaluado', {
				removeItemButton: true,
				itemSelectText: 'Seleccione',
				noChoicesText: 'No hay opciones para elegir',
			});

			select2.passedElement.element.addEventListener('change', function (event) {
				@this.set('gerencia_sub_gerencia_de_evaluado', select2.getValue(true));
				@this.call('generar_grafica')
			});
			
			Livewire.on('actualizarAreas', function (areas) {
				select.clearChoices();
				select.removeActiveItems();
				select.setChoices(areas, 'value', 'label', true);
			});
		});
	</script>
	@endpush
</div>
