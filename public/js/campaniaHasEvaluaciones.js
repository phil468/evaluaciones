// Gestión de evaluaciones para campañas
window.initEvaluacionesTable = initEvaluacionesTable;
window.openEvaluacionModal = openEvaluacionModal;

/**
 * Inicializa la tabla de evaluaciones para una campaña específica
 * @param {number} campaniaId - ID de la campaña
 */
function initEvaluacionesTable(campaniaId) {
    if (window.evalModel && window.evalModel.table) {
        window.evalModel.table.destroy();
    }

    window.evalModel = new BaseModel({
        modelName: 'Evaluación',
        gender: 'female',
        tableSelector: "#evaluaciones-table",
        formSelector: "#editEvaluacionForm",
        formPrefix: "editEvaluacion",
        modalSelector: "#editEvaluacionModal",
        createButtonSelector: "#createEvaluacionButton",
        saveButtonSelector: "#saveEvaluacionChanges",
        ajaxURL: EVALUACIONES_BY_CAMPANIA_URL.replace(':id', campaniaId),
        storeURL: EVALUACIONES_STORE_URL,
        updateURL: EVALUACIONES_UPDATE_URL,
        showURL: EVALUACIONES_SHOW_URL,
        deleteURL: EVALUACIONES_DELETE_URL,
        autoCampaniaId: true,
        fields: [
            'nombreParaMostrar', 'identificador', 'tipoDeEvaluacionId',
            'fechaInicio', 'fechaFin', 'fechaCorte', 'minimo', 'maximo',
            'fechaInicioPrimeraFaseMatricula', 'fechaFinPrimeraFaseMatricula',
            'fechaInicioSegundaFase', 'fechaFinSegundaFase',
            'fechaParaMostrarResultados', 'title'
        ],
        locale: true,
        columns: [
            { title: "ID", field: "id", width: 70 },
            {
                title: "Acciones",
                formatter: function (cell) {
                    var id = cell.getRow().getData().id;
                    return `<div class="btn-group" role="group">
                        <button class="mr-1 btn btn-info btn-sm edit-button" data-id="${id}"><i class="fas fa-edit"></i></button>
                        <button class="btn btn-danger btn-sm delete-button" data-id="${id}"><i class="fas fa-trash"></i></button>
                    </div>`;
                },
                width: 150
            },
            { title: "Nombre para mostrar", field: "nombre_para_mostrar" },
            { title: "Title", field: "title" },
            { title: "Status", field: "status", formatter: function (cell) {
                // activo = 1, inactivo = 0, realizado = 2
                var status = cell.getValue();
                if (status === 1) {
                    return '<span class="badge badge-primary">Activo</span>';
                }
                if (status === 0) {
                    return '<span class="badge badge-secondary">Inactivo</span>';
                }
                if (status === 2) {
                    return '<span class="badge badge-success">Realizado</span>';
                }
            } 
        },
            { title: "Identificador", field: "identificador" },
            { title: "Tipo", field: "tipo_de_evaluacion.name" },
            {
                title: "Fecha Inicio",
                field: "fecha_inicio",
                formatter: function (cell) {
                    return cell.getValue() ? new Date(cell.getValue()).toLocaleDateString() + ' ' + new Date(cell.getValue()).toLocaleTimeString() : '';
                }
            },
            {
                title: "Fecha Fin",
                field: "fecha_fin",
                formatter: function (cell) {
                    return cell.getValue() ? new Date(cell.getValue()).toLocaleDateString() + ' ' + new Date(cell.getValue()).toLocaleTimeString() : '';
                }
            },
            {
                title: "Fecha Corte",
                field: "fecha_corte",
                formatter: function (cell) {
                    return cell.getValue() ? new Date(cell.getValue()).toLocaleDateString() + ' ' + new Date(cell.getValue()).toLocaleTimeString() : '';
                }
            },
            { title: "Fecha Inicio Primera Fase Matricula", field: "fecha_inicio_primera_fase_matricula",
                formatter: function (cell) {
                    return cell.getValue() ? new Date(cell.getValue()).toLocaleDateString() + ' ' + new Date(cell.getValue()).toLocaleTimeString() : '';
                }
            },
            { title: "Fecha Fin Primera Fase Matricula", field: "fecha_fin_primera_fase_matricula",
                formatter: function (cell) {
                    return cell.getValue() ? new Date(cell.getValue()).toLocaleDateString() + ' ' + new Date(cell.getValue()).toLocaleTimeString() : '';
                }
            },
            { title: "Fecha Inicio Segunda Fase", field: "fecha_inicio_segunda_fase",
                formatter: function (cell) {
                    return cell.getValue() ? new Date(cell.getValue()).toLocaleDateString() + ' ' + new Date(cell.getValue()).toLocaleTimeString() : '';
                }
            },
            { title: "Fecha Fin Segunda Fase", field: "fecha_fin_segunda_fase",
                formatter: function (cell) {
                    return cell.getValue() ? new Date(cell.getValue()).toLocaleDateString() + ' ' + new Date(cell.getValue()).toLocaleTimeString() : '';
                }
            },
            { title: "Fecha para Mostrar Resultados", field: "fecha_para_mostrar_resultados",
                formatter: function (cell) {
                    return cell.getValue() ? new Date(cell.getValue()).toLocaleDateString() + ' ' + new Date(cell.getValue()).toLocaleTimeString() : '';
                }
            },            
            { title: "Mínimo", field: "minimo", formatter: function (cell) { return cell.getValue() + '%'; } },
            { title: "Máximo", field: "maximo", formatter: function (cell) { return cell.getValue() + '%'; } }
        ],
        afterCreate: function () {
            actualizarCamposTipoEvaluacion();
        },
        afterEdit: function () {
            actualizarCamposTipoEvaluacion();
        }
    });
        
    llenarSelectTipoEvaluacion(); // Llenar selects al inicializar

    window.evalModel.init();
}

