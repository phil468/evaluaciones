    <div class="btn-group">
        {{-- <a title="Eliminar" class="btn btn-danger" onclick="confirm('¿Confirma eliminar {{$id}} - {{ $name ?? '' }}? \n ¡Los datos eliminados no pueden ser recuperados!')
        ||event.stopImmediatePropagation()" wire:click="destroy({{$id}})"><i class="fa fa-trash"></i></a> --}}
            @if(isset($pdf)&& !empty($pdf))
                <a title="Descargar PDF" class="btn btn-warning text-white" wire:click="descargarPDF('{{$pdf}}')"><i class="fas fa-file-pdf fa-lg"></i></a>              
            @endif 
    </div>
 