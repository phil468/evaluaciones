$(function() {
    // Inicializar Select2 con AJAX
    function initSelect2(selector, url, extraData = {}) {
        $(selector).select2({
            theme: 'bootstrap-5',
            width: "100%",
            ajax: {
                url: url,
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return { q: params.term, ...extraData };
                },
                processResults: function(data) {
                    return { results: data.results };
                }
            },
            placeholder: 'Seleccione...',
            allowClear: true,
            minimumInputLength: 2
        });
    }

    initSelect2('#personalEmpresaId', SELECT2_EMPRESA_URL);
    initSelect2('#personalGerenciaId', SELECT2_GERENCIA_URL);
    initSelect2('#personalAreaId', SELECT2_AREA_URL);
    initSelect2('#personalCargoId', SELECT2_CARGO_URL);
    initSelect2('#personalReportaA', SELECT2_REPORTA_URL);    

    // Reporta a: excluir a sí mismo si es edición
    function initReportaA(excludeId = null) {
        $('#personalReportaA').select2({
            theme: 'bootstrap-5',
            width: "100%",
            ajax: {
                url: SELECT2_REPORTA_URL,
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    let data = { q: params.term };
                    if (excludeId) data.exclude = excludeId;
                    return data;
                },
                processResults: function(data) {
                    return { results: data.results };
                }
            },
            placeholder: 'Seleccione...',
            allowClear: true,
            minimumInputLength: 2
        });
    }

    // Inicializar tabla y lógica CRUD con baseModel.js
    const personalModel = new BaseModel({
        tableSelector: "#personal-table",
        modalSelector: "#personalModal",
        formSelector: "#personalForm",
        formPrefix: "personal",
        modelName: "Personal",
        gender: 'male',
        createButtonSelector: "#createButton",
        saveButtonSelector: "#savePersonalChanges",
        ajaxURL: PERSONAL_URL,
        ajaxResponse: function(url, params, response) {
            // Tabulator espera un array, pero Laravel devuelve {data: [...]} si hay paginación
            return response.data.data || response;
        },
        storeURL: PERSONAL_CREATE_URL,
        updateURL: PERSONAL_UPDATE_URL,
        showURL: PERSONAL_SHOW_URL,
        deleteURL: PERSONAL_DELETE_URL,
        fields: [
            "id", "dni", "name", "nombres", "apellidoPaterno", "apellidoMaterno",
            "empresaId", "gerenciaId", "areaId", "cargoId", "reportaA",
            "correoEmpresa", "celularEmpresa", "correoPersonal", "telefonoPersonal",
            "celularPersonal", "estado", "genero", "fechaIngreso", "cesado", "fechaCese",
            "seleccionado"
        ],
        columns: [
            { title: "ID", field: "id", width: 80 },
            {
                title: "Acciones",
                field: "actions",
                formatter: function(cell) {
                    const id = cell.getRow().getData().id;
                    const seleccionado = cell.getRow().getData().seleccionado;
                    const dni = cell.getRow().getData().dni;
                    
                    let buttons = `
                        <button class="btn btn-sm btn-warning text-white edit-button" data-id="${id}">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-danger delete-button" data-id="${id}">
                            <i class="fas fa-trash"></i>
                        </button>
                        <button class="btn btn-sm btn-info btn-update-individual" data-dni="${dni}" title="Actualizar desde API">
                            <i class="fas fa-sync"></i>
                        </button>
                    `;
                    
                    // Agregar botón de exportar a campaña solo si está seleccionado
                    if (seleccionado) {
                        buttons += `
                            <button class="btn btn-sm btn-success export-to-campaign-button" data-id="${id}">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        `;
                    }
                    
                    return buttons;

                }
            },
            { title: "DNI", field: "dni", headerFilter: "input" },
            {
                title: "Seleccionado",
                field: "seleccionado",
                hozAlign: "center",
                width: 120,
                formatter: "tickCross",
                editor: true, // Permite edición directa
                headerFilter: "select",
                headerFilterParams: { values: {"": "Todos", "1": "Sí", "0": "No"} }
            },
            { title: "Nombre", field: "name", headerFilter: "input" },
            { title: "Empresa", field: "empresa.name", headerFilter: "input" },
            { title: "Tipo personal", field: "tipo_personal.name", headerFilter: "input" },
            { title: "Gerencia", field: "gerencia.name", headerFilter: "input" },
            { title: "Área", field: "area.name", headerFilter: "input" },
            { 
                title: "Cargo", 
                field: "cargo.name", 
                headerFilter: "input",
                editor: "list", // Usar editor tipo select
                editorParams: {
                    // Cargar opciones de cargos dinámicamente
                    values: function(cell) {
                        // Retornar promesa que resuelve a un objeto con valores para el select
                        return new Promise((resolve, reject) => {
                            $.ajax({
                                url: SELECT2_CARGO_URL, // Reutilizar la misma URL que usas para el select2
                                dataType: 'json',
                                data: { q: "" }, // Busqueda vacía para traer todos o los primeros N
                                success: function(data) {
                                    // Convertir el resultado a formato {value1: "label1", value2: "label2"}
                                    let values = {};
                                    data.results.forEach(item => {
                                        values[item.id] = item.text;
                                    });
                                    resolve(values);
                                },
                                error: function(error) {
                                    console.error("Error cargando cargos:", error);
                                    reject(error);
                                }
                            });
                        });
                    }
                }
            },
            // { title: "Reporta a", field: "superior.name", headerFilter: "input" },
            { 
                title: "Reporta a", 
                field: "superior.name", 
                headerFilter: "input",
                editor: "list",
                editorParams: {
                    // Cargar opciones de personal dinámicamente
                    values: function(cell) {
                        const currentPersonalId = cell.getRow().getData().id;
                        
                        return new Promise((resolve, reject) => {
                            $.ajax({
                                url: SELECT2_REPORTA_URL,
                                dataType: 'json',
                                data: { 
                                    q: "", 
                                    exclude: currentPersonalId, // Excluir el personal actual
                                    solo_activos: true // Solo personal activo
                                },
                                success: function(data) {
                                    // Convertir el resultado a formato {value1: "label1", value2: "label2"}
                                    let values = {};
                                    data.results.forEach(item => {
                                        values[item.id] = item.text;
                                    });
                                    resolve(values);
                                },
                                error: function(error) {
                                    console.error("Error cargando personal:", error);
                                    reject(error);
                                }
                            });
                        });
                    }
                }
            },
            { title: "Ingreso", field: "fecha_ingreso",
                formatter: function(cell) {
                    return cell.getValue() 
                    ? new Date(cell.getValue()).toLocaleDateString('es-PE', {
                        year: 'numeric',
                        month: '2-digit',
                        day: '2-digit'
                    })
                    : '';
                },
                // filtro por rangos de fechas
                headerFilter: "date",
                headerFilterFunc: function(headerValue, rowValue) {
                    if (!headerValue) return true; // Si no hay filtro, mostrar todo

                    // Extrae solo la parte de la fecha (YYYY-MM-DD)
                    const filterDate = headerValue.slice(0, 10);
                    const rowDate = rowValue ? rowValue.slice(0, 10) : '';
                    return filterDate === rowDate;                   
                }
            },
            {
                title: "Cese", 
                field: "cesado", 
                formatter: "tickCross",
            },
            { title: "Estado", field: "estado", formatter: "tickCross", headerFilter: "select", headerFilterParams: { values: {"": "Todos", "true": "Activo", "false": "Inactivo"} } },
            { title: "Fecha Cese", field: "fecha_cese",
                formatter: function(cell) {
                    return cell.getValue() 
                    ? new Date(cell.getValue()).toLocaleDateString('es-PE', {
                        year: 'numeric',
                        month: '2-digit',
                        day: '2-digit'
                    })
                    : '';
                }
            },
            { title: "Sexo", field: "sexo", 
                formatter: function(cell) {
                    const value = cell.getValue();
                    if (value === 'M') return '<span class="badge bg-info">Masculino</span>';
                    if (value === 'F') return '<span class="badge bg-danger">Femenino</span>';
                    return '<span class="badge bg-secondary">No especificado</span>';
                } 
            },
            { 
                title: "Correo Empresa", 
                field: "correo_empresa", 
                headerFilter: "input",
                formatter: function(cell) {
                    const value = cell.getValue() || '';
                    const row = cell.getRow();
                    const data = row.getData();
                    
                    // Si el personal no tiene user_id o user relacionado, solo mostrar el valor
                    if (!data.user || !data.user.email) {
                        return `<div class="d-flex align-items-center">
                                <span class="me-2">${value}</span>
                                <button class="btn btn-sm btn-link p-0 edit-correo-button" data-id="${data.id}" title="Editar correo">
                                    <i class="fas fa-edit text-primary"></i>
                                </button>
                                </div>`;
                    }
                    
                    const userEmail = data.user.email;
                    
                    // Comprobar si el correo_empresa es diferente al email del usuario
                    if (value.toLowerCase() !== userEmail.toLowerCase() && userEmail) {
                        return `<div class="d-flex align-items-center">
                                <span class="me-2">${value}</span>
                                <button class="btn btn-sm btn-link p-0 edit-correo-button" data-id="${data.id}" title="Editar correo">
                                    <i class="fas fa-edit text-primary"></i>
                                </button>
                                <button class="btn btn-sm btn-link p-0 ms-1" data-bs-toggle="tooltip" data-bs-placement="top"
                                        title="Diferente al email del usuario: ${userEmail}">
                                    <i class="fas fa-exclamation-triangle text-warning"></i>
                                </button>
                                <button class="btn btn-sm btn-link p-0 ms-1 update-correo-empresa"
                                        data-user-email="${userEmail}" data-personal-id="${data.id}"
                                        title="Actualizar correo empresarial">
                                    <i class="fas fa-sync-alt text-primary"></i>
                                </button>
                                </div>`;
                    }
                    
                    return `<div class="d-flex align-items-center">
                            <span class="me-2">${value}</span>
                            <button class="btn btn-sm btn-link p-0 edit-correo-button" data-id="${data.id}" title="Editar correo">
                                <i class="fas fa-edit text-primary"></i>
                            </button>
                            </div>`;
                }
            },
            { title: "Planilla", field: "planilla.name", headerFilter: "input" },
            { title: "Id Planilla Nisira", field: "planilla.idplanilla_nisira", headerFilter: "input" },
            { title:  "Tipo trabajador", field: "tipo_trabajador.name", headerFilter: "input" },
        ],
        beforeOpenModal: function(data) {
            // Si hay datos (modo edición)
            if (data && data.id) {
                // Usar la función existente para llenar todos los campos                
                // Inicializar reporta_a excluyendo el ID actual (para evitar ciclos)
                // initReportaA(data.id);
                fillPersonalForm(data);
            } else {
                // Modo creación - limpiar selects
                $('#personalEmpresaId').val(null).trigger('change');
                $('#personalGerenciaId').val(null).trigger('change');
                $('#personalAreaId').val(null).trigger('change');
                $('#personalCargoId').val(null).trigger('change');
                $('#personalReportaA').val(null).trigger('change');
                // initReportaA();
            }
        }
    });

    personalModel.init();
    
    // Detectar cuando un celda ha sido editada directamente en la tabla
    personalModel.table.on("cellEdited", function(cell) {
        // Implementar debounce para evitar múltiples solicitudes
        clearTimeout(window.updateCellTimeout);
    
        window.updateCellTimeout = setTimeout(() => {

            const row = cell.getRow();
            const data = row.getData();
            const id = data.id;
            const field = cell.getField();
            const value = cell.getValue();
            
            // Solo para el campo 'seleccionado'
            // if (field === 'seleccionado') {
            // Mostrar indicador de carga en la celda
            row.getElement().style.backgroundColor = "#f3f9ff";
        
            // Determinar el campo y preparar los datos de forma más eficiente
            const updateData = {};
            let needsFullRowUpdate = false;
            
            switch (field) {
                case 'seleccionado':
                    updateData.seleccionado = value ? 1 : 0;
                    break;
                case 'cargo.name':
                    updateData.cargo_id = value;
                    needsFullRowUpdate = true; // Este campo requiere actualización completa
                    break;
                case 'correo_empresa':
                    updateData.correo_empresa = value;
                    break;
                case 'superior.name':
                    updateData.reporta_a = value; // El value es el ID del superior seleccionado
                    updateData.actualizar_cargo = true; // Flag para indicar que también debe actualizar el cargo
                    needsFullRowUpdate = true; // Necesitamos actualizar toda la fila
                    break;
                default:
                    cell.restoreOldValue();
                    row.getElement().style.backgroundColor = "";
                    return;
            }

            // Enviar actualización al servidor con indicador visual mejorado
            const cellElement = cell.getElement();
            cellElement.classList.add('updating-cell');
            
            // Enviar actualización al servidor
            $.ajax({
                url: PERSONAL_UPDATE_URL.replace(':id', id),
                method: 'PUT',
                data: updateData,
                // headers: {
                //     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                // },
                success: function(response) {
                    // Éxito: restaurar color de fondo
                    row.getElement().style.backgroundColor = "";
                    cellElement.classList.remove('updating-cell');

                    // Optimización: solo actualizar lo necesario según el tipo de campo
                    if (field === 'seleccionado') {
                        data.seleccionado = Boolean(value);
                        const actionsCell = row.getCell("actions");
                        if (actionsCell) {
                            actionsCell.getElement().innerHTML = personalModel.table.columnManager.columnsByField.actions.definition.formatter(actionsCell, null, data);
                        }
                    } else if (needsFullRowUpdate) {
                        // Para campos que requieren actualización completa
                        row.getElement().classList.add('row-updating');
                        personalModel.table.updateRow(id, function() {
                            return $.ajax({
                                url: PERSONAL_SHOW_URL.replace(':id', id),
                                method: 'GET'
                            });
                        }).then(() => {
                            row.getElement().classList.remove('row-updating');
                        });
                    }

                    // Notificación discreta en lugar de un modal completo
                    // toastr.success('Campo actualizado correctamente');

                    Swal.fire({
                        icon: 'success',
                        title: 'Actualización exitosa',
                        text: 'Campo actualizado correctamente',
                        toast: true
                    });
                    
                    // actualizar la tabla
                    // este cambio de selccionado, tiene que actualizar la columna acciones también
                    

                },
                error: function(xhr) {
                    // Error: revertir el cambio
                    cell.restoreOldValue();
                    row.getElement().style.backgroundColor = "";
                    // Mostrar mensaje de error
                    Swal.fire({
                        icon: 'error',
                        title: 'Error al actualizar',
                        text: 'No se pudo actualizar el campo. Intente nuevamente.',
                        toast: true
                    });
                    // // toastr.error('Error al actualizar el campo');
                    // console.error('Error:', xhr);                    
                
                    // toastr.error('No se pudo actualizar el campo');
                    console.error('Error:', xhr);
                }
            });
        // }
        }, 300); // Debounce de 300ms para evitar múltiples solicitudes
    });

    // Botón para marcar seleccionados según criterios específicos
    $("#marcarSeleccionadosBtn").on("click", function() {
        Swal.fire({
            title: '¿Marcar personal seleccionado?',
            text: 'Esto marcará como seleccionado a todo el personal activo con planilla tipo E y fecha de ingreso válida según las fechas de corte',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, marcar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                // Mostrar indicador de carga
                Swal.fire({
                    title: 'Procesando...',
                    text: 'Esto puede tomar unos momentos.',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    didOpen: () => {
                        Swal.showLoading();
                        // Realizar la petición AJAX
                        $.ajax({
                            url: PERSONAL_MARCAR_SELECCIONADOS_URL,
                            type: 'POST',
                            // headers: {
                            //     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            // },
                            success: function(response) {
                                Swal.fire({
                                    title: 'Proceso completado',
                                    text: response.message,
                                    icon: 'success'
                                }).then(() => {
                                    // Recargar la tabla para mostrar los cambios
                                    personalModel.table.setData();
                                });
                            },
                            error: function(xhr) {
                                Swal.fire({
                                    title: 'Error',
                                    text: xhr.responseJSON?.message || 'Ha ocurrido un error al procesar la solicitud',
                                    icon: 'error'
                                });
                            }
                        });
                    }
                });
            }
        });
    });

    // Botón para exportar TODOS los registros marcados como seleccionados a la campaña actual
    $("#exportarTodosSeleccionadosCampania").on("click", function() {
        Swal.fire({
            title: '¿Exportar todos los seleccionados?',
            text: 'Se enviarán a la campaña actual TODOS los registros marcados como seleccionados en la base de datos',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, exportar todos',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                // Mostrar indicador de carga
                Swal.fire({
                    title: 'Procesando...',
                    text: 'Esto puede tomar unos momentos.',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    didOpen: () => {
                        Swal.showLoading();
                        
                        // Realizar la petición AJAX
                        $.ajax({
                            url: EXPORTAR_TODOS_SELECCIONADOS_URL,
                            type: 'POST',
                            // headers: {
                            //     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            // },
                            success: function(response) {
                                Swal.fire({
                                    title: 'Proceso completado',
                                    text: response.message,
                                    icon: 'success'
                                });
                            },
                            error: function(xhr) {
                                Swal.fire({
                                    title: 'Error',
                                    text: xhr.responseJSON?.message || 'Ha ocurrido un error al procesar la solicitud',
                                    icon: 'error'
                                });
                            }
                        });
                    }
                });
            }
        });
    });

    // Delegación de eventos para los botones de exportar individual (ya que se generan dinámicamente)
    $(document).on("click", ".export-to-campaign-button", function() {
        const id = $(this).data("id");
        
        Swal.fire({
            title: '¿Exportar este registro?',
            text: 'Se enviará a la campaña actual',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, exportar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                // Mostrar indicador de carga
                Swal.fire({
                    title: 'Procesando...',
                    text: 'Exportando registro...',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    didOpen: () => {
                        Swal.showLoading();
                        // Realizar la petición AJAX
                        $.ajax({
                            url: EXPORTAR_PERSONAL_URL,
                            // url: "{{ route('campanias.exportarPersonalACampaniaActual') }}",
                            type: 'POST',
                            data: {
                                ids: [id],
                                // _token: $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                Swal.fire({
                                    title: 'Proceso completado',
                                    text: response.message,
                                    icon: 'success'
                                });
                            },
                            error: function(xhr) {
                                Swal.fire({
                                    title: 'Error',
                                    text: xhr.responseJSON?.message || 'Ha ocurrido un error al procesar la solicitud',
                                    icon: 'error'
                                });
                            }
                        });
                    }
                });
            }
        });
    });

    // Botón de actualización general
    $(document).on("click", '#actualizacionGeneralBtn', function() {
        if (confirm('¿Estás seguro de realizar la actualización general del personal? Este proceso puede tardar varios minutos.')) {
            showLoading('Actualizando personal...');
            
            $.ajax({
                url: ACTUALIZACION_GENERAL_URL,
                type: 'POST',
                success: function(data) {
                    hideLoading();
                    if (data.success) {
                        showAlert('success', 'Actualización general completada exitosamente');
                        // Recargar la tabla
                        personalModel.table.setData();
                    } else {
                        showAlert('error', 'Error en la actualización general: ' + data.message);
                    }
                },
                error: function(xhr) {
                    hideLoading();
                    showAlert('error', 'Error en la actualización: ' + xhr.responseText);
                }
            });
        }
    });
    
    // Delegación de eventos para botones de actualización individual
    $(document).on("click", '.btn-update-individual', function() {
        const dni = $(this).data('dni');
        
        if (confirm(`¿Estás seguro de actualizar la información de la persona con DNI ${dni}?`)) {
            showLoading(`Actualizando datos del DNI ${dni}...`);
            
            $.ajax({
                url: ACTUALIZACION_INDIVIDUAL_URL.replace(':dni', dni),
                type: 'POST',
                success: function(data) {
                    hideLoading();
                    if (data.success) {
                        showAlert('success', data.message);
                        // Recargar la tabla
                        personalModel.table.setData();
                    } else {
                        showAlert('error', 'Error: ' + data.message);
                    }
                },
                error: function(xhr) {
                    hideLoading();
                    showAlert('error', 'Error en la actualización: ' + xhr.responseText);
                }
            });
        }
    });
    
    // Evento para buscar personal por DNI al perder el foco
    $('#personalDni').on('blur', function() {
        const dni = $(this).val().trim();
        
        if (dni.length === 8 && /^\d+$/.test(dni)) {
            showLoading('Buscando personal...');
            
            $.ajax({
                url: BUSCAR_POR_DNI_URL,
                type: 'POST',
                data: { dni: dni },
                success: function(data) {
                    hideLoading();
                    
                    if (data.success) {
                        // Llenar el formulario con los datos del personal
                        fillPersonalForm(data.personal);
                        
                        if (data.encontrado_en === 'api') {
                            showAlert('success', 'Personal encontrado en el sistema externo y cargado correctamente');
                        }
                    } else {
                        showAlert('warning', data.message);
                    }
                },
                error: function(xhr) {
                    hideLoading();
                    showAlert('error', 'Error al buscar el personal: ' + xhr.responseText);
                }
            });
        }
    });
    
    // Función para llenar el formulario con los datos del personal
    function fillPersonalForm(personal) {
        document.getElementById('personalId').value = personal.id;
        document.getElementById('personalName').value = personal.name;
        document.getElementById('personalNombres').value = personal.nombres || '';
        document.getElementById('personalApellidoPaterno').value = personal.apellido_paterno || '';
        document.getElementById('personalApellidoMaterno').value = personal.apellido_materno || '';
        document.getElementById('personalEstado').value = personal.estado ? '1' : '0';
        document.getElementById('personalGenero').value = personal.sexo || '';
        document.getElementById('personalCesado').value = personal.cesado ? '1' : '0';
        document.getElementById('personalSeleccionado').value = personal.seleccionado ? '1' : '0';
        
        // Fechas
        if (personal.fecha_ingreso) {
            document.getElementById('personalFechaIngreso').value = personal.fecha_ingreso.split(' ')[0];
        }
        if (personal.fecha_cese) {
            document.getElementById('personalFechaCese').value = personal.fecha_cese.split(' ')[0];
        }
        
        // Datos de contacto
        document.getElementById('personalCorreoEmpresa').value = personal.correo_empresa || '';
        document.getElementById('personalCelularEmpresa').value = personal.celular_empresa || '';
        document.getElementById('personalCorreoPersonal').value = personal.correo_personal || '';
        document.getElementById('personalTelefonoPersonal').value = personal.telefono_personal || '';
        document.getElementById('personalCelularPersonal').value = personal.celular_personal || '';
        
        // Select2 para relaciones
        if (personal.empresa_id) {
            setSelect2Value('personalEmpresaId', personal.empresa_id, personal.empresa ? personal.empresa.name : '');
        }
        if (personal.gerencia_id) {
            setSelect2Value('personalGerenciaId', personal.gerencia_id, personal.gerencia ? personal.gerencia.name : '');
        }
        if (personal.area_id) {
            setSelect2Value('personalAreaId', personal.area_id, personal.area ? personal.area.name : '');
        }
        if (personal.cargo_id) {
            setSelect2Value('personalCargoId', personal.cargo_id, personal.cargo ? personal.cargo.name : '');
        }
        if (personal.reporta_a) {
            setSelect2Value('personalReportaA', personal.reporta_a, personal.superior ? personal.superior.name : '');
        }
    }
    
    // Función para establecer valores en controles Select2
    function setSelect2Value(elementId, id, text) {
        const select = $(`#${elementId}`);
        
        // Crear una nueva opción y agregarla
        if (id && text) {
            const newOption = new Option(text, id, true, true);
            select.append(newOption).trigger('change');
        }
    }
    
    // Funciones auxiliares para mostrar/ocultar cargando y alertas
    function showLoading(message) {
        // Implementa tu lógica para mostrar un indicador de carga
        Swal.fire({
            title: message || 'Cargando...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
    }
    
    function hideLoading() {
        Swal.close();
    }
    
    function showAlert(type, message) {
        Swal.fire({
            icon: type,
            title: type === 'success' ? 'Éxito' : type === 'warning' ? 'Advertencia' : 'Error',
            text: message,
            timer: type === 'success' ? 3000 : undefined,
            timerProgressBar: type === 'success'
        });
    }

    // Delegación de eventos para el botón de actualizar correo_empresa
    $(document).on("click", ".update-correo-empresa", function(e) {
        e.stopPropagation(); // Evitar que se propague al editor de celda
        
        const userEmail = $(this).data('user-email');
        const personalId = $(this).data('personal-id');
        
        if (!userEmail || !personalId) {
            // toastr.error('Datos insuficientes para realizar la actualización');
            //swall en formato toast
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Datos insuficientes para realizar la actualización',
                toast: true,
            });
            return;
        }
        
        // Confirmar la actualización
        Swal.fire({
            title: '¿Actualizar correo empresarial?',
            text: `Se cambiará al email del usuario: ${userEmail}`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sí, actualizar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                // Mostrar indicador de carga
                const row = personalModel.table.getRow(personalId);
                row.getElement().style.backgroundColor = "#f3f9ff";
                
                // Realizar la actualización
                $.ajax({
                    url: PERSONAL_UPDATE_URL.replace(':id', personalId),
                    method: 'PUT',
                    data: {
                        correo_empresa: userEmail,
                        update_from_user: true
                    },
                    success: function(response) {
                        // Actualizar la fila completa para reflejar el cambio
                        row.getElement().classList.add('row-updating');
                        personalModel.table.updateRow(personalId, function() {
                            return $.ajax({
                                url: PERSONAL_SHOW_URL.replace(':id', personalId),
                                method: 'GET'
                            });
                        }).then(() => {
                            row.getElement().classList.remove('row-updating');
                            row.getElement().style.backgroundColor = "";
                            Swal.fire({
                                icon: 'success',
                                title: 'Actualizado',
                                text: 'Correo actualizado correctamente',
                                toast: true,
                                position: 'top-end',
                                timer: 3000,
                                showConfirmButton: false
                            });
                            
                            // toastr.success('Correo empresarial actualizado correctamente');
                        });
                    },
                    error: function(xhr) {
                        row.getElement().style.backgroundColor = "";

                        Swal.fire({
                            icon: 'error',
                            title: 'Error al actualizar',
                            text: 'No se pudo actualizar el correo empresarial. Intente nuevamente.',
                            toast: true,
                            position: 'top-end',                            
                        });

                        // toastr.error('Error al actualizar el correo empresarial');
                        console.error('Error:', xhr);
                    }
                });
            }
        });
    });

    
    // Inicializar tooltips para elementos dinámicos
    $('body').tooltip({
        selector: '[data-bs-toggle="tooltip"]'
    });

    // Delegación de eventos para el botón de editar correo
    $(document).on("click", ".edit-correo-button", function(e) {
        e.stopPropagation();
        const id = $(this).data('id');
        const row = personalModel.table.getRow(id);
        const currentValue = row.getData().correo_empresa || '';
        
        Swal.fire({
            title: 'Editar correo empresarial',
            input: 'email',
            inputValue: currentValue,
            inputPlaceholder: 'email@empresa.com',
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
                // Actualizar el valor en el servidor
                $.ajax({
                    url: PERSONAL_UPDATE_URL.replace(':id', id),
                    method: 'PUT',
                    data: { correo_empresa: result.value },
                    success: function(response) {
                        // Actualizar la fila en la tabla TTabulator 6.3
                        // encontar la fila que el campo id sea id
                        row.update(response.personal).then(() => {
                            // Mostrar notificación de éxito
                            Swal.fire({
                                icon: 'success',
                                title: 'Actualizado',
                                text: 'Correo actualizado correctamente',
                                toast: true,
                                position: 'top-end',
                                timer: 3000,
                            });
                        });

                        // Swal.fire({
                        //     icon: 'success',
                        //     title: 'Actualizado',
                        //     text: 'Correo actualizado correctamente',
                        //     toast: true,
                        //     position: 'top-end',
                        //     timer: 3000,
                        //     showConfirmButton: false
                        // });
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'No se pudo actualizar el correo',
                            toast: true
                        });
                    }
                });
            }
        });
    });

});