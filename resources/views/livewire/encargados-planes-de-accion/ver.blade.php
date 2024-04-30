@section('title', __('Encargados Planes De Accions'))
<div class="container-fluid">
	<div class="row justify-content-center">
		<div class="col-md-12">
            <div class="card rounded-xl">
                <div class="text-white card-header bg-vanguard rounded-t-xl">
					<div style="display: flex; justify-content: space-between; align-items: center;">
						<div class="float-left">
							<h5 class="h5">Resultados de XXX </h4>
						</div>
					</div>
				</div>
				
				<div class="card-body">
					<canvas id="myChart"></canvas>
				</div>
                <div wire:loading wire:target="store,update,create,edit,destroy">
                    <x-loading-indicator />
                </div>	
			</div>
		</div>
	</div>
	<script>
        var ctx = document.getElementById('myChart').getContext('2d');
        var labels = {!! json_encode($secciones->pluck('nombre')) !!};
        var data = {!! json_encode($secciones->pluck('promedio')) !!};
        var backgroundColors = data.map((value) => 'rgba(75, 192, 192, 0.2)');
        var borderColors = data.map((value) => 'rgba(75, 192, 192, 1)');

        var sortedData = [...data].sort((a, b) => a - b);
        var lowestValues = sortedData.slice(0, 2);

        data.forEach((value, index) => {
            if (lowestValues.includes(value)) {
                backgroundColors[index] = 'rgba(255, 99, 132, 0.2)';
                borderColors[index] = 'rgba(255, 99, 132, 1)';
            }
        });

        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Promedio de valor numérico',
                    data: data,
                    backgroundColor: backgroundColors,
                    borderColor: borderColors,
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                onClick: function(event, array) {
                    if (array.length > 0) {
                        var index = array[0].index;
                        if (lowestValues.includes(data[index])) {
                            window.location.href = '/ruta/a/tu/enlace';
                        }
                    }
                }
            }
        });
    </script>
</div>
