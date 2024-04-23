<!-- Modal -->
<div wire:ignore.self class="modal fade" id="indicacionesModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="indicacionesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
       <div class="modal-content" style="
       border-radius: 15px;
   ">
        
        <div style="position: absolute; top: -35px; left: 50%; transform: translateX(-50%);">
            <div style="width: 70px; height: 70px; border-radius: 50%; background-color: #6ECBC9; display: flex; justify-content: center; align-items: center;">
                <i class="fas fa-bell" style="font-size: 3em; color: aliceblue;"></i>
            </div>
        </div>

            <div class="modal-body">

                <h5 class="h5">INDICACIONES</h5>
                <ul style="
                    display: block !important;
                    list-style-type: disc !important;
                    margin-block-start: 1em !important;
                    margin-block-end: 1em !important;
                    margin-inline-start: 0px !important;
                    margin-inline-end: 0px !important;
                    padding-inline-start: 40px !important;
                    unicode-bidi: isolate !important;
                ">
                    <li>En la siguiente sección deberás calificar (del 1 al 10) las competencias que se brindan.</li>
                    <li>En todo momento necesitamos que respondas de la manera más neutral y sincera posible.</li>
                    <li>Recuerda que solo tiene un intento para completar la evaluación, una vez enviado.</li>
                </ul>
                <div class="text-center align-center">
                    <button type="button" wire:click.prevent="aceptar()" class="rounded-full btn btn-vanguard " 
                    {{-- style="
                    border-radius: 25px;
                "  --}}
                data-dismiss="modal" >Aceptar</button>
                    <a type="button" href="{{url('/evaluaciones-de-desempeno/1')}}" class="rounded-full btn btn-outline-vanguard " 
                    {{-- style="
                    border-radius: 25px;
                "  --}}
                >Volver</a>
                </div>
            </div>
        </div>    
    </div>
</div>
