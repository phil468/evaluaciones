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
				<div class="card-body">
                    <div>
                        <form wire:submit.prevent="import">
                            <div class="form-group">
                                <label for="archivo">Archivo Excel</label>
                                <input type="file" class="form-control" id="archivo" wire:model="archivo">
                                @error('archivo') <span class="error text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-group">
                                <label for="evaluacion_id">Evaluación</label>
                                <select class="form-control" id="evaluacion_id" wire:model="evaluacion_id">
                                    <option value="">Seleccione una evaluación</option>
                                    @foreach($evaluaciones as $evaluacion)
                                        <option value="{{ $evaluacion->id }}">{{ $evaluacion->title }}</option>
                                    @endforeach
                                </select>
                                @error('evaluacion_id') <span class="error text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-group">
                                <label for="seccion_id">Sección</label>
                                <select class="form-control" id="seccion_id" wire:model="seccion_id">
                                    <option value="">Seleccione una sección</option>
                                    @foreach($secciones as $seccion)
                                        <option value="{{ $seccion->id }}">{{ $seccion->name }}</option>
                                    @endforeach
                                </select>
                                @error('seccion_id') <span class="error text-danger">{{ $message }}</span> @enderror
                            </div>

                            <button type="submit" class="btn btn-primary">Importar Preguntas</button>
                        </form>

                        @if (session()->has('message'))
                            <div class="mt-3 alert alert-success">
                                {{ session('message') }}
                            </div>
                        @endif

                        @if (session()->has('error'))
                            <div class="mt-3 alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif
                    </div>
				</div>
			</div>
		</div>
	</div>
</div>
                