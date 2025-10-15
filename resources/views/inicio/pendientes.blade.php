{{-- filepath: c:\laragon\www\evaluaciones\resources\views\inicio\pendientes.blade.php --}}
@extends('adminlte::page')

@section('title', 'Pendientes de Evaluación')

@section('content_header')
    <h1 class="m-0 text-center text-dark font-weight-bold">
        <a href="{{ url()->previous() }}" class="mr-2 btn-link btn-light btn-sm">
            <i class="fas fa-arrow-left"></i>
            Atrás
        </a>
        Pendientes
    </h1>
@stop

@section('content')
<div class="container-fluid">
    <div class="card rounded-xl">
        <div class="card-body">

            <!-- Evaluación de Desempeño por Competencias -->
            <div id="seccion-competencias" class="mb-5" style="display: none;">
                <h1 class="mb-3 h5 font-style-poppins font-weight-bold">Evaluación de Desempeño por Competencias</h1>
                
                <div class="mb-4 progress rounded-2xl bg-primary" style="height: 25px;">
                    <div id="progress-bar-competencias" class="progress-bar" role="progressbar" style="width: 0%;" 
                        aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                        0% completado
                    </div>
                </div>
                
                <div id="evaluados-competencias-container" class="row">
                    <div class="py-4 text-center w-100">
                        <i class="fas fa-spinner fa-spin fa-2x"></i>
                        <p class="mt-2">Cargando evaluaciones pendientes...</p>
                    </div>
                </div>
            </div>

            <!-- Evaluación de Desempeño por Objetivos -->
            <div id="seccion-objetivos" class="mb-5" style="display: none;">
                <h1 class="mb-3 h5 font-style-poppins font-weight-bold">Evaluación de Desempeño por Objetivos</h1>
                
                <div class="mb-4 progress rounded-2xl bg-primary" style="height: 25px;">
                    <div id="progress-bar-objetivos" class="progress-bar" role="progressbar" style="width: 0%;" 
                        aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                        0% completado
                    </div>
                </div>
                
                <div id="evaluados-objetivos-container" class="row">
                    <div class="py-4 text-center w-100">
                        <i class="fas fa-spinner fa-spin fa-2x"></i>
                        <p class="mt-2">Cargando evaluaciones por objetivos...</p>
                    </div>
                </div>
            </div>


            <!-- Feedback y Planes de Mejora Individual -->
            <div id="seccion-planes" class="mb-4" style="display: none;">
                <h1 class="mb-3 h5 font-style-poppins font-weight-bold">Feedback y Planes de Desarrollo Individual</h1>
                
                <div class="mb-4 progress rounded-2xl bg-primary" style="height: 25px;">
                    <div id="progress-bar-planes" class="progress-bar" role="progressbar" style="width: 0%;" 
                        aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                        0% completado
                    </div>
                </div>
                
                <div id="planes-mejora-container" class="row">
                    <div class="py-4 text-center w-100">
                        <i class="fas fa-spinner fa-spin fa-2x"></i>
                        <p class="mt-2">Cargando planes de mejora...</p>
                    </div>
                </div>
            </div>

            <!-- Mensaje cuando no hay secciones activas -->
            <div id="sin-secciones-activas" class="py-5 text-center" style="display: none;">
                <div class="alert alert-info">
                    <i class="mb-3 fas fa-info-circle fa-2x"></i>
                    <h4>No hay evaluaciones activas</h4>
                    <p>Actualmente no hay evaluaciones de desempeño o planes de mejora activos para mostrar.</p>
                </div>
            </div>

        </div>
    </div>
</div>
@stop

@section('css')
{{-- ...existing styles... --}}
@stop

