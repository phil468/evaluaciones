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
            </div>
            
            <!-- Evaluación de Desempeño por Objetivos -->
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
            </div>
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
</style>
@stop

@section('js')
<script>
    $(function () {
        // Puedes agregar funcionalidad adicional aquí
        // Por ejemplo, tooltips o popups
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
@stop