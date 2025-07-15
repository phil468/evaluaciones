const ObjetivosPrecargados = (function() {
    // Variables privadas
    let table;
    let config = {};
    
    // Inicializar el módulo
    function init(options) {
        config = options;
        setupTable();
        setupEventListeners();
    }
    
    // Configurar la tabla con Tabulator
    function setupTable() {
        table = new Tabulator("#objetivos-table", {
            ajaxURL: config.dataUrl,
            layout: "fitData",
            pagination: true,
            paginationSize: 25,
            paginationSizeSelector: [10, 25, 50, 100],
            columns: [
                {title: "ID", field: "id", sorter: "number", width: 70},
                {title: "Tipo Jerarquía", field: "tipo_de_jerarquia_id", formatter: function(cell) {
                    return "TIPO " + cell.getValue();
                }},
                {title: "Grupal", field: "grupal", formatter: function(cell) {
                    return cell.getValue() == 1 ? "Sí" : "No";
                }},
                {title: "Meta", field: "meta", formatter: function(cell, formatterParams, onRendered) {
                    const grupal = cell.getRow().getData().grupal;
                    if (!grupal) {
                        onRendered(function(el) {
                            el.classList.add("table-secondary");
                        });
                    }
                    return cell.getValue() || "";
                }},
                {title: "% Participación", field: "porcentaje_de_participacion", formatter: function(cell) {
                    return cell.getValue() + "%";
                }},
                {title: "Tipo Objetivo", field: "tipo_objetivo", formatter: function(cell, formatterParams, onRendered) {
                    const grupal = cell.getRow().getData().grupal;
                    const tipoObjetivo = cell.getValue();
                    if (!grupal) {
                        onRendered(function(el) {
                            el.classList.add("table-secondary");
                        });
                    }
                    return tipoObjetivo ? tipoObjetivo.unidad + "(" + tipoObjetivo.simbolo + ")" : "";
                }},
                {title: "Resultado A/E", field: "resultado_anterior_o_esperado", formatter: function(cell, formatterParams, onRendered) {
                    const grupal = cell.getRow().getData().grupal;
                    const tipoObjetivo = cell.getRow().getData().tipo_objetivo;
                    const valor = cell.getValue();
                    
                    if (!grupal) {
                        onRendered(function(el) {
                            el.classList.add("table-secondary");
                        });
                    }
                    
                    if (!tipoObjetivo) return valor;
                    
                    if (tipoObjetivo.id === 2) {
                        return valor + "%";
                    } else if (tipoObjetivo.id === 1) {
                        return parseFloat(valor).toLocaleString('es-ES', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                    }
                    
                    return valor;
                }},
                {title: "% Mínimo", field: "evaluacion.minimo", formatter: function(cell) {
                    return cell.getValue() + "%";
                }},
                {title: "Mínimo", field: "minimo", formatter: function(cell, formatterParams, onRendered) {
                    const grupal = cell.getRow().getData().grupal;
                    const tipoObjetivo = cell.getRow().getData().tipo_objetivo;
                    const valor = cell.getValue();
                    
                    if (!grupal) {
                        onRendered(function(el) {
                            el.classList.add("table-secondary");
                        });
                    }
                    
                    if (!tipoObjetivo) return valor;
                    
                    if (tipoObjetivo.id === 2) {
                        return valor + "%";
                    } else if (tipoObjetivo.id === 1) {
                        return parseFloat(valor).toLocaleString('es-ES', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                    }
                    
                    return valor;
                }},
                {title: "% Máximo", field: "evaluacion.maximo", formatter: function(cell) {
                    return cell.getValue() + "%";
                }},
                {title: "Máximo", field: "maximo", formatter: function(cell, formatterParams, onRendered) {
                    const grupal = cell.getRow().getData().grupal;
                    const tipoObjetivo = cell.getRow().getData().tipo_objetivo;
                    const valor = cell.getValue();
                    
                    if (!grupal) {
                        onRendered(function(el) {
                            el.classList.add("table-secondary");
                        });
                    }
                    
                    if (!tipoObjetivo) return valor;
                    
                    if (tipoObjetivo.id === 2) {
                        return valor + "%";
                    } else if (tipoObjetivo.id === 1) {
                        return parseFloat(valor).toLocaleString('es-ES', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                    }
                    
                    return valor;
                }},
                {title: "Valor", field: "valor", formatter: function(cell) {
                    const tipoObjetivo = cell.getRow().getData().tipo_objetivo;
                    const valor = cell.getValue();
                    
                    if (!valor && valor !== 0) return "";
                    
                    if (!tipoObjetivo) return valor;
                    
                    if (tipoObjetivo.id === 2) {
                        return valor + "%";
                    } else if (tipoObjetivo.id === 1) {
                        return parseFloat(valor).toLocaleString('es-ES', {minimumFractionDigits: 4, maximumFractionDigits: 4});
                    }
                    
                    return valor;
                }, 
                headerSort: false,
                formatter:function(cell, formatterParams, onRendered){
                    const data = cell.getRow().getData();
                    const valor = cell.getValue();
                    
                    if (config.canEdit) {
                        return `<button class="btn btn-link actualizar-valor" data-id="${data.id}">${valor == null ? "Ingresar Valor" : valor }</button>`;
                    }
                    
                    return valor || "";
                }},
                {title: "% Logro STI", field: "porcentaje_de_logro_STI", formatter: function(cell) {
                    return (cell.getValue() == null ? '' : cell.getValue() + "%");
                }},
                {title: "Peso Pond.", field: "peso_ponderado", formatter: function(cell) {
                    return (cell.getValue() == null ? '' : cell.getValue() + "%");
                }},
                {title: "Evidencias", field: "evidencias", formatter: function(cell) {
                    const evidencias = cell.getValue();
                    const data = cell.getRow().getData();
                    let html = '';
                    
                    if (evidencias && evidencias.length > 0) {
                        html += '<div class="mb-2">';
                        evidencias.forEach(evidencia => {
                            html += `<div class="mb-1">
                                <a href="/public/${evidencia.ruta}" 
                                target="_blank" 
                                class="btn btn-link p-0">${evidencia.name}</a>
                                ${config.canEdit ? `<button class="btn btn-danger btn-sm eliminar-evidencia" data-id="${evidencia.id}"><i class="fa fa-trash"></i></button>` : ''}
                            </div>`;
                        });
                        html += '</div>';
                    }
                    
                    if (config.canEdit) {
                        html += `<button class="btn btn-vanguard btn-sm rounded-full subir-evidencia" data-id="${data.id}"><i class="fa fa-plus"></i></button>`;
                    }
                    
                    return html;
                }, headerSort: false},
                {title: "Evaluación", field: "evaluacion.title"},
                {title: "Acciones", formatter: function(cell) {
                    const data = cell.getRow().getData();
                    let html = '<div class="btn-group">';
                    
                    if (config.canEdit) {
                        html += `<button class="btn btn-sm btn-vanguard rounded-xl editar-objetivo" data-id="${data.id}">Editar</button>`;
                    }
                    
                    if (config.canDelete) {
                        html += `<button class="btn btn-sm btn-danger rounded-xl eliminar-objetivo" data-id="${data.id}">Borrar</button>`;
                    }
                    
                    html += '</div>';
                    return html;
                }, headerSort: false, width: 150}
            ],
            locale: true,
            langs: {
                "es": {
                    "data": {
                        "loading": "Cargando",
                        "error": "Error"
                    },
                    "pagination": {
                        "page_size": "Elementos",
                        "page_title": "Ver Página",
                        "first": "Primera",
                        "first_title": "Primera Página",
                        "last": "Última",
                        "last_title": "Última Página",
                        "prev": "Anterior",
                        "prev_title": "Página Anterior",
                        "next": "Siguiente",
                        "next_title": "Página Siguiente"
                    },
                    "headerFilters": {
                        "default": "filtrar columna..."
                    }
                }
            }
        });
    }
    
    // Configurar los event listeners para los botones
    function setupEventListeners() {
        // Botón nuevo objetivo
        document.getElementById('btn-nuevo').addEventListener('click', mostrarFormularioObjetivo);
        
        // Delegación de eventos para botones dinámicos
        document.getElementById('objetivos-table').addEventListener('click', function(e) {
            // Editar objetivo
            if (e.target.closest('.editar-objetivo')) {
                const id = e.target.closest('.editar-objetivo').dataset.id;
                editarObjetivo(id);
            }
            
            // Eliminar objetivo
            if (e.target.closest('.eliminar-objetivo')) {
                const id = e.target.closest('.eliminar-objetivo').dataset.id;
                confirmarEliminarObjetivo(id);
            }
            
            // Actualizar valor
            if (e.target.closest('.actualizar-valor')) {
                const id = e.target.closest('.actualizar-valor').dataset.id;
                mostrarFormularioActualizarValor(id);
            }
            
            // Subir evidencia
            if (e.target.closest('.subir-evidencia')) {
                const id = e.target.closest('.subir-evidencia').dataset.id;
                mostrarFormularioSubirEvidencia(id);
            }
            
            // Eliminar evidencia
            if (e.target.closest('.eliminar-evidencia')) {
                const id = e.target.closest('.eliminar-evidencia').dataset.id;
                confirmarEliminarEvidencia(id);
            }
        });
    }
    
    // Mostrar formulario para nuevo/editar objetivo
    function mostrarFormularioObjetivo(data = null) {
        const isEdit = data !== null;
        
        Swal.fire({
            title: isEdit ? 'Editar Objetivo Precargado' : 'Nuevo Objetivo Precargado',
            html: `
                <form id="form-objetivo" class="text-left">
                    <div class="form-group">
                        <label>Tipo de Jerarquía</label>
                        <select id="tipo_de_jerarquia_id" class="form-control">
                            <option value="1">TIPO 1</option>
                            <option value="2">TIPO 2</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Grupal</label>
                        <select id="grupal" class="form-control">
                            <option value="1">Sí</option>
                            <option value="0">No</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Meta</label>
                        <textarea id="meta" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <label>% De Participación</label>
                        <input type="number" id="porcentaje_de_participacion" class="form-control" min="0" max="100" step="0.01">
                    </div>
                    <div class="form-group">
                        <label>Tipo de Objetivo</label>
                        <select id="tipo_objetivo_id" class="form-control">
                            ${config.tiposObjetivo.map(tipo => `<option value="${tipo.id}">${tipo.unidad} (${tipo.simbolo})</option>`).join('')}
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Resultado Anterior/Esperado</label>
                        <input type="number" id="resultado_anterior_o_esperado" class="form-control" step="0.01">
                    </div>
                    <div class="form-group">
                        <label>Evaluación</label>
                        <select id="evaluacion_id" class="form-control">
                            ${config.evaluaciones.map(eval => `<option value="${eval.id}">${eval.title}</option>`).join('')}
                        </select>
                    </div>
                </form>
            `,
            focusConfirm: false,
            showCancelButton: true,
            confirmButtonText: isEdit ? 'Actualizar' : 'Guardar',
            cancelButtonText: 'Cancelar',
            preConfirm: () => {
                return {
                    tipo_de_jerarquia_id: document.getElementById('tipo_de_jerarquia_id').value,
                    grupal: document.getElementById('grupal').value,
                    meta: document.getElementById('meta').value,
                    porcentaje_de_participacion: document.getElementById('porcentaje_de_participacion').value,
                    tipo_objetivo_id: document.getElementById('tipo_objetivo_id').value,
                    resultado_anterior_o_esperado: document.getElementById('resultado_anterior_o_esperado').value,
                    evaluacion_id: document.getElementById('evaluacion_id').value
                };
            },
            didOpen: () => {
                if (isEdit) {
                    // Cargar datos para edición
                    document.getElementById('tipo_de_jerarquia_id').value = data.tipo_de_jerarquia_id;
                    document.getElementById('grupal').value = data.grupal ? "1" : "0";
                    document.getElementById('meta').value = data.meta || '';
                    document.getElementById('porcentaje_de_participacion').value = data.porcentaje_de_participacion;
                    document.getElementById('tipo_objetivo_id').value = data.tipo_objetivo_id || '';
                    document.getElementById('resultado_anterior_o_esperado').value = data.resultado_anterior_o_esperado || '';
                    document.getElementById('evaluacion_id').value = data.evaluacion_id;
                }
            }
        }).then((result) => {
            if (result.isConfirmed) {
                if (isEdit) {
                    actualizarObjetivo(data.id, result.value);
                } else {
                    crearObjetivo(result.value);
                }
            }
        });
    }
    
    // Crear nuevo objetivo
    function crearObjetivo(formData) {
        showLoading();
        
        fetch(config.storeUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': config.csrf
            },
            body: JSON.stringify(formData)
        })
        .then(response => response.json())
        .then(data => {
            hideLoading();
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Éxito',
                    text: data.message
                });
                table.replaceData();
            } else {
                mostrarErrores(data.errors);
            }
        })
        .catch(error => {
            hideLoading();
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Ha ocurrido un error al crear el objetivo'
            });
        });
    }
    
    // Cargar y mostrar formulario de edición
    function editarObjetivo(id) {
        showLoading();
        
        fetch(config.showUrl.replace(':id', id))
        .then(response => response.json())
        .then(data => {
            hideLoading();
            mostrarFormularioObjetivo(data);
        })
        .catch(error => {
            hideLoading();
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Ha ocurrido un error al cargar los datos del objetivo'
            });
        });
    }
    
    // Actualizar objetivo existente
    function actualizarObjetivo(id, formData) {
        showLoading();
        
        fetch(config.updateUrl.replace(':id', id), {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': config.csrf
            },
            body: JSON.stringify(formData)
        })
        .then(response => response.json())
        .then(data => {
            hideLoading();
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Éxito',
                    text: data.message
                });
                table.replaceData();
            } else {
                mostrarErrores(data.errors);
            }
        })
        .catch(error => {
            hideLoading();
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Ha ocurrido un error al actualizar el objetivo'
            });
        });
    }
    
    // Confirmar eliminación de objetivo
    function confirmarEliminarObjetivo(id) {
        Swal.fire({
            title: '¿Está seguro?',
            text: "Los objetivos precargados borrados no pueden ser recuperados",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, borrar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                eliminarObjetivo(id);
            }
        });
    }
    
    // Eliminar objetivo
    function eliminarObjetivo(id) {
        showLoading();
        
        fetch(config.destroyUrl.replace(':id', id), {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': config.csrf
            }
        })
        .then(response => response.json())
        .then(data => {
            hideLoading();
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Éxito',
                    text: data.message
                });
                table.replaceData();
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'No se pudo eliminar el objetivo'
                });
            }
        })
        .catch(error => {
            hideLoading();
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Ha ocurrido un error al eliminar el objetivo'
            });
        });
    }
    
    // Mostrar formulario para actualizar valor
    function mostrarFormularioActualizarValor(id) {
        Swal.fire({
            title: 'Actualizar Valor',
            html: `
                <form id="form-valor" class="text-left">
                    <div class="form-group">
                        <label for="valor">Valor</label>
                        <input type="number" id="valor" class="form-control" step="0.01" required>
                    </div>
                </form>
            `,
            focusConfirm: false,
            showCancelButton: true,
            confirmButtonText: 'Actualizar',
            cancelButtonText: 'Cancelar',
            preConfirm: () => {
                return {
                    valor: document.getElementById('valor').value
                };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                actualizarValor(id, result.value.valor);
            }
        });
    }
    
    // Actualizar valor del objetivo
    function actualizarValor(id, valor) {
        showLoading();
        
        fetch(config.actualizarValorUrl.replace(':id', id), {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': config.csrf
            },
            body: JSON.stringify({ valor: valor })
        })
        .then(response => response.json())
        .then(data => {
            hideLoading();
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Éxito',
                    text: data.message
                });
                table.replaceData();
            } else {
                mostrarErrores(data.errors);
            }
        })
        .catch(error => {
            hideLoading();
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Ha ocurrido un error al actualizar el valor'
            });
        });
    }
    
    // Mostrar formulario para subir evidencia
    function mostrarFormularioSubirEvidencia(id) {
        Swal.fire({
            title: 'Subir Evidencia',
            html: `
                <form id="form-evidencia" class="text-left">
                    <div class="form-group">
                        <label for="evidencia">Archivo</label>
                        <input type="file" id="evidencia" class="form-control" required>
                    </div>
                </form>
            `,
            focusConfirm: false,
            showCancelButton: true,
            confirmButtonText: 'Subir',
            cancelButtonText: 'Cancelar',
            preConfirm: () => {
                const fileInput = document.getElementById('evidencia');
                if (!fileInput.files || fileInput.files.length === 0) {
                    Swal.showValidationMessage('Debe seleccionar un archivo');
                    return false;
                }
                return true;
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const fileInput = document.getElementById('evidencia');
                subirEvidencia(id, fileInput.files[0]);
            }
        });
    }
    
    // Subir evidencia
    function subirEvidencia(id, file) {
        showLoading();
        
        const formData = new FormData();
        formData.append('evidencia', file);
        formData.append('_token', config.csrf);
        
        fetch(config.subirEvidenciaUrl.replace(':id', id), {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            hideLoading();
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Éxito',
                    text: data.message
                });
                table.replaceData();
            } else {
                mostrarErrores(data.errors);
            }
        })
        .catch(error => {
            hideLoading();
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Ha ocurrido un error al subir la evidencia'
            });
        });
    }
    
    // Confirmar eliminación de evidencia
    function confirmarEliminarEvidencia(id) {
        Swal.fire({
            title: '¿Está seguro?',
            text: "La evidencia eliminada no podrá ser recuperada",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                eliminarEvidencia(id);
            }
        });
    }
    
    // Eliminar evidencia
    function eliminarEvidencia(id) {
        showLoading();
        
        fetch(config.eliminarEvidenciaUrl.replace(':id', id), {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': config.csrf
            }
        })
        .then(response => response.json())
        .then(data => {
            hideLoading();
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Éxito',
                    text: data.message
                });
                table.replaceData();
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'No se pudo eliminar la evidencia'
                });
            }
        })
        .catch(error => {
            hideLoading();
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Ha ocurrido un error al eliminar la evidencia'
            });
        });
    }
    
    // Mostrar errores de validación
    function mostrarErrores(errors) {
        let errorMessages = '';
        for (const field in errors) {
            errorMessages += `${errors[field].join('<br>')}<br>`;
        }
        
        Swal.fire({
            icon: 'error',
            title: 'Error de validación',
            html: errorMessages
        });
    }
    
    // Mostrar indicador de carga
    function showLoading() {
        document.getElementById('loading-indicator').style.display = 'block';
    }
    
    // Ocultar indicador de carga
    function hideLoading() {
        document.getElementById('loading-indicator').style.display = 'none';
    }
    
    // API pública del módulo
    return {
        init: init
    };
})();