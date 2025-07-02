function BaseModel(config) {
    this.table = null;
    this.config = config;
    this.config.langs = config.langs || {
            "es-419": {
                "data": {
                    "loading": "Cargando",
                    "error": "Error",
                },
                "columns": {},
                "ajax": {
                    "loading": "Cargando",
                    "error": "Error"
                },
                "groups": {
                    "item": "item",
                    "items": "items"
                },
                "pagination": {
                    "page_size": "Tamaño de página",
                    "page_title": "Mostrar página",
                    "first": "Primera",
                    "first_title": "Primera página",
                    "last": "Última",
                    "last_title": "Última página",
                    "prev": "Anterior",
                    "prev_title": "Página anterior",
                    "next": "Siguiente",
                    "next_title": "Página siguiente",
                    "all": "Todo"
                },
                "headerFilters": {
                    "default": "Filtrar columna...",
                    "columns": {}
                }
            }
        };

    this.init = function() {
        this.initTable();
        this.initEvents();
    };

    this.initTable = function() {
        this.table = new Tabulator(config.tableSelector, {
            ajaxURL: config.ajaxURL,
            layout: "fitDataFill",
            columns: config.columns,
            locale: true,
            langs: config.langs || {},            
            pagination: config.pagination || "local",
            paginationSize: config.paginationSize || 10,
            paginationSizeSelector: config.paginationSizeSelector || [10, 25, 50, 100],
        });
    };

    this.openModal = function(data = {}) {
        // Ejecuta el hook antes de abrir el modal, si existe
        if (typeof config.beforeOpenModal === 'function') {
            config.beforeOpenModal(data);
        }
        
        // Limpia y llena el formulario
        $(config.formSelector)[0].reset();
    
        // Limpiar explícitamente el campo ID
        $(`#${config.formPrefix}Id`).val('');

        // Lista de campos de fecha según tu modelo Laravel
        const dateFields = [
            'date',
            'fecha_inicio',
            'fecha_fin',
            'fecha_corte',
            'fecha_inicio_segunda_fase',
            'fecha_fin_segunda_fase',
            'fecha_inicio_primera_fase_matricula',
            'fecha_fin_primera_fase_matricula',
            'fecha_para_mostrar_resultados'
        ];

        function formatDateTimeLocal(dateString) {
            if (!dateString) return '';
            const d = new Date(dateString);
            const pad = n => n < 10 ? '0' + n : n;
            return d.getFullYear() + '-' +
                pad(d.getMonth() + 1) + '-' +
                pad(d.getDate()) + 'T' +
                pad(d.getHours()) + ':' +
                pad(d.getMinutes());
        }

        function formatDate(dateString) {
            if (!dateString) return '';
            const d = new Date(dateString);
            const pad = n => n < 10 ? '0' + n : n;
            return d.getFullYear() + '-' +
                pad(d.getMonth() + 1) + '-' +
                pad(d.getDate());
        }

        for (const key in data) {
            var fieldCamelCase = key.replace(/_([a-z])/g, (match, letter) => letter.toUpperCase());
            var selector = `#${config.formPrefix}${fieldCamelCase.charAt(0).toUpperCase() + fieldCamelCase.slice(1)}`;

            // Si es campo de fecha
            if (dateFields.includes(key)) {
                // Si el input es tipo date
                if ($(selector).attr('type') === 'date') {
                    $(selector).val(formatDate(data[key]));
                } else if ($(selector).attr('type') === 'datetime-local') {
                    $(selector).val(formatDateTimeLocal(data[key]));
                } else {
                    $(selector).val(data[key]);
                }
            } else {
                $(selector).val(data[key]);
            }
        }
        $(config.modalSelector).modal('show');
    };

    this.save = function() {
        const id = $(`#${config.formPrefix}Id`).val();
        let formData = {};
        config.fields.forEach(field => {
            // Convierte el campo a camelCase y obtiene su valor
            var field_snake_case = field.replace(/([a-z])([A-Z])/g, '$1_$2').toLowerCase(); // Convierte a snake_case
            // field = field.replace(/_/g, ''); // Elimina guiones bajos
            // Asigna el valor del campo al objeto formData
            // Asegúrate de que el campo exista en el formulario

            formData[field_snake_case] = $(`#${config.formPrefix}${field.charAt(0).toUpperCase() + field.slice(1)}`).val();
        });

        // Si la config indica que debe agregar campania_id automáticamente
        if (config.autoCampaniaId) {
            formData['campania_id'] = $('#configId').text();
        }
        
        const url = id ? config.updateURL.replace(':id', id) : config.storeURL;
        const method = id ? 'PUT' : 'POST';

        saveOrUpdateItem({
            modelName: config.modelName,
            url: url,
            method: method,
            data: formData,
            modalSelector: config.modalSelector,
            successCallback: () => this.table.replaceData(),
            errorCallback: function(errorMessage) {
                console.error(`Error al guardar ${config.modelName}:`, errorMessage);
            },
            loadingText: id ? 'Actualizando...' : 'Guardando...',
            successText: id ? `${config.modelName} actualizada correctamente.` : `${config.modelName} creada correctamente.`
        });
    };

    this.delete = function(id) {
        deleteItem(
            config.modelName,
            id,
            config.deleteURL,
            () => this.table.replaceData(),
            function(errorMessage) {
                console.error(`Error al eliminar ${config.modelName}:`, errorMessage);
            },
            config.gender || 'male'
        );
    };    this.initEvents = function() {
        // Crear nuevo
        $(config.createButtonSelector).off("click").on("click", () => {
            // Mostrar mensaje de carga
            showLoading('Preparando formulario', 'Cargando formulario para crear...');
            this.openModal();
            // Cerrar mensaje cuando el modal esté completamente mostrado
            $(config.modalSelector).one('shown.bs.modal', function() {
                Swal.close();
            });
        });

        // Editar
        $(config.tableSelector).off("click", ".edit-button").on("click", ".edit-button", (e) => {
            const id = $(e.currentTarget).data("id");
            // Mostrar mensaje de carga
            showLoading('Cargando datos', 'Obteniendo información...');
            
            $.get(config.showURL.replace(':id', id), (data) => {
                this.openModal(data);
                // Cerrar mensaje cuando el modal esté completamente mostrado
                $(config.modalSelector).one('shown.bs.modal', function() {
                    Swal.close();
                });
            }).fail(function() {
                Swal.fire('Error', 'No se pudieron cargar los datos', 'error');
            });
        });

        // Guardar
        $(config.saveButtonSelector).off("click").on("click", () => this.save());

        // Eliminar
        $(config.tableSelector).off("click", ".delete-button").on("click", ".delete-button", (e) => {
            const id = $(e.currentTarget).data("id");
            this.delete(id);
        });
    };
}

// Exponer globalmente
window.BaseModel = BaseModel;