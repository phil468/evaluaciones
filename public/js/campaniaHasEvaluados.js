/**
 * Script para gestionar los evaluados por campaña utilizando el patrón BaseModel
 */

// URL para búsqueda de personal
// const PERSONAL_SEARCH_URL = '/personal/search-evaluado';

// Clase para gestionar evaluados por campaña
class CampaniaHasEvaluados {
    constructor() {
        this.campaniaIdGlobal = null;
        this.evaluadosModel = null;
        this.table = null;

        // Hacer disponible la función para otros scripts
        window.initEvaluadosTable = this.initEvaluadosTable.bind(this);

        // Aumentar la función existente para incluir inicialización de evaluados
        const originalOnConfigurarCampania = window.onConfigurarCampania || function () { };
        window.onConfigurarCampania = (campaniaId) => {
            originalOnConfigurarCampania(campaniaId);

            // Inicializar tabla de evaluados
            // this.initEvaluadosTable(campaniaId);
            // Inicializar eventos para importación
            this.initImportEvaluadosEvents(campaniaId);
            this.initGenerarEvaluadorHasEvaluadosEvents(campaniaId);
        };

        // this.init();
    }

    /**
     * Inicializa la clase
     */
    init() {
        this.initEventListeners();
    }

    /**
     * Inicializa los event listeners
     */
    initEventListeners() {
        // Filtros para la tabla (siempre disponibles)
        $(document).on('keyup', '#filtroEvaluado', () => {
            this.filterEvaluadosTable();
        });

        $(document).on('change', '#filtroArea, #filtroTipoEvaluacion', () => {
            this.filterEvaluadosTable();
        });

        // Evento para confirmar la baja de un evaluado
        $(document).off('click').on('click', '#confirmarBajaEvaluado', () => {
            const id = $('#bajaEvaluadoId').val();
            const tipo = $('#bajaEvaluadoTipo').val();
            const motivo = $('#bajaEvaluadoMotivo').val();

            if (tipo === 'competencias') {
                this.toggleCompetencias(id, false, motivo);
            } else if (tipo === 'objetivos') {
                this.toggleObjetivos(id, false, motivo);
            }

            $('#bajaEvaluadoModal').modal('hide');
        });
    }

