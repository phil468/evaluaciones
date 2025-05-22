@extends('adminlte::page')

@section('title', 'Resumen de Evaluación por Competencias')

@section('content_header')
    <h1></h1>
@stop

@section('content')

    <div class="container-fluid">
        
        <div class="modal fade" id="calibracionModal" tabindex="-1" aria-labelledby="calibracionModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="text-white modal-header bg-vanguard">
                        <h5 class="modal-title h5" id="calibracionModalLabel">Calibración</h5>
                        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Cerrar">
                            <span aria-hidden="true"><i class="fas fa-times"></i></span>
                        </button>
                    </div>
                    <div class="modal-body">
                        
                        <!-- Spinner de carga -->
                        <div id="calibracionSpinner" class="py-5 text-center">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden"></span>
                            </div>
                            <div class="mt-2">Cargando datos de calibración...</div>
                        </div>
                        
                        <form method="POST" action="{{ route('calibracion.comite.guardar') }}" id="formCalibracion" style="display: none;">
                            @csrf
                            <input type="hidden" name="personal_id" id="cal_personal_id">
                            <input type="hidden" name="competencia_id" id="cal_competencia_id">
                            <input type="hidden" name="campania_id" id="cal_campania_id">

                            <!-- Primera Card: Comité de Calibración -->
                            <div class="mb-4 ">
                                <div class="h5">Comité de Calibración</div>
                                <div class="">
                                    <div class="form-group">
                                        <label>Nombre</label>
                                        <input type="text" class="form-control" id="cal_nombre" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label>Nivel Jerárquico</label>
                                        <input type="text" class="form-control" name="nivel_jerarquico" id="nivel_jerarquico" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label>Área</label>
                                        <input type="text" class="form-control" name="area" id="area" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label>Personas del Comité</label>
                                        <select name="personas[]"  class="select2" multiple="multiple" id="personas" required>
                                        </select>
                                        <small class="form-text text-muted">Use la barra de búsqueda para encontrar personas</small>
                                        <div id="mensaje-correo"></div>
                                    </div>
                                    <div class="form-group">
                                        <label>Comentario</label>
                                        <textarea class="form-control" name="comentario" id="comentario"></textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Segunda Card: Calibración de Evaluación -->
                            <div class="">
                                <div class="h5">Calibración de Evaluación</div>
                                <div class="">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped" id="tabla-calibracion">
                                            <thead>
                                                <tr>
                                                    <th>Persona</th>
                                                    <th>Competencia</th>
                                                    <th>Pregunta</th>
                                                    <th>Puntaje</th>
                                                    <th>Calibrado</th>
                                                    <th>Área</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-3">
                                <button type="submit" class="btn btn-lg btn-vanguard">Guardar Calibración</button>
                                <button type="button" class="btn btn-light" click="cerrarCalibracion()" data-dismiss="modal">Cancelar</button>
                            </div>

                        </form>

                    </div>
                </div>
            </div>
        </div>
        
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card rounded-xl">
                    <div class="text-white card-header bg-vanguard rounded-t-xl">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <div class="float-left">
                                <h5 class="h5">Resumen de Evaluación por Competencias</h5>
                            </div>

                        </div>
                    </div>
                    <div class="card-body">
                        <div id="resumen-table"></div>
                    </div>
                </div>
            </div>
        </div>
        
    </div>

    <!-- Modal Detalle Evaluación -->
    <div class="modal fade" id="detalleModal" tabindex="-1" aria-labelledby="detalleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
        <div class="text-white modal-header bg-vanguard">
            <h5 class="modal-title h5" id="detalleModalLabel">Detalle de Evaluación por Competencia</h5>
            <button type="button" class="btn-close" data-dismiss="modal" aria-label="Cerrar"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body" id="detalleModalBody">
        </div>
        </div>
    </div>
    </div>

@endsection

