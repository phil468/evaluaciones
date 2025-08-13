// Modelo para gestión de Cargos
document.addEventListener('DOMContentLoaded', function() {
    // Configuración para el modelo Cargo
    const cargoModel = new BaseModel({
    
        tableSelector: "#example-table",
        modalSelector: "#editModal",
        formSelector: "#editForm",
        formPrefix: "edit",
        modelName: "Cargo",
        gender: 'male',
        createButtonSelector: "#createButton",
        saveButtonSelector: "#saveChanges",
        ajaxURL: CARGOS_DATA_URL,// "/cargos/data",
        storeURL: CARGOS_STORE_URL, //"/cargos",
        updateURL: CARGOS_UPDATE_URL, //"/cargos/:id",
        showURL: CARGOS_SHOW_URL, //"/cargos/:id",
        deleteURL: CARGOS_DELETE_URL,  //"/cargos/:id",
        fields: [
            "id",
            "name",
            "estado", 
            "tipoDePuestoId",
            "idCargoNisira"
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
                }
            },
            { title: "Nombre", field: "name", formatter: "plaintext", headerFilter: "input" },
            { 
                title: "Personas", 
                field: "personals_count", 
                hozAlign: "center",
                width: 110,
                formatter: function(cell) {
                    return cell.getValue() || 0;
                }
            },
            { title: "Tipo de Puesto", field: "tipo_de_puesto.name", formatter: function(cell) {
                return cell.getValue() || "";
            }, headerFilter: "input" },
            { title: "ID Cargo Nisira", field: "idcargo_nisira", headerFilter: "input" },
            { 
                title: "Estado", 
                field: "estado", 
                formatter: "tickCross", 
                headerFilter: "select",
                headerFilterParams: {
                    values: {"": "Todos", "true": "Activo", "false": "Inactivo"}
                }
            }
        ],
        beforeOpenModal: function(data) {
            // Cargar los tipos de puesto
            $.ajax({
                url: TIPO_PUESTO_LISTA_URL,//"/api/tipo_puesto/lista",
                type: 'GET',
                success: function(response) {
                    var select = $('#editTipoDePuestoId');
                    select.empty();
                    select.append('<option value="">-- Seleccione --</option>');
                    $.each(response, function(key, value) {
                        select.append('<option value="' + value.id + '">' + value.name + '</option>');
                    });
                    
                    // Si hay datos y tienen tipo_de_puesto_id, seleccionarlo
                    if (data && data.tipo_de_puesto_id) {
                        select.val(data.tipo_de_puesto_id);
                    }
                },
                error: function(xhr) {
                    console.error('Error al cargar tipos de puesto:', xhr);
                }
            });
        }
    });

    // Inicializar el modelo
    cargoModel.init();

    // Evento para el botón de crear nuevo
    // $("#createButton").on("click", function() {
    //     cargoModel.openModal();
    // });

    // Evento para el botón de editar
    // $("#example-table").on("click", ".edit-button", function() {
    //     const id = $(this).data("id");
    //     $.ajax({
    //         url: CARGOS_UPDATE_URL.replace(':id', id), //"/cargos/:id",
    //         type: 'GET',
    //         success: function(data) {
    //             cargoModel.openModal(data);
    //         }
    //     });
    // });

    // Evento para el botón de guardar cambios
    // $("#saveChanges").on("click", function() {
    //     cargoModel.save();
    // });

    // Evento para el botón de eliminar
    $("#example-table").on("click", ".delete-button", function() {
        const id = $(this).data("id");
        deleteItem("Cargo", id,
            CARGOS_DELETE_URL,
            function() {
            cargoModel.table.replaceData();
        });
    });

    $("#actualizarTiposBtn").on("click", function() {
        Swal.fire({
            title: '¿Actualizar tipos de puesto?',
            text: 'Esta acción intentará asignar un tipo de puesto a los cargos que no lo tengan, basándose en la similitud de nombres. ¿Desea continuar?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, actualizar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Procesando...',
                    text: 'Esto puede tomar unos momentos.',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Realizar la petición AJAX
                $.ajax({
                    url: CARGOS_ACTUALIZAR_TIPOS_URL,
                    type: 'POST',
                    // data: {
                    //     _token: $('meta[name="csrf-token"]').attr('content')
                    // },
                    success: function(response) {
                        Swal.fire({
                            title: '¡Completado!',
                            text: response.message,
                            icon: 'success'
                        }).then(() => {
                            // Recargar los datos de la tabla
                            cargoModel.table.replaceData();
                        });
                    },
                    error: function(xhr) {
                        let errorMessage = 'Ocurrió un error al procesar la solicitud.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        
                        Swal.fire({
                            title: 'Error',
                            text: errorMessage,
                            icon: 'error'
                        });
                    }
                });
            }
        });
    });

});