@section('js')
<script>
    $(function () {
        $('[data-toggle="tooltip"]').tooltip();

        const RUTA_EVALUACION = "{{ route('evaluacion.show', ['tipo_de_evaluacion_id' => ':tipo', 'id' => ':id']) }}";
        const RUTA_PLANES_MEJORA = "{{ route('planes-de-mejora', ['dashboard' => 'dashboard', 'empleado_id' => ':empleado_id']) }}";

        function cargarEvaluacionesPendientes() {
            $.ajax({
                url: "{{ route('evaluaciones.pendientes.data') }}",
                type: "GET",
                dataType: "json",
                beforeSend: function() {
                    mostrarCargando();
                },
                success: function(response) {
                    // // Actualizar competencias
                    // actualizarSeccion('competencias', response.evaluaciones_competencias, response.estadisticas_competencias, 1);
                    
                    // // Actualizar objetivos
                    // actualizarSeccion('objetivos', response.evaluaciones_objetivos, response.estadisticas_objetivos, 2);
                    
                    // // Actualizar planes de mejora
                    // actualizarPlanesMejora(response.planes_mejora, response.estadisticas_planes);
                    // Mostrar/ocultar secciones según estén activas
                    mostrarSeccionesActivas(response.secciones_activas);
                    
                    // Solo actualizar secciones que están activas
                    if (response.secciones_activas.competencias) {
                        actualizarSeccion('competencias', response.evaluaciones_competencias, response.estadisticas_competencias, 1);
                    }
                    
                    if (response.secciones_activas.objetivos) {
                        actualizarSeccion('objetivos', response.evaluaciones_objetivos, response.estadisticas_objetivos, 2);
                    }
                    
                    if (response.secciones_activas.planes) {
                        actualizarPlanesMejora(response.planes_mejora, response.estadisticas_planes);
                    }
                },
                error: function(xhr) {
                    console.error("Error cargando evaluaciones:", xhr);
                    mostrarError();
                }
            });
        }

        function mostrarSeccionesActivas(seccionesActivas) {
            // Ocultar todas las secciones primero
            $('#seccion-competencias').hide();
            $('#seccion-objetivos').hide();
            $('#seccion-planes').hide();
            $('#sin-secciones-activas').hide();

            // Mostrar solo las secciones activas
            if (seccionesActivas.competencias) {
                $('#seccion-competencias').show();
            }
            if (seccionesActivas.objetivos) {
                $('#seccion-objetivos').show();
            }
            if (seccionesActivas.planes) {
                $('#seccion-planes').show();
            }

            // Si no hay ninguna sección activa, mostrar mensaje
            if (!seccionesActivas.competencias && !seccionesActivas.objetivos && !seccionesActivas.planes) {
                $('#sin-secciones-activas').show();
            }
        }

        function mostrarCargando() {
            const cargandoHtml = `
                <div class="py-4 text-center w-100">
                    <i class="fas fa-spinner fa-spin fa-2x"></i>
                    <p class="mt-2">Cargando...</p>
                </div>
            `;
            $("#evaluados-competencias-container").html(cargandoHtml);
            $("#evaluados-objetivos-container").html(cargandoHtml);
            $("#planes-mejora-container").html(cargandoHtml);
        }

        function actualizarSeccion(tipo, evaluaciones, stats, tipoEvaluacion) {
            // Actualizar barra de progreso
            $(`#progress-bar-${tipo}`).css("width", stats.porcentaje + "%")
                .attr("aria-valuenow", stats.porcentaje)
                .text(stats.label + " completado");

            // Generar contenido
            let html = "";
            if (evaluaciones.length > 0) {
                evaluaciones.forEach(function(row) {
                    html += generarTarjetaEvaluado(row, tipoEvaluacion);
                });
            } else {
                html = `
                    <div class="col-12">
                        <div class="alert alert-info">
                            No tienes evaluaciones ${tipo} asignadas.
                        </div>
                    </div>
                `;
            }

            $(`#evaluados-${tipo}-container`).html(html);
        }

        function actualizarPlanesMejora(planes, stats) {
            // Actualizar barra de progreso
            $("#progress-bar-planes").css("width", stats.porcentaje + "%")
                .attr("aria-valuenow", stats.porcentaje)
                .text(stats.label + " completado");

            // Generar contenido
            let html = "";
            if (planes.length > 0) {
                planes.forEach(function(plan) {
                    html += generarTarjetaPlanMejora(plan);
                });
            } else {
                html = `
                    <div class="col-12">
                        <div class="alert alert-info">
                            No tienes planes de mejora asignados.
                        </div>
                    </div>
                `;
            }

            $("#planes-mejora-container").html(html);
        }

        function generarTarjetaEvaluado(row, tipoEvaluacion) {
            const nombreCompleto = `${row.evaluado.nombres || ''} ${row.evaluado.apellido_paterno || ''} ${row.evaluado.apellido_materno || ''}`.trim();
            const nuevaRuta = RUTA_EVALUACION.replace(':tipo', tipoEvaluacion).replace(':id', row.id);
            
            let estadoBadge, estadoBtn, estadoTexto;
            if (row.cesado) {
                estadoBadge = 'badge-secondary';
                estadoBtn = 'btn-secondary disabled';
                estadoTexto = 'Cesado';
            } else if (tipoEvaluacion === 1 && row.realizado) {
                estadoBadge = 'badge-success';
                estadoBtn = 'btn-success disabled';
                estadoTexto = 'Completado';
            } else if (tipoEvaluacion === 2 && !row.estado_pendiente) {
                estadoBadge = 'badge-success';
                estadoBtn = 'btn-success disabled';
                estadoTexto = 'Completado';
            } else {
                estadoBadge = 'badge-warning';
                estadoBtn = 'btn-vanguard';
                estadoTexto = tipoEvaluacion === 2 ? 
                    (row.evaluacion.primera_fase_activa ? 'Ingresar Objetivos' : 'Ingresar Resultados') : 
                    'Pendiente de evaluar';
            }

            return `
                <div class="mb-4 col-md-3">
                    <div class="card employee-card h-100">
                        <div class="pt-3 text-center card-body">
                            <div class="mb-2 employee-avatar">
                                <i class="fas fa-user-circle fa-4x text-secondary"></i>
                            </div>
                            <h5 class="mb-0 employee-name">${nombreCompleto}</h5>
                            <p class="mb-1 text-muted small">${row.cargo_nombre_evaluado}</p>
                            ${tipoEvaluacion === 1 ? `<div class="mb-2 badge badge-light">Dominio ${row.dominio_nombre}</div>` : ''}
                            ${tipoEvaluacion === 2 ? `<div class="mb-2 badge ${estadoBadge}">Fase ${row.evaluacion.primera_fase_activa ? '1' : '2'}</div>` : ''}

                            <a href='${nuevaRuta}'
                               class="btn ${estadoBtn} btn-block btn-sm rounded-xl"
                               data-toggle="tooltip" 
                               data-placement="top" 
                               title="${estadoTexto}">
                                ${estadoTexto}
                            </a>
                        </div>
                    </div>
                </div>
            `;
        }

        function generarTarjetaPlanMejora(plan) {
            const nombreCompleto = `${plan.empleado.nombres || ''} ${plan.empleado.apellido_paterno || ''} ${plan.empleado.apellido_materno || ''}`.trim();
            const nuevaRuta = RUTA_PLANES_MEJORA.replace(':empleado_id', plan.empleado_id);
            
            let estadoBadge, estadoBtn, estadoTexto;
            
            if (!plan.habilitado) {
                estadoBadge = 'badge-secondary';
                estadoBtn = 'btn-secondary disabled';
                estadoTexto = 'Deshabilitado';
            } else if (!plan.estado_pendiente) {
                estadoBadge = 'badge-success';
                estadoBtn = 'btn-success disabled';
                estadoTexto = 'Completado';
            } else {
                estadoBadge = 'badge-warning';
                estadoBtn = 'btn-vanguard';
                estadoTexto = plan.plan_de_mejora.primera_fase_activa ? 'Dar Feedback' : 'Ingresar Seguimiento';
            }

            return `
                <div class="mb-4 col-md-3">
                    <div class="card employee-card h-100">
                        <div class="pt-3 text-center card-body">
                            <div class="mb-2 employee-avatar">
                                <i class="fas fa-user-circle fa-4x text-secondary"></i>
                            </div>
                            <h5 class="mb-0 employee-name">${nombreCompleto}</h5>
                            <p class="mb-1 text-muted small">${plan.campania_has_evaluado.puesto?.name || 'Sin cargo'}</p>
                            <div class="mb-2 badge ${estadoBadge}">Fase ${plan.plan_de_mejora.primera_fase_activa ? '1' : '2'}</div>

                            <a href='${nuevaRuta}'
                               class="btn ${estadoBtn} btn-block btn-sm rounded-xl"
                               data-toggle="tooltip" 
                               data-placement="top" 
                               title="${estadoTexto}">
                                ${estadoTexto}
                            </a>
                        </div>
                    </div>
                </div>
            `;
        }

        function mostrarError() {
            const errorHtml = `
                <div class="col-12">
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i> 
                        Error al cargar las evaluaciones. Intente nuevamente.
                    </div>
                </div>
            `;
            $("#evaluados-competencias-container").html(errorHtml);
            $("#evaluados-objetivos-container").html(errorHtml);
            $("#planes-mejora-container").html(errorHtml);
        }

        // Cargar datos al iniciar
        cargarEvaluacionesPendientes();

        // Actualizar cada 5 minutos
        setInterval(cargarEvaluacionesPendientes, 5 * 60 * 1000);
    });
</script>
@stop