@extends('adminlte::page')

@section('title', 'Personal')

@section('content_header')
    <h1></h1>
@stop

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                
                @php
                    $campaniaActual = \App\Models\Campania::where('es_campania_actual', true)->first();
                    $evaluaciones = $campaniaActual
                        ? \App\Models\Evaluacione::where('campania_id', $campaniaActual->id)->get()
                        : collect();
                @endphp

                @if($campaniaActual)
                    {{-- Mostrar información de la campaña actual --}}
                    {{-- Si no hay campaña actual, no se muestra nada --}}
                    {{-- Si no hay evaluaciones, no se muestra la lista --}}
                    {{-- Si hay evaluaciones, se muestra la lista --}}
                    <div class="mb-3 card rounded-xl">
                        <div class="text-white card-header bg-vanguard rounded-t-xl">
                            <strong>Campaña Actual:</strong> {{ $campaniaActual->name }}
                        </div>
                        <div class="card-body">
                            <p><strong>FECHA DE CREACIÓN:</strong> {{ $campaniaActual->created_at }}</p>
                            <p><strong>ESTADO:</strong> {{ $campaniaActual->status ? 'Activo' : 'Inactivo' }}</p>
                            {{-- <h6>Evaluaciones relacionadas:</h6> --}}
                            <ul>
                                @foreach($evaluaciones as $ev)
                                    <li>
                                        {{-- en mayusculas --}}
                                        {{-- >strtoupper($ev->tipo_evaluacion) }}: --}}
                                        <strong>{{ strtoupper($ev->nombre_para_mostrar) }}: </strong>
                                        {{-- FORMATEAR FECHA --}}
                                        {{-- <span class="text-muted">({{\Carbon\Carbon::parse($ev->fecha_inicio)->format('d/m/Y') }} - {{
                                                \Carbon\Carbon::parse($ev->fecha_fin)->format('d/m/Y') }})</span> --}}
                                        {{-- <br> --}}
                                        <span class="text-muted"> Fecha de corte {{ \Carbon\Carbon::parse($ev->fecha_corte)->format('d/m/Y') }}</span>
                                        {{-- Mostrar fecha de corte --}}
                                        {{-- Si no hay fecha de corte, no se muestra --}}
                                        {{-- (Fecha de corte: {{   $ev->fecha_corte }}) --}}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <div class="card rounded-xl">
                    <div class="text-white card-header bg-vanguard rounded-t-xl">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <div class="float-left">
                                <h5 class="h5">Personal (se muestra solo personal no cesado)</h5>
                            </div>
                            <div class="float-right">
                                <button class="btn btn-sm btn-light" id="createButton">
                                    <i class="fas fa-plus"></i> Nuevo
                                </button>
                                {{-- <button class="btn btn-sm btn-primary" id="actualizacionGeneralBtn">
                                    <i class="fas fa-sync"></i> Actualización General
                                </button>
                                <a class="btn btn-sm btn-light" href="{{ route('personal.historial-actualizaciones') }}">
                                    <i class="fas fa-history"></i> Historial de Actualizaciones
                                </a>
                                <button class="btn btn-sm btn-light" id="marcarSeleccionadosBtn">
                                    <i class="fas fa-check-double"></i> Marcar Seleccionados
                                </button>
                                <button class="btn btn-sm btn-light" id="exportarTodosSeleccionadosCampania">
                                    <i class="fas fa-users"></i> 
                                    Exportar TODOS los seleccionados a Campaña
                                </button> --}}
                                {{-- <button class="btn btn-sm btn-light" id="exportarSeleccionadosCampania">
                                    <i class="fas fa-paper-plane"></i> 
                                    Enviar seleccionados a Campaña Actual
                                </button> --}}
                                {{-- <div class="ml-2 btn-group">
                                    <button type="button" class="btn btn-sm btn-light dropdown-toggle"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-download"></i> Exportar
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a class="dropdown-item" href="#" id="exportExcel">
                                            <i class="fas fa-file-excel text-success"></i> Excel
                                        </a>
                                        <a class="dropdown-item" href="#" id="exportPDF">
                                            <i class="fas fa-file-pdf text-danger"></i> PDF
                                        </a>
                                        <a class="dropdown-item" href="#" id="exportCSV">
                                            <i class="fas fa-file-csv text-primary"></i> CSV
                                        </a>
                                    </div>
                                </div> --}}
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                                {{-- <button class="btn btn-vanguard" id="createButton">
                                    <i class="fas fa-plus"></i> Nuevo
                                </button> --}}
                          <div class="btn-group btn-sm" role="group">
                            <button id="btnGroupDrop1" type="button" class="mb-2 btn btn-vanguard dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Opciones
                            </button>
                            <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">  
                                <button class="m-1 btn btn-vanguard" id="actualizacionGeneralBtn">
                                    <i class="fas fa-sync"></i> Actualización General
                                </button>
                                <a target="_blank" class="m-1 btn btn-vanguard" href="{{ route('personal.historial-actualizaciones') }}">
                                    <i class="fas fa-history"></i> Historial de Actualizaciones
                                </a>
                                <button class="m-1 btn btn-vanguard" id="marcarSeleccionadosBtn">
                                    <i class="fas fa-check-double"></i> Marcar Seleccionados
                                </button>
                                <button class="m-1 btn btn-vanguard" id="exportarTodosSeleccionadosCampania">
                                    <i class="fas fa-users"></i> 
                                    Exportar TODOS los seleccionados a Campaña
                                </button>
                            </div>
                        </div>
                        <div id="personal-table"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para crear/editar personal -->
    <div class="modal fade" id="personalModal" tabindex="-1" role="dialog" aria-labelledby="personalModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header bg-vanguard">
                    <h5 class="text-white modal-title h5" id="personalModalLabel">Crear Personal</h5>
                    <button type="button" class="text-white close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="personalForm">
                        <input type="hidden" id="personalId">
                        <div class="row">
                            <!-- Datos Básicos -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="personalDni">DNI <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="personalDni" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="personalSeleccionado">Seleccionado</label>
                                    <select class="form-control" id="personalSeleccionado" name="seleccionado">
                                        <option value="0">No</option>
                                        <option value="1">Sí</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="personalName">Nombre Completo <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="personalName" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="personalNombres">Nombres <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="personalNombres" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="personalApellidoPaterno">Apellido Paterno <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="personalApellidoPaterno" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="personalApellidoMaterno">Apellido Materno</label>
                                    <input type="text" class="form-control" id="personalApellidoMaterno">
                                </div>
                            </div>
                            <!-- Información de la empresa -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="personalEmpresaId">Empresa <span class="text-danger">*</span></label>
                                    <select class="form-control select2" id="personalEmpresaId" style="width:100%"></select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="personalGerenciaId">Gerencia</label>
                                    <select class="form-control select2" id="personalGerenciaId" style="width:100%"></select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="personalAreaId">Área</label>
                                    <select class="form-control select2" id="personalAreaId" style="width:100%"></select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="personalCargoId">Cargo</label>
                                    <select class="form-control select2" id="personalCargoId" style="width:100%"></select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="personalReportaA">Reporta a</label>
                                    <select class="form-control select2" id="personalReportaA" style="width:100%"></select>
                                </div>
                            </div>
                            <!-- Contacto -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="personalCorreoEmpresa">Correo Empresa</label>
                                    <input type="email" class="form-control" id="personalCorreoEmpresa">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="personalCelularEmpresa">Celular Empresa</label>
                                    <input type="text" class="form-control" id="personalCelularEmpresa">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="personalCorreoPersonal">Correo Personal</label>
                                    <input type="email" class="form-control" id="personalCorreoPersonal">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="personalTelefonoPersonal">Teléfono Personal</label>
                                    <input type="text" class="form-control" id="personalTelefonoPersonal">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="personalCelularPersonal">Celular Personal</label>
                                    <input type="text" class="form-control" id="personalCelularPersonal">
                                </div>
                            </div>
                            <!-- Información adicional -->
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="personalEstado">Estado</label>
                                    <select class="form-control" id="personalEstado">
                                        <option value="1">Habilitado</option>
                                        <option value="0">Deshabilitado</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="personalGenero">Género</label>
                                    <select class="form-control" id="personalGenero">
                                        <option value="">Seleccione...</option>
                                        <option value="M">Masculino</option>
                                        <option value="F">Femenino</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="personalFechaIngreso">Fecha Ingreso</label>
                                    <input type="date" class="form-control" id="personalFechaIngreso">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="personalCesado">Cesado</label>
                                    <select class="form-control" id="personalCesado">
                                        <option value="0">No</option>
                                        <option value="1">Sí</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="personalFechaCese">Fecha de Cese</label>
                                    <input type="date" class="form-control" id="personalFechaCese">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="savePersonalChanges">Guardar</button>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>

        /* Agregar al CSS existente */
        .updating-cell {
            position: relative;
        }

        .updating-cell::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 123, 255, 0.1);
            border: 1px solid rgba(0, 123, 255, 0.5);
            pointer-events: none;
        }

        .row-updating {
            opacity: 0.7;
            transition: opacity 0.3s;
        }

        .update-correo-empresa {
            opacity: 0.7;
            transition: opacity 0.2s;
        }

        .update-correo-empresa:hover {
            opacity: 1;
        }

        .correo-editable {
            cursor: text;
            padding: 2px 4px;
            border-radius: 3px;
            transition: background-color 0.2s;
        }

        .correo-editable:hover {
            background-color: rgba(0, 123, 255, 0.1);
        }

        /* Estilos para indicar que la celda es editable */
        .tabulator-cell[tabulator-field="correo_empresa"] {
            cursor: text;
        }

        /* Indicador visual para botones */
        .btn-link {
            transition: transform 0.2s, opacity 0.2s;
        }

        .btn-link:hover {
            transform: scale(1.2);
            opacity: 1 !important;
        }
        
    </style> 
    {{-- <link href="https://unpkg.com/tabulator-tables@5.4.4/dist/css/tabulator.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .bg-vanguard { background-color: #3490dc; }
        .rounded-xl { border-radius: 0.5rem; }
        .rounded-t-xl { border-top-left-radius: 0.5rem; border-top-right-radius: 0.5rem; }
    </style> --}}
