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

@section('title', 'Importar Preguntas')

@section('content_header')
    <h1></h1>
@stop

@section('content')

    @livewire('importar-preguntas')

@stop

@section('css')
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop

@section('js')
    <script nonce="{{ $nonce }}" type="text/javascript">
        // window.livewire.on('dataReturned', () => {
        //     location.hash = "#busqueda";
        //     location.hash = "#resultados";
        // });
    </script>
@stop
