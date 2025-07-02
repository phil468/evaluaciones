@extends('adminlte::page')

@section('title', 'Configuración de Campaña')

@section('content_header')
    <h1></h1>
@stop

@section('content')
    <div class="row">
        <!-- Panel de Información de la Campaña -->
        <div class="col-12">
            <div class="card rounded-xl">
                <div class="text-white card-header bg-vanguard rounded-t-xl">
                    <h3 class="card-title">Información de la Campaña</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><strong>ID:</strong></label>
                                <p>{{ $campania->id }}</p>
                            </div>
                            <div class="form-group">
                                <label><strong>Nombre:</strong></label>
                                <p>{{ $campania->name }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><strong>Estado:</strong></label>
                                <p>{{ $campania->estado ? 'Activo' : 'Inactivo' }}</p>
                            </div>
                            <div class="form-group">
                                <label><strong>Fecha de Creación:</strong></label>
                                <p>{{ $campania->created_at->format('d/m/Y H:i:s') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Panel de Preguntas -->
        <div class="col-12">
            <div class="card rounded-xl">
                <div class="text-white card-header bg-vanguard rounded-t-xl">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <h3 class="card-title">Gestión de Preguntas</h3>
                        <div>
                            <button class="mr-2 btn btn-sm btn-light" id="importButton">
                                <i class="fas fa-file-import"></i> Importar Preguntas
                            </button>
                            <button class="btn btn-sm btn-light" id="createButton">
                                <i class="fas fa-plus"></i> Nueva Pregunta
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div id="preguntas-table"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Importar Preguntas -->
    <div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="importModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="text-white modal-header bg-vanguard">
                    <h5 class="modal-title" id="importModalLabel">Importar Preguntas</h5>
                    <button type="button" class="text-white close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="importForm">
                        <div class="form-group">
                            <label for="importFile">Archivo Excel</label>
                            <input type="file" class="form-control-file" id="importFile" accept=".xlsx,.xls">
                            <small class="form-text text-muted">
                                El archivo debe contener las columnas: pregunta, competencia, dominio, campaña, numero_orden, quitar
                            </small>
                        </div>
                    </form>
                    <div class="mt-4">
                        <h6>Previsualización:</h6>
                        <div id="preview-table"></div>
                        <div id="validation-messages" class="mt-3">
                            <h6>Mensajes de Validación:</h6>
                            <ul class="list-group" id="validation-list"></ul>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-vanguard" id="processImport">Procesar Importación</button>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
@stop

@section('js')

    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            // Tabla principal de preguntas
            var table = new Tabulator("#preguntas-table", {
                ajaxURL: "{{ route('campanias.preguntas', $campania->id) }}",
                layout: "fitColumns",
                columns: [{
                        title: "ID",
                        field: "id",
                        headerSort: false,
                        width: 60
                    },
                    {
                        title: "Pregunta",
                        field: "pregunta",
                        headerFilter: "input"
                    },
                    {
                        title: "Competencia",
                        field: "competencia"
                    },
                    {
                        title: "Dominio",
                        field: "dominio"
                    },
                    {
                        title: "N° Orden",
                        field: "numero_orden",
                        width: 100
                    },
                    {
                        title: "Estado",
                        field: "estado",
                        formatter: "tickCross",
                        width: 80
                    },
                    {
                        title: "Acciones",
                        formatter: function(cell, formatterParams, onRendered) {
                            return `<div class="btn -group" role="group">
                                <button class="mr-1 btn btn-info btn-sm edit-button"><i class="fas fa-edit"></i></button>
                                <button class="btn btn-danger btn-sm delete-button"><i class="fas fa-trash"></i></button>
                            </div>`;
                        },
                        width: 100,
                        hozAlign: "center"
                    }
                ]
            });

            // Tabla de previsualización para importación
            var previewTable = new Tabulator("#preview-table", {
                layout: "fitColumns",
                columns: [{
                        title: "Pregunta",
                        field: "pregunta"
                    },
                    {
                        title: "Competencia",
                        field: "competencia"
                    },
                    {
                        title: "Dominio",
                        field: "dominio"
                    },
                    {
                        title: "Campaña",
                        field: "campana"
                    },
                    {
                        title: "N° Orden",
                        field: "numero_orden"
                    },
                    {
                        title: "Quitar",
                        field: "quitar"
                    },
                    {
                        title: "Estado",
                        field: "estado_validacion",
                        formatter: function(cell) {
                            return cell.getValue() ?
                                '<i class="fas fa-check text-success"></i>' :
                                '<i class="fas fa-times text-danger"></i>';
                        }
                    }
                ]
            });

            // Evento para importar archivo
            $('#importButton').click(function() {
                $('#importModal').modal('show');
            });

            // Procesar archivo Excel cuando se selecciona
            $('#importFile').change(function(e) {
                var file = e.target.files[0];
                var reader = new FileReader();
                reader.onload = function(e) {
                    var data = new Uint8Array(e.target.result);
                    var workbook = XLSX.read(data, {
                        type: 'array'
                    });
                    var firstSheet = workbook.Sheets[workbook.SheetNames[0]];
                    var jsonData = XLSX.utils.sheet_to_json(firstSheet);

                    // Validar datos y mostrar previsualización
                    validateAndPreviewData(jsonData);
                };
                reader.readAsArrayBuffer(file);
            });

            function validateAndPreviewData(data) {
                // Limpiar mensajes anteriores
                $('#validation-list').empty();                // Validar cada registro
                var validatedData = data.map(function(row) {
                    var isValid = true;
                    var messages = [];

                    // Verificar campos requeridos
                    if (!row.pregunta || !row.competencia || !row.dominio || !row.campaña || !row.numero_orden) {
                        isValid = false;
                        messages.push("Faltan campos requeridos");
                    }

                    // Validar formato de pregunta
                    if (row.pregunta) {
                        if (row.pregunta.length < 10) {
                            isValid = false;
                            messages.push("La pregunta debe tener al menos 10 caracteres");
                        }
                        if (row.pregunta.split(' ').length < 3) {
                            isValid = false;
                            messages.push("La pregunta debe contener al menos 3 palabras");
                        }
                    }

                    // Validar número de orden
                    if (row.numero_orden) {
                        if (isNaN(row.numero_orden) || row.numero_orden < 1) {
                            isValid = false; 
                            messages.push("El número de orden debe ser un número positivo");
                        }
                    }

                    // Validar campaña
                    if (row.campaña && row.campaña !== '{{ $campania->name }}') {
                        isValid = false;
                        messages.push("La campaña debe coincidir con la campaña actual");
                    }

                    // Validar campo quitar
                    if (row.quitar && row.quitar.toLowerCase() !== 'x' && row.quitar !== '') {
                        isValid = false;
                        messages.push("El campo quitar solo puede estar vacío o contener 'x'");
                    }

                    // Agregar resultado de validación al registro
                    return {
                        ...row,
                        estado_validacion: isValid,
                        mensajes: messages
                    };
                });

                // Mostrar datos en la tabla de previsualización
                previewTable.setData(validatedData);                // Contar registros válidos e inválidos
                var totalRegistros = validatedData.length;
                var registrosValidos = validatedData.filter(r => r.estado_validacion).length;
                var registrosInvalidos = totalRegistros - registrosValidos;

                // Mostrar resumen
                $('#validation-list').append(
                    `<li class="list-group-item list-group-item-info">
                        Resumen de validación:
                        <ul>
                            <li>Total de registros: ${totalRegistros}</li>
                            <li>Registros válidos: ${registrosValidos}</li>
                            <li>Registros con errores: ${registrosInvalidos}</li>
                        </ul>
                    </li>`
                );

                // Mostrar mensajes de validación detallados
                validatedData.forEach(function(row, index) {
                    if (!row.estado_validacion) {
                        row.mensajes.forEach(function(mensaje) {
                            $('#validation-list').append(
                                `<li class="list-group-item list-group-item-danger">
                                    <strong>Fila ${index + 1}:</strong> ${mensaje}
                                    <br>
                                    <small class="text-muted">Datos: ${JSON.stringify(row)}</small>
                                </li>`
                            );
                        });
                    }
                });

                // Habilitar/deshabilitar botón de importación según validación
                $('#processImport').prop('disabled', registrosInvalidos > 0);
                if (registrosInvalidos > 0) {
                    $('#processImport').removeClass('btn-vanguard').addClass('btn-secondary');
                } else {
                    $('#processImport').addClass('btn-vanguard').removeClass('btn-secondary');
                }
            }

            // Procesar importación
            $('#processImport').click(function() {
                var data = previewTable.getData();

                $.ajax({
                    url: "{{ route('campanias.importar-preguntas', $campania->id) }}",
                    type: 'POST',
                    data: {
                        preguntas: data,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        $('#importModal').modal('hide');
                        table.replaceData();
                        Swal.fire({
                            icon: 'success',
                            title: '¡Éxito!',
                            text: 'Preguntas importadas correctamente'
                        });
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: '¡Error!',
                            text: 'Error al importar preguntas'
                        });
                    }
                });
            });
        });
    </script>
@stop
