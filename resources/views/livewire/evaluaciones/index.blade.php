@extends('adminlte::page')

@section('title', 'Evaluaciones') 

@section('content_header')
    <h1></h1>
@stop

@section('content')

@livewire('evaluaciones')
@livewire('planes-configuracion')

@stop

@section('css')
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop

@section('js')
    <script type="text/javascript">

    </script>
@stop