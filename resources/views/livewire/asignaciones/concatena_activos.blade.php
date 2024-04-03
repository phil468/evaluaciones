    <div class="">
        {{-- <a title="Eliminar" class="btn btn-danger" onclick="confirm('¿Confirma eliminar {{$id}} - {{ $name ?? '' }}? \n ¡Los datos eliminados no pueden ser recuperados!')
        ||event.stopImmediatePropagation()" wire:click="destroy({{$id}})"><i class="fa fa-trash"></i></a> --}}
            @if(isset($id))
            @php
                $asignacion = App\Models\Asignacione::find($id);
                // foreach ($asignacion->activos as $activo) {
                //     echo $activo->descripcion;
                // }
            @endphp
            @foreach ($asignacion->activos as $activo)
            <p class="mb-1">{!!$activo->descripcion!!} </p>
                
            @endforeach
            @endif 
    </div>
 