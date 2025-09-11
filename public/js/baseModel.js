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
        // if (typeof config.beforeOpenModal === 'function') {
        //     config.beforeOpenModal(data);
        // }
        // 1) Reset ANTES
        if (config.formSelector && $(config.formSelector).length) {
            $(config.formSelector)[0].reset();
        }
        
        // Limpia y llena el formulario
        $(config.formSelector)[0].reset();
    
        // Limpiar explícitamente el campo ID
        // $(`#${config.formPrefix}Id`).val('');
        // 2) Setear hidden ID si existe
        const idFieldSelector = `#${config.formPrefix}Id`;
        if ($(idFieldSelector).length) {
            $(idFieldSelector).val(data && data.id ? data.id : '');
        } else {
            console.warn(`El campo ID no existe en el formulario: ${idFieldSelector}`);
            // Aquí puedes manejar el caso en que el campo ID no exista
        }

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

        if (data) {
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
        } else {
            // Aquí puedes manejar el caso en que no hay datos
            // Por ejemplo, limpiar los campos del formulario
            console.warn('No se proporcionaron datos para llenar el formulario.');
        }

        // 4) Ejecutar hooks DESPUÉS del reset (para que no borre lo seteado por el hook)
        if (typeof config.beforeOpenModal === 'function') {
            config.beforeOpenModal(data || {});
        }
        // if (typeof config.afterOpenModal === 'function') {
        //     config.afterOpenModal(data || {});
        // }

        $(config.modalSelector).modal('show');
    };

    this.save = function() {
        const id = $(`#${config.formPrefix}Id`).val();

        // Permitir payload custom
        let formData = null;
        if (typeof config.beforeSave === 'function') {
            const payload = config.beforeSave();
            if (payload === false) return; // validación abortó
            formData = payload;
        } else if (config.fields && Array.isArray(config.fields)) {
            formData = {};
            config.fields.forEach(field => {
                const sn = field.replace(/([a-z])([A-Z])/g, '$1_$2').toLowerCase();
                const sel = `#${config.formPrefix}${field.charAt(0).toUpperCase() + field.slice(1)}`;
                formData[sn] = $(sel).val();
            });
        } else {
            formData = {};
        }

        if (config.autoCampaniaId) formData['campania_id'] = $('#configId').text();
        
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