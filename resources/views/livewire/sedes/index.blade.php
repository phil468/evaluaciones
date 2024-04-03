@extends('adminlte::page')

@section('title', 'Sedes')

@section('content_header')
    <h1></h1>
@stop

@section('content')

@livewire('sedes')

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