<!-- filepath: c:\laragon\www\evaluaciones\resources\views\livewire\evaluacion\indicaciones-view.blade.php -->
<div class="modal fade" id="indicacionesModalView" tabindex="-1" role="dialog" aria-labelledby="indicacionesModalViewLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
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
                
                <div class="mt-3 text-center align-center">
                    <button type="button" class="rounded-full btn btn-vanguard" data-dismiss="modal">Aceptar</button>
                </div>
            </div>
        </div>    
    </div>
</div>