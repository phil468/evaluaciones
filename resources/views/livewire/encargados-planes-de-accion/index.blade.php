@extends('adminlte::page')

@section('title', 'Planes De Mejora')

@section('content_header')
    <h1></h1>
@stop

@section('content')

@livewire('encargados-planes-de-accions', [
        'ingreso' => $ingreso??null,
        'dashboard' => $dashboard??null,
        'empleado_id' => $empleado_id??null
    ])

@stop

@section('css')
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop

@section('js')
    <script type="text/javascript">
        // window.livewire.on('dataReturned', () => {
        //     location.hash = "#busqueda";
        //     location.hash = "#resultados";
        // });
    </script>
@stop