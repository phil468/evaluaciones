<div class="btn-group">
    @canany(['editar-capacitacion'])
        <a title="Editar" data-toggle="modal" data-target="#updateRegistroModal" class="btn btn-vanguard" wire:click="edit({{$id}})"><i class="fa fa-edit"></i></a>
    @endcan
    @canany(['editar-capacitacion'])
        <a title="Quitar de lista" class="btn btn-warning" onclick="confirm('¿Confirma quitar de la lista {{$id}} - {{ $name ?? '' }}? \n ¡Se perderán todas las asistencias registradas!')
    ||event.stopImmediatePropagation()" wire:click="destroy({{$id}})">
    <i class="fa fa-minus" aria-hidden="true"></i>
    {{-- <i class="fa fa-trash"></i> --}}
</a>
    @endcan
        {{-- @if(isset($pdf))
            <a title="Descargar PDF" class="text-white btn btn-warning" wire:click="descargarPDF('{{$pdf}}')"><i class="fas fa-file-pdf fa-lg"></i></a>              
        @endif  --}}
</div>