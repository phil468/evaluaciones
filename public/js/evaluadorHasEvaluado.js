function initEvaluadorHasEvaluadoTable(campaniaId) {
    // Reemplaza los :campania_id y :id en las URLs
    function urlReplace(url, id = null) {
        let u = url.replace(':campania_id', campaniaId);
        if (id !== null) u = u.replace(':id', id);
        return u;
    }

    // helper: ¿es evaluación por competencias?
    function esCompetencias(rowData) {
        const tipo = rowData?.evaluacion?.tipo_de_evaluacion_id ?? rowData?.tipo_de_evaluacion_id;
        // Ajusta el ID si tu catálogo difiere (1 = competencias)
        return String(tipo) === '1';
    }

    // Render botón Editar: solo si realizado=false, cesado=false y esCompetencias
    function renderEditButton(rowData) {
        const bloqueado = rowData.realizado === true || rowData.realizado === 1
            || rowData.cesado === true || rowData.cesado === 1
            || !esCompetencias(rowData);
        const disabledAttr = bloqueado ? 'disabled title="No editable (realizado, cesado o tipo distinto a competencias)"' : '';
        return `<button class="btn btn-info btn-sm edit-button" data-id="${rowData.id}" ${disabledAttr}>
                    <i class="fas fa-edit"></i>
                </button>`;
    }

    const config = {
        modelName: 'EvaluadorHasEvaluado',
        formPrefix: 'editEvaluadorHasEvaluado',
        formSelector: '#editEvaluadorHasEvaluadoForm',
        modalSelector: '#editEvaluadorHasEvaluadoModal',
        tableSelector: '#evaluadores-has-evaluados-table',
        createButtonSelector: '#addEvaluadorHasEvaluadoButton',
        saveButtonSelector: '#saveEvaluadorHasEvaluadoChanges',
        ajaxURL: urlReplace(window.evaluadorHasEvaluadoUrls.ajaxURL),
        showURL: window.evaluadorHasEvaluadoUrls.showURL,
        storeURL: window.evaluadorHasEvaluadoUrls.storeURL,
        updateURL: window.evaluadorHasEvaluadoUrls.updateURL,
        // updateURL: window.evaluadorHasEvaluadoUrls.updateEvaluacionDeCompetenciasURL,
        deleteURL: window.evaluadorHasEvaluadoUrls.deleteURL,
        fields: [
            'evaluadorId','evaluadoId','campaniaId','gradoId','realizado','cesado','peso','pesoProrrateado','jerarquia','tipoJerarquia'
        ],
        columns: [
            { title: "ID", field: "id", width: 60, headerFilter: "input" },
            {
                title: "Acciones",
                formatter: function (cell) {
                    var data = cell.getRow().getData();
                    return `
                        <div class="btn-group" role="group">
                            ${renderEditButton(data)}
                            <button class="btn btn-danger btn-sm delete-button" data-id="${data.id}"><i class="fas fa-trash"></i></button>
                        </div>
                    `;
                },
                width: 140,
                // headerFilter: true
            },
            { title: "Evaluador", field: "evaluador.name", headerFilter: "input" },
            { title: "Evaluado", field: "evaluado.name", headerFilter: "input" },
            { title: "Evaluación", field: "evaluacion.nombre_para_mostrar", headerFilter: "input" },
            { title: "Campaña", field: "campania.name", headerFilter: "input" },
            { title: "Grado", field: "grado.name", headerFilter: "input" },
            { title: "Realizado", field: "realizado", formatter: "tickCross", 
                headerFilter: true,
                headerFilterEmptyCheck: (value) => value === false || value === '' || value == null,
            },
            { 
                //cesado debe mostrar "activo" (etiqueta verde) si es falso o "cesado" (etiqueta roja) si es verdadero. Y además debe permitir el filtro
                title: "Cesado",
                field: "cesado",
                formatter: function(cell) {
                    const value = cell.getValue();
                    return value ? '<span class="badge badge-danger">Cesado</span>' : '<span class="badge badge-success">Activo</span>';
                },
                headerFilter: true,
                headerFilterFunc: (headerValue, rowValue) => {
                    if (headerValue === "" || headerValue == null) return true; // sin filtro
                    const hv = headerValue === "1" || headerValue === 1 || headerValue === true;
                    const rv = rowValue === true || rowValue === 1 || rowValue === "1";
                    return rv === hv;
                },
            },
            { title: "Peso", field: "peso", hozAlign: "center", width: 80, headerFilter: true },
            { title: "Peso Prorrateado", field: "peso_prorrateado", hozAlign: "center", width: 120, headerFilter: true },
            { title: "Jerarquía", field: "jerarquia", hozAlign: "center", width: 100, headerFilter: true },
            { title: "Tipo de Jerarquía",
                formatter: function (cell) {
                    var id = cell.getRow().getData().tipo_jerarquia_id;
                    if (id === 1) return '2 OBJETIVOS';
                    if (id === 2) return '5 OBJETIVOS';
                    return '';
                },
                // headerFilter: true
            }
        ],
        beforeOpenModal: function(data) {
            // Si es nuevo (sin id), nunca bloquear
            const esNuevo = !data || !data.id;

            const bloqueado = !esNuevo && (
                data.realizado === true || data.realizado === 1
                || data.cesado === true || data.cesado === 1
                || !esCompetencias(data)
            );
            // const bloqueado = data.realizado === true || data.realizado === 1
            //     || data.cesado === true || data.cesado === 1
            //     || !esCompetencias(data);

            // select2: inicializa solo una vez
            if (!$('#editEvaluadorHasEvaluadoEvaluadorId').hasClass('select2-hidden-accessible')) {
                $('#editEvaluadorHasEvaluadoEvaluadorId').select2({
                    theme: 'bootstrap-5',
                    dropdownParent: $('#editEvaluadorHasEvaluadoModal'),
                    width: '100%',
                    ajax: {
                        url: window.evaluadorHasEvaluadoUrls.personalSearchURL,
                        dataType: 'json', delay: 250,
                        data: params => ({ q: params.term }),
                        processResults: d => ({ results: d.map(i => ({ id: i.id, text: i.name })) }),
                        cache: true
                    },
                    minimumInputLength: 2, placeholder: 'Buscar evaluador...',
                    allowClear: true
                });
            }

            if (!$('#editEvaluadorHasEvaluadoEvaluadoId').hasClass('select2-hidden-accessible')) {
                $('#editEvaluadorHasEvaluadoEvaluadoId').select2({
                    theme: 'bootstrap-5',
                    dropdownParent: $('#editEvaluadorHasEvaluadoModal'),
                    width: '100%',
                    ajax: {
                        url: window.evaluadorHasEvaluadoUrls.personalSearchURL,
                        dataType: 'json', delay: 250,
                        data: params => ({ q: params.term }),
                        processResults: d => ({ results: d.map(i => ({ id: i.id, text: i.name })) }),
                        cache: true
                    },
                    minimumInputLength: 2, placeholder: 'Buscar evaluado...',
                    allowClear: true
                });
            }

            // Inyectar opción seleccionada actual
            if (data.evaluador_id) {
                $('#editEvaluadorHasEvaluadoEvaluadorId').empty()
                    .append(new Option(data.evaluador?.name || `ID ${data.evaluador_id}`, data.evaluador_id, true, true))
                    .trigger('change');
            }

            if (data.evaluado_id) {
                $('#editEvaluadorHasEvaluadoEvaluadoId').empty()
                    .append(new Option(data.evaluado?.name || `ID ${data.evaluado_id}`, data.evaluado_id, true, true))
                    .trigger('change');
            }

            // Grados: llenar y luego seleccionar (evita carrera)
            $.get(urlReplace(window.evaluadorHasEvaluadoUrls.selectsURL))
                .done(function(resp){
                    const $grado = $('#editEvaluadorHasEvaluadoGradoId');
                    $grado.empty().append('<option value="">Seleccione...</option>');
                    if (resp.grados && Array.isArray(resp.grados)) {
                        resp.grados.forEach(g => $grado.append(new Option(g.name || g.nombre || g.label, g.id)));
                    }
                    $('#editEvaluadorHasEvaluadoGradoId').val(data.grado_id || '').trigger('change');
                });

            // Otros campos
            $('#editEvaluadorHasEvaluadoPeso').val(data.peso ?? '');
            $('#editEvaluadorHasEvaluadoPesoProrrateado').val(data.peso_prorrateado ?? '');
            $('#editEvaluadorHasEvaluadoCesado').prop('checked', !!data.cesado);

            // Bloqueo
            $('#ehe-locked-info').toggleClass('d-none', !bloqueado);
            $('#saveEvaluadorHasEvaluadoChanges').prop('disabled', bloqueado);
            $('#editEvaluadorHasEvaluadoForm :input').prop('disabled', bloqueado);
            $('.modal-footer .btn-secondary').prop('disabled', false);
        },

        beforeSave: function() {
            const peso = $('#editEvaluadorHasEvaluadoPeso').val();
            const pr = $('#editEvaluadorHasEvaluadoPesoProrrateado').val();

            if (peso !== '' && Number(peso) < 0) {
                Swal.fire('Validación', 'El peso no puede ser negativo.', 'warning');
                return false;
            }
            if (pr !== '' && Number(pr) < 0) {
                Swal.fire('Validación', 'El peso prorrateado no puede ser negativo.', 'warning');
                return false;
            }

            return {
                id: $('#editEvaluadorHasEvaluadoId').val(),
                evaluador_id: $('#editEvaluadorHasEvaluadoEvaluadorId').val(),
                evaluado_id: $('#editEvaluadorHasEvaluadoEvaluadoId').val(),
                // evaluacion_id: $('#editEvaluadorHasEvaluadoEvaluacionId').val(),
                grado_id: $('#editEvaluadorHasEvaluadoGradoId').val() || null,
                peso: peso === '' ? null : parseFloat(peso),
                peso_prorrateado: pr === '' ? null : parseFloat(pr),
                cesado: $('#editEvaluadorHasEvaluadoCesado').is(':checked') ? 1 : 0,
                // cargo_de_evaluador: $('#editCargoEvaluador').val(),
                // area_de_evaluador: $('#editAreaEvaluador').val(),
                // gerencia_sub_gerencia_de_evaluador: $('#editGerenciaEvaluador').val(),
                // cargo_de_evaluado: $('#editCargoEvaluado').val(),
                // area_de_evaluado: $('#editAreaEvaluado').val(),
                // gerencia_sub_gerencia_de_evaluado: $('#editGerenciaEvaluado').val(),
                // tipo_jerarquia_id: $('#editJerarquia').val() || null,
            };
        },

        afterSaved: function() {
            if (window.evaluadorHasEvaluadoModel && window.evaluadorHasEvaluadoModel.table) {
                window.evaluadorHasEvaluadoModel.table.replaceData();
            }
        }

    };

    // Destruye instancia anterior si existe
    if (window.evaluadorHasEvaluadoModel && window.evaluadorHasEvaluadoModel.table) {
        window.evaluadorHasEvaluadoModel.table.destroy();
    }
    window.evaluadorHasEvaluadoModel = new BaseModel(config);
    window.evaluadorHasEvaluadoModel.init();

    (function ensureWarningsContainer() {
        const $table = $(config.tableSelector);
        if (!$('#evaluadores-has-evaluados-warnings').length && $table.length) {
            $('<div id="evaluadores-has-evaluados-warnings" class="mt-2"></div>').insertAfter($table);
        }
    })();

    // Avisos: por cada evaluado, suma de peso_prorrateado de filas activas (no cesadas) y competencias debe ser 1
    function updatePesosWarnings() {
        const $warnings = $('#evaluadores-has-evaluados-warnings');
        if (!$warnings.length) return;

        $warnings.removeClass('alert alert-warning').empty();

        const rows = window.evaluadorHasEvaluadoModel.table.getData();
        const sums = new Map(); // evaluado_id -> { nombre, sum }

        rows.forEach(r => {
            const eid = r.evaluado_id || (r.evaluado && r.evaluado.id);
            const nombre = (r.evaluado && r.evaluado.name) || `ID ${eid}`;
            if (!eid) return;

            // Solo competencias y activos (no cesado)
            if (!esCompetencias(r)) return;
            const activo = !(r.cesado === 1 || r.cesado === true);
            if (!activo) return;

            const pr = parseFloat(r.peso_prorrateado ?? 0) || 0;
            if (!sums.has(eid)) sums.set(eid, { nombre, sum: 0 });
            sums.get(eid).sum += pr;
        });

        const problemas = [];
        const tol = 0.0001; // tolerancia por redondeo
        sums.forEach(v => {
            if (Math.abs(v.sum - 1) > tol) {
                problemas.push(`${v.nombre}: suma de pesos prorrateados activos = ${v.sum.toFixed(4)} (debe ser 1)`);
            }
        });

        if (problemas.length) {
            $warnings
                .addClass('alert alert-warning')
                .html(`<strong>Atención:</strong> Los siguientes evaluados no suman 1 en sus pesos prorrateados activos (competencias):<br>${problemas.join('<br>')}`);
        }
    }

    // Recalcular avisos en eventos relevantes
    const t = window.evaluadorHasEvaluadoModel.table;
    t.on('dataLoaded', updatePesosWarnings);
    t.on('dataProcessed', updatePesosWarnings);
    t.on('dataFiltered', updatePesosWarnings);
    t.on('cellEdited', updatePesosWarnings);

}