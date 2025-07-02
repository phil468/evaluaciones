// Variables para la importación

let currentCampaniaId = null;
let importData = null;
let previewTable = null;

function llenarSelectsPreguntaModal(campaniaId, selected = {}) {
    // Competencias
    $.get(PREGUNTAS_COMPETENCIAS_URL.replace(':id', campaniaId), function(competencias) {
        const $select = $('#editPreguntaCampaniaHasCompetenciaId');
        $select.empty();
        competencias.forEach(function(comp) {
            $select.append(new Option(comp.competencia.name, comp.id));
        });
        if (selected.competencia_id) $select.val(selected.competencia_id);
    });

    // Dominios
    $.get(PREGUNTAS_DOMINIOS_URL.replace(':id', campaniaId), function(dominios) {
        const $select = $('#editPreguntaDominioId');
        $select.empty();
        dominios.forEach(function(dom) {
            $select.append(new Option(dom.name, dom.id));
        });
        if (selected.dominio_id) $select.val(selected.dominio_id);
    });
}

function initPreguntasTable(campaniaId) {
    if (window.pregModel && window.pregModel.table) {
        window.pregModel.table.destroy();
    }

    window.pregModel = new BaseModel({
        modelName: 'Pregunta',
        gender: 'female',
        tableSelector: "#preguntas-table",
        formSelector: "#editPreguntaForm",
        formPrefix: "editPregunta", // Para campos como editPreguntaId, editPreguntaTexto, etc.
        modalSelector: "#editPreguntaModal",
        createButtonSelector: "#createPreguntaButton",
        saveButtonSelector: "#savePreguntaChanges",
        ajaxURL: PREGUNTAS_BY_CAMPANIA_URL.replace(':id', campaniaId),
        storeURL: PREGUNTAS_STORE_URL,
        updateURL: PREGUNTAS_UPDATE_URL, // Debe tener ':id'
        showURL: PREGUNTAS_SHOW_URL,     // Debe tener ':id'
        deleteURL: PREGUNTAS_DELETE_URL, // Debe tener ':id'
        autoCampaniaId: true,
        fields: [
            'pregunta', 'campaniaHasCompetenciaId', 'dominioId', 'numeroOrden'
        ],
        locale: true,
        columns: [
            { title: "ID", field: "id", width: 70 },
            {
                title: "Acciones",
                formatter: function(cell) {
                    // Aquí puedes poner los botones de editar/eliminar
                    return `
                        <button class="btn btn-sm btn-info edit-button" data-id="${cell.getRow().getData().id}"><i class="fas fa-edit"></i></button>
                        <button class="btn btn-sm btn-danger delete-button" data-id="${cell.getRow().getData().id}"><i class="fas fa-trash"></i></button>
                    `;
                },
                width: 150
            },
            { title: "Pregunta", field: "pregunta" },
            { title: "Competencia", field: "competencia" },
            { title: "Dominio", field: "dominio" },
            { title: "Orden", field: "numero_orden" },
        ],
    });
    
    llenarSelectsPreguntaModal(campaniaId); // Llenar selects al inicializar
    mostrarResumenOrdenPorDominio(campaniaId); // Mostrar resumen de orden por dominio
    
    // Guardar el método save original
    const originalSave = window.pregModel.save;
    
    // Sobrescribir el método save
    window.pregModel.save = function() {
        // Llamar al método original
        originalSave.call(window.pregModel);
        
        // Actualizar el resumen después de un breve retraso
        setTimeout(() => {
            mostrarResumenOrdenPorDominio(campaniaId);
        }, 500);
    };
    
    // Inicializar el modelo de preguntas
    window.pregModel.init();

}

