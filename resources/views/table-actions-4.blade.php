
<div class="btn-group">
    <a title="Editar" data-toggle="modal" class="btn btn-lg btn-outline-vanguard" wire:click="edit({{$id}})"><i class="fa fa-edit"></i></a>
    
    <a title="Eliminar" class="btn btn-lg btn-outline-danger" onclick="confirm('¿Confirma eliminar {{$id}} - {{ $name ?? '' }}? \n ¡Los datos eliminados no pueden ser recuperados!')||event.stopImmediatePropagation()" wire:click="destroy({{$id}})"><i class="fa fa-trash"></i></a>
    
</div>