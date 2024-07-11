@extends('adminlte::page')

@section('title', 'Planes De Accion')

@section('content_header')
    <h1></h1>
@stop

@section('content')

@php
    $campania = 
    App\Models\PlanesConfiguracion::select('planes_de_accion_configuracion.campania')
    ->vigente()
    // ->where('planes_de_accion_configuracion.tipo_de_evaluacion_id', $tipo_de_evaluacion_id)
    ->groupBy('planes_de_accion_configuracion.campania')
    ->orderBy('planes_de_accion_configuracion.campania', 'desc')
    ->get();
@endphp

@if ($campania->isEmpty())
    @include('livewire.planes-de-mejora.planes_no_vigentes')
@endif

@foreach ($campania as $value)
    @livewire('encargados-planes-de-accions', [
            'ingreso' => $ingreso??null,
            'dashboard' => $dashboard??null,
            'empleado_id' => $empleado_id??null
        ])
@endforeach

{{-- @isset($ingreso)
    @livewire('dashboard', [
        'personal_id' => auth()->user()->personal_id, 
        'vista_personal' => true, 
        'title' => 'Resultados de evaluación'
        ]
        )
@endisset --}}

@isset($dashboard)
    @livewire('dashboard', [
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