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

    @include('livewire.objetivos-lista.historial')
    
@stop

@section('css')
    <style>
        .evidencias-container {
            max-height: 150px;
            overflow-y: auto;
        }
        
        .evidencias-lista {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }
        
        .evidencias-lista .btn-link {
            text-align: left;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        #historial-contenido td {
            max-width: 300px;
            overflow-wrap: break-word;
        }
        
        .modal-xl {
            max-width: 90%;
        }
        
        @media (max-width: 768px) {
            .modal-xl {
                max-width: 95%;
            }
        }
    
    </style>
@stop

@section('js')
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            let table = new Tabulator("#objetivos-table", {
                ajaxURL: "{{ route('objetivos-lista.data') }}",
                layout: "fitDataFill",
                pagination: "local",
                paginationSize: 10,
                paginationSizeSelector: [10, 25, 50, 100],
                columns: [
                    {
                        title: "ID",
                        field: "id",
                        headerFilter: true,
                        width: 50,
                        // download: false  // Esta línea evita que la columna se exporte
                    },
                    {
                        title: "Historial",
                        field: "historial",
                        formatter: function(cell) {
                            let data = cell.getValue();
                            return `
                                <button class="btn btn-sm btn-info rounded-xl" 
                                    onclick="verHistorial(${data.id})" 
                                    data-toggle="tooltip" 
                                    title="Ver historial de cambios">
                                    <i class="fas fa-history"></i>
                                    <span class="badge badge-light">${data.total_cambios}</span>
                                </button>
                            `;
                        },
                        headerFilter: true,
                        width: 100,
                        download: false  // Esta línea evita que la columna se exporte
                    },    
                    {
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
                        title: "Evidencias Estado",
                        field: "evidencias_estado",
                        formatter: "html",
                        headerFilter: true,
                    },
                    {
                        title: "Evidencias",
                        field: "evidencias",
                        formatter: function(cell) {
                            let data = cell.getValue();
                            
                            if (data.sin_evidencias) {
                                return '<span class="badge badge-secondary">Marcado check SIN EVIDENCIAS</span>';
                            }
                            
                            if (!data.tiene_evidencias) {
                                return '<span class="badge badge-warning">No ha cargado evidencias</span>';
                            }
                            
                            let evidenciasList = data.lista.map(ev => `
                                <a href={{ route('download', '') }}/${ev.id} 
                                class="btn btn-link btn-sm text-decoration-none"
                                target="_blank">
                                <i class="fas fa-file-download"></i> ${ev.nombre}
                                </a>`
                            ).join('<br>');
                            
                            return `
                                <div class="evidencias-container">
                                    <div class="evidencias-lista">
                                        ${evidenciasList}
                                    </div>
                                </div>
                            `;
                        },                        
                        accessorDownload: function(value, data) {
                            if (value.sin_evidencias) {
                                return "Marcado check SIN EVIDENCIAS";
                            }
                            
                            if (!value.tiene_evidencias) {
                                return "No ha cargado evidencias";
                            }
                            
                            return "Tiene Evidencias";
                        },
                        headerFilter: true,
                        width: 200
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
                        title: "Info",
                        field: "info_estado",
                        headerFilter: true,
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
                        headerFilter: true,
                        accessorDownload: function(value, data) {
                            return value.mensaje;
                        },
                    },
                    {
                        title: "Fecha de Creación",
                        field: "created_at",
                        headerFilter: true,
                        // formatter: "timestamp",
                        // formatterParams: {
                        //     inputFormat: "YYYY-MM-DD HH:mm:ss",
                        //     outputFormat: "DD/MM/YYYY HH:mm",
                        //     invalidPlaceholder: "(invalid date)",
                        // },
                        // accessorDownload: function(value, data) {
                        //     return moment(value).format("DD/MM/YYYY HH:mm");
                        // },
                        // download: true,                        
                    },
                    {
                        title: "Fecha de Modificación",
                        field: "updated_at",
                        headerFilter: true,
                        // formatter: "date",
                        // formatterParams: {
                        //     inputFormat: "YYYY-MM-DD HH:mm:ss",
                        //     outputFormat: "DD/MM/YYYY HH:mm",
                        //     invalidPlaceholder: "(invalid date)",
                        // },
                        // accessorDownload: function(value, data) {
                        //     return moment(value).format("DD/MM/YYYY HH:mm");
                        // },
                        // download: true,                        
                    }
            ],
                rowFormatter: function(row) {
                    let data = row.getData();
                    if (data.estado === "REALIZADO") {
                        row.getElement().style.backgroundColor = "#d4edda"; // Color verde claro de Bootstrap
                        row.getElement().style.color = "#155724"; // Color de texto verde oscuro
                    } else if (data.estado === "REGISTRADO") {
                        row.getElement().style.backgroundColor = "#fff3cd"; // Color amarillo claro de Bootstrap
                        row.getElement().style.color = "#856404"; // Color de texto amarillo oscuro
                    }
                },
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
                // window.location.href = `/download/${id}`;
                window.location.href = "{{ route('download', '') }}/" + id;
            };
        });
    </script>

    <script>
        // ... código existente ...
        
        // Función para ver historial
        function verHistorial(id) {
            // Mostrar loading
            $('#historial-contenido').html('<tr><td colspan="5" class="text-center"><i class="fas fa-spinner fa-spin"></i> Cargando...</td></tr>');
            $('#auditoriaModal').modal('show');
            
            // Hacer la petición
            // fetch(`/objetivos-lista/historial/${id}`)
            fetch("{{ route('objetivos-lista.historial', '') }}/" + id)
                .then(response => response.json())
                .then(data => {
                    let html = '';
                    
                    data.forEach(item => {
                        html += `
                            <tr>
                                <td>
                                    <span class="badge badge-${item.evento === 'created' ? 'success' : 'info'}">
                                        ${item.evento === 'created' ? 'Creación' : 'Actualización'}
                                    </span>
                                </td>
                                <td>${item.usuario}</td>
                                <td>${item.fecha}</td>
                                <td>
                                    ${formatearCambios(item.antiguos_valores)}
                                </td>
                                <td>
                                    ${formatearCambios(item.nuevos_valores)}
                                </td>
                            </tr>
                        `;
                    });
                    
                    $('#historial-contenido').html(html || '<tr><td colspan="5" class="text-center">No hay cambios registrados</td></tr>');
                })
                .catch(error => {
                    console.error('Error:', error);
                    $('#historial-contenido').html('<tr><td colspan="5" class="text-center text-danger">Error al cargar el historial</td></tr>');
                });
        }

        function formatearCambios(valores) {
            if (!valores || Object.keys(valores).length === 0) {
                return '<em class="text-muted">Sin cambios</em>';
            }
            
            return Object.entries(valores)
                .map(([key, value]) => `
                    <div class="mb-1">
                        <strong>${key}:</strong> 
                        <span class="text-muted">${value ?? 'No definido'}</span>
                    </div>
                `)
                .join('');
        }

        // Cerrar modal
        $('#auditoriaModal').on('hidden.bs.modal', function () {
            $('#historial-contenido').html('');
        });
        
        // Inicializar tooltips
        $(function () {
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
    
@stop
