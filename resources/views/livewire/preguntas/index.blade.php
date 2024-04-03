{{-- @extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            @livewire('preguntas')
        </div>     
    </div>   
</div>
@endsection --}}

@extends('adminlte::page')

@section('title', 'Personal')

@section('content_header')
    <h1></h1>
@stop

@section('content')

@livewire('preguntas')

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