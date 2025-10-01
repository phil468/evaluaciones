@extends('adminlte::page')

@section('title', 'Configuracion Planes de Accion')

@section('content_header')
    <h1></h1>
@stop

@section('content')

    @livewire('planes-configuracion')

@stop

@section('css')

@stop

@section('js')
    <script nonce="{{ $nonce }}" type="text/javascript"></script>
@stop
