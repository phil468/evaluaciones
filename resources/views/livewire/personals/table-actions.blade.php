<div class="btn-group">
    @canany(['editar-personal'])
        <a title="Editar" data-toggle="modal" data-target="#updateModal" class="btn btn-vanguard" wire:click="edit({{$id}})"><i class="fa fa-edit"></i></a>
    @endcan
    @canany(['borrar-personal'])
        <a title="Eliminar" class="btn btn-danger" onclick="confirm('¿Confirma eliminar {{$id}} - {{ $name ?? '' }}? \n ¡Los datos eliminados no pueden ser recuperados!')
    ||event.stopImmediatePropagation()" wire:click="destroy({{$id}})"><i class="fa fa-trash"></i></a>
    @endcan   
        @if(isset($pdf))
            <a title="Descargar PDF" class="text-white btn btn-warning" wire:click="descargarPDF('{{$pdf}}')"><i class="fas fa-file-pdf fa-lg"></i></a>              
        @endif 
</div>