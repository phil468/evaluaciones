@extends('adminlte::page')

@section('title', 'Historial de Actualizaciones de Personal')

@section('content_header')
    <h1></h1>
@stop

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="rounded-lg card">
                    <div class="text-white rounded-t-lg card-header bg-vanguard h5">
                        <h3 class="card-title h3">Actualizaciones realizadas</h3>
                        <div class="card-tools">
                            <form action="{{ route('personal.historial-actualizaciones') }}" method="GET" class="form-inline">
                                <div class="input-group input-group-sm">
                                    <input type="date" name="fecha_desde" class="form-control" value="{{ request('fecha_desde') }}">
                                    <input type="date" name="fecha_hasta" class="ml-2 form-control" value="{{ request('fecha_hasta') }}">
                                    <select name="tipo" class="ml-2 form-control">
                                        <option value="">Todos los tipos</option>
                                        <option value="general" {{ request('tipo') == 'general' ? 'selected' : '' }}>General</option>
                                        <option value="individual" {{ request('tipo') == 'individual' ? 'selected' : '' }}>Individual</option>
                                    </select>
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-default">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="p-0 card-body table-responsive">
                        <table class="table table-hover text-nowrap">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Tipo</th>
                                    <th>Ejecutado por</th>
                                    <th>Resumen</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($actualizaciones as $actualizacion)
                                <tr>
                                    <td>{{ $actualizacion->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <span class="badge {{ $actualizacion->tipo == 'general' ? 'badge-primary' : 'badge-info' }}">
                                            {{ $actualizacion->tipo == 'general' ? 'General' : 'Individual' }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($actualizacion->ejecutado_por_sistema)
                                            <span class="badge badge-secondary">Sistema (Automatizado)</span>
                                        @elseif($actualizacion->usuario)
                                            {{ $actualizacion->usuario->name }}
                                        @else
                                            <span class="text-muted">Usuario eliminado</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($actualizacion->tipo == 'general')
                                            @php
                                            
                                                // Asegurarnos que $detalles sea un array
                                                $detalles = is_string($actualizacion->detalles) ? json_decode($actualizacion->detalles, true) : $actualizacion->detalles;
                                                
                                                // Verificar la estructura para acceder a los resultados
                                                $actualizacion_res = false;
                                                
                                                $estados_res = false;
                                                
                                                if (isset($detalles['resultado_actualizacion']) && isset($detalles['resultado_actualizacion']['res'])) {
                                                    $actualizacion_res = $detalles['resultado_actualizacion']['res'];
                                                }
                                                
                                                if (isset($detalles['resultado_estados']) && isset($detalles['resultado_estados']['res'])) {
                                                    $estados_res = $detalles['resultado_estados']['res'];
                                                }
                                                
                                                // $detalles = $actualizacion->detalles;
                                                // $actualizacion_res = isset($detalles['resultado_actualizacion']['res']) ? $detalles['resultado_actualizacion']['res'] : false;
                                                // $estados_res = isset($detalles['resultado_estados']['res']) ? $detalles['resultado_estados']['res'] : false;
                                            @endphp
                                            <span class="badge {{ $actualizacion_res ? 'badge-success' : 'badge-danger' }}">
                                                Actualización de Personal: {{ $actualizacion_res ? 'Éxito' : 'Error' }}
                                            </span>
                                            <span class="badge {{ $estados_res ? 'badge-success' : 'badge-danger' }}">
                                                Actualización de Cese: {{ $estados_res ? 'Éxito' : 'Error' }}
                                            </span>
                                        @elseif($actualizacion->tipo == 'individual')
                                            @php
                                                // Asegurarnos que $detalles sea un array
                                                $detalles = is_string($actualizacion->detalles) ? json_decode($actualizacion->detalles, true) : $actualizacion->detalles;
                                                // dd($detalles); // Muestra la estructura de los datos
                                                // Verificar la estructura para acceder al DNI y resultado
                                                $dni = isset($detalles['dni']) ? $detalles['dni'] :
                                                'N/A';
                                                $resultado = isset($detalles['resultado']['res']) ? $detalles['resultado']['res'] : false;
                                                // Asegurarnos que $detalles sea un array
                                                
                                                
                                                // $detalles = $actualizacion->detalles;
                                                // $dni = $detalles['dni'] ?? 'N/A';
                                                // $resultado = isset($detalles['resultado']['res']) ? $detalles['resultado']['res'] : false;
                                            @endphp
                                            <span>DNI: {{ $dni }}</span>
                                            <span class="badge {{ $resultado ? 'badge-success' : 'badge-danger' }}">
                                                {{ $resultado ? 'Éxito' : 'Error' }}
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-info ver-detalles" 
                                                data-toggle="modal" data-target="#detallesModal"
                                                data-detalles="{{ $actualizacion->detalles }}">
                                            Ver detalles
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="clearfix card-footer">
                        {{ $actualizaciones->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para detalles -->
    <div class="modal fade" id="detallesModal" tabindex="-1" role="dialog" aria-labelledby="detallesModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="text-white modal-header bg-vanguard h5">
                    <h5 class="modal-title" id="detallesModalLabel">Detalles de la actualización</h5>
                    <button type="button" class="text-white close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <pre id="detallesJson" style="max-height: 500px; overflow-y: auto;"></pre>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
    .json-viewer {
        font-family: 'Source Code Pro', monospace;
        line-height: 1.6;
        background: #f9f9f9;
        border-radius: 5px;
        padding: 15px;
    }
    .json-property {
        margin-bottom: 10px;
        border-bottom: 1px solid #eee;
        padding-bottom: 5px;
    }
    .json-property:last-child {
        border-bottom: none;
    }
    .json-property pre {
        margin-top: 5px;
        background: #f0f0f0;
        padding: 10px;
        border-radius: 4px;
        white-space: pre-wrap;
    }
    </style>
@stop


@section('js')
<script>
    $(function() {
        $('.ver-detalles').on('click', function() {
            const detallesRaw = $(this).data('detalles');
            console.log("Datos recibidos:", detallesRaw);
            
            try {
                // Parsear los datos si vienen como string
                let detalles = detallesRaw;
                if (typeof detallesRaw === 'string') {
                    detalles = JSON.parse(detallesRaw);
                }
                
                console.log("Datos procesados:", detalles);
                // Crear una representación más amigable
                let html = '<div class="json-viewer">';
                
                // Tipo de actualización
                html += '<div class="json-property"><strong>Tipo:</strong> ' + 
                        (detalles.tipo === 'individual' ? 'Individual' : 'General') + '</div>';
                
                // DNI (solo para actualizaciones individuales)
                if (detalles.dni) {
                    html += '<div class="json-property"><strong>DNI:</strong> ' + detalles.dni + '</div>';
                }
                
                // Resultado para actualizaciones individuales
                if (detalles.resultado) {
                    html += '<div class="json-property"><strong>Resultado:</strong> ' + 
                            (detalles.resultado.res ? 
                            '<span class="badge badge-success">Éxito</span>' : 
                            '<span class="badge badge-danger">Error</span>') + '</div>';
                    
                    if (detalles.resultado.message) {
                        // Reemplazar caracteres de escape y formatear el mensaje
                        let formattedMessage = detalles.resultado.message
                            .replace(/\\n/g, '<br>')  // Convertir \n en saltos de línea HTML
                            .replace(/\\\//g, '/');   // Convertir \/ en /
                            
                        html += '<div class="json-property"><strong>Mensaje:</strong> <pre>' + 
                                formattedMessage + '</pre></div>';
                    }
                }
                
                // Resultados para actualizaciones generales
                if (detalles.resultado_actualizacion) {
                    html += '<div class="json-property"><strong>Actualización Personal:</strong> ' + 
                            (detalles.resultado_actualizacion.res ? 
                            '<span class="badge badge-success">Éxito</span>' : 
                            '<span class="badge badge-danger">Error</span>') + '</div>';
                    
                    if (detalles.resultado_actualizacion.message) {
                        let formattedMessage = detalles.resultado_actualizacion.message
                            .replace(/\\n/g, '<br>')
                            .replace(/\\\//g, '/');
                            
                        html += '<div class="json-property"><strong>Mensaje:</strong> <pre>' + 
                                formattedMessage + '</pre></div>';
                    }
                }
                
                if (detalles.resultado_estados) {
                    html += '<div class="json-property"><strong>Actualización Cese:</strong> ' + 
                            (detalles.resultado_estados.res ? 
                            '<span class="badge badge-success">Éxito</span>' : 
                            '<span class="badge badge-danger">Error</span>') + '</div>';
                    
                    if (detalles.resultado_estados.message) {
                        let formattedMessage = detalles.resultado_estados.message
                            .replace(/\\n/g, '<br>')
                            .replace(/\\\//g, '/');
                            
                        html += '<div class="json-property"><strong>Mensaje:</strong> <pre>' + 
                                formattedMessage + '</pre></div>';
                    }
                }
                
                // Información de quién ejecutó la actualización
                if (detalles.ejecutado_por) {
                    html += '<div class="json-property"><strong>Ejecutado por:</strong> ID: ' + 
                            detalles.ejecutado_por + '</div>';
                }

                if (detalles.ejecutado_por_nombre) {
                    html += '<div class="json-property"><strong>Nombre del ejecutor:</strong> ' + 
                            detalles.ejecutado_por_nombre + '</div>';
                }
                
                html += '</div>';
                
                $('#detallesJson').html(html);
            } catch (e) {
                console.error("Error al procesar JSON:", e);
                $('#detallesJson').html(
                    '<div class="alert alert-warning">' +
                    '<h4>Error al procesar los datos</h4>' +
                    '<p>' + e.message + '</p>' +
                    '<pre style="max-height: 200px; overflow-y: auto;">' + detallesRaw + '</pre>' +
                    '</div>'
                );
            }
        });
    });
</script>
@stop