@extends('adminlte::page')

@section('title', 'Planes De Accion')

@section('content_header')
    <h1></h1>
@stop

@section('content')

    @php
    // dd(App\Models\PlanesConfiguracion::          // ->where()
    //         vigente()
    //         ->get(), now()
        

    //     );
        $campania = 
        App\Models\PlanesConfiguracion::select('planes_de_accion_configuracion.campania_id')
            // ->where()
            ->vigente()
            // ->where('planes_de_accion_configuracion.tipo_de_evaluacion_id', $tipo_de_evaluacion_id)
            ->groupBy('planes_de_accion_configuracion.campania')
            ->orderBy('planes_de_accion_configuracion.campania', 'desc')
            ->get();
        // dd($campania);

        // buscamos campaña cuyo campo es_campania_actual es true
        $campania_actual = App\Models\Campania::where('es_campania_actual', true)->first();

    @endphp

    @if ($campania->isEmpty())
        @include('livewire.planes-de-mejora.planes_de_mejora_no_vigentes')
    @endif

    @foreach ($campania as $value)
        @livewire('encargados-planes-de-accions', [
            'ingreso' => $ingreso ?? null,
            'dashboard' => $dashboard ?? null,
            'empleado_id' => $empleado_id ?? null,
            'campania_id' => $value->campania_id,
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
        {{-- enviar la campaña activa de ahora --}}
        {{-- @livewire('dashboard', [
            'personal_id' => $empleado_id,
            'vista_personal' => true,
            'title' => 'Dashboard del personal',
            'ingresar_plan' => true,
            'showHeader' => false,
            'campania_id' => $campania_actual->id ?? 0,
        ]) --}}
    @endisset

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
