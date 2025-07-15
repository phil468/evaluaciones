@extends('adminlte::page')

@section('title', 'Evaluación')

@section('content_header')
    <h1 class="text-center font-style-class"> Evaluación de Desempeño por <span class="text-vanguard" style="color: #568ba5;">Competencias</span> </h1>
@stop

@section('content')

    @livewire('evaluacion', ['evaluacion_id' => $evaluacion_id])

@stop

@section('css')
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop

@section('js')
    <script nonce="{{ $nonce }}" type="text/javascript">
        // window.livewire.on('dataReturned', () => {
        //     location.hash = "#busqueda";
        //     location.hash = "#resultados";
        // });
    </script>
    <script>
        Livewire.on('confirmarGuardado', () => {
            if (confirm(
                    'Recuerde que solo tiene una oportunidad para realizar la evaluación.\n ¿Está seguro de enviar sus respuestas?'
                    )) {
                //Bloquear boton con id = confirmarGuardado
                document.getElementById('confirmarGuardado').disabled = true;
                Livewire.emit('guardar');
            }
        });
    </script>
@stop
