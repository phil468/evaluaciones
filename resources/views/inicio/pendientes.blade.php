@extends('adminlte::page')

@section('title', 'Pendientes de Evaluación')

@section('content_header')
    <h1 class="m-0 text-center text-dark font-weight-bold">
        {{-- botón de atrás alineado a la izquierda --}}
        
        <a href="{{ url()->previous() }}" class="mr-2 btn-link btn-light btn-sm">
            <i class="fas fa-arrow-left"></i>
            Atrás
        </a>

        Pendientes
    </h1>
@stop

@section('content')
<div class="container-fluid">
    {{-- card --}}

    <div class="card rounded-xl">
        <div class="card-body">

            <!-- Evaluación de Desempeño por Competencias -->

            <div class="mb-4">
                <h1 class="mb-3 h5 font-style-poppins font-weight-bold">Evaluación de Desempeño por Competencias</h1>
                
                <!-- Barra de progreso que se actualizará dinámicamente -->
                <div class="mb-4 progress rounded-2xl" style="height: 25px; background-color: #6ECBC9">
                    <div id="progress-bar" class="progress-bar bg-vanguard" role="progressbar" style="width: 0%;" 
                        aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                        0% completado
                    </div>
                </div>
                
                <!-- Contenedor donde se cargarán dinámicamente las tarjetas de evaluados -->
                <div id="evaluados-container" class="row">
                    <!-- Las tarjetas de evaluados se cargarán aquí mediante JavaScript -->
                    <div class="text-center w-100 py-4">
                        <i class="fas fa-spinner fa-spin fa-2x"></i>
                        <p class="mt-2">Cargando evaluaciones pendientes...</p>
                    </div>
                </div>
            </div>
            {{-- <div class="mb-4">
                <h1 class="mb-3 h5 font-style-poppins font-weight-bold">Evaluación de Desempeño por Competencias</h1>
                
                <div class="mb-4 progress rounded-2xl" style="height: 25px;">
                    <div class="progress-bar bg-vanguard" role="progressbar" style="width: 65%;" 
                        aria-valuenow="65" aria-valuemin="0" aria-valuemax="100">
                        65% completado
                    </div>
                </div>
                
                <div class="row">
                    <!-- Tarjeta de empleado - Competencias -->
                    <div class="col-md-3">
                        <div class="card employee-card">
                            <div class="pt-3 text-center card-body">
                                <div class="mb-2 employee-avatar">
                                    <i class="fas fa-user-circle fa-4x text-secondary"></i>
                                </div>
                                <h5 class="mb-0 employee-name">JOSE AGUILAR</h5>
                                <p class="mb-1 text-muted small">ASISTENTE DE PRODUCCIÓN</p>
                                <div class="mb-2 badge badge-light">Evaluación 270</div>
                                
                                <button class="btn btn-info btn-block btn-sm rounded-xl">Pendiente de evaluar</button>
                            </div>
                        </div>
                    </div>
                </div>

            </div> --}}
            
            {{-- <!-- Evaluación de Desempeño por Objetivos -->
            <div class="mb-4">
                <h4 class="mb-3 h5 font-style-poppins font-weight-bold">Evaluación de Desempeño por Objetivos</h4>
                
                <div class="mb-4 progress rounded-2xl" style="height: 25px;">
                    <div class="progress-bar bg-primary" role="progressbar" style="width: 30%;" 
                        aria-valuenow="30" aria-valuemin="0" aria-valuemax="100">
                        30% completado
                    </div>
                </div>
                
                <div class="row">
                    <!-- Tarjeta de empleado - Fase 1 -->
                    <div class="col-md-3">
                        <div class="card employee-card">
                            <div class="pt-3 text-center card-body">
                                <div class="mb-2 employee-avatar">
                                    <i class="fas fa-user-circle fa-4x text-secondary"></i>
                                </div>
                                <h5 class="mb-0 employee-name">JOSE AGUILAR</h5>
                                <p class="mb-1 text-muted small">ASISTENTE DE PRODUCCIÓN</p>
                                <div class="mb-2 badge badge-light">Evaluación 270</div>
                                <div class="mb-2 text-muted small">Fase 1</div>
                                <button class="btn btn-info btn-block btn-sm rounded-xl">Ingresar Objetivos</button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Tarjeta de empleado - Fase 2 -->
                    <div class="col-md-3">
                        <div class="card employee-card">
                            <div class="pt-3 text-center card-body">
                                <div class="mb-2 employee-avatar">
                                    <i class="fas fa-user-circle fa-4x text-secondary"></i>
                                </div>
                                <h5 class="mb-0 employee-name">JOSE AGUILAR</h5>
                                <p class="mb-1 text-muted small">ASISTENTE DE PRODUCCIÓN</p>
                                <div class="mb-2 badge badge-light">Evaluación 270</div>
                                <div class="mb-2 text-muted small">Fase 2</div>
                                <button class="btn btn-info btn-block btn-sm rounded-xl">Ingresar Resultados</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Feedback y Planes de Mejora Individual -->
            <div class="mb-4">
                <h4 class="mb-3 h5 font-style-poppins font-weight-bold">Feedback y Planes de Mejora Individual</h4>
                
                <div class="mb-4 progress rounded-2xl" style="height: 25px;">
                    <div class="progress-bar bg-primary" role="progressbar" style="width: 30%;" 
                        aria-valuenow="30" aria-valuemin="0" aria-valuemax="100">
                        30% completado
                    </div>
                </div>
                
                <div class="row">
                    <!-- Tarjeta de empleado - Fase 1 -->
                    <div class="col-md-3">
                        <div class="card employee-card">
                            <div class="pt-3 text-center card-body">
                                <div class="mb-2 employee-avatar">
                                    <i class="fas fa-user-circle fa-4x text-secondary"></i>
                                </div>
                                <h5 class="mb-0 employee-name">JOSE AGUILAR</h5>
                                <p class="mb-1 text-muted small">ASISTENTE DE PRODUCCIÓN</p>
                                <div class="mb-2 badge badge-light">Evaluación 270</div>
                                <div class="mb-2 text-muted small">Fase 1</div>
                                <button class="btn btn-info btn-block btn-sm rounded-xl">Dar feedback</button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Tarjeta de empleado - Fase 2 -->
                    <div class="col-md-3">
                        <div class="card employee-card">
                            <div class="pt-3 text-center card-body">
                                <div class="mb-2 employee-avatar">
                                    <i class="fas fa-user-circle fa-4x text-secondary"></i>
                                </div>
                                <h5 class="mb-0 employee-name">JOSE AGUILAR</h5>
                                <p class="mb-1 text-muted small">ASISTENTE DE PRODUCCIÓN</p>
                                <div class="mb-2 badge badge-light">Evaluación 270</div>
                                <div class="mb-2 text-muted small">Fase 2</div>
                                <button class="btn btn-info btn-block btn-sm rounded-xl">Ingresar Seguimiento</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div> --}}
        </div>
    </div>
