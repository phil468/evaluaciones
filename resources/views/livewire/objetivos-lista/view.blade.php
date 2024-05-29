{{-- @section('title', __('Seguimiento de Objetivos')) --}}
<div class="container-fluid">
	<div class="row justify-content-center">
		<div class="col-md-12">
			<div class="card rounded-xl">
                <div class="text-white card-header bg-vanguard rounded-t-xl">
					<div style="display: flex; justify-content: space-between; align-items: center;">
						<div class="float-left">
							<h5 class="h5">Respuesta de Evaluación por Resultados</h5>
						</div>
						@if (session()->has('message'))
						<div wire:poll.4s class="btn btn-sm btn-success" style="margin-top:0px; margin-bottom:0px;"> {{ session('message') }} </div>
						@endif
					</div>
				</div>
				@include('livewire.objetivos-lista.historial')
				<div class="card-body">
					@livewire('objetivos-lista-table')
				</div>
			</div>
		</div>
	</div>
</div>
