<!-- resources/views/tipo_relacion_jerarquicas/index.blade.php -->
@extends('adminlte::page')

@section('title', 'Tipos de Relación Jerárquica')

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
                                <h5 class="h5">Tipos de Relación Jerárquica</h5>
                            </div>
                            <div class="float-right">
                                <button class="btn btn-sm btn-light" id="createButton">
                                    <i class="fas fa-plus"></i> Nuevo
                                </button>
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

    <!-- Modal Edición/Creación -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-vanguard">
                    <h5 class="text-white modal-title h5" id="editModalLabel">Editar Tipo de Relación Jerárquica</h5>
                    <button type="button" class="text-white close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Formulario de edición -->
                    <form id="editForm">
                        <input type="hidden" id="editId">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="editEstado">Estado</label>
                                    <select class="form-control" id="editEstado">
                                        <option value="1">Activo</option>
                                        <option value="0">Inactivo</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="editName">Nombre</label>
                                    <input type="text" class="form-control" id="editName" required>
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
    <!-- Fin Modal -->
@endsection

@section('js')
    <script src="{{ asset('js/tipo_relacion_jerarquicas.js') }}"></script>
    <script>
        const TIPO_RELACION_DATA_URL = "{{ route('tipo_relacion_jerarquicas.data') }}";
        const TIPO_RELACION_STORE_URL = "{{ route('tipo_relacion_jerarquicas.store') }}";
        const TIPO_RELACION_UPDATE_URL = "{{ route('tipo_relacion_jerarquicas.update', ':id') }}";
        const TIPO_RELACION_DELETE_URL = "{{ route('tipo_relacion_jerarquicas.destroy', ':id') }}";
        const TIPO_RELACION_SHOW_URL = "{{ route('tipo_relacion_jerarquicas.show', ':id') }}";
    </script>
@stop