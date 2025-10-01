$(function() {
    // Inicializar Select2 con AJAX
    function initSelect2(selector, url, extraData = {}, opts = {}) {
        $(selector).select2({
            theme: 'bootstrap-5',
            width: "100%",
            dropdownParent: $('#personalModal'),           // clave para modal
            placeholder: 'Seleccione...',
            allowClear: true,
            minimumInputLength: opts.minimumInputLength ?? 0,
            ajax: {
                url,
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return { q: params.term || '', ...extraData };
                },
                processResults: function (data) {
                    return { results: data.results };
                },
                cache: true
            },
            language: {
                inputTooShort: () => "Escriba para buscar...",
                noResults: () => "Sin resultados",
                searching: () => "Buscando..."
            },
            ...opts
        });
    }

    initSelect2('#personalEmpresaId', SELECT2_EMPRESA_URL);
    // initSelect2('#personalGerenciaId', SELECT2_GERENCIA_URL);
    initSelect2('#personalAreaId', SELECT2_AREA_URL);
    initSelect2('#personalCargoId', SELECT2_CARGO_URL);
    initSelect2('#personalReportaA', SELECT2_REPORTA_URL, { solo_activos: true });

    // Reporta a: excluir a sí mismo si es edición
    function initReportaA(excludeId = null) {
        $('#personalReportaA').empty(); // Limpiar las opciones existentes

        $('#personalReportaA').select2({
            theme: 'bootstrap-5',
            width: "100%",
            ajax: {
                url: SELECT2_REPORTA_URL,
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    let data = { 
                        q: params.term || '',  // Enviar cadena vacía si no hay término
                        solo_activos: true 
                    };
                    if (excludeId) data.exclude = excludeId;
                    return data;
                },
                processResults: function(data) {
                    return { results: data.results };
                },
                cache: true // Habilitar caché para mejor rendimiento
            },
            placeholder: 'Seleccione...',
            allowClear: true,
            minimumInputLength: 2,  // Cambiar a 0 para permitir ver opciones sin escribir
            language: {
                inputTooShort: function() {
                    return "Escriba para buscar...";
                },
                noResults: function() {
                    return "No se encontraron resultados";
                },
                searching: function() {
                    return "Buscando...";
                }
            }
        });
    
        // Cargar datos iniciales (primeros 20 registros)
        $.ajax({
            url: SELECT2_REPORTA_URL,
            dataType: 'json',
            data: { 
                q: '', 
                exclude: excludeId,
                solo_activos: true,
                limit: 20 // Solicitar primeros 20 registros
            },
            success: function(data) {
                // Agregar opciones iniciales
                if (data.results && data.results.length > 0) {
                    var initialOptions = '';
                    data.results.forEach(function(item) {
                        initialOptions += '<option value="' + item.id + '">' + item.text + '</option>';
                    });
                    $('#personalReportaA').append(initialOptions);
                }
            }
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

        // >>> AÑADIR: opciones Tabulator (dependiendo cómo BaseModel las mezcle; si usa p.e. 'tableOptions' cámbialo)
        tabulatorOptions: {
            layout:"fitColumns",
            selectable:true,
            selectableRangeMode:"click",
            // rowSelectionChanged:function(data){
            //     $('#exportSelectedExcelBtn').prop('disabled', data.length === 0);
            // },
            // (si necesitas height, persistence, etc.)
            height:"650px",
            // rowSelectionChanged: function(data){
            //     console.log("Filas seleccionadas:", data);
            //     $('#exportSelectedExcelBtn').prop('disabled', data.length === 0);
            //     console.log(data.length);
            //     $('#selectedCount').text(data.length);          // contador opcional
            //     console.log($('#selectedCount').text());
            // },
        },

        fields: [
            "id", "dni", "name", "nombres", "apellidoPaterno", "apellidoMaterno",
            "empresaId", "gerenciaId", "areaId", "cargoId", "reportaA",
            "correoEmpresa", "celularEmpresa", "correoPersonal", "telefonoPersonal",
            "celularPersonal", "estado", "genero", "fechaIngreso", "cesado", "fechaCese",
            "seleccionado"
        ],
        columns: [
            {
                title: "",
                field: "_select",
                width: 38,
                hozAlign: "center",
                headerHozAlign: "center",
                headerSort: false,
                formatter: "rowSelection",
                titleFormatter: "rowSelection",                 // checkbox en el header (seleccionar/deseleccionar visibles)
                titleFormatterParams: { rowRange: "active" },   // solo filas filtradas actuales
                cellClick: function(e, cell) {                  // togglear solo al click en la celda
                    cell.getRow().toggleSelect();
                    e.stopPropagation();
                }
                // no debe exportar esta columna
                ,download: false

            },
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
                headerFilterParams: { values: {"": "Todos", "1": "Sí", "0": "No"} },
                accessorDownload:(v)=> v ? 'Sí':'No'
            },
            { title: "Nombre", field: "name", headerFilter: "input" },
            { title: "Empresa", field: "empresa.name", headerFilter: "input" ,
              accessorDownload:(v,row)=> row.empresa?.name || '' 
            },
            { title: "Tipo personal", field: "tipo_personal.name", headerFilter: "input" ,
              accessorDownload:(v,row)=> row.tipo_personal?.name || '' },
            // { title: "Gerencia", field: "gerencia.name", headerFilter: "input" },
            { title: "Área", field: "area.name", headerFilter: "input" ,
              accessorDownload:(v,row)=> row.area?.name || '' },
            { 
                title: "Cargo", 
                field: "cargo.name", 
                headerFilter: "input",
                editor: "list", // Usar editor tipo select,
                accessorDownload:(v,row)=> row.cargo?.name || '' 
            },

            {
                title:"Reporta a",
                field:"superior", // usa el objeto
                formatter:(cell)=>{
                    const s = cell.getValue();
                    return s ? `${s.name} (${s.dni||''})` : '';
                },
                accessorDownload:(v,row)=>{
                    const s = row.superior;
                    return s ? `${s.name} - ${s.dni||''}` : '';
                },
                headerFilter:"input"
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
                accessorDownload:(v,row)=>{
                    if(!row.fecha_ingreso) return '';
                    const p = row.fecha_ingreso.substring(0,10).split('-');
                    return `${p[2]}/${p[1]}/${p[0]}`;
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
                accessorDownload:(v)=> v ? 'Sí':'No' 
            },
            { 
                title: "Estado", 
                field: "estado", 
                formatter: "tickCross", 
                headerFilter: "select", 
                headerFilterParams: { values: {"": "Todos", "true": "Activo", "false": "Inactivo"} },
                accessorDownload:(v)=> v ? 'Activo':'Inactivo' 
            },
            { 
                title: "Fecha Cese", field: "fecha_cese",
                formatter: function(cell) {
                    return cell.getValue() 
                    ? new Date(cell.getValue()).toLocaleDateString('es-PE', {
                        year: 'numeric',
                        month: '2-digit',
                        day: '2-digit'
                    })
                    : '';
                },
                accessorDownload:(v,row)=>{
                    if(!row.fecha_cese) return '';
                    const p = row.fecha_cese.substring(0,10).split('-');
                    return `${p[2]}/${p[1]}/${p[0]}`;
                }
            },
            { title: "Sexo", field: "sexo", 
                formatter: function(cell) {
                    const value = cell.getValue();
                    if (value === 'M') return '<span class="badge bg-info">Masculino</span>';
                    if (value === 'F') return '<span class="badge bg-danger">Femenino</span>';
                    return '<span class="badge bg-secondary">No especificado</span>';
                } ,
              accessorDownload:(v)=>{
                if(v==='M') return 'Masculino';
                if(v==='F') return 'Femenino';
                return 'No especificado';
              }
            },
            { 
                title: "Correo Empresa", 
                field: "correo_empresa", 
                headerFilter: "input",
                accessorDownload:(v)=> v || '' ,
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
            { title: "Planilla", field: "planilla.name", headerFilter: "input",
              accessorDownload:(v,row)=> row.planilla?.name || ''  },
            { title: "Id Planilla Nisira", field: "planilla.idplanilla_nisira", headerFilter: "input" ,
              accessorDownload:(v,row)=> row.planilla?.idplanilla_nisira || '' },
            { title:  "Tipo trabajador", field: "tipo_trabajador.name", headerFilter: "input" ,
              accessorDownload:(v,row)=> row.tipo_trabajador?.name || '' },
        ],

        beforeOpenModal: function(data) {

            // Limpiar selects
            $('#personalEmpresaId, #personalAreaId, #personalCargoId, #personalReportaA')
                .val(null).trigger('change');

            if (data && data.id) {
                setTimeout(()=> fillPersonalForm(data), 150);
            } else {
                $('#personalForm')[0].reset();
            }

        }
    });

    function loadAreaPath(areaId) {
        $('#personalAreaPath').text('');
        if (!areaId) return;
        $.get(AREA_PATH_URL.replace(':id', areaId))
            .done(res => {
                $('#personalAreaPath').text(res.path);
            })
            .fail(() => {
                $('#personalAreaPath').text('No se pudo obtener la ruta');
            });
    }

    // Evento al seleccionar / limpiar área
    $('#personalAreaId').on('select2:select', function(e){
        loadAreaPath(e.params.data.id);
    });

    $('#personalAreaId').on('select2:clear', function(){
        $('#personalAreaPath').text('');
    });

    personalModel.init();    

    
    // Quitar (o dejar) la definición previa en tabulatorOptions; esta es la que funcionará:
    const tablaPersonal = personalModel.table || Tabulator.findTable('#personal-table')?.[0];

    if(tablaPersonal){
        // Remueve posible listener previo para evitar duplicados
        tablaPersonal.off('rowSelectionChanged');

        tablaPersonal.on('rowSelectionChanged', function(data, rows){
            // data = array de objetos seleccionados
            $('#exportSelectedExcelBtn').prop('disabled', data.length === 0);
            $('#selectedCount').text(data.length);
            // Debug
            console.log('rowSelectionChanged disparado. Seleccionadas:', data.length);
        });
    } else {
        console.warn('No se encontró instancia Tabulator para #personal-table');
    }

    // Export visible (filtros + orden actuales)
    function exportVisibleToXLSX(){
        const t = Tabulator.findTable('#personal-table')?.[0];
        if(!t){ Swal.fire('Error','Tabla no lista','error'); return; }
        const filename = `personal_${luxon.DateTime.now().toFormat('yyyyLLdd_HHmmss')}.xlsx`;
        t.download('xlsx', filename, {sheetName:'Personal'});
    }

    // Export solo filas seleccionadas
    // function exportSelectedToXLSX(){
    //     const t = Tabulator.findTable('#personal-table')?.[0];
    //     if(!t){ Swal.fire('Error','Tabla no lista','error'); return; }
    //     const sel = t.getSelectedData();
    //     if(!sel.length){
    //         Swal.fire({icon:'warning', title:'Sin selección', text:'Seleccione filas.'});
    //         return;
    //     }
    //     const temp = new Tabulator(document.createElement('div'), {
    //         data: sel,
    //         columns: t.getColumns().filter(c=>c.isVisible()).map(c=>{
    //             const def = c.getDefinition();
    //             return {
    //                 title:def.title,
    //                 field:def.field,
    //                 accessorDownload:def.accessorDownload,
    //                 mutator:def.mutator
    //             };
    //         })
    //     });
    //     const filename = `personal_seleccion_${luxon.DateTime.now().toFormat('yyyyLLdd_HHmmss')}.xlsx`;
    //     temp.download('xlsx', filename, {sheetName:'Seleccion'});
    // }

    function exportSelectedToXLSX(){
        const t = Tabulator.findTable('#personal-table')?.[0];
        if(!t){
            Swal.fire({icon:'error',title:'Error',text:'Tabla no lista'});
            return;
        }
        const selCount = t.getSelectedData().length;
        if(!selCount){
            Swal.fire({icon:'warning',title:'Sin selección',text:'Seleccione filas.'});
            return;
        }
        const filename = `personal_seleccion_${luxon.DateTime.now().toFormat('yyyyLLdd_HHmmss')}.xlsx`;
        t.download('xlsx', filename, {
            sheetName:'Seleccion',
            rowRange:'selected' // <<< SOLO filas seleccionadas
        });
    }


    // Eventos export
    $('#exportExcelBtn').off('click').on('click', exportVisibleToXLSX);
    $('#exportSelectedExcelBtn').off('click').on('click', exportSelectedToXLSX);

    let importValidOK = false;

    function resetImportState(){
        importValidOK = false;
        $('#submitImportPersonalBtn').prop('disabled', true);
        $('#validateImportPersonalBtn').prop('disabled', false);
        $('#importPersonalResultado').html('');
    }

    // Al abrir modal limpiar estado
    $(document).on('show.bs.modal', '#importPersonalModal', function(){
        resetImportState();
        const form = document.getElementById('importPersonalForm');
        if(form){
            form.reset();
        }
    });

    // Cambiar archivo -> reset
    $(document).on('change', '#importPersonalForm input[name="archivo"]', function(){
        resetImportState();
    });

    // Modal Import: descargar plantilla
    $('#downloadTemplateBtn').on('click', ()=> {
        window.location = ROUTE_PERSONAL_TEMPLATE;
    });

    /* ---- VALIDAR (dry run) ---- */
    $('#validateImportPersonalBtn').on('click', function(){
        const form = document.getElementById('importPersonalForm');
        if(!form) return;

        const fileInput = form.querySelector('input[name="archivo"]');
        if(!fileInput || !fileInput.files.length){
            $('#importPersonalResultado').html('<span class="text-danger">Seleccione un archivo.</span>');
            return;
        }

        const fd = new FormData(form);
        $('#validateImportPersonalBtn').prop('disabled', true);
        $('#submitImportPersonalBtn').prop('disabled', true);
        $('#importPersonalResultado').html('<span class="text-info">Validando...</span>');

        $.ajax({
            url: ROUTE_PERSONAL_IMPORT_VALIDATE,
            type: 'POST',
            data: fd,
            processData: false,
            contentType: false,
            success: function(resp){
                importValidOK = resp.success;
                $('#validateImportPersonalBtn').prop('disabled', false);
                $('#submitImportPersonalBtn').prop('disabled', !importValidOK);

                let html = `<div class="text-success mb-1">
                    Validación OK. Se crearían ${resp.sim_insertados} y actualizarían ${resp.sim_actualizados} registros.
                </div>`;

                if(resp.areas_por_crear?.length){
                    html += `<div><strong>Áreas nuevas:</strong> ${resp.areas_por_crear.join(', ')}</div>`;
                }
                if(resp.cargos_por_crear?.length){
                    html += `<div><strong>Cargos nuevos:</strong> ${resp.cargos_por_crear.join(', ')}</div>`;
                }
                $('#importPersonalResultado').html(html);
            },
            error: function(xhr){
                $('#validateImportPersonalBtn').prop('disabled', false);
                let html = '';
                if(xhr.status === 422){
                    const j = xhr.responseJSON || {};
                    const errs = (j.errores||[]).map(e=>`<li>${e}</li>`).join('');
                    html = `<div class="text-warning">Validación con incidencias:
                            <ul class="mb-1">${errs}</ul>
                            <div>Se crearían ${j.sim_insertados||0} y actualizarían ${j.sim_actualizados||0} (no se importó).</div>
                            </div>`;
                } else {
                    html = '<span class="text-danger">Error en la validación.</span>';
                }
                $('#importPersonalResultado').html(html);
            }
        });
    });

    // Importar archivo
    $('#submitImportPersonalBtn').on('click', function(){
        if(!importValidOK){
            $('#importPersonalResultado').append('<div class="text-danger">Primero valide el archivo.</div>');
            return;
        }

        const form = document.getElementById('importPersonalForm');
        if(!form) return;
        const fd = new FormData(form);

        $('#submitImportPersonalBtn').prop('disabled', true);
        $('#importPersonalResultado').append('<div class="mt-1">Importando...</div>');

        $.ajax({
            url: ROUTE_PERSONAL_IMPORT,
            type: 'POST',
            data: fd,
            processData: false,
            contentType: false,
            success: function(resp){
                $('#submitImportPersonalBtn').prop('disabled', false);
                if(resp.success){
                    $('#importPersonalResultado').append(
                        `<div class="text-success mt-1">Importación exitosa. Insertados: ${resp.insertados}, Actualizados: ${resp.actualizados}</div>`
                    );
                    // refrescar tabla
                    const t = personalModel.table || Tabulator.findTable('#personal-table')?.[0];
                    t && t.replaceData && t.replaceData(); // Tabulator 6.x
                } else {
                    $('#importPersonalResultado').append(
                        `<div class="text-danger mt-1">${resp.message || 'Error en importación'}</div>`
                    );
                }
            },
            error: function(xhr){
                $('#submitImportPersonalBtn').prop('disabled', false);
                if(xhr.status === 422){
                    const j = xhr.responseJSON || {};
                    const errs = (j.errores||[]).map(e=>`<li>${e}</li>`).join('');
                    $('#importPersonalResultado').append(
                        `<div class="text-warning mt-1">Importación con incidencias:
                            <ul class="mb-1">${errs}</ul>
                            <div>Insertados: ${j.insertados||0}, Actualizados: ${j.actualizados||0}</div>
                        </div>`
                    );
                } else {
                    $('#importPersonalResultado').append('<div class="text-danger mt-1">Error inesperado al importar.</div>');
                }
            }
        });
    });

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
                    updateData.reporta_a = value === "" ? null : value; // El value es el ID del superior seleccionado
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
                data: { dni },
                success: function(data) {
                    hideLoading();
                    if (data.success) {
                        fillPersonalForm(data.personal);
                        if (data.encontrado_en === 'api') {
                            showAlert('success', 'Personal encontrado y cargado');
                        }
                    } else {
                        showAlert('warning', data.message);
                    }
                },
                error: function(xhr) {
                    hideLoading();
                    showAlert('error', 'Error al buscar: ' + xhr.responseText);
                }
            });
        }
    });
    
    // Función para llenar el formulario con los datos del personal
    function fillPersonalForm(personal) {
        $('#personalId').val(personal.id || '');
        $('#personalName').val(personal.name || '');
        $('#personalNombres').val(personal.nombres || '');
        $('#personalApellidoPaterno').val(personal.apellido_paterno || '');
        $('#personalApellidoMaterno').val(personal.apellido_materno || '');
        $('#personalEstado').val(personal.estado ? '1' : '0');
        $('#personalGenero').val(personal.sexo || '');
        $('#personalCesado').val(personal.cesado ? '1' : '0');
        $('#personalSeleccionado').val(personal.seleccionado ? '1' : '0');

        if (personal.fecha_ingreso) {
            $('#personalFechaIngreso').val(personal.fecha_ingreso.substring(0,10));
        } else {
            $('#personalFechaIngreso').val('');
        }
        if (personal.fecha_cese) {
            $('#personalFechaCese').val(personal.fecha_cese.substring(0,10));
        } else {
            $('#personalFechaCese').val('');
        }

        $('#personalCorreoEmpresa').val(personal.correo_empresa || '');

        if (personal.empresa_id) setSelect2Value('personalEmpresaId', personal.empresa_id, personal.empresa?.name || '');
        if (personal.area_id)    setSelect2Value('personalAreaId', personal.area_id, personal.area?.name || '');

        if (personal.area_id) {
            setSelect2Value('personalAreaId', personal.area_id, personal.area?.name || '');
            loadAreaPath(personal.area_id);
        } else {
            setSelect2Value('personalAreaId', null, null);
            $('#personalAreaPath').text('');
        }

        if (personal.cargo_id)   setSelect2Value('personalCargoId', personal.cargo_id, personal.cargo?.name || '');

        if (personal.reporta_a && personal.superior) {
            setSelect2Value('personalReportaA', personal.reporta_a, personal.superior.name);
        } else {
            setSelect2Value('personalReportaA', null, null);
        }
    }
    
    // Función para establecer valores en controles Select2
    function setSelect2Value(elementId, id, text) {
        const select = $(`#${elementId}`);
        
        select.empty();
        // Limpiar selecciones anteriores
        // select.val(null).trigger('change');
        
        // Esperar un momento para asegurar que Select2 esté completamente inicializado
        setTimeout(() => {
            
            if (id && text) {
                // Agrega la opción seleccionada manualmente
                const newOption = new Option(text, id, true, true);
                select.append(newOption);
                select.val(id).trigger('change');
            } else {
                // Si no hay valor, deja vacío
                select.val(null).trigger('change');
            }

            // if (id && text && select.find(`option[value="${id}"]`).length === 0) {
            //     const newOption = new Option(text, id, true, true);
            //     select.append(newOption);
            // }
            // select.val(id).trigger('change');
            // // Verificar si la opción ya existe
            // if (select.find(`option[value="${id}"]`).length === 0) {
            //     // Crear una nueva opción y agregarla
            //     if (id && text) {
            //         const newOption = new Option(text, id, true, true);
            //         select.append(newOption);
            //     }
            // }
            
            // // Establecer el valor
            // select.val(id).trigger('change');
            
            console.log(`Valor establecido para ${elementId}: ${id} - ${text}`);
        }, 200);
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