@section('js')
<script type="text/javascript">
    var table;
    document.addEventListener('DOMContentLoaded', function() {
        table = new Tabulator("#resumen-table", {
            ajaxURL: "{{ route('resumen.evaluacion.data') }}",
            layout: "fitDataFill",
            pagination: "local",
            paginationSize: 25,
            paginationSizeSelector: [10, 25, 50, 100],
            columns: [
                {title: "Persona", field: "persona", headerFilter: true},
                {title: "Competencia", field: "competencia", headerFilter: true},
                {title: "Puntaje", field: "puntaje", headerFilter: true,},
                {title: "Calibrado", field: "puntaje_calibrado", headerFilter: true,
                
                    formatter: function(cell) {
                        var value = cell.getValue();
                        if (value !== '' && value !== null && value !== 0) {
                            cell.getElement().style.backgroundColor = "#ffe066"; // Amarillo claro
                            cell.getElement().style.fontWeight = "bold";
                        } else {
                            cell.getElement().style.backgroundColor = "";
                            cell.getElement().style.fontWeight = "";
                        }
                        return value;
                    }

                },
                {title: "Área", field: "area", headerFilter: true},
                {title: "Comité", formatter:"tickCross", field: "comite",
                    formatterParams :{ 
                        allowEmpty : true , 
                        allowTruthy : true , 
                    },                   
                },
                
                {title: "Campaña", field: "campania", headerFilter: true},
                {title: "Fecha", field: "fecha", headerFilter: true},
                {title: "Comentario", field: "comentario", headerFilter: true},
                {
                    title: "Detalle",
                    field: "detalle_url",
                    formatter: function(cell) {
                        var url = cell.getValue();
                        return `<button class="btn btn-info btn-sm" onclick="abrirDetalleModal('${url}')">Ver Detalle</button>`;
                    }
                },
                {
                    title: "Calibración",
                    field: "calibracion_url",
                    formatter: function(cell) {
                        var data = cell.getRow().getData();
                        return `<button class="btn btn-warning btn-sm" onclick='abrirCalibracion(${JSON.stringify(data)})'>Calibrar</button>`;
                    }
                },
                
            ],
            locale: true,
            langs: {                
                "es": {
                    "data": {
                        "loading": "Cargando", //data loader text
                        "error": "Error", //data error text
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

        table.on("dataLoadError", function(error){
            console.error("Error cargando datos:", error);
        });
    });

    function abrirDetalleModal(url) {
        $('#detalleModalBody').html
        ('<div class="py-5 text-center"><div class="spinner-border text-primary" role="status"><span class="visually-hidden"></span></div><div class="mt-2">Cargando datos de detalle...</div></div>');
        $('#detalleModal').modal('show');
        fetch(url)
            .then(response => response.text())
            .then(html => {
                // Extrae solo el contenido de la tabla del detalle
                let parser = new DOMParser();
                let doc = parser.parseFromString(html, 'text/html');
                let tabla = doc.querySelector('.card-body');
                $('#detalleModalBody').html(tabla ? tabla.innerHTML : html);
            });
    }
    
    function abrirCalibracion(data) {
        Swal.fire({
            title: '¿Estás seguro que deseas hacer la calibración?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sí, continuar',
            cancelButtonText: 'No, cancelar',
            // cancelButtonColor: '#ffffff',
        }).then((result) => {
            if (result.isConfirmed) {
                // Mostrar modal con spinner
                $('#calibracionSpinner').show();
                $('#formCalibracion').hide();
                $('#mensaje-correo').html('');
                $('#calibracionModal').modal('show');
            
                // Actualizar los campos del formulario
                $('#cal_personal_id').val(data.personal_id);
                $('#cal_competencia_id').val(data.competencia_id);
                $('#cal_campania_id').val(data.campania_id);
                $('#cal_nombre').val(data.persona);
                $('#nivel_jerarquico').val(data.nivel_jerarquico);
                $('#area').val(data.area);
                
                // Inicializar select2 con las opciones preseleccionadas
                let preselectedOptions = [];
                if(data.comite_personas && data.comite_personas.length > 0) {
                    preselectedOptions = data.comite_personas.map(p => ({
                        id: p.id,
                        text: p.name,
                        selected: true
                    }));
                }
                
                // Inicializar select2 siempre
                $('#personas').empty().select2({
                    theme: 'bootstrap-5',
                    width: '100%',
                    placeholder: "Selecciona personas del comité",
                    data: preselectedOptions, // Agregamos las opciones preseleccionadas aquí
                    ajax: {
                        url: '{{ route("personal.search-comite") }}',
                        dataType: 'json',
                        delay: 250,
                        data: function (params) {
                            return {
                                search: params.term || '',
                                page: params.page || 1
                            };
                        },
                        processResults: function (data, params) {
                            params.page = params.page || 1;
                            return {
                                results: data.results,
                                pagination: {
                                    more: data.pagination.more
                                }
                            };
                        },
                        cache: true
                    },
                    minimumInputLength: 2,
                    language: {
                        inputTooShort: function() {
                            return "Por favor ingrese 2 o más caracteres";
                        },
                        searching: function() {
                            return "Buscando...";
                        },
                        noResults: function() {
                            return "No se encontraron resultados";
                        }
                    }
                })
                .on('select2:select select2:unselect', function (e) {
                    var personal_ids = $(this).val();
                    
                    $.ajax({
                        url: '{{ route("personal.verificar-correo") }}',
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            personal_ids: personal_ids
                        },
                        success: function (data) {
                            var mensaje = '';
                            if (data.sin_correo.length > 0) {
                                mensaje = '<div class="text-danger"><strong>¡Atención!</strong> Los siguientes miembros del comité no tienen correo electrónico registrado: ' + data.sin_correo.join(', ') + '. No se les enviará notificación.</div>';
                            } else {
                                mensaje = '<div class="text-success">Todos los miembros del comité tienen correo electrónico registrado.</div>';
                            }
                            $('#mensaje-correo').html(mensaje);
                        }
                    });
                });                

                if(data.comite_personas && data.comite_personas.length > 0) {
                    // Si hay personas en el comité, readonly el select2
                    $('#personas').prop("disabled", false);                    
                }

                fetch(`{{ route('calibracion.obtener-datos', ['personal_id' => ':personal_id', 'competencia_id' => ':competencia_id', 'campania_id' => ':campania_id']) }}`
                    .replace(':personal_id', data.personal_id)
                    .replace(':competencia_id', data.competencia_id)
                    .replace(':campania_id', data.campania_id))
                .then(response => response.json())
                .then(datos => {
                    console.log('Datos de calibración:', datos);
                    if (!datos.datos || datos.datos.length === 0) {
                        $('#tabla-calibracion tbody').html('<tr><td colspan="6" class="text-center">No hay datos disponibles</td></tr>');
                        return;
                    }
                    
                    let esActualizacion = false; // Variable para indicar si es una actualización
                    
                    if (datos.comite) {
                        console.log('Comité:', datos.comite);
                        // Si existe el comité llenar los datos y colocarlos como readonly
                        $('#comentario').val(datos.comite.comentario);
                        
                        $('#comentario').attr('readonly', true);
                        $("#personas").prop("disabled", true);
                        esActualizacion = true; // Es una actualización
                    } else {
                        // Si no existe el comité, limpiar los campos
                        $('#comentario').val('');
                        $('#comentario').removeAttr('readonly');
                        $("#personas").prop("disabled", false);
                    }

                    let html = '';
                    datos.datos.forEach(item => {

                        html += `
                            <tr>
                                <td>${item.personal?.name || ''}</td>
                                <td>${item.competencia?.name || ''}</td>
                                <td>${item.pregunta?.pregunta || ''}</td>
                                <td>${item.puntaje || ''}</td>
                                <td class="table-warning">
                                    <input type="number" 
                                        class="form-control" 
                                        step="0.01" 
                                        name="puntaje_calibrado[${item.id}]" 
                                        value="${item.puntaje_calibrado || ''}"
                                        required>
                                </td>
                                <td>${item.area?.name || ''}</td>
                            </tr>
                        `;
                    });
                    $('#tabla-calibracion tbody').html(html);
                    
                    // Ocultar spinner y mostrar formulario
                    $('#calibracionSpinner').hide();
                    $('#formCalibracion').show();       

                    // Pasar la variable esActualizacion a la función submitForm
                    $('#formCalibracion').data('esActualizacion', esActualizacion);             
                }).catch(error => {
                    console.error('Error:', error);
                    Swal.fire('Error', 'No se pudieron cargar los datos de calibración', 'error');
                    $('#calibracionModal').modal('hide');
                });
            }
        });
    }

    function cerrarCalibracion() {
        $('#tabla-calibracion tbody').empty();
        $('#formCalibracion')[0].reset();
        $('#calibracionModal').modal('hide');
        $('#mensaje-correo').html('');
    
        // Resetear estado del modal
        setTimeout(() => {
            $('#calibracionSpinner').show();
            $('#formCalibracion').hide();
        }, 300);
        
    }

    $('#formCalibracion').on('submit', function(e) {
        e.preventDefault();
        var form = this;
        var personal_ids = $('#personas').val();
        var formData = $(form).serializeArray();                     

        console.log('Datos del formulario:', formData);
        console.log('IDs de personas seleccionadas:', personal_ids);

        // Obtener el valor de esActualizacion desde los datos del formulario
        var esActualizacion = $(form).data('esActualizacion');

        // Verificar si hay personas sin correo
        if (esActualizacion) {
            // Si es una actualización, no verificar correo
            submitForm(form, formData, esActualizacion);
            return;
        }
        // Si no es una actualización, verificar correo
        if (personal_ids.length === 0) {
            Swal.fire('Error', 'Debes seleccionar al menos una persona del comité.', 'error');
            return;
        }
        $.ajax({
            url: '{{ route("personal.verificar-correo") }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                personal_ids: personal_ids
            },
            success: function (data) {
                if (data.sin_correo.length > 0) {
                    Swal.fire({
                        title: '¿Está seguro que desea guardar?',
                        text: 'Los siguientes miembros del comité no tienen correo electrónico registrado: ' + data.sin_correo.join(', ') + '. No se les enviará notificación.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Sí, guardar',
                        cancelButtonText: 'No, cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            submitForm(form, formData, esActualizacion);
                        }
                    });
                } else {
                    submitForm(form, formData, esActualizacion);
                }
            }
        });
    });

    function submitForm(form, formData, esActualizacion) {
        Swal.fire({
            title: '¿ESTÁS SEGURO QUE DESEAS REGISTRAR LA CALIBRACIÓN?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, guardar',
            cancelButtonText: 'No, cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post(form.action, formData, function(resp) {
                    if(resp.success) {
                        cerrarCalibracion();
                        table.replaceData();
                        Swal.fire('¡Guardado!', 'La calibración fue registrada.', 'success');
                    } else {
                        Swal.fire('Error', 'No se pudo guardar la calibración.', 'error');
                    }
                });
            }
        });
    }

</script>
@stop