/**
 * Abre el modal de evaluación con los datos proporcionados
 * @param {Object} data - Datos de la evaluación
 */
function openEvaluacionModal(data) {
    // Asignar valores a los campos del formulario
    $('#editEvaluacionNombreParaMostrar').val(data.nombre_para_mostrar);
    $('#editEvaluacionIdentificador').val(data.identificador);
    //status
    $('#editEvaluacionStatus').val(data.status); // Por defecto activo
    $('#editEvaluacionTipoDeEvaluacionId').val(data.tipo_de_evaluacion_id);
    $('#editEvaluacionFechaInicio').val(data.fecha_inicio ? formatDateTimeForInput(data.fecha_inicio) : '');
    $('#editEvaluacionFechaFin').val(data.fecha_fin ? formatDateTimeForInput(data.fecha_fin) : '');
    $('#editEvaluacionFechaCorte').val(data.fecha_corte ? formatDateForInput(data.fecha_corte) : '');
    $('#editEvaluacionMinimo').val(data.minimo);
    $('#editEvaluacionMaximo').val(data.maximo);
    $('#editEvaluacionTitle').val(data.title || ''); // Si es nuevo, el título estará vacío

    // Campos para tipo 2 (evaluación por objetivos)
    $('#editEvaluacionFechaInicioPrimeraFaseMatricula').val(data.fecha_inicio_primera_fase_matricula ? formatDateTimeForInput(data.fecha_inicio_primera_fase_matricula) : '');
    $('#editEvaluacionFechaFinPrimeraFaseMatricula').val(data.fecha_fin_primera_fase_matricula ? formatDateTimeForInput(data.fecha_fin_primera_fase_matricula) : '');
    $('#editEvaluacionFechaInicioSegundaFase').val(data.fecha_inicio_segunda_fase ? formatDateTimeForInput(data.fecha_inicio_segunda_fase) : '');
    $('#editEvaluacionFechaFinSegundaFase').val(data.fecha_fin_segunda_fase ? formatDateTimeForInput(data.fecha_fin_segunda_fase) : '');
    $('#editEvaluacionFechaParaMostrarResultados').val(data.fecha_para_mostrar_resultados ? formatDateTimeForInput(data.fecha_para_mostrar_resultados) : '');

    // Actualizar el estado de los campos según el tipo de evaluación
    actualizarCamposTipoEvaluacion();

    // Mostrar el modal
    $('#editEvaluacionModal').modal('show');
}

/**
 * Actualiza la visibilidad de los campos según el tipo de evaluación seleccionado
 */
function actualizarCamposTipoEvaluacion() {
    const tipoEvaluacion = parseInt($('#editEvaluacionTipoDeEvaluacionId').val()) || 0;
    const $fasesFields = $('.fases-fields');

    // Si es tipo 2 (evaluación por objetivos), mostrar campos de fases
    if (tipoEvaluacion === 2) {
        $fasesFields.show();
    } else {
        $fasesFields.hide();
    }
}

/**
 * Formatea una fecha para un campo input datetime-local
 * @param {string} dateTimeString - Fecha en formato string
 * @returns {string} - Fecha formateada para input datetime-local
 */
function formatDateTimeForInput(dateTimeString) {
    if (!dateTimeString) return '';
    const date = new Date(dateTimeString);
    return date.toISOString().slice(0, 16); // formato YYYY-MM-DDThh:mm
}

/**
 * Formatea una fecha para un campo input date
 * @param {string} dateString - Fecha en formato string
 * @returns {string} - Fecha formateada para input date
 */
function formatDateForInput(dateString) {
    if (!dateString) return '';
    const date = new Date(dateString);
    return date.toISOString().slice(0, 10); // formato YYYY-MM-DD
}

// Función para llenar el select de tipos de evaluación
function llenarSelectTipoEvaluacion() {
    $.get(TIPOS_EVALUACION_URL, function(data) {
        const $select = $('#editEvaluacionTipoDeEvaluacionId');
        $select.empty();
        data.forEach(function(tipo) {
            $select.append(new Option(tipo.name, tipo.id));
        });
    });
}

// Evento para actualizar campos cuando cambia el tipo de evaluación
$(document).on('change', '#editEvaluacionTipoDeEvaluacionId', function () {
    actualizarCamposTipoEvaluacion();
});
