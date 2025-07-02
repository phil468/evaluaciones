@extends('adminlte::page')

@section('title', 'Evaluaciones Pendientes')

@section('content_header')
    <h1 class="font-style-class">Evaluaciones Pendientes</h1>
@stop

@section('content')
<div class="container-fluid">
    <!-- Barra de progreso general -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5>Progreso general de evaluaciones</h5>
                    <div class="progress" style="height: 25px;">
                        <div class="progress-bar bg-vanguard" role="progressbar" style="width: 65%;" 
                            aria-valuenow="65" aria-valuemin="0" aria-valuemax="100">
                            65% completado
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Evaluación de Desempeño por Competencias -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">Evaluación de Desempeño por Competencias</h3>
                    <div class="card-tools">
                        <span class="badge badge-warning">4 pendientes</span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Tarjeta de empleado 1 -->
                        <div class="col-md-6 col-lg-4">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div>
                                            <h5 class="mb-0">Carlos Rodríguez</h5>
                                            <span class="text-muted">Analista de Sistemas</span>
                                        </div>
                                        <span class="badge badge-info">ED-2024-001</span>
                                    </div>
                                    <div class="progress mb-3" style="height: 5px">
                                        <div class="progress-bar bg-primary" role="progressbar" style="width: 0%" 
                                            aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="text-sm">Fase: Pendiente de evaluar</span>
                                        <button class="btn btn-sm btn-vanguard">Evaluar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Tarjeta de empleado 2 -->
                        <div class="col-md-6 col-lg-4">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div>
                                            <h5 class="mb-0">María López</h5>
                                            <span class="text-muted">Coordinadora de RRHH</span>
                                        </div>
                                        <span class="badge badge-info">ED-2024-002</span>
                                    </div>
                                    <div class="progress mb-3" style="height: 5px">
                                        <div class="progress-bar bg-primary" role="progressbar" style="width: 30%" 
                                            aria-valuenow="30" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="text-sm">Fase: Evaluación en progreso</span>
                                        <button class="btn btn-sm btn-vanguard">Continuar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Evaluación de Desempeño por Objetivos -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card card-outline card-success">
                <div class="card-header">
                    <h3 class="card-title">Evaluación de Desempeño por Objetivos</h3>
                    <div class="card-tools">
                        <span class="badge badge-warning">3 pendientes</span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Tarjeta de empleado 1 -->
                        <div class="col-md-6 col-lg-4">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div>
                                            <h5 class="mb-0">Jorge Mendoza</h5>
                                            <span class="text-muted">Gerente Comercial</span>
                                        </div>
                                        <span class="badge badge-info">ED-2024-010</span>
                                    </div>
                                    <div class="progress mb-3" style="height: 5px">
                                        <div class="progress-bar bg-success" role="progressbar" style="width: 50%" 
                                            aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="text-sm">Fase: Ingresar resultados</span>
                                        <button class="btn btn-sm btn-vanguard">Ingresar Resultados</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Tarjeta de empleado 2 -->
                        <div class="col-md-6 col-lg-4">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div>
                                            <h5 class="mb-0">Laura Sánchez</h5>
                                            <span class="text-muted">Analista de Marketing</span>
                                        </div>
                                        <span class="badge badge-info">ED-2024-012</span>
                                    </div>
                                    <div class="progress mb-3" style="height: 5px">
                                        <div class="progress-bar bg-success" role="progressbar" style="width: 25%" 
                                            aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="text-sm">Fase: Ingresar objetivos</span>
                                        <button class="btn btn-sm btn-vanguard">Ingresar Objetivos</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Tarjeta de empleado 3 -->
                        <div class="col-md-6 col-lg-4">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div>
                                            <h5 class="mb-0">Daniel Torres</h5>
                                            <span class="text-muted">Desarrollador Senior</span>
                                        </div>
                                        <span class="badge badge-info">ED-2024-015</span>
                                    </div>
                                    <div class="progress mb-3" style="height: 5px">
                                        <div class="progress-bar bg-success" role="progressbar" style="width: 75%" 
                                            aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="text-sm">Fase: Evaluar resultados</span>
                                        <button class="btn btn-sm btn-vanguard">Evaluar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Feedback y Planes de Mejora Individual -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card card-outline card-info">
                <div class="card-header">
                    <h3 class="card-title">Feedback y Planes de Mejora Individual</h3>
                    <div class="card-tools">
                        <span class="badge badge-warning">2 pendientes</span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Tarjeta de empleado 1 -->
                        <div class="col-md-6 col-lg-4">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div>
                                            <h5 class="mb-0">Ana Gómez</h5>
                                            <span class="text-muted">Asistente Administrativo</span>
                                        </div>
                                        <span class="badge badge-info">ED-2024-008</span>
                                    </div>
                                    <div class="progress mb-3" style="height: 5px">
                                        <div class="progress-bar bg-info" role="progressbar" style="width: 90%" 
                                            aria-valuenow="90" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="text-sm">Fase: Dar feedback</span>
                                        <button class="btn btn-sm btn-vanguard">Dar Feedback</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Tarjeta de empleado 2 -->
                        <div class="col-md-6 col-lg-4">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div>
                                            <h5 class="mb-0">Ricardo Pérez</h5>
                                            <span class="text-muted">Supervisor de Operaciones</span>
                                        </div>
                                        <span class="badge badge-info">ED-2024-020</span>
                                    </div>
                                    <div class="progress mb-3" style="height: 5px">
                                        <div class="progress-bar bg-info" role="progressbar" style="width: 80%" 
                                            aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="text-sm">Fase: Ingresar plan de mejora</span>
                                        <button class="btn btn-sm btn-vanguard">Ingresar Plan</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Botones de acciones adicionales -->
    <div class="row">
        <div class="col-12 text-center">
            <button class="btn btn-secondary mr-2">
                <i class="fas fa-sync-alt mr-1"></i> Actualizar
            </button>
            <button class="btn btn-info">
                <i class="fas fa-file-export mr-1"></i> Exportar Pendientes
            </button>
        </div>
    </div>
</div>
@stop

@section('css')
<style>
    /* Estilos personalizados */
    .font-style-class {
        font-family: "poppins", sans-serif;
        font-weight: 700;
        font-style: normal;
        font-size: 2.2em;
    }

    .btn-vanguard {
        background-color: #568ca5;
        color: white;
    }

    .btn-vanguard:hover {
        background-color: #3c7286;
        color: white;
    }
    
    .card {
        border-radius: 10px;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.05);
        margin-bottom: 20px;
    }
    
    .bg-vanguard {
        background-color: #568ca5;
    }
    
    /* Añade estilos para las fases de evaluación */
    .badge-info {
        background-color: #17a2b8;
    }
    
    .badge-warning {
        background-color: #ffc107;
        color: #212529;
    }
</style>
@stop

@section('js')
{{-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Aquí puedes agregar cualquier inicialización de JS necesaria
        console.log('Vista de evaluaciones pendientes cargada!');
        
        // Ejemplo: Inicializar tooltips de Bootstrap
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
@stop