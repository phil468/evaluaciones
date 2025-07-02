@extends('adminlte::page')

@section('title', 'Escala de Mediciones')

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
                                <h5 class="h5">Escala de Mediciones</h5>
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
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header bg-vanguard">
                    <h5 class="text-white modal-title h5" id="editModalLabel">Editar Escala de Medición</h5>
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
                                    <label for="editName">Name</label>
                                    <input type="text" class="form-control" id="editName" required>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-4 col-xl-3">
                                <div class="form-group">
                                    <label for="editValorMenor">Valor Menor</label>
                                    <input type="number" class="form-control" id="editValorMenor">
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-4 col-xl-3">
                                <div class="form-group">
                                    <label for="editValorMayor">Valor Mayor</label>
                                    <input type="number" class="form-control" id="editValorMayor">
                                </div>
                            </div>
                        {{-- </div>
                        <div class="row"> --}}
                        {{-- </div>
                        <div class="row"> --}}
                            <div class="col-md-6 col-lg-4 col-xl-3">
                                <div class="form-group">
                                    <label for="editRangoMenor">Rango Menor</label>
                                    <input type="text" class="form-control" id="editRangoMenor">
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-4 col-xl-3">
                                <div class="form-group">
                                    <label for="editRangoMayor">Rango Mayor</label>
                                    <input type="text" class="form-control" id="editRangoMayor">
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-4 col-xl-3">
                                <div class="form-group">
                                    <label for="editColor">Color</label>
                                    <input type="color" class="form-control" id="editColor">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="editInterpretacion">Interpretación</label>
                                    <textarea class="form-control" id="editInterpretacion" rows="3"></textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="editRecomendacion">Recomendación</label>
                                    <textarea class="form-control" id="editRecomendacion" rows="3"></textarea>
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
            ajaxURL: "{{ route('escala_mediciones.data') }}",
            ajaxConfig:"get", //ajax HTTP request type
            layout:"fitDataFill",
            columns:[
                {title:"ID", field:"id", width:70 },
                {
                    title: "", 
                    field: "actions", 
                    formatter: function(cell, formatterParams) {
                        return "<button class='text-white btn btn-sm btn-warning edit-button' data-id='" + cell.getRow().getData().id + "'><i class='fas fa-edit'></i></button>" +
                               "<button class='btn btn-sm btn-danger delete-button' data-id='" + cell.getRow().getData().id + "'><i class='fas fa-trash'></i></button>";
                    }
                },
                {title:"Estado", field:"estado", formatter:"tickCross" },
                {title:"Nombre", field:"name" },
                {title:"Valor Menor", field:"valor_menor" },
                {title:"Valor Mayor", field:"valor_mayor" },
                {title:"Rango Menor", field:"rango_menor" },
                {title:"Rango Mayor", field:"rango_mayor" },
                {title:"Color", field:"color", formatter:"color" },
                {title:"Interpretación", field:"interpretacion" },
                {title:"Recomendación", field:"recomendacion" },
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
        $("#example-table").on("click", ".edit-button", function(){
            var id = $(this).data("id");
            var data = table.getRow(id).getData();

            $('#editId').val(data.id);
            $('#editValorMenor').val(data.valor_menor);
            $('#editValorMayor').val(data.valor_mayor);
            $('#editName').val(data.name);
            $('#editRangoMenor').val(data.rango_menor);
            $('#editRangoMayor').val(data.rango_mayor);
            $('#editColor').val(data.color);
            $('#editInterpretacion').val(data.interpretacion);
            $('#editRecomendacion').val(data.recomendacion);
            $('#editEstado').val(data.estado);
            $('#editModal').modal('show');
        });

        // Evento click para el botón "Crear Nuevo"
        $("#createButton").on("click", function(){
            // Limpiar los campos del modal
            $('#editId').val('');
            $('#editValorMenor').val('');
            $('#editValorMayor').val('');
            $('#editName').val('');
            $('#editRangoMenor').val('');
            $('#editRangoMayor').val('');
            $('#editColor').val('');
            $('#editInterpretacion').val('');
            $('#editRecomendacion').val('');
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
                        valor_menor: $('#editValorMenor').val(),
                        valor_mayor: $('#editValorMayor').val(),
                        name: $('#editName').val(),
                        rango_menor: $('#editRangoMenor').val(),
                        rango_mayor: $('#editRangoMayor').val(),
                        color: $('#editColor').val(),
                        interpretacion: $('#editInterpretacion').val(),
                        recomendacion: $('#editRecomendacion').val(),
                        estado: $('#editEstado').val(),
                        _token: '{{ csrf_token() }}',
                    };

                    var url = (id == '') ? "{{ route('escala_mediciones.store') }}" : "{{route('escala_mediciones.update', '')}}/" + id;
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
                                text: 'Escala de Medición actualizada correctamente.',
                            });
                            table.replaceData();
                        },
                        error: function(xhr, status, error) {
                            Swal.fire({
                                icon: 'error',
                                title: '¡Error!',
                                text: 'No se pudo actualizar la Escala de Medición.',
                            });
                        }
                    });
                }
            });
        });

        // Evento click para el botón "Eliminar"
        $("#example-table").on("click", ".delete-button", function(){
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
                        url: "{{route('escala_mediciones.destroy', '')}}/" + id,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: '¡Éxito!',
                                text: 'Escala de Medición eliminada correctamente.',
                            });
                            table.replaceData();
                        },
                        error: function(xhr, status, error) {
                            Swal.fire({
                                icon: 'error',
                                title: '¡Error!',
                                text: 'No se pudo eliminar la Escala de Medición.',
                            });
                        }
                    });
                }
            });
        });
    });
</script>
@stop