@extends('adminlte::page')

@section('title', 'Campañas')

@section('content_header')
    <h1></h1>
@stop

@section('content')
    <div class="card rounded-xl">

        <div class="text-white card-header bg-vanguard rounded-t-xl">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div class="float-left">
                    <h5 class="h5">Gestión de Campaña</h5>
                </div>
                <div class="float-right">
                    <button class="btn btn-sm btn-light" id="createButton"><i class="fas fa-plus"></i>
                        Nuevo</button>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div id="campania-table"></div>
        </div>

    </div>

    <!-- Panel de configuración de campaña (inicialmente oculto) -->
    <div id="configPanel" class="mt-4" style="display: none;">
        <div class="card rounded-xl">
            <div class="text-white card-header bg-vanguard rounded-t-xl">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <h5 class="card-title">Configuración de Campaña</h5>
                    <button class="text-white close" id="closeConfig">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <!-- Panel de Información de la Campaña -->
                <div class="mb-4 card">
                    <div class="text-white card-header bg-primary">
                        <h3 class="card-title">Información de la Campaña</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><strong>ID:</strong></label>
                                    <p id="configId"></p>
                                </div>
                                <div class="form-group">
                                    <label><strong>Nombre:</strong></label>
                                    <p id="configName"></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><strong>Estado:</strong></label>
                                    <p id="configEstado"></p>
                                </div>
                                <div class="form-group">
                                    <label><strong>Fecha de Creación:</strong></label>
                                    <p id="configCreatedAt"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Panel de Competencias -->
                <div class="mt-4 card">
                    <div class="text-white card-header bg-primary" style="cursor:pointer;" data-toggle="collapse"
                        data-target="#competenciasCollapse" aria-expanded="true" aria-controls="competenciasCollapse">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <h3 class="card-title">Gestión de Competencias</h3>
                            <div>
                                <button class="ml-2 btn btn-sm btn-light" type="button">
                                    <i class="fas fa-chevron-down"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div id="competenciasCollapse" class="collapse show">
                        <div class="card-body">
                            <div class="mb-2">
                                <button class="btn btn-primary" id="createCompetenciaButton" type="button">
                                    <i class="fas fa-plus"></i> Nueva Competencia
                                </button>
                            </div>
                            <div id="competencias-table"></div>
                        </div>
                    </div>
                </div>

                <!-- Panel de Dominios -->
                <div class="mt-4 card">
                    <div class="text-white card-header bg-primary" style="cursor:pointer;" data-toggle="collapse"
                        data-target="#dominiosCollapse" aria-expanded="true" aria-controls="dominiosCollapse">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <h3 class="card-title">Gestión de Dominios en Campaña</h3>
                            <div>
                                <button class="ml-2 btn btn-sm btn-light" type="button">
                                    <i class="fas fa-chevron-down"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div id="dominiosCollapse" class="collapse show">
                        <div class="card-body">
                            <div class="mb-2">
                                <button class="btn btn-primary" id="createDominioButton" type="button">
                                    <i class="fas fa-plus"></i> Nuevo Dominio
                                </button>
                            </div>
                            <div id="dominios-table"></div>
                        </div>
                    </div>
                </div>

                <!-- Panel de Preguntas -->
                <div class="mt-4 card">
                    <div class="text-white card-header bg-primary" style="cursor:pointer;" data-toggle="collapse"
                        data-target="#preguntasCollapse" aria-expanded="true" aria-controls="preguntasCollapse">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <h3 class="card-title">Gestión de Preguntas</h3>
                            <div>
                                <button class="ml-2 btn btn-sm btn-light" type="button">
                                    <i class="fas fa-chevron-down"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div id="preguntasCollapse" class="collapse show">
                        <div class="card-body">
                            <div class="mb-2">
                                <button class="mr-2 btn btn-primary" id="importModalButton">
                                    <i class="fas fa-file-import"></i> Importar Preguntas
                                </button>
                                <button class="btn btn-primary" id="createPreguntaButton" type="button">
                                    <i class="fas fa-plus"></i> Nueva Pregunta
                                </button>
                            </div>
                            <div id="preguntas-table"></div>
                            <div id="orden-info" class="mt-3" style="display: none;">
                                <div class="alert" role="alert">
                                    <h6 class="alert-heading">Información de Numeración</h6>
                                    <div class="orden-stats"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Panel de Puestos en campaña -->
                <div class="mt-4 card">
                    <div class="text-white card-header bg-primary" style="cursor:pointer;" data-toggle="collapse"
                        data-target="#puestosCollapse" aria-expanded="true" aria-controls="puestosCollapse">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <h3 class="card-title ">Gestión de Puestos en Campaña</h3>
                            <div>
                                <button class="ml-2 btn btn-sm btn-light" type="button">
                                    <i class="fas fa-chevron-down"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div id="puestosCollapse" class="collapse show">
                        <div class="card-body">
                            <div class="mb-2">
                                <button class="btn btn-primary" id="createPuestoButton" type="button">
                                    <i class="fas fa-plus"></i> Nuevo Puesto
                                </button>
                            </div>
                            <div id="puestos-table"></div>
                        </div>
                    </div>
                </div>

                <!-- Panel de Evaluados por Campaña -->
                <div class="mt-4 card">
                    <div class="text-white card-header bg-primary" style="cursor:pointer;" data-toggle="collapse"
                        data-target="#evaluadosCollapse" aria-expanded="true" aria-controls="evaluadosCollapse">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <h3 class="card-title">Configuración de Evaluados</h3>
                            <div>
                                <button class="ml-2 btn btn-sm btn-light" type="button">
                                    <i class="fas fa-chevron-down"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div id="evaluadosCollapse" class="collapse show">
                        <div class="card-body">
                            <div class="mb-2">                                
                                <div class="mb-2">
                                    <button class="btn btn-primary" id="addEvaluadoButton">
                                        <i class="fas fa-plus"></i> Agregar Evaluado
                                    </button>
                                    <button class="btn btn-primary" id="importEvaluadosButton">
                                        <i class="fas fa-file-import"></i> Importar
                                    </button>
                                    <button class="btn btn-success" id="generarEvaluadorHasEvaluado">
                                        <i class="fas fa-cogs"></i> Generar Evaluadores
                                    </button>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <input type="text" id="filtroEvaluado" class="form-control" placeholder="Filtrar por nombre o DNI">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <select id="filtroArea" class="form-control">
                                                <option value="">Todas las áreas</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <select id="filtroTipoEvaluacion" class="form-control">
                                                <option value="">Todos los tipos</option>
                                                <option value="competencias">Evaluación de competencias</option>
                                                <option value="objetivos">Evaluación por objetivos</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="evaluados-table"></div>
                        </div>
                    </div>
                </div>

                <!-- Panel de Evaluador-Has-Evaluado -->
                <div class="mt-4 card">
                    <div class="text-white card-header bg-primary" style="cursor:pointer;" data-toggle="collapse"
                        data-target="#evaluadoresHasEvaluadosCollapse" aria-expanded="true" aria-controls="evaluadoresHasEvaluadosCollapse">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <h3 class="card-title">Configuración de Evaluadores</h3>
                            <div>
                                <button class="ml-2 btn btn-sm btn-light" type="button">
                                    <i class="fas fa-chevron-down"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div id="evaluadoresHasEvaluadosCollapse" class="collapse show">
                        <div class="card-body">
                            <div class="mb-2">
                                <button class="btn btn-primary" id="addEvaluadorHasEvaluadoButton">
                                    <i class="fas fa-plus"></i> Agregar Evaluador
                                </button>
                            </div>
                            <div id="evaluadores-has-evaluados-table"></div>
                        </div>
                    </div>
                </div>

                <!-- Panel de Configuración de Evaluaciones -->
                <div class="mt-4 card">
                    <div class="text-white card-header bg-primary" style="cursor:pointer;" data-toggle="collapse"
                        data-target="#evaluacionesCollapse" aria-expanded="true" aria-controls="evaluacionesCollapse">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <h3 class="card-title">Configuración de Evaluaciones</h3>
                            <div>
                                <button class="ml-2 btn btn-sm btn-light" type="button">
                                    <i class="fas fa-chevron-down"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div id="evaluacionesCollapse" class="collapse show">
                        <div class="card-body">
                            <div class="mb-2">
                                <button class="btn btn-primary" id="createEvaluacionButton" type="button">
                                    <i class="fas fa-plus"></i> Nueva Evaluación
                                </button>
                            </div>
                            <div id="evaluaciones-table"></div>
                        </div>
                    </div>
                </div>

                <!-- Panel de Pesos -->
                <div class="mt-4 card">
                    <div class="text-white card-header bg-primary" style="cursor:pointer;" data-toggle="collapse"
                        data-target="#pesosCollapse" aria-expanded="true" aria-controls="pesosCollapse">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <h3 class="card-title">Gestión de Pesos</h3>
                            <div>
                                <button class="ml-2 btn btn-sm btn-light" type="button">
                                    <i class="fas fa-chevron-down"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div id="pesosCollapse" class="collapse show">
                        <div class="card-body">
                            <div class="mb-2">
                                <button class="btn btn-primary" id="createPesoButton" type="button">
                                    <i class="fas fa-plus"></i> Nuevo Peso
                                </button>
                            </div>
                            <div id="pesos-table"></div>
                        </div>
                    </div>
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
                    <form id="importForm" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="excelFile">Seleccionar archivo Excel</label>
                            <input type="file" class="form-control-file" id="excelFile" accept=".xlsx,.xls">
                            <small class="form-text text-muted">El archivo debe contener las columnas: pregunta,
                                competencia, dominio, campaña, numero_orden, quitar</small>
                        </div>

                        <!-- Panel de Validación -->
                        <div id="validationSummary" class="mt-3" style="display: none;">
                            <div class="card">
                                <div class="card-header bg-primary">
                                    Resumen de Validación
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <p>Total de registros: <span id="totalRows">0</span></p>
                                        </div>
                                        <div class="col-md-4">
                                            <p>Registros válidos: <span id="validRows" class="text-success">0</span></p>
                                        </div>
                                        <div class="col-md-4">
                                            <p>Registros con errores: <span id="errorRows" class="text-danger">0</span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Panel de Previsualización -->
                        <div id="previewContainer" class="mt-3" style="display: none;">
                            <div class="card">
                                <div class="card-header bg-primary">
                                    Vista Previa de Datos
                                </div>
                                <div class="card-body">
                                    <div id="previewTable"></div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-sm" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" id="validateButton">Validar</button>
                    <button type="button" class="btn btn-success" id="importButton"
                        style="display: none;">Importar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Editar Campaña -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="text-white modal-header bg-vanguard">
                    <h5 class="modal-title" id="editModalLabel">Editar Campaña</h5>
                    <button type="button" class="text-white close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editForm">
                        <input type="hidden" id="editId">
                        <div class="form-group">
                            <label for="editName">Nombre</label>
                            <input type="text" class="form-control" id="editName" required>
                        </div>
                        <div class="form-group">
                            <label for="editRelacionadoAnteriorId">Relacionado Anterior</label>
                            <select class="form-control" id="editRelacionadoAnteriorId">
                                <option value="">Ninguno</option>
                                <!-- Opciones dinámicas desde JS -->
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="editEsCampaniaActual">¿Es campaña actual?</label>
                            <select class="form-control" id="editEsCampaniaActual">
                                <option value="0">No</option>
                                <option value="1">Sí</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="editEstado">Estado</label>
                            <select class="form-control" id="editEstado">
                                <option value="1">Activo</option>
                                <option value="0">Inactivo</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-sm" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-vanguard" id="saveChanges">Guardar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Editar Competencia -->
    <div class="modal fade" id="editCompetenciaModal" tabindex="-1" role="dialog"
        aria-labelledby="editCompetenciaModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="text-white modal-header bg-vanguard">
                    <h5 class="modal-title" id="editCompetenciaModalLabel">Editar Competencia</h5>
                    <button type="button" class="text-white close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editCompetenciaForm">
                        <input type="hidden" id="editCompetenciaId">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="editCompetenciaCompetenciaId">Competencia</label>
                                    <select class="form-control" id="editCompetenciaCompetenciaId" required>
                                        <!-- Opciones dinámicas desde el backend -->
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="editCompetenciaRelacionadoAnteriorId">Relacionado Anterior</label>
                                    <select class="form-control" id="editCompetenciaRelacionadoAnteriorId">
                                        <option value="">Ninguno</option>
                                        <!-- Opciones dinámicas desde el backend -->
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="editCompetenciaEstado">Estado</label>
                                    <select class="form-control" id="editCompetenciaEstado">
                                        <option value="1">Activo</option>
                                        <option value="0">Inactivo</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="editCompetenciaTipoCompetenciaId">Tipo de Competencia</label>
                                    <select class="form-control" id="editCompetenciaTipoCompetenciaId">
                                        <!-- Opciones dinámicas desde el backend -->
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="editCompetenciaTipoMedicionId">Tipo de Medición</label>
                                    <select class="form-control" id="editCompetenciaTipoMedicionId">
                                        <!-- Opciones dinámicas desde el backend -->
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="editCompetenciaColor">Color</label>
                                    <input type="color" class="form-control" id="editCompetenciaColor">
                                </div>
                            </div>
                        </div>
                        <!-- Agrega más campos según tu modelo -->
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-sm" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-vanguard" id="saveCompetenciaChanges">Guardar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Preguntas fixed -->
    <div class="modal fade" id="editPreguntaModal" tabindex="-1" role="dialog"
        aria-labelledby="preguntasFixedModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="text-white modal-header bg-vanguard">
                    <h5 class="modal-title" id="editPreguntaModalLabel">Editar Pregunta</h5>
                    <button type="button" class="text-white close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editPreguntaForm">
                        <input type="hidden" id="editPreguntaId">
                        <div class="form-group">
                            <label for="editPreguntaPregunta">Pregunta</label>
                            <textarea class="form-control" id="editPreguntaPregunta" rows="3" required></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="editPreguntaCampaniaHasCompetenciaId">Competencia*</label>
                                    <select class="form-control" id="editPreguntaCampaniaHasCompetenciaId" required>
                                        <!-- Se llenará dinámicamente -->
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="editPreguntaDominioId">Dominio*</label>
                                    <select class="form-control" id="editPreguntaDominioId" required>
                                        <!-- Se llenará dinámicamente -->
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="editPreguntaOrden">Número de Orden*</label>
                                    <input type="number" class="form-control" id="editPreguntaNumeroOrden"
                                        min="1" required>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-sm" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-vanguard" id="savePreguntaChanges">Guardar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Evaluaciones -->
    <div class="modal fade" id="editEvaluacionModal" tabindex="-1" role="dialog"
        aria-labelledby="editEvaluacionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="text-white modal-header bg-vanguard">
                    <h5 class="modal-title" id="editEvaluacionModalLabel">Editar Evaluación</h5>
                    <button type="button" class="text-white close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editEvaluacionForm">
                        <input type="hidden" id="editEvaluacionId">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="editEvaluacionNombreParaMostrar">Nombre para Mostrar*</label>
                                    <input type="text" class="form-control" id="editEvaluacionNombreParaMostrar"
                                        required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="editEvaluacionIdentificador">Identificador*</label>
                                    <input type="text" class="form-control" id="editEvaluacionIdentificador" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="editEvaluacionTipoDeEvaluacionId">Tipo de Evaluación*</label>
                                    <select class="form-control" id="editEvaluacionTipoDeEvaluacionId" required>
                                        <!-- Se llenará dinámicamente -->
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="editEvaluacionFechaInicio">Fecha de Inicio*</label>
                                    <input type="datetime-local" class="form-control" id="editEvaluacionFechaInicio"
                                        required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="editEvaluacionFechaFin">Fecha de Fin*</label>
                                    <input type="datetime-local" class="form-control" id="editEvaluacionFechaFin"
                                        required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="editEvaluacionFechaCorte">Fecha de Corte</label>
                                    <input type="date" class="form-control" id="editEvaluacionFechaCorte">
                                    <small class="form-text text-muted">Fecha límite para el procesamiento de datos</small>
                                </div>
                            </div>
                        </div>

                        <!-- Campos específicos para evaluaciones por objetivos (tipo 2) -->
                        <div class="fases-fields" style="display: none;">
                            <hr>
                            <h5>Configuración de Fases (Evaluación por Objetivos)</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="editEvaluacionFechaInicioPrimeraFaseMatricula">Fecha Inicio Primera
                                            Fase</label>
                                        <input type="datetime-local" class="form-control"
                                            id="editEvaluacionFechaInicioPrimeraFaseMatricula">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="editEvaluacionFechaFinPrimeraFaseMatricula">Fecha Fin Primera
                                            Fase</label>
                                        <input type="datetime-local" class="form-control"
                                            id="editEvaluacionFechaFinPrimeraFaseMatricula">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="editEvaluacionFechaInicioSegundaFase">Fecha Inicio Segunda Fase</label>
                                        <input type="datetime-local" class="form-control"
                                            id="editEvaluacionFechaInicioSegundaFase">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="editEvaluacionFechaFinSegundaFase">Fecha Fin Segunda Fase</label>
                                        <input type="datetime-local" class="form-control"
                                            id="editEvaluacionFechaFinSegundaFase">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="editEvaluacionMinimo">Mínimo (%)*</label>
                                        <input type="number" class="form-control" id="editEvaluacionMinimo"
                                            min="0" max="100" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="editEvaluacionMaximo">Máximo (%)*</label>
                                        <input type="number" class="form-control" id="editEvaluacionMaximo"
                                            min="0" max="100" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="editEvaluacionFechaParaMostrarResultados">Fecha para Mostrar
                                            Resultados</label>
                                        <input type="datetime-local" class="form-control"
                                            id="editEvaluacionFechaParaMostrarResultados">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-sm" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-vanguard" id="saveEvaluacionChanges">Guardar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Evaluados -->
    <div class="modal fade" id="editEvaluadoModal" tabindex="-1" role="dialog"
        aria-labelledby="editEvaluadoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="text-white modal-header bg-vanguard">
                    <h5 class="modal-title" id="editEvaluadoModalLabel">Editar Evaluado</h5>
                    <button type="button" class="text-white close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editEvaluadoForm">
                        <input type="hidden" id="editEvaluadoId">
                        <input type="hidden" id="editEvaluadoCampaniaId">
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="editEvaluadoPersonalId">Personal*</label>
                                    <select class="form-control" id="editEvaluadoPersonalId" required>
                                        <option value="">Seleccione...</option>
                                        <!-- Se llenará dinámicamente -->
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="editEvaluadoAreaId">Área</label>
                                    <select class="form-control" id="editEvaluadoAreaId">
                                        <option value="">Seleccione...</option>
                                        <!-- Se llenará dinámicamente -->
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="editEvaluadoPuestoId">Puesto</label>
                                    <select class="form-control" id="editEvaluadoPuestoId">
                                        <option value="">Seleccione...</option>
                                        <!-- Se llenará dinámicamente -->
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="editEvaluadoTipoDePuestoCampaniaId">Nivel Jerárquico</label>
                                    <select class="form-control" id="editEvaluadoTipoDePuestoCampaniaId">
                                        <option value="">Seleccione...</option>
                                        <!-- Se llenará dinámicamente -->
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            {{-- <div class="col-md-6"> --}}
                                <div class="form-group">
                                    {{-- <label for="editEvaluadoDominioId">Dominio</label> --}}
                                    <select class="form-control" id="editEvaluadoDominioId" hidden>
                                        <option value="">Seleccione...</option>
                                        <!-- Se llenará dinámicamente -->
                                    </select>
                                </div>
                            {{-- </div> --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="editEvaluadoSuperiorPersonalId">Superior Jerárquico</label>
                                    <select class="form-control" id="editEvaluadoSuperiorPersonalId">
                                        <option value="">Seleccione...</option>
                                        <!-- Se llenará dinámicamente -->
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="editEvaluadoHabilitadoParaEvaluacionDeCompetenciasCheck">
                                        <label class="form-check-label" for="editEvaluadoHabilitadoParaEvaluacionDeCompetenciasCheck">
                                            Habilitado para evaluación de competencias
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="editEvaluadoHabilitadoParaEvaluacionPorObjetivosCheck">
                                        <label class="form-check-label" for="editEvaluadoHabilitadoParaEvaluacionPorObjetivosCheck">
                                            Habilitado para evaluación por objetivos
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="editEvaluadoEstadoCheck" checked>
                                        <label class="form-check-label" for="editEvaluadoEstadoCheck">
                                            Activo
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="editEvaluadoCesadoCheck">
                                        <label class="form-check-label" for="editEvaluadoCesadoCheck">
                                            Cesado
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Añadir este checkbox para permitir actualizar también el personal -->
                        <div class="mt-3 form-group form-check">
                            <input type="checkbox" class="form-check-input" id="actualizar_personal" name="actualizar_personal">
                            <label class="form-check-label" for="actualizar_personal">
                                Actualizar también el área en el registro de personal
                            </label>
                            <small class="form-text text-muted">
                                Si marca esta opción, el cambio de área se aplicará también al registro principal del empleado.
                            </small>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-sm" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-vanguard" id="saveEvaluadoChanges">Guardar</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal para Evaluador-Has-Evaluado -->
    <div class="modal fade" id="editEvaluadorHasEvaluadoModal" tabindex="-1" role="dialog"
        aria-labelledby="editEvaluadorHasEvaluadoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content rounded-2xl">
                <div class="text-white modal-header bg-vanguard rounded-t-2xl">
                    <h5 class="modal-title" id="editEvaluadorHasEvaluadoModalLabel">Evaluador-Has-Evaluado</h5>
                    <button type="button" class="text-white close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editEvaluadorHasEvaluadoForm">
                        <input type="hidden" id="editEvaluadorHasEvaluadoId">
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label for="editEvaluadorId">Evaluador*</label>
                                <select class="form-control" id="editEvaluadorId" required></select>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="editEvaluadoId">Evaluado*</label>
                                <select class="form-control" id="editEvaluadoId" required></select>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="editEvaluacionId">Evaluación*</label>
                                <select class="form-control" id="editEvaluacionId" required></select>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="editCargoEvaluador">Cargo de Evaluador*</label>
                                <input type="text" class="form-control" id="editCargoEvaluador" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="editAreaEvaluador">Área de Evaluador*</label>
                                <input type="text" class="form-control" id="editAreaEvaluador" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="editGerenciaEvaluador">Gerencia/Subgerencia de Evaluador*</label>
                                <input type="text" class="form-control" id="editGerenciaEvaluador" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="editCargoEvaluado">Cargo de Evaluado*</label>
                                <input type="text" class="form-control" id="editCargoEvaluado" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="editAreaEvaluado">Área de Evaluado*</label>
                                <input type="text" class="form-control" id="editAreaEvaluado" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="editGerenciaEvaluado">Gerencia/Subgerencia de Evaluado*</label>
                                <input type="text" class="form-control" id="editGerenciaEvaluado" required>
                            </div>
                            <div class="form-group col-md-4" id="jerarquiaGroup" style="display:none;">
                                <label for="editJerarquia">Jerarquía*</label>
                                <select class="form-control" id="editJerarquia">
                                    <option value="">Seleccione</option>
                                    <option value="1">TIPO 1 (INDIVIDUAL)</option>
                                    <option value="2">TIPO 2 (GRUPAL)</option>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary rounded-xl" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-vanguard" id="saveEvaluadorHasEvaluadoChanges">Guardar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Importar Evaluados -->
    <div class="modal fade" id="importEvaluadosModal" tabindex="-1" role="dialog" aria-labelledby="importEvaluadosModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="text-white modal-header bg-vanguard">
                    <h5 class="modal-title" id="importEvaluadosModalLabel">Importar Evaluados</h5>
                    <button type="button" class="text-white close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="importEvaluadosForm" enctype="multipart/form-data">
                        <input type="hidden" id="importEvaluadosCampaniaId">
                        <div class="form-group">
                            <label for="evaluadosExcelFile">Seleccionar archivo Excel</label>
                            <input type="file" class="form-control-file" id="evaluadosExcelFile" accept=".xlsx,.xls">
                            <small class="form-text text-muted">El archivo debe contener las columnas: DNI, nombre, área, puesto, etc.</small>
                        </div>

                        <!-- Panel de Validación -->
                        <div id="validationEvaluadosSummary" class="mt-3" style="display: none;">
                            <div class="card">
                                <div class="card-body">
                                    <h6>Resumen de validación</h6>
                                    <div id="validationEvaluadosContent"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Panel de Previsualización -->
                        <div id="previewEvaluadosContainer" class="mt-3" style="display: none;">
                            <div class="card">
                                <div class="text-white card-header bg-primary">
                                    <h6 class="mb-0">Previsualización de datos</h6>
                                </div>
                                <div class="card-body">
                                    <div id="previewEvaluadosContent"></div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-sm" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" id="validateEvaluadosButton">Validar</button>
                    <button type="button" class="btn btn-success" id="importEvaluadosButton"
                        style="display: none;">Importar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para dar de baja evaluado -->
    <div class="modal fade" id="bajaEvaluadoModal" tabindex="-1" role="dialog" aria-labelledby="bajaEvaluadoModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="text-white modal-header bg-vanguard">
                    <h5 class="modal-title" id="bajaEvaluadoModalLabel">Dar de baja evaluado</h5>
                    <button type="button" class="text-white close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="bajaEvaluadoForm">
                        <input type="hidden" id="bajaEvaluadoId">
                        <input type="hidden" id="bajaEvaluadoTipo">
                        
                        <div class="form-group">
                            <label for="bajaEvaluadoMotivo">Motivo de baja*</label>
                            <textarea class="form-control" id="bajaEvaluadoMotivo" rows="3" required></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger" id="confirmarBajaEvaluado">Confirmar baja</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Pesos -->
    <div class="modal fade" id="editPesoModal" tabindex="-1" role="dialog" aria-labelledby="editPesoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="text-white modal-header bg-vanguard">
                    <h5 class="modal-title" id="editPesoModalLabel">Editar Peso</h5>
                    <button type="button" class="text-white close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editPesoForm">
                        <input type="hidden" id="editPesoId">
                        <input type="hidden" id="editPesoCampaniaId">
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="editPesoTipoRelacionJerarquicaId">Tipo de Relación Jerárquica*</label>
                                    <select class="form-control" id="editPesoTipoRelacionJerarquicaId" required>
                                        <option value="">Seleccione...</option>
                                        <!-- Se llenará dinámicamente -->
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="editPesoGradoId">Grado*</label>
                                    <select class="form-control" id="editPesoGradoId" required>
                                        <option value="">Seleccione...</option>
                                        <!-- Se llenará dinámicamente -->
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="editPesoPeso">Peso*</label>
                                    <input type="number" class="form-control" id="editPesoPeso" required step="0.1" min="0" max="1">
                                    <small class="form-text text-muted">Valor entre 0 y 1</small>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-vanguard" id="savePesoChanges">Guardar</button>
                </div>
            </div>
        </div>
    </div>

@stop

@section('css')
@stop

@section('js')

    <script src="{{ asset('js/campaniaHasPreguntas.js') }}"></script>
    <script src="{{ asset('js/campaniaHasCompetencias.js') }}"></script>
    <script src="{{ asset('js/campaniaHasEvaluaciones.js') }}"></script>
    <script src="{{ asset('js/campaniaHasEvaluados.js') }}"></script>
    <script src="{{ asset('js/evaluadorHasEvaluado.js') }}"></script>
    <script src="{{ asset('js/pesos.js') }}"></script>
    <script>
        window.evaluadorHasEvaluadoUrls = {
            ajaxURL: "{{ url('campanias') }}/:campania_id/evaluador-has-evaluados",
            showURL: "{{ url('evaluador-has-evaluados') }}/:id",
            storeURL: "{{ url('evaluador-has-evaluados') }}",
            updateURL: "{{ url('evaluador-has-evaluados') }}/:id",
            deleteURL: "{{ url('evaluador-has-evaluados') }}/:id",
            selectsURL: "{{ url('campanias') }}/:campania_id/evaluador-has-evaluados/selects",
            personalSearchURL: "{{ url('personal/search') }}", // endpoint para búsqueda remota
            evaluacionSearchURL: "{{ url('evaluaciones/search') }}" // si lo necesitas
        };
    </script>
    <script>
        // import { deleteItem, showLoading } from '{{ asset('js/utils.js') }}';
        const COMPETENCIAS_BY_CAMPANIA_URL = "{{ route('campanias.getAllCompetenciasByCampaniaId', ':id') }}";
        const CHC_STORE_URL = "{{ route('campania_has_competencias.store') }}";
        const CHC_UPDATE_URL = "{{ route('campania_has_competencias.update', ':id') }}";
        const CHC_SHOW_URL = "{{ route('campania_has_competencias.show', ':id') }}";
        const CHC_DELETE_URL = "{{ route('campania_has_competencias.destroy', ':id') }}";

        const COMPETENCIAS_URL = "{{ route('competencias.data') }}";
        const CAMPANIAS_GETALL_URL = "{{ route('campanias.getAll') }}";
        const CAMPANIAS_RELACIONADOS_ANTERIORES_URL = "{{ route('campanias.getAllCompetenciasCampaniaAnterior', ':id') }}";
        const TIPOS_COMPETENCIA_URL = "{{ route('tipo_competencias.data') }}";
        const TIPOS_MEDICION_URL = "{{ route('tipo_mediciones.data') }}";

        const PREGUNTAS_BY_CAMPANIA_URL = "{{ route('campanias.preguntas', ':id') }}";
        const PREGUNTAS_ESTADO_BY_CAMPANIA_URL = "{{ route('campanias.preguntas-estado', ':id') }}";
        const PREGUNTAS_COMPETENCIAS_URL = "{{ route('campanias.competencias', ':id') }}";
        const PREGUNTAS_DOMINIOS_URL = "{{ route('campanias.dominios', ':id') }}";
        const PREGUNTAS_STORE_URL = "{{ route('preguntas.store') }}";
        const PREGUNTAS_UPDATE_URL = "{{ route('preguntas.update', ':id') }}";
        const PREGUNTAS_SHOW_URL = "{{ route('preguntas.show', ':id') }}";
        const PREGUNTAS_DELETE_URL = "{{ route('preguntas.destroy', ':id') }}";
        const PREGUNTAS_VALIDAR_IMPORT_URL = "{{ route('campanias.validar-preguntas', ':id') }}";
        const PREGUNTAS_IMPORT_URL = "{{ route('campanias.importar-preguntas', ':id') }}";

        // URLs para evaluaciones
        const EVALUACIONES_BY_CAMPANIA_URL = "{{ route('campanias.evaluaciones', ':id') }}";
        const EVALUACIONES_STORE_URL = "{{ route('evaluaciones.store') }}";
        const EVALUACIONES_UPDATE_URL = "{{ route('evaluaciones.update', ':id') }}";
        const EVALUACIONES_SHOW_URL = "{{ route('evaluaciones.show', ':id') }}";
        const EVALUACIONES_DELETE_URL = "{{ route('evaluaciones.destroy', ':id') }}";
        const TIPOS_EVALUACION_URL = "{{ route('tipos_evaluacion.data') }}";

        // URLs para evaluados
        const EVALUADOS_BY_CAMPANIA_URL = "{{ route('campanias.evaluados', ':id') }}";
        const EVALUADOS_STORE_URL = "{{ route('campania_has_evaluados.store') }}";
        const EVALUADOS_UPDATE_URL = "{{ route('campania_has_evaluados.update', ':id') }}";
        const EVALUADOS_SHOW_URL = "{{ route('campania_has_evaluados.show', ':id') }}";
        const EVALUADOS_DELETE_URL = "{{ route('campania_has_evaluados.destroy', ':id') }}";
        const EVALUADOS_TOGGLE_COMPETENCIAS_URL = "{{ route('campania_has_evaluados.toggle-competencias', ':id') }}";
        const EVALUADOS_TOGGLE_OBJETIVOS_URL = "{{ route('campania_has_evaluados.toggle-objetivos', ':id') }}";
        const EVALUADOS_SELECTS_URL = "{{ route('campania_has_evaluados.selects') }}";
        const EVALUADOS_IMPORT_URL = "{{ route('campania_has_evaluados.importar') }}";
        const EVALUADOS_VALIDATE_IMPORT_URL = "{{ route('campania_has_evaluados.validar-importar') }}";

        const PERSONAL_SEARCH_URL = "{{ route('personal.search-evaluado') }}";
        const PERSONAL_DETAILS_URL = "{{ route('personal.details') }}";
        
        // URLs para Pesos
        const CAMPANIAS_PESOS_URL = "{{ route('campanias.pesos', ':id') }}";
        const PESOS_SELECTS_URL = "{{ route('pesos.selects') }}";
        const PESOS_STORE_URL = "{{ route('pesos.store') }}";
        const PESOS_SHOW_URL = "{{ route('pesos.show', ':id') }}";
        const PESOS_UPDATE_URL = "{{ route('pesos.update', ':id') }}";
        const PESOS_DELETE_URL = "{{ route('pesos.destroy', ':id') }}";
        
        const GENERAR_EVALUADOR_HAS_EVALUADOS_URL = "{{ route('campanias.generarEvaluadorHasEvaluado', ':id') }}"; 

        document.addEventListener('DOMContentLoaded', function() {
            var table = new Tabulator("#campania-table", {
                ajaxURL: "{{ route('campanias.getData') }}",
                layout: "fitDataFill",
                columns: [{
                        title: "ID",
                        field: "id",
                        headerSort: false,
                        width: 60
                    },
                    {
                        title: "Nombre",
                        field: "name",
                    },
                    {
                        title: "Relacionado Anterior",
                        field: "relacionado_anterior.name",

                    },
                    {
                        title: "Estado",
                        field: "estado",
                        formatter: "tickCross",
                        headerSort: false,
                        width: 100
                    },
                    {
                        title: "Acciones",
                        formatter: function(cell, formatterParams, onRendered) {
                            var id = cell.getRow().getData().id;
                            return '<div class="btn-group" role="group">' +
                                '<button class="mr-1 btn btn-info btn-sm edit-button" data-id="' +
                                id + '"><i class="fas fa-edit"></i></button>' +
                                '<button class="mr-1 btn btn-danger btn-sm delete-button" data-id="' +
                                id + '"><i class="fas fa-trash"></i></button>' +
                                '<button class="btn btn-primary btn-sm config-button" data-id="' +
                                id + '"><i class="fas fa-cog"></i></button>' +
                                '</div>';
                        },
                        headerSort: false,
                        width: 150
                    }
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

            function llenarSelectRelacionadoAnterior(campaniaId = null, selectedId = null) {
                // Asegúrate de tener la URL en una variable global, por ejemplo:
                // const CAMPANIAS_GETALL_URL = "{{ route('campanias.getAll') }}";
                $.get(CAMPANIAS_GETALL_URL, function(data) {
                    const $select = $('#editRelacionadoAnteriorId');
                    $select.empty().append('<option value="">Ninguno</option>');
                    data.forEach(function(campania) {
                        // Evita que la campaña se relacione consigo misma
                        if (!campaniaId || campania.id != campaniaId) {
                            $select.append(new Option(campania.name, campania.id));
                        }
                    });
                    if (selectedId) $select.val(selectedId);
                });
            }

            // Evento click para el botón "Editar"
            $("#campania-table").on("click", ".edit-button", function() {
                var id = $(this).data("id");
                $.get("{{ route('campanias.index') }}/" + id, function(data) {
                    $('#editId').val(data.id);
                    $('#editName').val(data.name);
                    $('#editEstado').val(data.estado);
                    $('#editEsCampaniaActual').val(data.es_campania_actual); // Agregar esta línea
                    llenarSelectRelacionadoAnterior(data.id, data.relacionado_anterior_id);
                    $('#editModal').modal('show');
                });
            });

            // Evento click para el botón "Crear Nuevo"
            $("#createButton").on("click", function() {
                $('#editForm')[0].reset();
                $('#editId').val('');
                llenarSelectRelacionadoAnterior();
                $('#editModal').modal('show');
            });

            // Evento click para guardar cambios
            $('#saveChanges').on('click', function() {
                var id = $('#editId').val();
                var formData = {
                    name: $('#editName').val(),
                    estado: $('#editEstado').val(),
                    es_campania_actual: $('#editEsCampaniaActual').val(), // Agregar esta línea
                    relacionado_anterior_id: $('#editRelacionadoAnteriorId').val()
                };

                var url = id ? "{{ route('campanias.index') }}/" + id : "{{ route('campanias.store') }}";
                var method = id ? 'PUT' : 'POST';

                saveOrUpdateItem({
                    modelName: 'campaña',
                    url: url,
                    method: method,
                    data: formData,
                    modalSelector: '#editModal',
                    successCallback: function() {
                        table.replaceData();
                    },
                    errorCallback: function(errorMessage) {
                        console.error("Error al guardar la campaña:", errorMessage);
                    },
                    loadingText: id ? 'Actualizando...' : 'Guardando...',
                    successText: id ? 'Campaña actualizada correctamente.' :
                        'Campaña creada correctamente.'
                });
            });

            // Evento click para el botón "Eliminar"
            $("#campania-table").on("click", ".delete-button", function() {
                var id = $(this).data("id");

                deleteItem(
                    'campaña',
                    id,
                    "{{ route('campanias.index') }}",
                    function() {
                        table.replaceData();
                    },
                    function(errorMessage) {
                        console.error("Error al eliminar la campaña:", errorMessage);
                    },
                    'female' // Género femenino
                );
            });

            // Evento click para el botón "Configuración"
            $("#campania-table").on("click", ".config-button", function(e) {
                e.preventDefault();
                // Mostrar mensaje de carga
                showLoading('Cargando datos', 'Cargando configuración de campaña...');
                var id = $(this).data("id");

                // Cargar datos de la campaña
                $.get("{{ route('campanias.index') }}/" + id, function(data) {
                    $('#configId').text(data.id);
                    $('#configName').text(data.name);
                    $('#configEstado').text(data.estado == 1 ? 'Activo' : 'Inactivo');
                    $('#configCreatedAt').text(new Date(data.created_at).toLocaleString(
                    'es-PE')); // Mostrar panel de configuración
                    $('#configPanel').slideDown();
                    Swal.close(); // Cerrar el mensaje de carga
                    // Cargar tabla de preguntas
                    initCompetenciasTable(data.id);
                    initPreguntasTable(data.id);
                    initImportPreguntasEvents(data.id);
                    // Cargar tabla de evaluaciones
                    initEvaluacionesTable(data.id);
                    // Cargar tabla de evaluados
                    initEvaluadosTable(data.id);
                    // Inicializar tabla de pesos usando BaseModel
                    initPesosTable(data.id);
                    initEvaluadorHasEvaluadoTable(data.id);

                    // initPesosTable(data.id);
                    
                    // pesosModel = new PesosModel(data.id);
                    // pesosModel.init();
                    // Llenar el select de tipos de evaluación
                    // llenarSelectTipoEvaluacion();

                    onConfigurarCampania(id);

                    // loadPreguntasTable(id);

                    // Scroll suave hasta el panel
                    $('html, body').animate({
                        scrollTop: $("#configPanel").offset().top - 20
                    }, 500);
                }).fail(function(xhr) {
                    Swal.fire('Error', 'Error al cargar la campaña', 'error');
                })

            }); // Evento para cerrar el panel de configuración

            $("#closeConfig").click(function() {
                $('#configPanel').slideUp();
            });

            function showLoading(title, text) {
                Swal.fire({
                    title: title,
                    text: text,
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
            }
        });
        
        $(document).on('click', '[data-toggle="collapse"]', function() {
            var $icon = $(this).find('i.fas.fa-chevron-down, i.fas.fa-chevron-up');
            var $target = $($(this).data('target'));
            setTimeout(function() {
                if ($target.hasClass('show')) {
                    $icon.removeClass('fa-chevron-up').addClass('fa-chevron-down');
                } else {
                    $icon.removeClass('fa-chevron-down').addClass('fa-chevron-up');
                }
            }, 100);
        });

    </script>
@stop