// luego de mostrar la tabla de preguntas, mostar el resumen de orden por dominio
function mostrarResumenOrdenPorDominio(campaniaId) {
    $.ajax({
        url: PREGUNTAS_ESTADO_BY_CAMPANIA_URL.replace(':id', campaniaId),
        type: 'GET',
        success: function(response) {
            // Actualizar información de orden por dominio
            const ordenInfoPorDominio = response.ordenInfoPorDominio;
            const $ordenInfo = $('#orden-info');
            const $ordenStats = $('.orden-stats');

            let mensajeCompleto = '';

            for (const dominio in ordenInfoPorDominio) {
                const ordenInfo = ordenInfoPorDominio[dominio];
                let mensaje = `<p><strong>Dominio ${dominio}:</strong></p>`;
                let alertClass = 'alert-success';

                if (ordenInfo.completo) {
                    mensaje += `<p class="ml-3">✅ La numeración está completa del 1 al ${ordenInfo.maxOrden}.</p>`;
                } else {
                    alertClass = 'alert-warning';
                    mensaje += `<p class="ml-3">⚠️ Faltan los siguientes números de orden: ${ordenInfo.numerosFaltantes.join(', ')}</p>`;
                    mensaje += `<p class="ml-3">La numeración debería ser consecutiva del 1 al ${ordenInfo.maxOrden}.</p>`;
                }

                mensajeCompleto += `<div class="alert ${alertClass}" role="alert">${mensaje}</div>`;
            }

            if (Object.keys(ordenInfoPorDominio).length > 0) {
                $ordenStats.html(mensajeCompleto);
                $ordenInfo.show();
            } else {
                $ordenInfo.hide();
            }
        },
        error: function(xhr) {
            console.error(xhr);
        }
    });
}

// Función para manejar la importación de preguntas
function initImportPreguntasEvents(campaniaId) {
    // Evento click para el botón de importar preguntas
        // var currentCampaniaId = campaniaId;
    $("#importModalButton").off("click").on("click", function() {
        var currentCampaniaId = campaniaId;
        if (!currentCampaniaId) {
            Swal.fire('Error', 'Por favor seleccione una campaña primero', 'error');
            return;
        }
        $('#importModal').modal({
            backdrop: 'static',
            keyboard: false
        });
        $('#importModal').modal('show');
    });

    // Reiniciar el formulario cuando se cierra el modal
    $('#importModal').on('hidden.bs.modal', function() {
        resetImportForm();
    });

    $('#validateButton').off("click").on("click", function() {
        const fileInput = document.getElementById('excelFile');
        const file = fileInput.files[0];

        if (!file) {
            Swal.fire('Error', 'Por favor seleccione un archivo', 'error');
            return;
        }                

        const reader = new FileReader();
        reader.onload = function(e) {
            try {
                const workbook = XLSX.read(e.target.result, {
                    type: 'array'
                });
                const firstSheet = workbook.Sheets[workbook.SheetNames[0]];
                
                // Configurar opciones para incluir valores vacíos
                const rawData = XLSX.utils.sheet_to_json(firstSheet, {
                    defval: '', // Valor por defecto para celdas vacías
                    raw: false  // No convertir tipos automáticamente
                });

                // Procesar los datos para asegurar que todos los campos existan
                const jsonData = rawData.map(row => ({
                    pregunta: row.pregunta || '',
                    competencia: row.competencia || '',
                    dominio: row.dominio || '',
                    campaña: row.campaña || '',
                    numero_orden: row.numero_orden || '',
                    quitar: row.quitar || ''  // Aseguramos que quitar siempre existe
                }));

                // ordenar, los quitar con x van al ultimo
                jsonData.sort((a, b) => {
                    if (a.quitar && !b.quitar) return 1; // a va al final si tiene quitar
                    if (!a.quitar && b.quitar) return -1; // b va al final si tiene quitar
                    return 0; // mantienen el orden si ambos tienen o no tienen quitar
                });

                validateWithService(jsonData,campaniaId);
            } catch (error) {
                console.error(error);
                Swal.fire('Error', 'Error al procesar el archivo Excel', 'error');
            } 
        };
        reader.readAsArrayBuffer(file);
    });

    // Configura el evento click para el botón de importar
    $('#importButton').off('click').on('click', function() {
        if (!importData || !campaniaId) {
            Swal.fire('Error', 'No hay datos válidos para importar', 'error');
            return;
        }

        Swal.fire({
            title: '¿Confirmar importación?',
            text: `Se importarán ${importData.length} preguntas`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sí, importar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                // Mostrar mensaje de espera
                showLoading('Importando preguntas', 'Por favor espera mientras se importan las preguntas.');
                
                $.ajax({
                    url: PREGUNTAS_IMPORT_URL.replace(':id', campaniaId),
                    type: 'POST',
                    data: {
                        preguntas: importData,
                        // _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    success: function(response) {
                        Swal.close();
                        Swal.fire('Éxito', response.message, 'success');
                        $('#importModal').modal('hide');
                        window.pregModel.table.replaceData(); // Actualizar tabla de preguntas
                        mostrarResumenOrdenPorDominio(campaniaId); // Actualizar resumen de orden por dominio
                    },
                    error: function(xhr) {
                        Swal.close();
                        console.error(xhr);
                        Swal.fire('Error', xhr.responseJSON?.message || 'Error al importar las preguntas', 'error');
                    }
                });
            }
        });
    });

}

