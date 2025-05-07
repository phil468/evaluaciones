@extends('adminlte::page')

@section('title', 'Seguimiento Encargados Planes')

@section('content_header')
    <h1></h1>
@stop

@section('content')

    @livewire('seguimiento-encargados-planes')

@stop

@section('css')
@stop

@section('js')
    <script nonce="{{ $nonce }}" type="text/javascript"></script>
@stop