@stop

@section('js')
    {{-- <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script> --}}
    <script src="{{ asset('js/personalTable.js') }}"></script>
    <script>
        const PERSONAL_URL = "{{ route('personal.data') }}";
        const PERSONAL_CREATE_URL = "{{ route('personal.store') }}";
        const PERSONAL_UPDATE_URL = "{{ route('personal.update', ':id') }}";
        const PERSONAL_DELETE_URL = "{{ route('personal.destroy', ':id') }}";
        const PERSONAL_SHOW_URL = "{{ route('personal.show', ':id') }}";
        const SELECT2_EMPRESA_URL = "{{ route('api.personal.select2.empresa') }}";
        const SELECT2_GERENCIA_URL = "{{ route('api.personal.select2.gerencia') }}";
        const SELECT2_AREA_URL = "{{ route('api.personal.select2.area') }}";
        const SELECT2_CARGO_URL = "{{ route('api.personal.select2.cargo') }}";
        const SELECT2_REPORTA_URL = "{{ route('api.personal.select2.reporta') }}";
        const PERSONAL_MARCAR_SELECCIONADOS_URL = "{{ route('personal.marcar-seleccionados') }}";
        const EXPORTAR_TODOS_SELECCIONADOS_URL = "{{ route('campanias.exportarTodosSeleccionados') }}";
        const EXPORTAR_PERSONAL_URL = "{{ route('campanias.exportarPersonalACampaniaActual') }}";
        const ACTUALIZACION_INDIVIDUAL_URL = "{{ route('personal.actualizacion-individual', ':dni') }}";

        // Nuevas constantes para actualización de personal
        const ACTUALIZACION_GENERAL_URL = "{{ route('personal.actualizacion-general') }}";
        const BUSCAR_POR_DNI_URL = "{{ route('personal.buscar-por-dni') }}";
        const HISTORIAL_ACTUALIZACIONES_URL = "{{ route('personal.historial-actualizaciones') }}";

    </script>
@stop