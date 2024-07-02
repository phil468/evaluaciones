@extends('adminlte::page')

{{-- @section('title', 'EVALUACIÓN DE DESEMPEÑO') --}}

@section('content_header')
    <h1></h1>
@stop

@section('content')

    @livewire('evaluador-has-evaluados', ['tipo_de_evaluacion_id' => $tipo_de_evaluacion_id])

    @if ( $tipo_de_evaluacion_id == App\Models\TipoDeEvaluacione::RESULTADOS )
        @php
            $evaluaciones_por_resultado = Auth::user()->personal->evaluaciones()
            ->join('evaluaciones', 'evaluador_has_evaluados.evaluacion_id', '=', 'evaluaciones.id')
            ->select('evaluador_has_evaluados.id')
            ->where('evaluaciones.tipo_de_evaluacion_id', App\Models\TipoDeEvaluacione::RESULTADOS)
            ->where('evaluaciones.fecha_para_mostrar_resultados', '<', now())
            ->get();
        @endphp

        @foreach ($evaluaciones_por_resultado as $value)
            @livewire('objetivos', ['evaluador_has_evaluado_id' => $value->id, 'readOnly' => true])
        @endforeach
    @endif

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