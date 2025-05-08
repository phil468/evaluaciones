@extends('adminlte::page')

@section('title', 'Respuestas de Evaluación por Competencias')

@section('content_header')
    <h1></h1>
@stop

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card rounded-xl">
                    <div class="text-white card-header bg-vanguard rounded-t-xl">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <div class="float-left">
                                <h5 class="h5">Respuestas de Evaluación por Competencias</h5>
                            </div>
                            @if (session()->has('message'))
                                <div wire:poll.4s class="btn btn-sm btn-success" style="margin-top:0px; margin-bottom:0px;">
                                    {{ session('message') }}
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <button id="download-xlsx" class="btn btn-default rounded-xl">
                                <i class="mr-1 fas fa-file-excel"></i> Exportar Excel
                            </button>
                            <button id="download-pdf" class="btn btn-default rounded-xl">
                                <i class="mr-1 fas fa-file-pdf"></i> Exportar PDF
                            </button>
                        </div>
                        <div id="respuestas-table"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>    
@stop

@section('js')
<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function() {
        let table = new Tabulator("#respuestas-table", {
            ajaxURL: "{{ route('respuestas.data') }}",
            layout: "fitDataFill",
            pagination: "local",
            paginationSize: 50,
            paginationSizeSelector: [10, 25, 50, 100],
            columns: [
                {
                    title: "ID",
                    field: "id",
                    headerFilter: true,
                    width: 80
                },
                {
                    title: "ID Evaluado",
                    field: "evaluado_id",
                    headerFilter: true,
                    width: 100
                },
                {
                    title: "Evaluado",
                    field: "evaluado",
                    headerFilter: true
                },
                {
                    title: "Competencia",
                    field: "competencia",
                    headerFilter: true
                },
                {
                    title: "Pregunta",
                    field: "pregunta",
                    headerFilter: true,
                    width: 300
                },
                {
                    title: "Puntuación",
                    field: "puntuacion",
                    headerFilter: true,
                    width: 100
                },
                {
                    title: "Cargo",
                    field: "cargo_evaluado",
                    headerFilter: true
                },
                {
                    title: "Área",
                    field: "area_evaluado",
                    headerFilter: true
                },
                {
                    title: "Gerencia",
                    field: "gerencia_evaluado",
                    headerFilter: true
                }
            ],
            locale: true,
            langs: {                
                "es": {
                    "data": {
                        "loading": "Cargando", //data loader text
                        "error": "Error", //data error text
                    },
                    "columns": {},
                    "ajax": {
                        "loading": "Cargando",
                        "error": "Error"
                    },
                    "groups": {
                        "item": "item",
                        "items": "items"
                    },
                    "pagination": {
                        "page_size": "Tamaño de página",
                        "page_title": "Mostrar página",
                        "first": "Primera",
                        "first_title": "Primera página",
                        "last": "Última",
                        "last_title": "Última página",
                        "prev": "Anterior",
                        "prev_title": "Página anterior",
                        "next": "Siguiente",
                        "next_title": "Página siguiente",
                        "all": "Todo"
                    },
                    "headerFilters": {
                        "default": "Filtrar columna...",
                        "columns": {}
                    }
                }
            }
        });

        // Función para descargar Excel
        document.getElementById('download-xlsx').addEventListener('click', function() {
            table.download("xlsx", "respuestas.xlsx", {
                sheetName: "Respuestas"
            });
        });

        // Función para descargar PDF
        document.getElementById('download-pdf').addEventListener('click', function() {
            table.download("pdf", "respuestas.pdf", {
                orientation: "landscape",
                title: "Respuestas de Evaluación"
            });
        });
    });
</script>
@stop

@section('css')
<style>
    .tabulator {
        font-size: 14px;
    }
    
    .tabulator-row {
        height: 40px;
    }
</style>
@stop