@extends('adminlte::page')

@section('title', 'Planes De Mejora')

@section('content_header')
    <h1></h1>
@stop

@section('content')

    @livewire('encargados-planes-de-accions', [
        'ingreso' => $ingreso ?? null,
        'dashboard' => $dashboard ?? null,
        'empleado_id' => $empleado_id ?? null,
    ])

@stop

@section('css')
    <style nonce="{{ $nonce }}">
        .custom-file-input:lang(en)~.custom-file-label::after {
            content: "Buscar";
        }

        .custom-file-input:focus~.custom-file-label {
            border-color: #80bdff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }
    </style>
<style>
    .bg-light {
        background-color: #f8f9fa !important;
        cursor: not-allowed;
    }
    
    .alert-info {
        border-left: 4px solid #17a2b8;
    }
</style>
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
