@extends('adminlte::page')

@section('title', 'Dominio Pregunta')

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
                                <h5 class="h5">Dominio Pregunta</h5>
                            </div>
                            <div class="float-right">
                                <button class="btn btn-sm btn-light" id="createButton"><i class="fas fa-plus"></i>
                                    Nuevo</button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="example-table"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Crear/Editar DominioHasPregunta -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-vanguard">
                    <h5 class="text-white modal-title h5" id="editModalLabel">Crear/Editar Dominio Pregunta</h5>
                    <button type="button" class="text-white close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editForm">
                        <input type="hidden" id="editId">
                        <div class="form-group">
                            <label for="dominio_id">Dominio</label>
                            <select class="form-control" id="dominio_id" style="width: 100%;">
                                @foreach ($dominios as $dominio)
                                    <option value="{{ $dominio->id }}">{{ $dominio->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="pregunta_id">Pregunta</label>
                            <select class="form-control" id="pregunta_id" style="width: 100%;">
                                @foreach ($preguntas as $pregunta)
                                    <option value="{{ $pregunta->id }}">{{ $pregunta->pregunta }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="numero_orden">Número de Orden</label>
                            <input type="number" class="form-control" id="numero_orden">
                        </div>
                        <div class="form-group">
                            <label for="estado">Estado</label>
                            <select class="form-control" id="estado">
                                <option value="1">Activo</option>
                                <option value="0">Inactivo</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-vanguard" id="saveChanges">Guardar Cambios</button>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
@stop

@section('js')
    <script type="text/javascript">
        $(document).ready(function() {
            // $('.select2').select2({
            //     theme: "bootstrap"
            // });

            var table = new Tabulator("#example-table", {
                ajaxURL: "{{ route('dominio-has-preguntas.data') }}",
                ajaxConfig: "get",
                layout: "fitDataFill",
                columns: [{
                        title: "ID",
                        field: "id",
                        width: 70
                    },
                    {
                        title: "Acciones",
                        field: "actions",
                        formatter: function(cell, formatterParams) {
                            return "@can('editar-dominio-pregunta')<button class='text-white btn btn-sm btn-warning edit-button' data-id='" +
                                cell.getRow().getData().id +
                                "'><i class='fas fa-edit'></i></button>@endcan" +
                                "@can('borrar-dominio-pregunta')<button class='btn btn-sm btn-danger delete-button' data-id='" +
                                cell.getRow().getData().id +
                                "'><i class='fas fa-trash'></i></button>@endcan";
                        }
                    },
                    {
                        title: "Dominio",
                        field: "dominio.name"
                    },
                    {
                        title: "Pregunta",
                        field: "pregunta.pregunta"
                    },
                    {
                        title: "Número de Orden",
                        field: "numero_orden"
                    },
                    {
                        title: "Estado",
                        field: "estado",
                        formatter: "tickCross",
                        sorter: "boolean",
                        width: 80
                    },
                ],
                locale: true,
                langs: {
                    "es-419": {
                        "data": {
                            "loading": "Cargando",
                            "error": "Error",
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

            // Evento click para el botón "Editar"
            $("#example-table").on("click", ".edit-button", function() {
                var id = $(this).data("id");

                $.get("{{ route('dominio-has-preguntas.index') }}/" + id, function(data) {
                    $('#editId').val(data.id);
                    $('#dominio_id').val(data.dominio_id).trigger('change');
                    $('#pregunta_id').val(data.pregunta_id).trigger('change');
                    $('#numero_orden').val(data.numero_orden);
                    $('#estado').val(data.estado);
                    $('#editModal').modal('show');
                });
            });

            // Evento click para el botón "Crear Nuevo"
            $("#createButton").on("click", function() {
                $('#editId').val('');
                $('#dominio_id').val('').trigger('change');
                $('#pregunta_id').val('').trigger('change');
                $('#numero_orden').val('');
                $('#estado').val(1);
                $('#editModal').modal('show');
            });

            // Evento click para el botón "Guardar Cambios"
            $('#saveChanges').on('click', function() {
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: '¿Deseas guardar los cambios?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, guardar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        var id = $('#editId').val();
                        var data = {
                            dominio_id: $('#dominio_id').val(),
                            pregunta_id: $('#pregunta_id').val(),
                            numero_orden: $('#numero_orden').val(),
                            estado: $('#estado').val(),
                            _token: '{{ csrf_token() }}',
                        };

                        var url = (id == '') ? "{{ route('dominio-has-preguntas.store') }}" : "{{ route('dominio-has-preguntas.update', '') }}/" + id;
                        var type = (id == '') ? 'POST' : 'PUT';

                        $.ajax({
                            url: url,
                            type: type,
                            data: data,
                            success: function(response) {
                                $('#editModal').modal('hide');
                                Swal.fire({
                                    icon: 'success',
                                    title: '¡Éxito!',
                                    text: 'Dominio Pregunta guardado correctamente.',
                                });
                                table.replaceData();
                            },
                            error: function(xhr, status, error) {
                                Swal.fire({
                                    icon: 'error',
                                    title: '¡Error!',
                                    text: 'No se pudo guardar Dominio Pregunta.',
                                });
                            }
                        });
                    }
                });
            });

            // Evento click para el botón "Eliminar"
            $("#example-table").on("click", ".delete-button", function() {
                var id = $(this).data("id");

                Swal.fire({
                    title: '¿Estás seguro?',
                    text: '¿Deseas eliminar este registro?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('dominio-has-preguntas.index') }}/" + id,
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                Swal.fire({
                                    icon: 'success',
                                    title: '¡Éxito!',
                                    text: 'Dominio Pregunta eliminado correctamente.',
                                });
                                table.replaceData();
                            },
                            error: function(xhr, status, error) {
                                Swal.fire({
                                    icon: 'error',
                                    title: '¡Error!',
                                    text: 'No se pudo eliminar Dominio Pregunta.',
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>
@stop