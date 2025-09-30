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
            this.initGenerarEvaluadorHasEvaluadosCompetenciasEvents(campaniaId);
            this.initGenerarEvaluadorHasEvaluadosObjetivosEvents(campaniaId);
        };

        this.init();
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
        $('#confirmarBajaEvaluado').off('click').on('click', () => {
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
                        buttons += '<button class="btn btn-danger btn-sm delete-button mr-1" data-id="' + id + '"><i class="fas fa-trash"></i></button>';

                        buttons += `<button class="btn btn-default btn-sm reset-respuestas-button" data-id="${id}" title="Reiniciar respuestas de evaluación">
                                        <i class="fas fa-undo"></i>
                                    </button>`;

                        buttons += '</div>';
                        return buttons;
                    },
                    headerSort: false,
                    width: 200
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
                    title: "Ev. Competencias",
                    field: "habilitado_para_evaluacion_de_competencias",
                    formatter: "tickCross",
                    headerSort: false,
                    width: 150
                },
                {
                    title: "Puntaje",
                    field: "puntaje_de_evaluacion_de_competencias",
                },
                {
                    title: "Ev. Objetivos",
                    field: "habilitado_para_evaluacion_por_objetivos",
                    formatter: "tickCross",
                    headerSort: false,
                    width: 150
                },

                // Columna de advertencias
                {
                    title: "Advertencias",
                    field: "advertencias",
                    formatter: function(cell) {
                        const data = cell.getRow().getData();
                        let warnings = [];

                        console.log("Evaluado data:", data);
                        
                        // Verificar área
                        if (!data.area_id) {
                            warnings.push('Falta área');
                        }
                        
                        // Verificar tipo de puesto y nivel jerárquico                                       
                        if (!data.tipo_puesto_has_nivel_jerarquico.tipo_de_puesto) {
                            warnings.push('Falta tipo de puesto');
                        }

                        if (!data.tipo_puesto_has_nivel_jerarquico.nivel_jerarquico) {
                            warnings.push('Falta nivel jerárquico');
                        }
                        
                        // Verificar habilitaciones
                        if (!data.habilitado_para_evaluacion_de_competencias && !data.habilitado_para_evaluacion_por_objetivos) {
                            warnings.push('No habilitado ni para eval. competencias ni para eval. por objetivos');
                        }

                        //verificar correo personal
                        if (!data.personal.correo_empresa) {
                            warnings.push('Falta correo personal');
                        }

                        //verificar correo de usuario
                        if (!data.personal.user.email) {
                            warnings.push('Falta correo de usuario');
                        }

                        // Si no hay advertencias
                        if (warnings.length === 0) {
                            return '<span class="text-success"><i class="fas fa-check-circle"></i> Todo correcto</span>';
                        }
                        
                        // Si hay advertencias, mostrarlas como lista
                        let html = '<div class="text-danger">';
                        warnings.forEach(warning => {
                            html += `<div><i class="fas fa-exclamation-triangle"></i> ${warning}</div>`;
                        });
                        html += '</div>';
                        
                        return html;
                    },
                    headerSort: true,
                    width: 220
                },

                // Columna correo empresarial
                {
                    title: "Correo Empresa",
                    field: "personal.correo_empresa",
                    formatter: function(cell) {
                        const data = cell.getRow().getData();
                        const personalId = data.personal_id;
                        const correoEmpresa = data.personal?.correo_empresa || '';
                        
                        let html = `<div class="d-flex align-items-center">`;
                        
                        // Si tiene correo, mostrarlo con botón de edición
                        if (correoEmpresa) {
                            html += `
                                <button class="btn btn-sm btn-link p-0 edit-correo-button" 
                                        data-id="${personalId}" 
                                        title="Editar correo empresarial">
                                    <i class="fas fa-edit text-primary"></i>
                                </button>
                                <span class="me-2">${correoEmpresa}</span>
                            `;
                        } else {
                            // Si no tiene correo, mostrar botón para agregarlo
                            html += `
                                <button class="btn btn-sm btn-link p-0 edit-correo-button" 
                                        data-id="${personalId}" 
                                        title="Agregar correo empresarial">
                                    <i class="fas fa-plus text-primary"></i>
                                </button>
                                <span class="text-muted me-2"></span>
                            `;
                        }
                        
                        html += `</div>`;
                        return html;
                    },
                    headerSort: true,
                    width: 200
                },

                // Columna email de usuario
                {
                    title: "Email de Usuario",
                    field: "personal.user.email",
                    formatter: function(cell) {
                        const data = cell.getRow().getData();
                        const personalId = data.personal_id;
                        const correoEmpresa = data.personal?.correo_empresa || '';
                        const userEmail = data.personal?.user?.email || '';
                        const hasUser = !!data.personal?.user;
                        
                        let html = `<div class="d-flex align-items-center">`;
                        
                        // Si tiene usuario, mostrar el email
                        // if (hasUser) {
                        if (userEmail) {
                            
                            // Si el correo empresa y el email de usuario son diferentes
                            if (correoEmpresa && correoEmpresa !== userEmail) {
                                html += `
                                    <button class="btn btn-sm btn-warning sync-email-button" 
                                            data-personal-id="${personalId}" 
                                            data-correo="${correoEmpresa}"
                                            title="Sincronizar email con correo empresarial">
                                        <i class="fas fa-sync-alt"></i>
                                    </button>
                                `;
                            }
                            html += `<span class="me-2">${userEmail}</span>`;
                        } else {
                            // Si tiene correo empresa pero no usuario, mostrar botón para crear usuario
                            if (correoEmpresa) {
                                html += `
                                    <button class="btn btn-sm btn-success create-user-button" 
                                            data-personal-id="${personalId}" 
                                            data-correo="${correoEmpresa}"
                                            title="Crear usuario con este correo">
                                        <i class="fas fa-user-plus"></i>
                                    </button>
                                `;
                            }

                            // No tiene usuario
                            html += `<span class="text-muted me-2">Sin usuario</span>`;                            
                        }
                        
                        html += `</div>`;
                        return html;
                    },
                    headerSort: false,
                    width: 200
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
                    // title: "Superior",
                    // field: "superior",
                    // headerFilter: "input",
                    // formatter: (cell) => {
                    //     const superior = cell.getValue();
                    //     return superior ? `${superior.name} (${superior.dni})` : '';
                    // },
                    // headerSort: false,
                    // width: 150
                    title: "Superior",
                    field: "superior",
                    headerFilter: "input",
                    formatter: (cell) => {
                        const s = cell.getValue();
                        return s ? `${s.name} (${s.dni})` : '';
                    },
                    accessorDownload: (value, rowData) => {
                        const s = rowData.superior;
                        return s ? `${s.name} - ${s.dni}` : '';
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
                    accessorDownload: (value, rowData) => {
                        const pares = rowData.pares_mismo_superior;
                        const nivelActual = rowData.tipo_puesto_has_nivel_jerarquico?.tipo_de_puesto?.id;
                        const idActual = rowData.personal_id;
                        if (!Array.isArray(pares) || pares.length === 0) return '';
                        const filtrados = pares.filter(p =>
                            p.personal_id !== idActual &&
                            p.tipo_puesto_has_nivel_jerarquico &&
                            p.tipo_puesto_has_nivel_jerarquico.tipo_de_puesto &&
                            p.tipo_puesto_has_nivel_jerarquico.tipo_de_puesto.id === nivelActual
                        );
                        // Saltos de línea para que Excel lo muestre (activar "Wrap Text" si quieres verlo ajustado)
                        return filtrados
                            .map(p => `${p.personal?.name || 'N/A'}-${p.personal?.dni || 'N/A'}`)
                            .join(',');
                    },
                    headerSort: false,
                    width: 220,
                    headerFilter: "input"
                },
                {
                    title: "Subordinados",
                    field: "subordinados",
                    formatter: (cell) => {
                        // console.log ("Subordinados cell:", cell);
                        const subordinados = cell.getValue();
                        if (!subordinados || subordinados.length === 0) return '';
                        return subordinados.map(s => 
                            `${s.personal?.name || 'N/A'} (${s.personal?.dni || 'N/A'})`
                        ).join('<br>');
                    },
                    accessorDownload: (value, rowData) => {
                        const subs = rowData.subordinados;
                        if (!Array.isArray(subs) || subs.length === 0) return '';
                        return subs
                            .map(s => `${s.personal?.name || 'N/A'}-${s.personal?.dni || 'N/A'}`)
                            .join(',');
                    }
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

        // Inicializar botones de exportación
        this.initExportButtons();
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

        $("#evaluados-table").on('click.evaluadosEvents', ".reset-respuestas-button", (e) => {
            e.preventDefault();
            const id = $(e.currentTarget).data("id");
            Swal.fire({
                title: '¿Reiniciar respuestas?',
                html: `<div class="text-left">
                    <p>Esto eliminará todas las respuestas de evaluación de este evaluado <b>excepto las de autoevaluación</b> y dejará las evaluaciones como no realizadas.</p>
                    <p class="text-danger">Esta acción no se puede deshacer.</p>
                </div>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, reiniciar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    showLoading('Procesando', 'Reiniciando respuestas...');
                    $.ajax({
                        url: EVALUADOS_RESET_RESPUESTAS_URL.replace(':id', id),
                        type: 'POST',
                        success: (response) => {
                            Swal.fire({
                                title: 'Respuestas reiniciadas',
                                text: response.message || 'Las respuestas han sido reiniciadas correctamente.',
                                icon: 'success'
                            });
                            // Actualizar la tabla
                            window.campaniaHasEvaluadosInstance.table.replaceData();
                        },
                        error: (xhr) => {
                            Swal.fire({
                                title: 'Error',
                                text: xhr.responseJSON?.message || 'Ha ocurrido un error al reiniciar las respuestas.',
                                icon: 'error'
                            });
                        }
                    });
                }
            });
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

        // Evento para editar correo empresarial
        $("#evaluados-table").on('click.evaluadosEvents', ".edit-correo-button", function(e) {
            e.stopPropagation();
            const personalId = $(this).data('id');
            
            Swal.fire({
                title: 'Editar correo empresarial',
                input: 'email',
                inputValue: $(this).closest('div').find('span').text().trim() || '',
                inputPlaceholder: 'correo@empresa.com',
                showCancelButton: true,
                confirmButtonText: 'Guardar',
                cancelButtonText: 'Cancelar',
                inputValidator: (value) => {
                    if (value && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) {
                        return 'Email inválido';
                    }
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    showLoading('Actualizando', 'Guardando correo empresarial...');
                    
                    $.ajax({
                        url: PERSONAL_UPDATE_URL.replace(':id', personalId),
                        method: 'PUT',
                        data: { 
                            correo_empresa: result.value,
                            actualizar_user: true
                        },
                        success: function(response) {
                            Swal.fire({
                                title: 'Éxito',
                                text: response.message || 'Correo actualizado correctamente',
                                icon: 'success',
                                timer: 2000,
                                showConfirmButton: false
                            });
                            // Actualizar la tabla
                            window.campaniaHasEvaluadosInstance.table.replaceData();
                        },
                        error: function(xhr) {
                            let errorMsg = 'Error al actualizar el correo.';
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMsg = xhr.responseJSON.message;
                            }
                            Swal.fire('Error', errorMsg, 'error');
                        }
                    });
                }
            });
        });

        // Evento para sincronizar email con correo empresarial
        $("#evaluados-table").on('click.evaluadosEvents', ".sync-email-button", function(e) {
            e.stopPropagation();
            const personalId = $(this).data('personal-id');
            const correo = $(this).data('correo');
            
            Swal.fire({
                title: '¿Sincronizar email?',
                text: `El email del usuario será actualizado a: ${correo}`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Sí, actualizar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    showLoading('Actualizando', 'Sincronizando email del usuario...');
                    
                    $.ajax({
                        url: EVALUADOS_SYNC_USER_EMAIL.replace(':id', personalId),
                        method: 'POST',
                        data: { email: correo },
                        success: function(response) {
                            Swal.fire({
                                title: 'Éxito',
                                text: response.message || 'Email sincronizado correctamente',
                                icon: 'success',
                                timer: 2000,
                                showConfirmButton: false
                            });
                            // Actualizar la tabla
                            window.campaniaHasEvaluadosInstance.table.replaceData();
                        },
                        error: function(xhr) {
                            let errorMsg = 'Error al sincronizar el email.';
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMsg = xhr.responseJSON.message;
                            }
                            Swal.fire('Error', errorMsg, 'error');
                        }
                    });
                }
            });
        });

        // Evento para crear usuario
        $("#evaluados-table").on('click.evaluadosEvents', ".create-user-button", function(e) {
            e.stopPropagation();
            const personalId = $(this).data('personal-id');
            const correo = $(this).data('correo');
            
            Swal.fire({
                title: '¿Crear usuario?',
                text: `Se creará un usuario con el email: ${correo}`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Sí, crear',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    showLoading('Creando', 'Creando usuario...');
                    
                    $.ajax({
                        url: EVALUADOS_CREATE_USER.replace(':id', personalId),
                        method: 'POST',
                        success: function(response) {
                            Swal.fire({
                                title: 'Éxito',
                                text: response.message || 'Usuario creado correctamente',
                                icon: 'success',
                                timer: 2000,
                                showConfirmButton: false
                            });
                            // Actualizar la tabla
                            window.campaniaHasEvaluadosInstance.table.replaceData();
                        },
                        error: function(xhr) {
                            let errorMsg = 'Error al crear el usuario.';
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMsg = xhr.responseJSON.message;
                            }
                            Swal.fire('Error', errorMsg, 'error');
                        }
                    });
                }
            });
        });

    }

    /**
     * Inicializa los botones de exportación para la tabla
     */
    initExportButtons() {
        
        // Exportar a Excel
        $('#exportExcel').off('click').on('click', () => {
            if (!this.table) return;
            
            const fileName = `evaluados_campania_${this.campaniaIdGlobal}_${new Date().toISOString().split('T')[0]}.xlsx`;
            this.table.download("xlsx", fileName, {sheetName:"Evaluados"});
            
            // Mostrar mensaje de éxito
            Swal.fire({
                title: 'Exportación completada',
                text: 'Los datos han sido exportados a Excel correctamente',
                icon: 'success',
                timer: 2000,
                showConfirmButton: false
            });
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
                
                // Actualizar la tabla
                // this.table.replaceData(EVALUADOS_BY_CAMPANIA_URL.replace(':id', this.campaniaIdGlobal));
                window.campaniaHasEvaluadosInstance.table.replaceData();
                Swal.fire({
                    title: 'Éxito',
                    text: habilitar ? 'Evaluado habilitado para evaluación de competencias.' : 'Evaluado deshabilitado para evaluación de competencias.',
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false
                });
                // window.campaniaHasEvaluadosInstance.table.replaceData();
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

        // Descarga de plantilla
        $('#downloadEvaluadosTemplate').off('click').on('click', () => {
            if (!window.campaniaHasEvaluadosInstance?.campaniaIdGlobal) {
                Swal.fire('Atención','Primero abra la configuración de una campaña.','info'); 
                return;
            }
            const id = window.campaniaHasEvaluadosInstance.campaniaIdGlobal;
            window.location.href = EVALUADOS_IMPORT_TEMPLATE_URL.replace(':id', id);
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
                        const previewData = response.preview;//.slice(0, 10);
                        previewData.forEach(row => {
                            previewHtml += '<tr>';
                            headers.forEach(key => {
                                previewHtml += `<td>${row[key] !== null ? row[key] : ''}</td>`;
                            });
                            previewHtml += '</tr>';
                        });

                        previewHtml += '</tbody></table></div>';

                        // Si hay más de 10 registros, mostrar mensaje
                        // if (response.preview.length > 10) {
                        previewHtml += `<p class="text-muted">Mostrando ${response.preview.length} registros</p>`;
                        // }

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
                    // if (response.valid) {
                    //     $('#validateEvaluadosButton').hide();
                    //     $('#importEvaluadosSubmitButton').show();
                    // } else {
                    //     $('#validateEvaluadosButton').show();
                    //     $('#importEvaluadosSubmitButton').hide();
                    // }
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
                url: EVALUADOS_IMPORT_URL.replace(':id', campaniaId),
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

    initGenerarEvaluadorHasEvaluadosCompetenciasEvents(campaniaId) {
        $('#generarEvaluadorHasEvaluadoCompetencias').off('click').on('click', () => {
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
                    $.post(GENERAR_EVALUADOR_HAS_EVALUADOS_URL
                        .replace(':id', campaniaId)
                        .replace(':tipo', 'competencias'), {}, function(response) {
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

    initGenerarEvaluadorHasEvaluadosObjetivosEvents(campaniaId) {
        $('#generarEvaluadorHasEvaluadoObjetivos').off('click').on('click', () => {
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
                    $.post(GENERAR_EVALUADOR_HAS_EVALUADOS_URL
                        .replace(':id', campaniaId)
                        .replace(':tipo', 'objetivos'), {}, function(response) {
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