<!-- Modal -->
<div wire:ignore.self class="modal fade" id="graciasModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="graciasModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="
       border-radius: 15px;
       padding-top: 30px;
   ">
        
                <img src={{url('/img/evaluacion/icono_de_notificacion.png')}} alt="Imagen descriptiva"
                style="position: absolute; top: -110px; left: 50%; transform: translateX(-50%); max-width: 150px;">

            <div class="modal-body">

                <p class="text-center" style="font-size:1.5rem;">¡Gracias por contribuir con el desarrollo de nuestro equipo!</p>
                <div class="text-center align-center">
                    @if ($evaluacion_por_objetivos)
                        <a type="button"  href="{{url('/evaluaciones-de-desempeno/2')}}" class="rounded-full btn btn-vanguard" >Aceptar</a>                 
                    @else
                        <a type="button"  href="{{url('/evaluaciones-de-desempeno/1')}}" class="rounded-full btn btn-vanguard" >Aceptar</a>                 
                    @endif
                </div>
            </div>
        </div>    
    </div>
</div>
