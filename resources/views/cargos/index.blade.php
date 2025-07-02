@extends('adminlte::page')

@section('title', 'Cargos')

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
                                <h5 class="h5">Cargos</h5>
                            </div>
                            <div class="float-right">
                                <button class="mr-2 btn btn-sm btn-light" id="actualizarTiposBtn">
                                    <i class="fas fa-sync"></i> Actualizar Tipos de Puesto
                                </button>
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
                    <h5 class="text-white modal-title h5" id="editModalLabel">Editar Cargo</h5>
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
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="editTipoDePuesto">Tipo de Puesto</label>
                                    <select class="form-control" id="editTipoDePuesto">
                                        <option value="">-- Seleccione --</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="editIdcargoNisira">ID Cargo Nisira</label>
                                    <input type="text" class="form-control" id="editIdcargoNisira">
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
    <script src="{{ asset('js/cargos.js') }}"></script>
    <script>
        const CARGOS_DATA_URL = "{{ route('cargos.data') }}";
        const CARGOS_STORE_URL = "{{ route('cargos.store') }}";
        const CARGOS_UPDATE_URL = "{{ route('cargos.update', ':id') }}";
        const CARGOS_DELETE_URL = "{{ route('cargos.destroy', ':id') }}";
        const CARGOS_SHOW_URL = "{{ route('cargos.show', ':id') }}";    
        const TIPO_PUESTO_LISTA_URL = "{{ route('api.tipo_puesto.lista') }}";
        const CARGOS_ACTUALIZAR_TIPOS_URL = "{{ route('cargos.actualizar-tipos') }}";
    </script>
@stop
