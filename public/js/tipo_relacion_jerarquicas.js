// public/js/tipo_relacion_jerarquicas.js
document.addEventListener('DOMContentLoaded', function() {
    // Configuración para el modelo Tipo de Relación Jerárquica
    const tipoRelacionModel = new BaseModel({
        tableSelector: "#example-table",
        modalSelector: "#editModal",
        formSelector: "#editForm",
        formPrefix: "edit",
        modelName: "Tipo de Relación Jerárquica",
        gender: 'male',
        createButtonSelector: "#createButton",
        saveButtonSelector: "#saveChanges",
        ajaxURL: TIPO_RELACION_DATA_URL,
        storeURL: TIPO_RELACION_STORE_URL,
        updateURL: TIPO_RELACION_UPDATE_URL,
        showURL: TIPO_RELACION_SHOW_URL,
        deleteURL: TIPO_RELACION_DELETE_URL,
        fields: [
            "id",
            "name",
            "estado"
        ],
        columns: [
            { title: "ID", field: "id", width: 80 },
            {
                title: "Acciones",
                field: "actions",
                formatter: function(cell) {
                    const id = cell.getRow().getData().id;
                    return `
                        <button class="btn btn-sm btn-warning text-white edit-button" data-id="${id}">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-danger delete-button" data-id="${id}">
                            <i class="fas fa-trash"></i>
                        </button>
                    `;
                },
                headerSort: false,
                hozAlign: "center",
                width: 120
            },
            { title: "Nombre", field: "name", formatter: "plaintext", headerFilter: "input" },
            { 
                title: "Estado", 
                field: "estado", 
                formatter: "tickCross", 
                headerFilter: "select",
                headerFilterParams: {
                    values: {"": "Todos", "true": "Activo", "false": "Inactivo"}
                },
                hozAlign: "center",
                width: 100
            }
        ]
    });

    // Inicializar el modelo
    tipoRelacionModel.init();

    // Evento para el botón de eliminar
    $("#example-table").on("click", ".delete-button", function() {
        const id = $(this).data("id");
        deleteItem("Tipo de Relación Jerárquica", id,
            TIPO_RELACION_DELETE_URL.replace(':id', id),
            function() {
                tipoRelacionModel.table.replaceData();
            },
            'male'
        );
    });
});