@extends('adminlte::page')

@section('title', 'Objetivos')

@section('content_header')
    <h1></h1>
@stop

@section('content')
    @livewire('objetivos', ['evaluador_has_evaluado_id' => $evaluacion_id])
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