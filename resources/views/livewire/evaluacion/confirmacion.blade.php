<!-- Modal -->
<div wire:ignore.self class="modal fade" id="confirmacionModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="confirmacionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
       <div class="modal-content" style="
       border-radius: 15px;
       padding-top: 30px;
   ">
        
                <img src={{url('/img/evaluacion/icono_de_notificacion.png')}} alt="Imagen descriptiva"
                style="position: absolute; top: -110px; left: 50%; transform: translateX(-50%); max-width: 150px;">


            <div class="modal-body">
                <p class="text-center">Recuerda que solo tienes una oportunidad para realizar la evaluación.
<br>                    ¿Estás seguro de enviar tus repuestas?</p>
                <div class="text-center align-center">
                    <button type="button" wire:click.prevent="guardar()" class="rounded-full btn btn-vanguard" 
                    {{-- style="
                    border-radius: 25px;
                " --}}
                >Aceptar</button>
                    <button type="button" class="rounded-full btn btn-outline-vanguard" 
                    {{-- style="
                    border-radius: 25px;
                "  --}}
                data-dismiss="modal">Volver</button>                    
                </div>
            </div>
        </div>    
    </div>
</div>
