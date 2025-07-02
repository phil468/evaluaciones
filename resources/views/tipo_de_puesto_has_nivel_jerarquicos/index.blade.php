@extends('adminlte::page')

@section('title', 'Tipo de Puesto en Campaña')

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
                                <h5 class="h5">Tipo de Puesto en Campaña</h5>
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
                    <h5 class="text-white modal-title h5" id="editModalLabel">Editar Tipo de Puesto en Campaña</h5>
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
                                    <label for="editEstado">Estado</label>
                                    <select class="form-control" id="editEstado">
                                        <option value="1">Activo</option>
                                        <option value="0">Inactivo</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-4 col-xl-3">
                                <div class="form-group">
                                    <label for="editTipoDePuestoId">Tipo de Puesto</label>
                                    <select class="form-control" id="editTipoDePuestoId">
                                        @foreach ($tiposDePuesto as $tipoDePuesto)
                                            <option value="{{ $tipoDePuesto->id }}">{{ $tipoDePuesto->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-4 col-xl-3">
                                <div class="form-group">
                                    <label for="editNivelJerarquicoId">Nivel Jerárquico</label>
                                    <select class="form-control" id="editNivelJerarquicoId">
                                        @foreach ($nivelesJerarquicos as $nivelJerarquico)
                                            <option value="{{ $nivelJerarquico->id }}">{{ $nivelJerarquico->name }}
                                            </option>
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
                ajaxURL: "{{ route('tipo_puesto_niveles.data') }}",
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
                            return "@can('editar-tipo-de-puesto-has-nivel-jerarquico')<button class='text-white btn btn-sm btn-warning edit-button' data-id='" +
                                cell.getRow().getData().id +
                                "'><i class='fas fa-edit'></i></button>@endcan" +
                                "@can('borrar-tipo-de-puesto-has-nivel-jerarquico')<button class='btn btn-sm btn-danger delete-button' data-id='" +
                                cell.getRow().getData().id +
                                "'><i class='fas fa-trash'></i></button>@endcan";
                        }
                    },
                    {
                        title: "Estado",
                        field: "estado",
                        formatter: "tickCross"
                    },
                    {
                        title: "Tipo de Puesto",
                        field: "tipo_de_puesto.name"
                    },
                    {
                        title: "Nivel Jerárquico",
                        field: "nivel_jerarquico.name"
                    },
                    {
                        title: "Campaña",
                        field: "campania.name"
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
                $('#editEstado').val(data.estado);
                $('#editTipoDePuestoId').val(data.tipo_de_puesto_id);
                $('#editNivelJerarquicoId').val(data.nivel_jerarquico_id);
                $('#editCampaniaId').val(data.campania_id);
                $('#editModal').modal('show');
            });

            // Evento click para el botón "Crear Nuevo"
            $("#createButton").on("click", function() {
                // Limpiar los campos del modal
                $('#editId').val('');
                $('#editTipoDePuestoId').val('');
                $('#editNivelJerarquicoId').val('');
                $('#editCampaniaId').val('');
                $('#editEstado').val(1); // Establecer el estado por defecto a "Activo"

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
                            tipo_de_puesto_id: $('#editTipoDePuestoId').val(),
                            nivel_jerarquico_id: $('#editNivelJerarquicoId').val(),
                            campania_id: $('#editCampaniaId').val(),
                            estado: $('#editEstado').val(),
                            _token: '{{ csrf_token() }}',
                        };

                        var url = (id == '') ? "{{ route('tipo_puesto_niveles.store') }}" :
                            "{{ route('tipo_puesto_niveles.update', '') }}/" + id;
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
                                    text: 'Tipo de Puesto en Campaña actualizado correctamente.',
                                });
                                table.replaceData();
                            },
                            error: function(xhr, status, error) {
                                Swal.fire({
                                    icon: 'error',
                                    title: '¡Error!',
                                    text: 'No se pudo actualizar el Tipo de Puesto en Campaña.',
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
                            url: "{{ route('tipo_puesto_niveles.destroy', '') }}/" + id,
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                Swal.fire({
                                    icon: 'success',
                                    title: '¡Éxito!',
                                    text: 'Tipo de Puesto en Campaña eliminado correctamente.',
                                });
                                table.replaceData();
                            },
                            error: function(xhr, status, error) {
                                Swal.fire({
                                    icon: 'error',
                                    title: '¡Error!',
                                    text: 'No se pudo eliminar el Tipo de Puesto en Campaña.',
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>
@stop