</div>
@stop

@section('css')
<style>
    .employee-card {
        border-radius: 10px;
        box-shadow: 0 0 5px rgba(0,0,0,0.1);
    }
    
    .employee-name {
        font-weight: 600;
        font-size: 14px;
    }
    
    .progress {
        height: 8px;
        border-radius: 4px;
        background-color: #e9ecef;
    }
    
    .progress-bar {
        background-color: #43515a;
    }
    
    h4 {
        font-family: 'Poppins', sans-serif;
        font-weight: 600;
    }
    
    .employee-avatar {
        margin: 0 auto;
    }
    
    .btn-info {
        background-color: #568ca5;
        border-color: #568ca5;
    }
    
    .btn-info:hover {
        background-color: #457891;
        border-color: #457891;
    }
    
    .badge-light {
        background-color: #f8f9fa;
        color: #212529;
    }

    /* Estilos para tarjetas con enfoque en evaluaciones pendientes */
    .employee-card {
        border-radius: 10px;
        box-shadow: 0 0 5px rgba(0,0,0,0.1);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .employee-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    .progress {
        height: 25px;
        border-radius: 20px;
        background-color: #6ECBC9;
    }

    .progress-bar {
        font-size: 14px;
        font-weight: bold;
        border-radius: 20px;
    }

    .bg-vanguard {
        background-color: #43515a;
    }

    .btn-info {
        background-color: #568ca5;
        border-color: #568ca5;
    }

    .rounded-2xl {
        border-radius: 1rem !important;
    }

</style>
@stop

@section('js')
<script>
    $(function () {
        // Puedes agregar funcionalidad adicional aquí
        // Por ejemplo, tooltips o popups
        $('[data-toggle="tooltip"]').tooltip();

        //colocar una constante de la url para ser reemplazado más abajo
       //  la ruta copnfigurada en web.ph es : wire:    Route::get('/evaluacion/{tipo_de_evaluacion_id}/{id}', [EvaluacionController::class, 'show'])->name('evaluacion.show')->middleware(['can:ver-evaluaciones-de-desempeno']);
        const RUTA_EVALUACION = "{{ route('evaluacion.show', ['tipo_de_evaluacion_id' => 1, 'id' => ':id']) }}";
        //const EVALUACIONES_BY_CAMPANIA_URL = "{{ route('campanias.evaluaciones', ':id') }}";

        // Cargar datos de evaluaciones pendientes
        function cargarEvaluacionesPendientes() {
            $.ajax({
                url: "{{ route('evaluaciones.pendientes.data') }}",
                type: "GET",
                data: {
                    tipo_evaluacion: 1, // Tipo de evaluación (1=competencias, 2=objetivos)
                    // campania: "{{ date('Y') }}" // Campaña actual (año)
                },
                dataType: "json",
                beforeSend: function() {
                    // Mostrar indicador de carga
                    $("#evaluados-container").html(`
                        <div class="text-center w-100 py-4">
                            <i class="fas fa-spinner fa-spin fa-2x"></i>
                            <p class="mt-2">Cargando evaluaciones pendientes...</p>
                        </div>
                    `);
                },
                success: function(response) {
                    // Actualizar barra de progreso
                    const stats = response.estadisticas;
                    $("#progress-bar").css("width", stats.porcentaje + "%")
                        .attr("aria-valuenow", stats.porcentaje)
                        .text(stats.label + " completado");
                    
                    // Si hay clases específicas para el color
                    if (stats.realizados === 0) {
                        $("#progress-bar").removeClass("bg-secondary").addClass("bg-primary").addClass("w-100");
                    } else if (stats.total === stats.realizados) {
                        $("#progress-bar").removeClass("bg-primary").addClass("bg-secondary");
                    } else {
                        $("#progress-bar").removeClass("bg-secondary").addClass("bg-primary");
                    }
                    
                    // Generar contenido de tarjetas
                    let html = "";
                    
                    if (response.evaluaciones.length > 0) {
                        response.evaluaciones.forEach(function(row) {
                            // Solo mostrar las evaluaciones pendientes (no realizadas)
                            // if (!row.realizado && row.evaluacion.activa) {
                                html += generarTarjetaEvaluado(row);
                            // }
                        });
                        
                        if (html === "") {
                            html = `
                                <div class="col-12">
                                    <div class="alert alert-success">
                                        <i class="fas fa-check-circle"></i> No tienes evaluaciones pendientes.
                                    </div>
                                </div>
                            `;
                        }
                    } else {
                        html = `
                            <div class="col-12">
                                <div class="alert alert-info">
                                    No tienes evaluaciones asignadas.
                                </div>
                            </div>
                        `;
                    }
                    
                    $("#evaluados-container").html(html);
                },
                error: function(xhr) {
                    console.error("Error cargando evaluaciones:", xhr);
                    $("#evaluados-container").html(`
                        <div class="col-12">
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-circle"></i> 
                                Error al cargar las evaluaciones. Intente nuevamente.
                            </div>
                        </div>
                    `);
                }
            });
        }
        
        // Función para generar el HTML de cada tarjeta
        function generarTarjetaEvaluado(row) {
            console.log(row);
            const nombreCompleto = `${row.evaluado.nombres} ${row.evaluado.apellido_paterno} ${row.evaluado.apellido_materno}`;
            const cargoNombre = row.cargo_nombre;
            const gradoNombre = row.grado ? row.grado.name : '';

            nueva_ruta=RUTA_EVALUACION.replace(':id', row.id);
            
            return `
                <div class="col-md-3 mb-4">
                    <div class="card employee-card h-100">
                        <div class="card-body pt-3 text-center">
                            <div class="employee-avatar mb-2">
                                <i class="fas fa-user-circle fa-4x text-secondary"></i>
                            </div>
                            <h5 class="employee-name mb-0">${nombreCompleto}</h5>
                            <p class="text-muted small mb-1">${cargoNombre}</p>
                            <div class="badge badge-light mb-2">Evaluación ${gradoNombre}</div>

                            <a href='${nueva_ruta}'
                               class="btn btn-info btn-block btn-sm rounded-xl ${row.realizado ? 'disabled' : ''}"
                               
                               data-toggle="tooltip" 
                               data-placement="top" 
                               title="${row.realizado ? 'Evaluación completada' : 'Pendiente de evaluar'}">
                                ${row.realizado ? 'Completado' : 'Pendiente de evaluar'}
                            </a>
                        </div>
                    </div>
                </div>
            `;
        }

        // Cargar datos al iniciar la página
        cargarEvaluacionesPendientes();

        
        // Cargar datos al iniciar la página
        cargarEvaluacionesPendientes();
        
        // Opcional: Actualizar datos cada 5 minutos
        setInterval(cargarEvaluacionesPendientes, 5 * 60 * 1000);
    
    });
</script>
@stop