// Función para reiniciar el formulario de importación
function resetImportForm() {
    $('#importForm')[0].reset();
    $('#validationSummary').hide();
    $('#previewContainer').hide();
    $('#importButton').hide();
    if (typeof previewTable !== 'undefined' && previewTable) {
        previewTable.clearData();
    }
    importData = null;
}

function validateWithService(data, currentCampaniaId) {
    // Mostrar el mensaje de espera
    showLoading('Validando datos, por favor espere...');
    $.ajax({
        url: PREGUNTAS_VALIDAR_IMPORT_URL.replace(':id', currentCampaniaId),
        type: 'POST',
        data: {
            preguntas: data,
            // _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            Swal.close();
            if (!response.success) {
                Swal.fire('Error', response, 'error');
                return;
            }

            const summary = response.validation;

            // Actualizar el panel de resumen
            $('#validationSummary').html(`
                <div class="card">
                    <div class="card-header bg-primary">Resumen de Validación</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <p>Total de registros: <span class="font-weight-bold">${summary.total}</span></p>
                            </div>
                            <div class="col-md-4">
                                <p>Registros válidos: <span class="font-weight-bold text-success">${summary.valid}</span></p>
                            </div>
                            <div class="col-md-4">
                                <p>Registros con error: <span class="font-weight-bold text-danger">${summary.errors}</span></p>
                            </div>
                        </div>
                    </div>
                </div>
            `).show();

        
            // Mostrar el contenedor primero
            $('#previewContainer').show();

            // Esperar a que el contenedor sea visible
            // setTimeout(() => {
    
                // Configurar y mostrar la tabla de previsualización
                // if (!previewTable) {
                    previewTable = new Tabulator("#previewTable", {
                        layout: "fitData",
                        data: response.results,
                        columns: [{
                                title: "Fila",
                                field: "index",
                                width: 60
                            },
                            {
                                title: "Estado",
                                field: "icon",
                                formatter: "html",
                                width: 60
                            },
                            {
                                title: "Acción",
                                field: "action",
                                width: 100
                            },
                            {
                                title: "Pregunta",
                                field: "row.pregunta",
                                formatter: function(cell) {
                                    // Truncar el texto a 50 caracteres
                                    const fullText = cell.getValue();
                                    const truncated = fullText.length > 50 ? fullText.substring(0, 50) + '...' : fullText;
                                    return `<div class="text-truncate" title="${fullText}">${truncated}</div>`;
                                },
                                tooltip: function(cell) {
                                    return cell.getValue(); // Muestra el texto completo en tooltip
                                },
                            },
                            {
                                title: "Mensajes",
                                field: "messages",
                                formatter: function(cell) {
                                    const messages = cell.getValue();
                                    if (!messages || messages.length === 0) return '';
                                    return messages.map(msg => `<span class="">${msg}</span>`).join(' ');
                                },
                            },
                            {
                                title: "Competencia",
                                field: "row.competencia"
                            },
                            {
                                title: "Dominio",
                                field: "row.dominio"
                            },
                            {
                                title: "Orden",
                                field: "row.numero_orden",
                                width: 80
                            },
                            {
                                title: "Campaña",
                                field: "row.campaña",
                                width: 100
                            },
                        ],
                        locale: true,
                        langs: {
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
                        }
                    });
                // }

                // previewTable.setData(response.results);
                $('#previewContainer').show();

                // Habilitar/deshabilitar botón de importar
                $('#importButton').toggle(summary.valid > 0);

                // Guardar datos válidos para importar
                importData = response.results
                    .filter(r => r.isValid)
                    .map(r => r.row);
            // }, 100);
        },
        error: function(xhr) {
            console.error(xhr);
            
            Swal.close();
            Swal.fire('Error', xhr.responseJSON?.message || 'Error al validar los datos', 'error');
        },
    });
}

// Exportar funciones al final (después de definirlas)
window.initPreguntasTable = initPreguntasTable;
