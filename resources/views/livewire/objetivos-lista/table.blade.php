@extends('adminlte::page')

@section('title', 'Respuesta de Evaluación por Resultados')

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
                                <h5 class="h5">Respuesta de Evaluación por Resultados</h5>
                            </div>
                            @if (session()->has('message'))
                                <div wire:poll.4s class="btn btn-sm btn-success" style="margin-top:0px; margin-bottom:0px;">
                                    {{ session('message') }} </div>
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
                        <div id="objetivos-table"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop

@section('css')
@stop

@section('js')
    <script nonce="{{ $nonce }}" type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            let table = new Tabulator("#objetivos-table", {
                ajaxURL: "{{ route('objetivos-lista.data') }}",
                layout: "fitDataFill",
                pagination: "local",
                paginationSize: 10,
                paginationSizeSelector: [10, 25, 50, 100],
                columns: [{
                        title: "Estado",
                        field: "estado",
                        headerFilter: true
                    },
                    {
                        title: "Evaluador",
                        field: "evaluador",
                        headerFilter: true
                    },
                    {
                        title: "Evaluado",
                        field: "evaluado",
                        headerFilter: true
                    },
                    {
                        title: "Cargo",
                        field: "cargo_evaluado",
                        headerFilter: true
                    },
                    {
                        title: "Meta",
                        field: "meta",
                        headerFilter: true
                    },
                    {
                        title: "% Participación",
                        field: "porcentaje_participacion",
                        headerFilter: true
                    },
                    {
                        title: "Tipo",
                        field: "tipo_objetivo",
                        headerFilter: true
                    },
                    {
                        title: "Resultado Anterior",
                        field: "resultado_anterior",
                        headerFilter: true
                    },
                    {
                        title: "Mínimo",
                        field: "minimo",
                        headerFilter: true
                    },
                    {
                        title: "Máximo",
                        field: "maximo",
                        headerFilter: true
                    },
                    {
                        title: "Valor",
                        field: "valor",
                        headerFilter: true
                    },
                    {
                        title: "Evidencias",
                        field: "evidencias",
                        formatter: "html",
                        headerFilter: true
                    },
                    {
                        title: "% Logro",
                        field: "porcentaje_logro",
                        headerFilter: true
                    },
                    {
                        title: "Peso Ponderado",
                        field: "peso_ponderado",
                        headerFilter: true
                    },
                    {
                        title: "Validación",
                        field: "estado_validacion",
                        formatter: function(cell) {
                            let value = cell.getValue();
                            let classes = {
                                'success': 'badge-success',
                                'warning': 'badge-warning',
                                'danger': 'badge-danger'
                            };
                            return `<div class="badge ${classes[value.estado]}" data-toggle="tooltip" title="${value.mensaje}">
                                    ${value.mensaje}
                                </div>`;
                        },
                        headerFilter: true
                    }
                ],
                locale: true,
                langs: {
                    "es-es": {
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
                table.download("xlsx", "objetivos.xlsx", {
                    sheetName: "Objetivos"
                });
            });

            // Función para descargar PDF
            document.getElementById('download-pdf').addEventListener('click', function() {
                table.download("pdf", "objetivos.pdf", {
                    orientation: "landscape",
                    title: "Objetivos"
                });
            });

            // Función para descargar evidencias
            window.descargarEvidencias = function(id) {
                window.location.href = `/download/${id}`;
            };
        });
    </script>
@stop
