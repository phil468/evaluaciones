@extends('adminlte::page')

@section('title', 'Objetivos Precargados')

@section('content_header')
    <h1>Objetivos Precargados</h1>
@stop

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="rounded-2xl card">
                <div class="text-white card-header bg-vanguard rounded-t-xl">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <h4 class="h5">Lista Objetivos Precargado </h4>
                        </div>
                        
                        @can('crear-objetivos-precargados')
                        <a title="Nuevo" class="btn btn-sm btn-default rounded-xl" id="btn-nuevo">
                            <i class="fa fa-plus"></i> Nuevo
                        </a>
                        @endcan
                    </div>
                </div>
                
                <div class="card-body">
                    <div id="objetivos-table"></div>
                </div>
                
                <div id="loading-indicator" style="display: none;">
                    <div class="text-center">
                        <div class="spinner-border" role="status">
                            <span class="sr-only">Cargando...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
{{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tabulator-tables@5.1.0/dist/css/tabulator.min.css"> --}}
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

    .rounded-xl {
        border-radius: 0.5rem;
    }

    .tabulator-row .tabulator-cell {
        vertical-align: middle;
    }
</style>
@stop

@section('js')
{{-- <script src="https://cdn.jsdelivr.net/npm/tabulator-tables@5.1.0/dist/js/tabulator.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> --}}
<script src="{{ asset('js/objetivos-precargados.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        ObjetivosPrecargados.init({
            dataUrl: "{{ route('objetivos-precargados.data') }}",
            storeUrl: "{{ route('objetivos-precargados.store') }}",
            updateUrl: "{{ route('objetivos-precargados.update', ['id' => ':id']) }}",
            destroyUrl: "{{ route('objetivos-precargados.destroy', ['id' => ':id']) }}",
            actualizarValorUrl: "{{ route('objetivos-precargados.actualizar-valor', ['id' => ':id']) }}",
            subirEvidenciaUrl: "{{ route('objetivos-precargados.subir-evidencia', ['id' => ':id']) }}",
            eliminarEvidenciaUrl: "{{ route('objetivos-precargados.eliminar-evidencia', ['id' => ':id']) }}",
            getEvidenciasUrl: "{{ route('objetivos-precargados.get-evidencias', ['id' => ':id']) }}",
            csrf: "{{ csrf_token() }}",
            tiposObjetivo: @json($tipos_objetivo),
            evaluaciones: @json($evaluaciones),
            canEdit: @json(Auth::user()->can('editar-objetivos-precargados')),
            canDelete: @json(Auth::user()->can('borrar-objetivos-precargados')),
        });
    });
</script>
@stop