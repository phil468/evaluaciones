<!-- Modal -->
<div wire:ignore.self class="modal fade" id="indicacionesModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="indicacionesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
       <div class="modal-content" style="border-radius: 15px;">        
        <div style="position: absolute; top: -35px; left: 50%; transform: translateX(-50%);">
            <div style="width: 70px; height: 70px; border-radius: 50%; background-color: #6ECBC9; display: flex; justify-content: center; align-items: center;">
                <i class="fas fa-bell" style="font-size: 3em; color: aliceblue;"></i>
            </div>
        </div>

            <div class="modal-body">

                <h5 class="h5">INDICACIONES</h5>
                
                <p>En la siguiente sección, deberás calificar cada premisa con una puntuación del 0 al 10:</p>
                
                <ol class="escala-lista" type="1">
                    @foreach($escalaMediciones as $escala)
                    <li style="margin-bottom: 8px; display: list-item !important;">
                        <strong style="color: {{ $escala->color }}">{{ $escala->name }} ({{ $escala->valor_menor }}{{ $escala->valor_mayor != $escala->valor_menor ? '-'.$escala->valor_mayor : '' }}):</strong> 
                        {{ $escala->interpretacion }}
                    </li>
                    @endforeach
                </ol>
                
                <div class="mt-3 form-check">
                    <input class="form-check-input" type="checkbox" value="1" id="aceptoEscala" wire:model="acepto_escala">
                    <label class="form-check-label" for="aceptoEscala">
                        He leído y entiendo correctamente la definición de la escala.
                    </label>
                    @error('acepto_escala') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                
                <div class="mt-3 text-center align-center">
                    <button type="button" wire:click.prevent="aceptar()" class="rounded-full btn btn-vanguard" 
                            data-dismiss="modal" {{ !$acepto_escala ? 'disabled' : '' }}>Aceptar</button>
                    <a type="button" href="{{url('/evaluaciones-de-desempeno/1')}}" class="rounded-full btn btn-outline-vanguard">Volver</a>
                </div>
            </div>
        </div>    
    </div>
</div>
