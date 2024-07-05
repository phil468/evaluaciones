@extends('adminlte::page')

{{-- @section('title', 'EVALUACIÓN DE DESEMPEÑO') --}}

@section('content_header')
    <h1></h1>
@stop

@section('content')

    @php
        $campania = 
        App\Models\Evaluacione::select('evaluaciones.campania')
        ->vigente()
        ->where('evaluaciones.tipo_de_evaluacion_id', $tipo_de_evaluacion_id)
        ->groupBy('evaluaciones.campania')
        ->orderBy('evaluaciones.campania', 'desc')
        ->get();
    @endphp

    @if ($campania->isEmpty())
        @include('livewire.evaluador-has-evaluados.evaluaciones_no_vigentes')
    @endif
    
    @foreach ($campania as $value)
        @livewire('evaluador-has-evaluados', ['tipo_de_evaluacion_id' => $tipo_de_evaluacion_id , 'campania' => $value->campania])
    @endforeach

    @if ( $tipo_de_evaluacion_id == App\Models\TipoDeEvaluacione::RESULTADOS )
        @php
            $evaluaciones_por_resultado = Auth::user()->personal->evaluaciones()
            ->join('evaluaciones', 'evaluador_has_evaluados.evaluacion_id', '=', 'evaluaciones.id')
            ->select('evaluador_has_evaluados.id')
            ->where('evaluaciones.tipo_de_evaluacion_id', App\Models\TipoDeEvaluacione::RESULTADOS)
            ->where('evaluaciones.fecha_para_mostrar_resultados', '<', now())
            ->orderBy('evaluaciones.campania', 'desc')
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