@extends('adminlte::page')

{{-- @section('title', 'EVALUACIÓN DE DESEMPEÑO') --}}

@section('content_header')
    <h1></h1>
@stop

@section('content')

    @php
        $campania_vigentes = 
        App\Models\Evaluacione::select('evaluaciones.campania')
        ->vigente()
        ->where('evaluaciones.tipo_de_evaluacion_id', $tipo_de_evaluacion_id)
        ->groupBy('evaluaciones.campania')
        ->orderBy('evaluaciones.campania', 'desc')
        ->get();

        $campanias = App\Models\Evaluacione::select('evaluaciones.campania')
        ->where('evaluaciones.tipo_de_evaluacion_id', $tipo_de_evaluacion_id)
        ->groupBy('evaluaciones.campania')
        ->orderBy('evaluaciones.campania', 'desc')
        ->get();
    @endphp

    @if ($campania_vigentes->isEmpty())
        @include('livewire.evaluador-has-evaluados.evaluaciones_no_vigentes')
    @endif
    
    @php
        $campania_planes_de_mejora = 
        App\Models\PlanesConfiguracion::select('planes_de_accion_configuracion.campania')
        ->vigente()
        ->groupBy('planes_de_accion_configuracion.campania')
        ->orderBy('planes_de_accion_configuracion.campania', 'desc')
        ->get();
    @endphp

    @foreach ($campania_vigentes as $value)
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

    @if ( $tipo_de_evaluacion_id == App\Models\TipoDeEvaluacione::COMPETENCIAS )
        @foreach ($campanias as $value)
                @livewire('dashboard', [
                    'personal_id' => auth()->user()->personal_id, 
                    'vista_personal' => true, 
                    'title' => 'Resultados de evaluación',
                    'ingresar_plan' => false,
                    'showHeader' => true,
                    'campania' => $value->campania
                    ]
                    )
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