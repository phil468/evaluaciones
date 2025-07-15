@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1></h1>
@stop

@section('content')
    @livewire('dashboard', ['title' => 'Dashboard General'])
@stop

@section('css')
@stop

@section('js')
    <script nonce="{{ $nonce }}" type="text/javascript"></script>
@stop
