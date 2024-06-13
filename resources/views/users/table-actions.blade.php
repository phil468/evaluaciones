<div class="btn-group">
    @canany(['editar-user'])
        <a class="btn btn-vanguard btn-sm" href="{{ route('users.edit',$id) }}"><i class="fa fa-edit"></i></a>
    @endcan
    @canany(['borrar-user'])
        <a title="Eliminar" class="btn btn-danger" onclick="confirm('¿Confirma eliminar {{$id}} - {{ $name ?? '' }}? \n ¡Los datos eliminados no pueden ser recuperados!')
    ||event.stopImmediatePropagation()" wire:click="destroy({{$id}})"><i class="fa fa-trash"></i></a>
    @endcan   
</div>