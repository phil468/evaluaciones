<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card rounded-xl">
                <div class="text-white card-header bg-vanguard rounded-t-xl">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            @if ($tipo_de_evaluacion_id == 2)
                                <h5 class="h5">Evaluación de Desempeño por Resultados de personal a cargo</h5>
                                @section('title', __('Evaluación de Desempeño por Resultados'))
                            @endif
                            @if ($tipo_de_evaluacion_id == 1)
                                <h5 class="h5">EVALUACIÓN DE DESEMPEÑO POR COMPETENCIA</h5>
                                @section('title', __('Evaluación de Desempeño por Competencia'))
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="alert alert-default" role="alert">
                        No se encuentran Evaluaciones Vigentes.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>