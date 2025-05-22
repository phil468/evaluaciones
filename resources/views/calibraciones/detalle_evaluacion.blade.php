{{-- filepath: resources/views/calibraciones/detalle_evaluacion.blade.php --}}
@extends('adminlte::page')

@section('title', 'Detalle de Evaluación por Competencia')

@section('content_header')
    <h1>Detalle de Evaluación</h1>
@stop

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="text-white card-header bg-vanguard">
            Detalle de Evaluación por Competencia
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Persona</th>
                        <th>Competencia</th>
                        <th>Pregunta</th>
                        <th>Puntaje</th>
                        <th>Puntaje Calibrado</th>
                        <th>Área</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $item)
                        <tr>
                            <td>{{ $item->personal->name ?? '' }}</td>
                            <td>{{ $item->competencia->name ?? '' }}</td>
                            <td>{{ $item->pregunta->pregunta ?? '' }}</td>
                            <td>{{ $item->puntaje }}</td>
                            <td
                                @if($item->puntaje_calibrado !== '' && $item->puntaje_calibrado !== null && $item->puntaje_calibrado != 0)
                                    style="background-color: #ffe066; font-weight: bold;"
                                @endif
                            >
                                {{ $item->puntaje_calibrado }}
                            </td>
                            <td>{{ $item->area->name ?? '' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection