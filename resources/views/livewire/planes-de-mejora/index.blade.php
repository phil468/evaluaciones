@extends('adminlte::page')

@section('title', 'Planes De Accion')

@section('content_header')
    <h1></h1>
@stop

@section('content')

@livewire('encargados-planes-de-accions', [
        'ingreso' => $ingreso??null,
        'dashboard' => $dashboard??null,
        'empleado_id' => $empleado_id??null
    ])


@isset($ingreso)
    @livewire('dashboard', ['personal_id' => auth()->user()->personal_id, 'vista_personal' => true, 'title' => 'Resultados de evaluación'])
@endisset


@isset($dashboard)
    @livewire('dashboard', 
    [
        'personal_id' => $empleado_id, 
        'vista_personal' => true, 
        'title' => 'Dashboard del personal', 
        'ingresar_plan' => true, 
        'showHeader' => false
    ])
@endisset

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