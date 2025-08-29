function initEvaluadorHasEvaluadoTable(campaniaId) {
    // Reemplaza los :campania_id y :id en las URLs
    function urlReplace(url, id = null) {
        let u = url.replace(':campania_id', campaniaId);
        if (id !== null) u = u.replace(':id', id);
        return u;
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
        deleteURL: window.evaluadorHasEvaluadoUrls.deleteURL,
        columns: [
            { title: "ID", field: "id", width: 60, headerFilter: "input" },
            { title: "Evaluador", field: "evaluador.name", headerFilter: "input" },
            { title: "Evaluado", field: "evaluado.name", headerFilter: "input" },
            { title: "Evaluación", field: "evaluacion.nombre_para_mostrar", headerFilter: "input" },
            { title: "Campaña", field: "campania.name", headerFilter: "input" },
            { title: "Grado", field: "grado.name", headerFilter: "input" },
            { title: "Realizado", field: "realizado", formatter: "tickCross", 
                headerFilter: true,
                headerFilterEmptyCheck: (value) => value === false || value === '' || value == null,
                
                // headerFilter: "select",
                // headerFilterParams: {
                //     values: { "": "Todos", "1": "Sí", "0": "No" },
                // },
                // headerFilterFunc: (headerValue, rowValue) => {
                //     if (headerValue === "" || headerValue == null) return true; // sin filtro
                //     const hv = headerValue === "1" || headerValue === 1 || headerValue === true;
                //     const rv = rowValue === true || rowValue === 1 || rowValue === "1";
                //     return rv === hv;
                // },

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
            },
            {
                title: "Acciones",
                formatter: function (cell) {
                    var id = cell.getRow().getData().id;
                    return `
                        <div class="btn-group" role="group">
                            <button class="btn btn-info btn-sm edit-button" data-id="${id}"><i class="fas fa-edit"></i></button>
                            <button class="btn btn-danger btn-sm delete-button" data-id="${id}"><i class="fas fa-trash"></i></button>
                        </div>
                    `;
                },
                width: 120,
                // headerFilter: true
            }
        ],
        beforeOpenModal: function(data) {
            // Inicializa select2 con AJAX para personal
            $('#editEvaluadorId').select2({
                dropdownParent: $('#editEvaluadorHasEvaluadoModal'),
                width: '100%',
                ajax: {
                    url: window.evaluadorHasEvaluadoUrls.personalSearchURL,
                    dataType: 'json',
                    delay: 250,
                    data: params => ({ q: params.term }),
                    processResults: data => ({
                        results: data.map(item => ({ id: item.id, text: item.name }))
                    }),
                    cache: true
                },
                minimumInputLength: 2,
                placeholder: 'Buscar evaluador...'
            });

            $('#editEvaluadoId').select2({
                dropdownParent: $('#editEvaluadorHasEvaluadoModal'),
                width: '100%',
                ajax: {
                    url: window.evaluadorHasEvaluadoUrls.personalSearchURL,
                    dataType: 'json',
                    delay: 250,
                    data: params => ({ q: params.term }),
                    processResults: data => ({
                        results: data.map(item => ({ id: item.id, text: item.name }))
                    }),
                    cache: true
                },
                minimumInputLength: 2,
                placeholder: 'Buscar evaluado...'
            });

            // Si necesitas búsqueda remota para evaluaciones, hazlo igual
            // Si no, puedes llenarlo con AJAX normal como antes

            // Si editas, selecciona el valor actual:
            if (data && data.evaluador_id) {
                let option = new Option(data.evaluador?.name || '', data.evaluador_id, true, true);
                $('#editEvaluadorId').append(option).trigger('change');
            }
            if (data && data.evaluado_id) {
                let option = new Option(data.evaluado?.name || '', data.evaluado_id, true, true);
                $('#editEvaluadoId').append(option).trigger('change');
            }
        }

    };

    // Destruye instancia anterior si existe
    if (window.evaluadorHasEvaluadoModel && window.evaluadorHasEvaluadoModel.table) {
        window.evaluadorHasEvaluadoModel.table.destroy();
    }
    window.evaluadorHasEvaluadoModel = new BaseModel(config);
    window.evaluadorHasEvaluadoModel.init();
}