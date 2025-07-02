@extends('adminlte::page')

@section('title', 'Campañas y Competencias')

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
                                <h5 class="h5">Campañas y Competencias</h5>
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

    <!-- Modal Detalle Evaluación -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header bg-vanguard">
                    <h5 class="text-white modal-title h5" id="editModalLabel">Editar Campaña y Competencia</h5>
                    <button type="button" class="text-white close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Formulario de edición -->
                    <form id="editForm">
                        <input type="hidden" id="editId">
                        <div class="row">
                            <div class="col-md-6 col-lg-4 col-xl-3">
                                <div class="form-group">
                                    <label for="editCompetenciaId">Competencia</label>
                                    <select class="form-control" id="editCompetenciaId">
                                        @foreach ($competencias as $competencia)
                                            <option value="{{ $competencia->id }}">{{ $competencia->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-4 col-xl-3">
                                <div class="form-group">
                                    <label for="editCampaniaId">Campaña</label>
                                    <select class="form-control" id="editCampaniaId">
                                        @foreach ($campanias as $campania)
                                            <option value="{{ $campania->id }}">{{ $campania->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-4 col-xl-3">
                                <div class="form-group">
                                    <label for="editRelacionadoAnteriorId">Relacionado Anterior</label>
                                    <select class="form-control" id="editRelacionadoAnteriorId">
                                        <option value="">Ninguno</option>
                                        @foreach ($campaniaHasCompetencias as $campaniaHasCompetencia)
                                            <option value="{{ $campaniaHasCompetencia->id }}">
                                                {{ $campaniaHasCompetencia->competencia->name }} -
                                                {{ $campaniaHasCompetencia->campania->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-4 col-xl-3">
                                <div class="form-group">
                                    <label for="editEstado">Estado</label>
                                    <select class="form-control" id="editEstado">
                                        <option value="1">Activo</option>
                                        <option value="0">Inactivo</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-lg-4 col-xl-3">
                                <div class="form-group">
                                    <label for="editTipoCompetenciaId">Tipo de Competencia</label>
                                    <select class="form-control" id="editTipoCompetenciaId">
                                        @foreach ($tiposCompetencia as $tipoCompetencia)
                                            <option value="{{ $tipoCompetencia->id }}">{{ $tipoCompetencia->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-4 col-xl-3">
                                <div class="form-group">
                                    <label for="editTipoMedicionId">Tipo de Medición</label>
                                    <select class="form-control" id="editTipoMedicionId">
                                        @foreach ($tiposMedicion as $tipoMedicion)
                                            <option value="{{ $tipoMedicion->id }}">{{ $tipoMedicion->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-4 col-xl-3">
                                <div class="form-group">
                                    <label for="editColor">Color</label>
                                    <input type="color" class="form-control" id="editColor">
                                </div>
                            </div>
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
    <!-- Fin Modal Detalle Evaluación -->

@endsection

@section('js')
    <script type="text/javascript">
    
        document.addEventListener('DOMContentLoaded', function() {
            var table = new Tabulator("#example-table", {
                ajaxURL: "{{ route('campania_has_competencias.data') }}",
                ajaxConfig: "get", //ajax HTTP request type
                layout: "fitDataFill",
                columns: [{
                        title: "ID",
                        field: "id",
                        width: 70
                    },
                    {
                        title: "",
                        field: "actions",
                        formatter: function(cell, formatterParams) {
                            return "@can('editar-campania-has-competencia')<button class='text-white btn btn-sm btn-warning edit-button' data-id='" +
                                cell.getRow().getData().id +
                                "'><i class='fas fa-edit'></i></button>@endcan" +
                                "@can('borrar-campania-has-competencia')<button class='btn btn-sm btn-danger delete-button' data-id='" +
                                cell.getRow().getData().id +
                                "'><i class='fas fa-trash'></i></button>@endcan";
                        }
                    },
                    {
                        title: "Competencia",
                        field: "competencia.name"
                    },
                    {
                        title: "Campaña",
                        field: "campania.name"
                    },
                    {
                        title: "Relacionado Anterior",
                        field: "relacionado_anterior",
                        formatter: "lookup",
                        // Si deseas mostrar un valor por defecto cuando no hay relacionado anterior
                        formatter: function(cell, formatterParams) {
                            var value = cell.getValue();
                            // Si el valor es nulo o indefinido, retornar "Ninguno"
                            if (value === null || value === undefined) {
                                return "Ninguno";
                            }
                            // Si el valor es un objeto, retornar el nombre de la competencia y campaña
                            if (typeof value === 'object') {
                                return value.competencia.name + " - " + value.campania.name;
                            }
                            // Si es un string, retornar el valor directamente
                            return value;
                        }
                    },
                    {
                        title: "Estado",
                        field: "estado",
                        formatter: "tickCross"
                    },
                    {
                        title: "Tipo de Competencia",
                        field: "tipo_competencia.name"
                    },
                    {
                        title: "Tipo de Medición",
                        field: "tipo_medicion.name"
                    },
                    {
                        title: "Color",
                        field: "color",
                        formatter: "color"
                    },
                ],
                locale: true,
                langs: {
                    "es-419": {
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

            // Evento click para el botón "Editar"
            $("#example-table").on("click", ".edit-button", function() {
                var id = $(this).data("id");
                var data = table.getRow(id).getData();

                $('#editId').val(data.id);
                $('#editCompetenciaId').val(data.competencia_id);
                $('#editCampaniaId').val(data.campania_id);
                $('#editRelacionadoAnteriorId').val(data.relacionado_anterior_id);
                $('#editEstado').val(data.estado);
                $('#editTipoCompetenciaId').val(data.tipo_competencia_id);
                $('#editTipoMedicionId').val(data.tipo_medicion_id);
                $('#editColor').val(data.color);
                $('#editModal').modal('show');
            });

            // Evento click para el botón "Crear Nuevo"
            $("#createButton").on("click", function() {
                // Limpiar los campos del modal
                $('#editId').val('');
                $('#editCompetenciaId').val('');
                $('#editCampaniaId').val('');
                $('#editRelacionadoAnteriorId').val('');
                $('#editEstado').val(1); // Establecer el estado por defecto a "Activo"
                $('#editTipoCompetenciaId').val('');
                $('#editTipoMedicionId').val('');
                $('#editColor').val('');

                // Mostrar el modal
                $('#editModal').modal('show');
            });

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
                            competencia_id: $('#editCompetenciaId').val(),
                            campania_id: $('#editCampaniaId').val(),
                            relacionado_anterior_id: $('#editRelacionadoAnteriorId').val(),
                            estado: $('#editEstado').val(),
                            tipo_competencia_id: $('#editTipoCompetenciaId').val(),
                            tipo_medicion_id: $('#editTipoMedicionId').val(),
                            color: $('#editColor').val(),
                            _token: '{{ csrf_token() }}',
                        };

                        var url = (id == '') ? "{{ route('campania_has_competencias.store') }}" :
                            "{{ route('campania_has_competencias.update', '') }}/" + id;
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
                                    text: 'Campaña y Competencia actualizada correctamente.',
                                });
                                table.replaceData();
                            },
                            error: function(xhr, status, error) {
                                Swal.fire({
                                    icon: 'error',
                                    title: '¡Error!',
                                    text: 'No se pudo actualizar la Campaña y Competencia.',
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
                            url: "{{ route('campania_has_competencias.destroy', '') }}/" + id,
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                Swal.fire({
                                    icon: 'success',
                                    title: '¡Éxito!',
                                    text: 'Campaña y Competencia eliminada correctamente.',
                                });
                                table.replaceData();
                            },
                            error: function(xhr, status, error) {
                                Swal.fire({
                                    icon: 'error',
                                    title: '¡Error!',
                                    text: 'No se pudo eliminar la Campaña y Competencia.',
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>
@stop