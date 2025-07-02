window.initPesosTable = initPesosTable;
window.pesosModel = null;

function initPesosTable(campaniaId) {
    if (window.pesosModel && window.pesosModel.table) {
        window.pesosModel.table.destroy();
    }

    // Cargar los datos para los selects
    $.ajax({
        url: PESOS_SELECTS_URL,
        type: 'GET',
        async: false, // Para asegurar que se carguen antes de inicializar el modelo
        success: function(response) {
            window.tiposRelacion = response.tipos_relacion;
            window.grados = response.grados;
        },
        error: function(xhr) {
            console.error('Error al cargar datos para selects:', xhr);
            Swal.fire('Error', 'No se pudieron cargar los datos necesarios', 'error');
        }
    });

    window.pesosModel = new BaseModel({
        modelName: 'Peso',
        gender: 'male',
        tableSelector: "#pesos-table",
        formSelector: "#editPesoForm",
        formPrefix: "editPeso", // Para campos como editPesoId, editPesoNombre
        modalSelector: "#editPesoModal",
        createButtonSelector: "#createPesoButton",
        saveButtonSelector: "#savePesoChanges",
        ajaxURL: CAMPANIAS_PESOS_URL.replace(':id', campaniaId),
        storeURL: PESOS_STORE_URL,
        updateURL: PESOS_UPDATE_URL, // Debe tener ':id'
        showURL: PESOS_SHOW_URL,     // Debe tener ':id'
        deleteURL: PESOS_DELETE_URL, // Debe tener ':id'
        autoCampaniaId: true,
        fields: [
            'tipoRelacionJerarquicaId', 
            'gradoId', 
            'peso'
        ],
        beforeOpenModal: function(data = {}) {
            // Limpiar y llenar los selects
            $('#editPesoTipoRelacionJerarquicaId').empty().append('<option value="">Seleccione...</option>');
            $('#editPesoGradoId').empty().append('<option value="">Seleccione...</option>');
            
            // Llenar el select de tipos de relación jerárquica
            if (window.tiposRelacion) {
                window.tiposRelacion.forEach(function(tipo) {
                    $('#editPesoTipoRelacionJerarquicaId').append(
                        `<option value="${tipo.id}">${tipo.name}</option>`
                    );
                });
            }
            
            // Llenar el select de grados
            if (window.grados) {
                window.grados.forEach(function(grado) {
                    $('#editPesoGradoId').append(
                        `<option value="${grado.id}">${grado.name}</option>`
                    );
                });
            }
            
            // Si estamos editando, establecer los valores
            if (data.id) {
                $('#editPesoTipoRelacionJerarquicaId').val(data.tipo_relacion_jerarquica_id);
                $('#editPesoGradoId').val(data.grado_id);
                $('#editPesoPeso').val(data.peso);
            } else {
                // Si estamos creando, establecer valores por defecto
                $('#editPesoPeso').val('0');
            }
            
            // Siempre establecer el ID de la campaña
            $('#editPesoCampaniaId').val(campaniaId);
        },
        validateForm: function() {
            const tipoRelacionId = $('#editPesoTipoRelacionJerarquicaId').val();
            const gradoId = $('#editPesoGradoId').val();
            const peso = $('#editPesoPeso').val();
            
            if (!tipoRelacionId) {
                Swal.fire('Error', 'Debe seleccionar un tipo de relación jerárquica', 'error');
                return false;
            }
            
            if (!gradoId) {
                Swal.fire('Error', 'Debe seleccionar un grado', 'error');
                return false;
            }
            
            if (!peso || peso < 0 || peso > 1) {
                Swal.fire('Error', 'El peso debe ser un valor entre 0 y 1', 'error');
                return false;
            }
            
            return true;
        },
        columns: [
            { title: "ID", field: "id", width: 60, sorter: "number" },
            {
                title: "Acciones",
                formatter: function(cell) {
                    const id = cell.getRow().getData().id;
                    return `
                        <div class="btn-group" role="group">
                            <button class="mr-1 btn btn-info btn-sm edit-button" data-id="${id}">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-danger btn-sm delete-button" data-id="${id}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    `;
                },
                headerSort: false,
                hozAlign: "center",
                width: 120
            },
            { 
                title: "Tipo de Relación", 
                field: "tipo_relacion_jerarquica.name",
                formatter: function(cell) {
                    const value = cell.getValue();
                    return value || '';
                },
                headerFilter: "input"
            },
            { 
                title: "Grado", 
                field: "grado.name",
                formatter: function(cell) {
                    const value = cell.getValue();
                    return value || '';
                },
                headerFilter: "input"
            },
            { 
                title: "Peso", 
                field: "peso",
                formatter: function(cell) {
                    return cell.getValue();
                }
            }
        ],
        // locale: true,
        // langs: {
        //     "es-es": {
        //         "pagination": {
        //             "first": "Primera",
        //             "first_title": "Primera Página",
        //             "last": "Última",
        //             "last_title": "Última Página",
        //             "prev": "Anterior",
        //             "prev_title": "Página Anterior",
        //             "next": "Siguiente",
        //             "next_title": "Página Siguiente",
        //             "page_size": "Tamaño de página",
        //             "all": "Todo"
        //         }
        //     }
        // }
    });

    window.pesosModel.init();
}