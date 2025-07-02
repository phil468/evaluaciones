@extends('adminlte::page')

@section('title', 'Resultados de Equipo')

@section('content_header')
    <h1 class="text-center h1">Resultados de Equipo</h1>
@stop

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <!-- Tabla de resultados -->
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th class="text-center" style="color: #568ca5;">Puntaje Comp.</th>
                            <th class="text-center" style="color: #568ca5;">Puntaje PDI</th>
                            <th class="text-center" style="color: #568ca5;">Puntaje Obj.</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Aquí se iterarán los miembros del equipo dinámicamente --}}
                        @foreach ($miembrosEquipo ?? [] as $miembro)
                        <tr>
                            <td>{{ $miembro['nombre'] ?? 'José Aguilar' }}</td>
                            <td class="text-center">{{ $miembro['puntaje_competencias'] ?? 'XX' }}</td>
                            <td class="text-center">{{ $miembro['puntaje_pdi'] ?? 'XX' }}</td>
                            <td class="text-center">{{ $miembro['puntaje_objetivos'] ?? 'XX' }}</td>
                            <td class="text-center">
                                <a href="{{ route('resultados-de-equipo.detalle', ['id' => $miembro['id'] ?? 1]) }}" 
                                   class="btn btn-vanguard btn-sm">Ver resultados</a>
                            </td>
                        </tr>
                        @endforeach
                        
                        {{-- Ejemplo estático (eliminar en producción) --}}
                        <tr>
                            <td>José Aguilar</td>
                            <td class="text-center">XX</td>
                            <td class="text-center">XX</td>
                            <td class="text-center">XX</td>
                            <td class="text-center">
                                <a href="{{ route('resultados-de-equipo.detalle', ['id' => 1]) }}" 
                                   class="rounded-xl btn btn-vanguard btn-sm">Ver resultados</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
<style>
    .btn-vanguard {
        background-color: #568ca5;
        border-color: #568ca5;
        color: white;
    }
    
    .btn-vanguard:hover {
        background-color: #457891;
        border-color: #457891;
        color: white;
    }
    
    .card {
        border-radius: 15px;
        box-shadow: 0 0 10px rgba(0,0,0,0.05);
    }
</style>
@stop

@section('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Tabla de Resultados de Equipo cargada');
    });
</script>
@stop