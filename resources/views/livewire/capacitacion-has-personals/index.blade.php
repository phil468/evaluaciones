@extends('adminlte::page')

@section('title', 'Capacitaciones')

@section('content_header')
    <h1></h1>
@stop

@section('content')
@livewire('capacitacion-has-personals', ['capacitacion_id' => $capacitacion_id])

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