
<div class="btn-group">
    @if (isset($canEdit) && !$canEdit)

    @else
        <button title="Editar" data-toggle="modal" class="btn btn-lg btn-outline-vanguard" wire:click="edit({{$id}})">
            <i class="fa fa-edit"></i>
        </button>
    @endif
    
    @if (isset($canDelete) && !$canDelete)

    @else
        <button title="Eliminar"
                class="btn btn-lg btn-outline-danger" 
                onclick="confirm('¿Confirma eliminar {{$id}} - {{ $name ?? '' }}? \n ¡Los datos eliminados no pueden ser recuperados!')||event.stopImmediatePropagation()" 
                wire:click="destroy({{$id}})">
            <i class="fa fa-trash"></i>
        </button>
    @endif
    
</div>