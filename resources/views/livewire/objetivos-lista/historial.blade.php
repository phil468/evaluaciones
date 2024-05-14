<!-- Modal -->
<div wire:ignore.self class="modal fade" id="auditoriaModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="rounded-2xl modal-content">
            <div class="text-white modal-header bg-vanguard rounded-t-2xl">
                <h5 class="modal-title" id="auditoriaModalLabel">Historial</h5>
                <button type="button" class="text-white close" data-dismiss="modal" aria-label="Close">
                    <span wire:click.prevent="cancel()" aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                @isset($auditorias)
                    @if (!count($auditorias))
                        <p>No hay registros</p>
                    @else
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Evento</th>
                                <th>Antiguos Valores</th>
                                <th>Nuevos Valores</th>
                                <th>Fecha</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($auditorias as $auditoria)
                                <tr>
                                    <td>{{ $auditoria['id'] }}</td>
                                    <td>{{ $auditoria['event'] }}</td>
                                    <td>
                                        @php
                                            $objetivo = new \App\Models\Objetivo($auditoria['old_values']);
                                            $nombreTipoObjetivo = $objetivo->tipo_objetivo ? $objetivo->tipo_objetivo->name : '';
                                            $resultado = $objetivo->resultado ? $objetivo->resultado : '';
                                            $evidencia = $objetivo->evidencia ? $objetivo->evidencia : '';
                                        @endphp

                                        @if ($objetivo->descripcion)
                                            <p>Descripción: {{ $objetivo->descripcion }}</p>                                            
                                        @endif
                                        @if ($nombreTipoObjetivo)
                                            <p>Tipo de Objetivo: {{ $nombreTipoObjetivo }}</p>
                                        @endif
                                        @if ($resultado)
                                            <p>Resultado: {{ $resultado }}</p>
                                        @endif
                                        @if ($evidencia )
                                            <p>Evidencia: {{ $evidencia }}</p>  
                                        @endif
                                        @if ($objetivo->evaluado)
                                            <p>Evaluado: {{ $objetivo->evaluado->name }}</p>
                                        @endif
                                        @if ($objetivo->evaluador)
                                            <p>Evaluador: {{ $objetivo->evaluador->name }}</p>                                            
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $objetivo = new \App\Models\Objetivo($auditoria['new_values']);
                                            $nombreTipoObjetivo = $objetivo->tipo_objetivo ? $objetivo->tipo_objetivo->name : '';
                                            $resultado = $objetivo->resultado ? $objetivo->resultado : '';
                                            $evidencia = $objetivo->evidencia ? $objetivo->evidencia : '';
                                        @endphp

                                        @if ($objetivo->descripcion)
                                            <p>Descripción: {{ $objetivo->descripcion }}</p>                                            
                                        @endif
                                        @if ($nombreTipoObjetivo)
                                            <p>Tipo de Objetivo: {{ $nombreTipoObjetivo }}</p>
                                        @endif
                                        @if ($resultado)
                                            <p>Resultado: {{ $resultado }}</p>
                                        @endif
                                        @if ($evidencia )
                                            <p>Evidencia: {{ $evidencia }}</p>  
                                        @endif
                                        @if ($objetivo->evaluado)
                                            <p>Evaluado: {{ $objetivo->evaluado->name }}</p>
                                        @endif
                                        @if ($objetivo->evaluador)
                                            <p>Evaluador: {{ $objetivo->evaluador->name }}</p>                                            
                                        @endif
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($auditoria['created_at'])->timezone('America/Lima')->format('d/m/Y h:i:s A') }}</td>                            
                            @endforeach                        
                        </tbody>
                    </table>
                    @endif                    
                @endisset            
            </div>
            <div class="modal-footer">
                <button type="button" wire:click.prevent="cancel()" class="btn rounded-xl btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
       </div>
    </div>
</div>
