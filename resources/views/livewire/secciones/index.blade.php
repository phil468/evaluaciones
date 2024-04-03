{{-- @extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            @livewire('secciones')
        </div>     
    </div>   
</div>
@endsection --}}


@extends('adminlte::page')

@section('title', 'Evaluación')

@section('content_header')
    <h1></h1>
@stop

@section('content')

@livewire('secciones')

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