    /**
         * Inicializa la tabla de evaluados por campaña
         * @param {number} campaniaId - ID de la campaña
         */
    initEvaluadosTable(campaniaId) {
        this.campaniaIdGlobal = campaniaId;

        // Limpiar filtros
        $('#filtroEvaluado').val('');
        $('#filtroArea').val('');
        $('#filtroTipoEvaluacion').val('');

        // Cargar opciones para el filtro de áreas
        this.llenarSelectAjax({
            selector: '#filtroArea',
            url: EVALUADOS_SELECTS_URL,
            dataPath: 'areas',
            valueField: 'id',
            textField: 'name',
            emptyOption: true,
            emptyText: 'Todas las áreas',
        });

        // Configuración para el modelo BaseModel
        const evaluadosConfig = {
            modelName: 'Evaluado',
            formPrefix: 'editEvaluado',
            formSelector: '#editEvaluadoForm',
            modalSelector: '#editEvaluadoModal',
            tableSelector: '#evaluados-table',
            createButtonSelector: '#addEvaluadoButton',
            saveButtonSelector: '#saveEvaluadoChanges',
            ajaxURL: EVALUADOS_BY_CAMPANIA_URL.replace(':id', campaniaId),
            showURL: EVALUADOS_SHOW_URL,
            storeURL: EVALUADOS_STORE_URL,
            updateURL: EVALUADOS_UPDATE_URL,
            deleteURL: EVALUADOS_DELETE_URL,
            autoCampaniaId: true,
            gender: 'male',
            fields: [
                'id', 'personal_id', 'campania_id', 'area_id', 'puesto_id',
                'tipo_de_puesto_campania_id','superior_personal_id',
                // 'habilitado_para_evaluacion_de_competencias',
                // 'habilitado_para_evaluacion_por_objetivos'
            ],
            columns: [
                {
                    title: "ID",
                    field: "id",
                    headerSort: false,
                    width: 60
                },
                {
                    title: "Acciones",
                    formatter: (cell, formatterParams, onRendered) => {
                        const data = cell.getRow().getData();
                        const id = data.id;
                        let buttons = '<div class="btn-group" role="group">';

                        // Botón editar
                        buttons += '<button class="btn btn-info btn-sm edit-button mr-1" data-id="' + id + '"><i class="fas fa-edit"></i></button>';

                        // Activar/Desactivar competencias, usar tooltip
                        if (data.habilitado_para_evaluacion_de_competencias) {
                            buttons += '<button class="btn btn-warning btn-sm toggle-competencias-button mr-1" data-id="' + id + '" data-action="disable" title="Deshabilitar evaluación de competencias"><i class="fas fa-ban"></i></button>';
                        } else {
                            buttons += '<button class="btn btn-success btn-sm toggle-competencias-button mr-1" data-id="' + id + '" data-action="enable" title="Habilitar evaluación de competencias"><i class="fas fa-check"></i></button>';
                        }

                        // Activar/Desactivar objetivos
                        if (data.habilitado_para_evaluacion_por_objetivos) {
                            buttons += '<button class="btn btn-warning btn-sm toggle-objetivos-button mr-1" data-id="' + id + '" data-action="disable" title="Deshabilitar evaluación por objetivos"><i class="fas fa-ban"></i></button>';
                        } else {
                            buttons += '<button class="btn btn-success btn-sm toggle-objetivos-button mr-1" data-id="' + id + '" data-action="enable" title="Habilitar evaluación por objetivos"><i class="fas fa-check"></i></button>';
                        }

                        // Botón eliminar
                        buttons += '<button class="btn btn-danger btn-sm delete-button" data-id="' + id + '"><i class="fas fa-trash"></i></button>';

                        buttons += '</div>';
                        return buttons;
                    },
                    headerSort: false,
                    width: 150
                },
                {
                    title: "DNI",
                    field: "personal.dni",
                    headerFilter: "input"
                },
                {
                    title: "Nombre",
                    field: "personal.name",
                    headerFilter: "input"
                },
                {
                    title: "Área",
                    field: "area.name",
                    headerFilter: "input"
                },
                {
                    title: "Puesto",
                    field: "puesto.name",
                    headerFilter: "input"
                },
                {
                    title: "Tipo de Puesto",
                    field: "tipo_puesto_has_nivel_jerarquico.tipo_de_puesto.name",
                },
                {
                    title: "Nivel Jerárquico",
                    field: "tipo_puesto_has_nivel_jerarquico.nivel_jerarquico.name",
                },
                {
                    title: "Dominio",
                    field: "tipo_puesto_has_nivel_jerarquico.dominio.name",
                },
                {
                    title: "Grado",
                    field: "tipo_puesto_has_nivel_jerarquico.dominio.grado.name",
                },
                {
                    title: "Eval. Competencias",
                    field: "habilitado_para_evaluacion_de_competencias",
                    formatter: "tickCross",
                    headerSort: false,
                    width: 110
                },
                {
                    title: "Eval. Objetivos",
                    field: "habilitado_para_evaluacion_por_objetivos",
                    formatter: "tickCross",
                    headerSort: false,
                    width: 100
                },
                {
                    title: "Estado",
                    field: "estado",
                    formatter: "tickCross",
                    headerSort: false,
                    width: 80
                },
                {
                    title: "Cesado",
                    field: "cesado",
                    formatter: "tickCross",
                    headerSort: false,
                    width: 80
                },
                {
                    title: "Superior",
                    field: "superior",
                    headerFilter: "input",
                    formatter: (cell) => {
                        const superior = cell.getValue();
                        return superior ? `${superior.name} (${superior.dni})` : '';
                    },
                    headerSort: false,
                    width: 150
                },
                {
                    title: "Pares",
                    field: "pares_mismo_superior",
                    formatter: (cell) => {
                        const pares = cell.getValue();
                        const rowData = cell.getRow().getData();

                        // Nivel jerárquico del evaluado actual
                        const tipoDePuestoActual = rowData.tipo_puesto_has_nivel_jerarquico?.tipo_de_puesto.id;
                        const personalIdActual = rowData.personal_id;

                        if (!pares || pares.length === 0) return '';

                        // Filtrar: excluir a sí mismo y solo pares con el mismo nivel jerárquico
                        const paresFiltrados = pares.filter(par =>
                            par.personal_id !== personalIdActual &&
                            par.tipo_puesto_has_nivel_jerarquico &&
                            par.tipo_puesto_has_nivel_jerarquico.tipo_de_puesto.id === tipoDePuestoActual
                        );

                        return paresFiltrados.map(p =>
                            `${p.personal?.name || 'N/A'} (${p.personal?.dni || 'N/A'})`
                        ).join('<br>');
                    },
                    headerSort: false,
                    width: 220,
                    headerFilter: "input"
                },
                {
                    title: "Subordinados",
                    field: "subordinados",
                    formatter: (cell) => {
                        console.log ("Subordinados cell:", cell);
                        const subordinados = cell.getValue();
                        if (!subordinados || subordinados.length === 0) return '';
                        return subordinados.map(s => 
                            `${s.personal?.name || 'N/A'} (${s.personal?.dni || 'N/A'})`
                        ).join('<br>');
                    },
                },
            ],            // Hook que se ejecuta antes de abrir el modal            
            beforeOpenModal: (data) => {
                // Asignar el ID de campaña
                $('#editEvaluadoCampaniaId').val(this.campaniaIdGlobal);
                
                // Llenar los selects con los datos necesarios primero
                this.llenarSelectsEvaluadoModal();
                
                if (data.id) {
                    // Estamos editando
                    $('#editEvaluadoModalLabel').text('Editar Evaluado');
                    // Asignar valores de checkboxes ya que BaseModel no los maneja directamente
                    $('#editEvaluadoEstadoCheck').prop('checked', data.estado);
                    $('#editEvaluadoCesadoCheck').prop('checked', data.cesado);
                    $('#editEvaluadoHabilitadoParaEvaluacionDeCompetenciasCheck').prop('checked', data.habilitado_para_evaluacion_de_competencias);
                    $('#editEvaluadoHabilitadoParaEvaluacionPorObjetivosCheck').prop('checked', data.habilitado_para_evaluacion_por_objetivos);
                    
                    // Añadir el personal seleccionado al select2
                    if (data.personal) {
                        // Crear una opción para el personal actual
                        const personalOption = new Option(`${data.personal.name} (${data.personal.dni})`, data.personal_id, true, true);
                        $('#editEvaluadoPersonalId').append(personalOption).trigger('change');
                        
                        // Si hay superior, añadir también
                        if (data.superior_personal) {
                            const superiorOption = new Option(`${data.superior_personal.name} (${data.superior_personal.dni})`, data.superior_personal_id, true, true);
                            $('#editEvaluadoSuperiorPersonalId').append(superiorOption).trigger('change');
                        }
                    }
                } else {                    // Estamos creando
                    $('#editEvaluadoModalLabel').text('Agregar Evaluado');
                    $('#editEvaluadoEstadoCheck').prop('checked', true);
                    $('#editEvaluadoCesadoCheck').prop('checked', false);
                    $('#editEvaluadoHabilitadoParaEvaluacionDeCompetenciasCheck').prop('checked', false);
                    $('#editEvaluadoHabilitadoParaEvaluacionPorObjetivosCheck').prop('checked', false);
                }
            }
        };

        // Inicializa el BaseModel para evaluados
        this.evaluadosModel = new BaseModel(evaluadosConfig);

        // Extender la función save para incluir los campos de checkbox
        const originalSave = this.evaluadosModel.save;        
        this.evaluadosModel.save = () => {
            const id = $(`#editEvaluadoId`).val();
            let formData = {};

            // Recopilar datos del formulario usando los campos definidos
            evaluadosConfig.fields.forEach(field => {
                var field_snake_case = field.replace(/([a-z])([A-Z])/g, '$1_$2').toLowerCase();
                formData[field_snake_case] = $(`#editEvaluado${field.charAt(0).toUpperCase() + field.slice(1)}`).val();
            });            // Asegurarse de que los valores de los checkboxes están incluidos
            // Convertir explícitamente a valores booleanos numéricos 1/0 para Laravel
            formData.estado = $('#editEvaluadoEstadoCheck').is(':checked') ? 1 : 0;
            formData.cesado = $('#editEvaluadoCesadoCheck').is(':checked') ? 1 : 0;
            formData.habilitado_para_evaluacion_de_competencias = $('#editEvaluadoHabilitadoParaEvaluacionDeCompetenciasCheck').is(':checked') ? 1 : 0;
            formData.habilitado_para_evaluacion_por_objetivos =   $('#editEvaluadoHabilitadoParaEvaluacionPorObjetivosCheck').is(':checked') ? 1 : 0;
            formData.personal_id = $('#editEvaluadoPersonalId').val();
            formData.superior_personal_id = $('#editEvaluadoSuperiorPersonalId').val();
            formData.area_id = $('#editEvaluadoAreaId').val();
            formData.puesto_id = $('#editEvaluadoPuestoId').val();
            formData.tipo_de_puesto_campania_id = $('#editEvaluadoTipoDePuestoCampaniaId').val();

            formData.actualizar_personal = $('#actualizar_personal').is(':checked')

            // Añadir campania_id si no está incluido
            if (evaluadosConfig.autoCampaniaId) {
                formData.campania_id = this.campaniaIdGlobal;
            }
              // Validaciones
            const validaciones = [];
            
            // El personal es obligatorio
            if (!formData.personal_id) {
                validaciones.push('Debe seleccionar un personal');
            }
            
            // El superior jerárquico no puede ser la misma persona
            if (formData.personal_id && formData.superior_personal_id && 
                formData.personal_id === formData.superior_personal_id) {
                validaciones.push('El superior jerárquico no puede ser la misma persona seleccionada');
            }
            
            // Si hay errores de validación, mostrar alerta y detener
            if (validaciones.length > 0) {
                Swal.fire({
                    title: 'Error de validación',
                    html: validaciones.map(v => `• ${v}`).join('<br>'),
                    icon: 'error'
                });
                return;
            }

            const url = id ? evaluadosConfig.updateURL.replace(':id', id) : evaluadosConfig.storeURL;
            const method = id ? 'PUT' : 'POST';

            saveOrUpdateItem({
                modelName: evaluadosConfig.modelName,
                url: url,
                method: method,
                data: formData,
                modalSelector: evaluadosConfig.modalSelector,
                successCallback: () => this.evaluadosModel.table.replaceData(),
                errorCallback: function (errorMessage) {
                    console.error(`Error al guardar ${evaluadosConfig.modelName}:`, errorMessage);
                },
                loadingText: id ? 'Actualizando...' : 'Guardando...',
                successText: id ? `${evaluadosConfig.modelName} actualizado correctamente.` : `${evaluadosConfig.modelName} creado correctamente.`
            });
        };

        // Inicializar la tabla
        this.evaluadosModel.init();
        this.table = this.evaluadosModel.table;

        // Agregar event listeners específicos para esta tabla
        this.initEvaluadosSpecificEvents();
    }    /**
     * Inicializa eventos específicos para la tabla de evaluados
     */
    initEvaluadosSpecificEvents() {
        
        // Primero eliminar todos los listeners con este namespace
        $("#evaluados-table").off('.evaluadosEvents');
        // Botones para toggle competencias y objetivos
        $("#evaluados-table").on('click.evaluadosEvents', ".toggle-competencias-button", (e) => {
            e.preventDefault();
            const id = $(e.currentTarget).data("id");
            const action = $(e.currentTarget).data("action");

            if (action === "disable") {
                this.openBajaEvaluadoModal(id, 'competencias');
            } else {
                this.toggleCompetencias(id, true);
            }
        });

        $("#evaluados-table").on('click.evaluadosEvents', ".toggle-objetivos-button", (e) => {
            e.preventDefault();
            const id = $(e.currentTarget).data("id");
            const action = $(e.currentTarget).data("action");

            if (action === "disable") {
                this.openBajaEvaluadoModal(id, 'objetivos');
            } else {
                this.toggleObjetivos(id, true);
            }
        });

        // Evento para confirmación de eliminación con advertencia detallada
        $("#evaluados-table").on('click.evaluadosEvents', ".delete-button", (e) => {
            e.preventDefault();
            const id = $(e.currentTarget).data("id");
            
            Swal.fire({
                title: '¿Está seguro de eliminar este evaluado?',
                html: `<div class="text-left">
                    <p class="font-weight-bold text-danger">¡ADVERTENCIA!</p>
                    <p>Esta acción eliminará permanentemente:</p>
                    <ul>
                        <li>Todos los <b>objetivos</b> asociados a este evaluado</li>
                        <li>Los <b>comités de calibración</b> del evaluado</li>
                        <li>Todas las <b>respuestas de evaluación</b> relacionadas</li>
                        <li>Los <b>resúmenes de evaluaciones</b> de desempeño</li>
                        <li>Las relaciones con <b>evaluadores</b></li>
                        <li>El registro principal del evaluado en esta campaña</li>
                    </ul>
                    <p class="text-danger">Esta acción no se puede deshacer.</p>
                </div>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Mostrar indicador de carga
                    showLoading('Procesando', 'Eliminando evaluado...');
                    
                    // Enviar petición DELETE al servidor
                    $.ajax({
                        url: EVALUADOS_DELETE_URL.replace(':id', id),
                        type: 'DELETE',
                        // headers: {
                        //     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        // },
                        success: (response) => {
                            Swal.fire({
                                title: 'Eliminado',
                                text: response.message || 'El evaluado ha sido eliminado correctamente',
                                icon: 'success'
                            });
                            // Actualizar la tabla
                            this.table.replaceData(EVALUADOS_BY_CAMPANIA_URL.replace(':id', this.campaniaIdGlobal))
                            // actualizar la tabla de evaluadores
                            if (window.evaluadorHasEvaluadoModel) {
                                window.evaluadorHasEvaluadoModel.table.replaceData();
                                // window.evaluadorHasEvaluadoUrls.ajaxURL.replace(':id', this.campaniaIdGlobal)
                            }
                        },
                        error: (xhr) => {
                            Swal.fire({
                                title: 'Error',
                                text: xhr.responseJSON?.message || 'Ha ocurrido un error al eliminar el evaluado',
                                icon: 'error'
                            });
                        }
                    });
                }
            });
        });
        
        // Evento para limpiar select2 cuando se cierra el modal
        $('#editEvaluadoModal').off('.evaluadosEvents').on('hidden.bs.modal.evaluadosEvents', () => {
            // Destruir select2 para evitar duplicados y problemas de memoria
            if ($('#editEvaluadoPersonalId').data('select2')) {
                $('#editEvaluadoPersonalId').select2('destroy');
            }
            
            if ($('#editEvaluadoSuperiorPersonalId').data('select2')) {
                $('#editEvaluadoSuperiorPersonalId').select2('destroy');
            }
        });
    }

    /**
     * Aplica filtros a la tabla de evaluados
     */
    filterEvaluadosTable() {
        const filtroTexto = $('#filtroEvaluado').val().toLowerCase();
        const filtroArea = $('#filtroArea').val();
        const filtroTipoEvaluacion = $('#filtroTipoEvaluacion').val();

        let filtros = [];

        if (filtroTexto) {
            filtros.push({
                field: function (data) {
                    const nombre = data.personal ? data.personal.name.toLowerCase() : "";
                    const dni = data.personal ? data.personal.dni.toLowerCase() : "";
                    return nombre + " " + dni;
                },
                type: "like",
                value: filtroTexto
            });
        }

        if (filtroArea) {
            filtros.push({ field: "area.id", type: "=", value: filtroArea });
        }

        if (filtroTipoEvaluacion) {
            if (filtroTipoEvaluacion === 'competencias') {
                filtros.push({ field: "habilitado_para_evaluacion_de_competencias", type: "=", value: true });
            } else if (filtroTipoEvaluacion === 'objetivos') {
                filtros.push({ field: "habilitado_para_evaluacion_por_objetivos", type: "=", value: true });
            }
        }

        this.table.setFilter(filtros);
    }

    /**
     * Llena los selects del modal de evaluados
     */    
    llenarSelectsEvaluadoModal() {
        // Inicializar select2 para el Personal con carga dinámica
        const $personal = $('#editEvaluadoPersonalId');
        
        // Destruir instancia previa si existe para evitar duplicados
        if ($personal.data('select2')) {
            $personal.select2('destroy');
        }
        
        // Limpiar el elemento antes de inicializar
        $personal.empty().append('<option value=""></option>');
        
        $personal.select2({
            theme: 'bootstrap-5',
            width: '100%',
            placeholder: "Seleccione un personal",
            dropdownParent: $('#editEvaluadoModal'),
            ajax: {
                url: PERSONAL_SEARCH_URL || '/personal/search-evaluado',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        search: params.term || '',
                        page: params.page || 1
                    };
                },
                processResults: function (data, params) {
                    params.page = params.page || 1;
                    return {
                        results: data.results,
                        pagination: {
                            more: data.pagination.more
                        }
                    };
                },
                cache: true
            },
            minimumInputLength: 2,
            language: {
                inputTooShort: function() {
                    return "Por favor ingrese 2 o más caracteres";
                },
                searching: function() {
                    return "Buscando...";
                },
                noResults: function() {
                    return "No se encontraron resultados";
                }
            },
            templateResult: formatPersonalResult,
            templateSelection: formatPersonalSelection
        });
        
        // Evento cuando cambia el personal seleccionado
        $personal.on('change', function() {
            const personalId = $(this).val();
            
            // Si se borra la selección, no hacemos nada
            if (!personalId) return;
            
            // Mostrar indicador de carga
            showLoading('Cargando', 'Obteniendo datos del personal...');
              // Llamar al endpoint para obtener los detalles del personal
            $.ajax({
                url: PERSONAL_DETAILS_URL || '/personal/details',
                data: {
                    personal_id: personalId
                },
                method: 'GET',
                success: function(data) {
                    // Seleccionar área automáticamente
                    if (data.area_id) {
                        $('#editEvaluadoAreaId').val(data.area_id);
                    }
                    
                    // Seleccionar puesto automáticamente
                    if (data.puesto_id) {
                        $('#editEvaluadoPuestoId').val(data.puesto_id);
                    }
                    
                    // Seleccionar nivel jerárquico automáticamente
                    if (data.nivel_jerarquico_id) {
                        $('#editEvaluadoTipoDePuestoCampaniaId').val(data.nivel_jerarquico_id);
                        
                        // Buscar el dominio relacionado con el nivel jerárquico
                        const campaniaId = $('#editEvaluadoCampaniaId').val();
                        if (campaniaId && data.nivel_jerarquico_id) {
                            cargarDominioParaNivelJerarquico(campaniaId, data.nivel_jerarquico_id);
                        }
                    }
                    
                    // Establecer estado y cesado automáticamente
                    $('#editEvaluadoEstadoCheck').prop('checked', data.estado);
                    $('#editEvaluadoCesadoCheck').prop('checked', data.cesado);
                    $('#editEvaluadoHabilitadoParaEvaluacionDeCompetenciasCheck').prop('checked', data.habilitado_para_evaluacion_de_competencias);
                    $('#editEvaluadoHabilitadoParaEvaluacionPorObjetivosCheck').prop('checked', data.habilitado_para_evaluacion_por_objetivos);
                    
                    // Si tiene superior, cargarlo en el select2
                    if (data.superior) {
                        const superiorOption = new Option(data.superior.text, data.superior.id, true, true);
                        $('#editEvaluadoSuperiorPersonalId').empty().append(superiorOption).trigger('change');
                    } else {
                        $('#editEvaluadoSuperiorPersonalId').empty().append('<option value=""></option>');
                    }
                    
                    // Ocultar indicador de carga
                    Swal.close();
                },
                error: function(xhr) {
                    console.error("Error al obtener detalles del personal:", xhr);
                    Swal.fire('Error', 'No se pudieron obtener los datos del personal', 'error');
                }
            });
        });

        // Evento cuando cambia el nivel jerárquico
        $('#editEvaluadoTipoDePuestoCampaniaId').on('change', function() {
            const nivelJerarquicoId = $(this).val();
            const campaniaId = $('#editEvaluadoCampaniaId').val();
            
            if (nivelJerarquicoId && campaniaId) {
                cargarDominioParaNivelJerarquico(campaniaId, nivelJerarquicoId);
            }
        });
        
        // Funciones para formatear los resultados y la selección
        function formatPersonalResult(personal) {
            if (!personal.id) {
                return personal.text;
            }
            return $(`<div>${personal.text}</div>`);
        }

        function formatPersonalSelection(personal) {
            return personal.text || personal.id;
        }

        /**
         * Función para cargar el dominio relacionado con un nivel jerárquico y campaña
         */
        function cargarDominioParaNivelJerarquico(campaniaId, nivelJerarquicoId) {
            const $dominios = $('#editEvaluadoDominioId');
            
            // Filtrar los dominios ya cargados según nivel jerárquico
            // Si no hay dominios que coincidan, dejamos como está
            $.get(EVALUADOS_SELECTS_URL, {
                campania_id: campaniaId,
                nivel_jerarquico_id: nivelJerarquicoId
            })
            .done((response) => {
                if (response.dominios && response.dominios.length > 0) {
                    // Si hay dominios que coinciden, seleccionamos el primero
                    const dominioEncontrado = response.dominios.find(d => 
                        d.campania_id == campaniaId && d.nivel_jerarquico_id == nivelJerarquicoId);
                    
                    if (dominioEncontrado) {
                        $dominios.val(dominioEncontrado.id);
                    }
                }
            })
            .fail(function(xhr) {
                console.error("Error al cargar dominio relacionado:", xhr);
            });
        }

        $.get(EVALUADOS_SELECTS_URL)
            .done((data) => {
                // Áreas
                const $areas = $('#editEvaluadoAreaId');
                $areas.empty().append('<option value="">Seleccione...</option>');
                data.areas.forEach(function (item) {
                    $areas.append(new Option(item.name, item.id));
                });

                // Puestos
                const $puestos = $('#editEvaluadoPuestoId');
                $puestos.empty().append('<option value="">Seleccione...</option>');
                data.puestos.forEach(function (item) {
                    $puestos.append(new Option(item.name, item.id));
                });

                // Niveles jerárquicos
                const $niveles = $('#editEvaluadoTipoDePuestoCampaniaId');
                $niveles.empty().append('<option value="">Seleccione...</option>');
                data.nivel_jerarquicos.forEach(function (item) {
                    $niveles.append(new Option(item.name, item.id));
                });

                // Dominios
                const $dominios = $('#editEvaluadoDominioId');
                $dominios.empty().append('<option value="">Seleccione...</option>');
                data.dominios.forEach(function (item) {
                    $dominios.append(new Option(item.name, item.id));
                });                // Superiores (también usando select2)                
                
                const $superiores = $('#editEvaluadoSuperiorPersonalId');
                
                // Destruir instancia previa si existe para evitar duplicados
                if ($superiores.data('select2')) {
                    $superiores.select2('destroy');
                }
                
                // Limpiar el elemento antes de inicializar
                $superiores.empty().append('<option value=""></option>');
                
                $superiores.select2({
                    theme: 'bootstrap-5',
                    width: '100%',
                    placeholder: "Seleccione un superior",
                    dropdownParent: $('#editEvaluadoModal'),
                    ajax: {
                        url: PERSONAL_SEARCH_URL || '/personal/search-evaluado',
                        dataType: 'json',
                        delay: 250,
                        data: function (params) {
                            return {
                                search: params.term || '',
                                page: params.page || 1
                            };
                        },
                        processResults: function (data, params) {
                            params.page = params.page || 1;
                            
                            // Filtrar resultados para eliminar el personal actualmente seleccionado
                            if (data.results && data.results.length > 0) {
                                const personalId = $('#editEvaluadoPersonalId').val();
                                if (personalId) {
                                    data.results = data.results.filter(item => item.id != personalId);
                                }
                            }
                            
                            return {
                                results: data.results,
                                pagination: {
                                    more: data.pagination.more
                                }
                            };
                        },
                        cache: true
                    },
                    minimumInputLength: 2,
                    language: {
                        inputTooShort: function() {
                            return "Por favor ingrese 2 o más caracteres";
                        },
                        searching: function() {
                            return "Buscando...";
                        },
                        noResults: function() {
                            return "No se encontraron resultados";
                        }
                    }
                });
            })
            .fail(function (xhr) {
                console.error("Error al cargar datos para selects:", xhr);
            });
    }

    /**
     * Abre el modal para confirmar la baja de un evaluado
     * @param {number} id - ID del evaluado
     * @param {string} tipo - Tipo de evaluación ('competencias' o 'objetivos')
     */
    openBajaEvaluadoModal(id, tipo) {
        $('#bajaEvaluadoId').val(id);
        $('#bajaEvaluadoTipo').val(tipo);
        $('#bajaEvaluadoMotivo').val('');

        const tipoText = tipo === 'competencias' ? 'evaluación de competencias' : 'evaluación por objetivos';
        $('#bajaEvaluadoModalLabel').text(`Dar de baja de ${tipoText}`);

        $('#bajaEvaluadoModal').modal('show');
    }

    /**
     * Activa/Desactiva la evaluación de competencias
     * @param {number} id - ID del evaluado
     * @param {boolean} habilitar - true para habilitar, false para deshabilitar
     * @param {string} motivo - Motivo de la baja (solo para deshabilitar)
     */
    toggleCompetencias(id, habilitar, motivo = null) {
        showLoading('Procesando', habilitar ? 'Habilitando evaluación de competencias...' : 'Deshabilitando evaluación de competencias...');

        const data = {
            habilitar: habilitar
        };

        if (!habilitar && motivo) {
            data.motivo = motivo;
        }

        $.ajax({
            url: EVALUADOS_TOGGLE_COMPETENCIAS_URL.replace(':id', id),
            method: 'POST',
            data: data,
            success: (response) => {
                this.table.replaceData(EVALUADOS_BY_CAMPANIA_URL.replace(':id', this.campaniaIdGlobal));
                Swal.fire({
                    title: 'Éxito',
                    text: habilitar ? 'Evaluado habilitado para evaluación de competencias.' : 'Evaluado deshabilitado para evaluación de competencias.',
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false
                });
            },
            error: function (xhr) {
                let errorMsg = 'Error al actualizar el evaluado.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                }

                Swal.fire('Error', errorMsg, 'error');
            }
        });
    }

    /**
     * Activa/Desactiva la evaluación por objetivos
     * @param {number} id - ID del evaluado
     * @param {boolean} habilitar - true para habilitar, false para deshabilitar
     * @param {string} motivo - Motivo de la baja (solo para deshabilitar)
     */
    toggleObjetivos(id, habilitar, motivo = null) {
        showLoading('Procesando', habilitar ? 'Habilitando evaluación por objetivos...' : 'Deshabilitando evaluación por objetivos...');

        const data = {
            habilitar: habilitar
        };

        if (!habilitar && motivo) {
            data.motivo = motivo;
        }

        $.ajax({
            url: EVALUADOS_TOGGLE_OBJETIVOS_URL.replace(':id', id),
            method: 'POST',
            data: data,
            success: (response) => {
                this.table.replaceData(EVALUADOS_BY_CAMPANIA_URL.replace(':id', this.campaniaIdGlobal));
                Swal.fire({
                    title: 'Éxito',
                    text: habilitar ? 'Evaluado habilitado para evaluación por objetivos.' : 'Evaluado deshabilitado para evaluación por objetivos.',
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false
                });
            },
            error: function (xhr) {
                let errorMsg = 'Error al actualizar el evaluado.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                }

                Swal.fire('Error', errorMsg, 'error');
            }
        });
    }

    /**
     * Inicializa eventos para la importación de evaluados
     * @param {number} campaniaId - ID de la campaña
     */
    initImportEvaluadosEvents(campaniaId) {
        // Evento para abrir el modal de importación
        $('#importEvaluadosButton').off('click').on('click', function () {
            $('#importEvaluadosForm')[0].reset();
            $('#importEvaluadosCampaniaId').val(campaniaId);
            $('#validationEvaluadosSummary').hide();
            $('#previewEvaluadosContainer').hide();
            $('#validateEvaluadosButton').show();
            $('#importEvaluadosSubmitButton').hide();
            $('#importEvaluadosModal').modal('show');
        });

        // Validar el archivo de importación
        $('#validateEvaluadosButton').off('click').on('click', () => {
            const fileInput = document.getElementById('evaluadosExcelFile');
            if (!fileInput.files[0]) {
                Swal.fire('Error', 'Debe seleccionar un archivo Excel', 'error');
                return;
            }

            const campaniaId = $('#importEvaluadosCampaniaId').val();
            const formData = new FormData();
            formData.append('file', fileInput.files[0]);
            formData.append('campania_id', campaniaId);

            showLoading('Procesando', 'Validando datos de importación...');

            $.ajax({
                url: EVALUADOS_VALIDATE_IMPORT_URL,
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function (response) {
                    Swal.close();

                    // Mostrar resumen de validación
                    $('#validationEvaluadosContent').html(`
                        <div class="alert ${response.valid ? 'alert-success' : 'alert-warning'}">
                            <p><strong>Registros totales:</strong> ${response.total}</p>
                            <p><strong>Registros válidos:</strong> ${response.valid_count}</p>
                            <p><strong>Registros con errores:</strong> ${response.error_count}</p>
                        </div>
                        ${response.errors && response.errors.length > 0 ?
                            `<div class="alert alert-danger">
                                <h6>Errores encontrados:</h6>
                                <ul>
                                    ${response.errors.map(error => `<li>${error}</li>`).join('')}
                                </ul>
                            </div>` : ''
                        }
                    `);
                    $('#validationEvaluadosSummary').show();

                    // Mostrar previsualización de datos
                    if (response.preview && response.preview.length > 0) {
                        let previewHtml = '<div class="table-responsive"><table class="table table-sm table-bordered table-striped">';
                        previewHtml += '<thead><tr>';

                        // Headers de la tabla
                        const headers = Object.keys(response.preview[0]);
                        headers.forEach(header => {
                            previewHtml += `<th>${header}</th>`;
                        });

                        previewHtml += '</tr></thead><tbody>';

                        // Registros de la tabla (máximo 10)
                        const previewData = response.preview.slice(0, 10);
                        previewData.forEach(row => {
                            previewHtml += '<tr>';
                            headers.forEach(key => {
                                previewHtml += `<td>${row[key] !== null ? row[key] : ''}</td>`;
                            });
                            previewHtml += '</tr>';
                        });

                        previewHtml += '</tbody></table></div>';

                        // Si hay más de 10 registros, mostrar mensaje
                        if (response.preview.length > 10) {
                            previewHtml += `<p class="text-muted">Mostrando 10 de ${response.preview.length} registros</p>`;
                        }

                        $('#previewEvaluadosContent').html(previewHtml);
                        $('#previewEvaluadosContainer').show();
                    }

                    // Habilitar/deshabilitar botón de importación
                    if (response.valid) {
                        $('#validateEvaluadosButton').hide();
                        $('#importEvaluadosSubmitButton').show();
                    } else {
                        $('#validateEvaluadosButton').show();
                        $('#importEvaluadosSubmitButton').hide();
                    }
                },
                error: function (xhr) {
                    let errorMsg = 'Error al validar el archivo.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    }

                    Swal.fire('Error', errorMsg, 'error');
                }
            });
        });

        // Importar evaluados
        $('#importEvaluadosSubmitButton').off('click').on('click', () => {
            const fileInput = document.getElementById('evaluadosExcelFile');
            const campaniaId = $('#importEvaluadosCampaniaId').val();
            const formData = new FormData();
            formData.append('file', fileInput.files[0]);
            formData.append('campania_id', campaniaId);

            showLoading('Procesando', 'Importando evaluados...');

            $.ajax({
                url: EVALUADOS_IMPORT_URL,
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: (response) => {
                    $('#importEvaluadosModal').modal('hide');
                    this.table.replaceData(EVALUADOS_BY_CAMPANIA_URL.replace(':id', campaniaId));

                    Swal.fire({
                        title: 'Importación completada',
                        text: `Se han importado ${response.imported} evaluados correctamente.`,
                        icon: 'success'
                    });
                },
                error: function (xhr) {
                    let errorMsg = 'Error al importar evaluados.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    }

                    Swal.fire('Error', errorMsg, 'error');
                }
            });
        });
    }

    /**
     * Función auxiliar para llenar selects vía AJAX
     */
    llenarSelectAjax(options) {
        const defaults = {
            selector: null,
            url: null,
            dataPath: null,
            valueField: 'id',
            textField: 'name',
            selectedValue: null,
            emptyOption: false,
            emptyText: 'Seleccione...'
        };

        const settings = $.extend({}, defaults, options);

        if (!settings.selector || !settings.url) {
            console.error('llenarSelectAjax: Faltan parámetros obligatorios');
            return;
        }

        $.get(settings.url, function (data) {
            const $select = $(settings.selector);
            $select.empty();

            if (settings.emptyOption) {
                $select.append(new Option(settings.emptyText, ''));
            }

            let items = data;
            if (settings.dataPath) {
                const paths = settings.dataPath.split('.');
                paths.forEach(path => {
                    items = items[path];
                });
            }

            items.forEach(function (item) {
                $select.append(new Option(item[settings.textField], item[settings.valueField]));
            });

            if (settings.selectedValue !== null) {
                $select.val(settings.selectedValue);
            }
        }).fail(function (xhr) {
            console.error(`Error al cargar datos para ${settings.selector}:`, xhr);
        });
    }

    initGenerarEvaluadorHasEvaluadosEvents(campaniaId) {
        $('#generarEvaluadorHasEvaluado').off('click').on('click', () => {
            // const campaniaId = campaniaId;
            Swal.fire({
                title: '¿Está seguro?',
                text: 'Esto generará los registros de evaluador-evaluado para la campaña.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, generar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    showLoading('Procesando', 'Generando registros...');
                    $.post(GENERAR_EVALUADOR_HAS_EVALUADOS_URL.replace(':id', campaniaId), {}, function(response) {
                        Swal.fire('¡Listo!', response.message, 'success');
                        // Actualizar la tabla de evaluadores
                        if (window.evaluadorHasEvaluadoModel) {
                            window.evaluadorHasEvaluadoModel.table.replaceData();
                        }
                    }).fail(function(xhr) {
                        Swal.fire('Error', xhr.responseJSON?.message || 'Error al generar registros', 'error');
                    });
                }
            });
        });
    }

    

}

// Inicializa la clase cuando el documento esté listo
$(document).ready(function () {
    window.campaniaHasEvaluadosInstance = new CampaniaHasEvaluados();
});