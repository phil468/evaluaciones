// const { sortBy } = require("lodash");

window.initCompetenciasTable = initCompetenciasTable;
window.onConfigurarCampania = onConfigurarCampania;
// window.openCompetenciaModal = openCompetenciaModal;

// 1. Función auxiliar para campos anidados
function getNested(obj, path) {
    return path.split('.').reduce((o, p) => o ? o[p] : '', obj);
}

// 2. Función genérica para llenar selects
function llenarSelectAjax({selector, url, valueField = 'id', textField = 'nombre', selectedValue = null, extraOptions = []}) {
    const $select = $(selector);
    $select.empty();
    extraOptions.forEach(opt => {
        $select.append(new Option(opt.text, opt.value));
    });
    $.get(url, function(data) {
        data.forEach(function(item) {
            $select.append(new Option(getNested(item, textField), getNested(item, valueField)));
        });
        if (selectedValue) $select.val(selectedValue);
    });
}

function onConfigurarCampania(campaniaId) {
    // Llenar los selects del modal de competencias con el contexto de la campaña
    llenarSelectsCompetenciaModal(campaniaId);
}

// 3. Refactoriza tu función para llenar todos los selects del modal
function llenarSelectsCompetenciaModal(campaniaId, selected = {}) {
    llenarSelectAjax({
        selector: '#editCompetenciaCompetenciaId',
        url: COMPETENCIAS_URL,
        valueField: 'id',
        textField: 'name',
        selectedValue: selected.competencia_id
    });

    llenarSelectAjax({
        selector: '#editCompetenciaRelacionadoAnteriorId',
        url: CAMPANIAS_RELACIONADOS_ANTERIORES_URL.replace(':id', campaniaId),
        valueField: 'id',
        textField: 'competencia.name',
        selectedValue: selected.relacionado_anterior_id,
        extraOptions: [{text: 'Ninguno', value: ''}]
    });

    llenarSelectAjax({
        selector: '#editCompetenciaTipoCompetenciaId',
        url: TIPOS_COMPETENCIA_URL,
        valueField: 'id',
        textField: 'name',
        selectedValue: selected.tipo_competencia_id
    });

    llenarSelectAjax({
        selector: '#editCompetenciaTipoMedicionId',
        url: TIPOS_MEDICION_URL,
        valueField: 'id',
        textField: 'name',
        selectedValue: selected.tipo_medicion_id
    });
}

// function openCompetenciaModal(data) {
//     // Asigna los valores a los selects ya llenados
//     $('#editCompetenciaCompetenciaId').val(data.competencia_id);
//     $('#editCompetenciaRelacionadoAnteriorId').val(data.relacionado_anterior_id);
//     $('#editCompetenciaTipoCompetenciaId').val(data.tipo_competencia_id);
//     $('#editCompetenciaTipoMedicionId').val(data.tipo_medicion_id);
//     $('#editCompetenciaEstado').prop('checked', data.estado);
//     $('#editCompetenciaColor').val(data.color);
//     $('#editCompetenciaModal').modal('show');
// }

function initCompetenciasTable(campaniaId) {
    if (window.chcModel && window.chcModel.table) {
        window.chcModel.table.destroy();
    }

    window.chcModel = new BaseModel({
        modelName: 'Competencia',
        gender: 'female',
        tableSelector: "#competencias-table",
        formSelector: "#editCompetenciaForm",
        formPrefix: "editCompetencia", // Para campos como editCompetenciaId, editCompetenciaNombre
        modalSelector: "#editCompetenciaModal",
        createButtonSelector: "#createCompetenciaButton",
        saveButtonSelector: "#saveCompetenciaChanges",
        ajaxURL: COMPETENCIAS_BY_CAMPANIA_URL.replace(':id', campaniaId),
        storeURL: CHC_STORE_URL,
        updateURL: CHC_UPDATE_URL, // Debe tener ':id'
        showURL: CHC_SHOW_URL,     // Debe tener ':id'
        deleteURL: CHC_DELETE_URL, // Debe tener ':id'
        autoCampaniaId: true,
        fields: [
            'competenciaId', 'relacionadoAnteriorId', 'estado',
            'tipoCompetenciaId', 'tipoMedicionId', 'color'
        ],
        locale: true,
        columns: [
            { title: "ID", field: "id", width: 70 },
            {
                title: "Acciones",
                formatter: function(cell) {
                    var id = cell.getRow().getData().id;
                    return `<div class="btn-group" role="group">
                        <button class="mr-1 btn btn-info btn-sm edit-button" data-id="${id}"><i class="fas fa-edit"></i></button>
                        <button class="btn btn-danger btn-sm delete-button" data-id="${id}"><i class="fas fa-trash"></i></button>
                    </div>`;
                },
                width: 150
            },
            { title: "Competencia", field: "competencia.name" },
            { title: "Estado", field: "estado", formatter: "tickCross" },
            {
                title: "Relacionado Anterior",
                field: "relacionado_anterior",
                formatter: function(cell) {
                    var value = cell.getValue();
                    if (value === null || value === undefined) return "";
                    if (typeof value === 'object') return value.competencia.name + " - " + value.campania.name;
                    return value;
                },
                // headerFilter: "input",
                // headerFilterPlaceholder: "Buscar relacionado...",
                width: 200,
                hozAlign: "left",
                sorter: function(a, b) {
                    if (a && b) {
                        return a.competencia.name.localeCompare(b.competencia.name);
                    }
                    return 0;
                },
                filter: function(value, row) {
                    console.log(value, row);
                    if (value && typeof value === 'object') {
                        return value.competencia.name.toLowerCase().includes(row.getFilterValue().toLowerCase());
                    }
                    return false;
                },
                // sortBy: function(a, b) {
                //     if (a && b) {
                //         return a.competencia.name.localeCompare(b.competencia.name);
                //     }
                //     return 0;
                // },
            },
            { title: "Tipo de Competencia", field: "tipo_competencia.name" },
            { title: "Tipo de Medición", field: "tipo_medicion.name" },
            { title: "Color", field: "color", formatter: "color" }
        ],

    });

    window.chcModel.init();
}
