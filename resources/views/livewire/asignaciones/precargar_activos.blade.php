<!-- Modal -->
<div wire:ignore.self class="modal fade" id="seleccionarActivoModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="seleccionarActivoModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
       <div class="rounded-2xl modal-content">
            <div class="text-white modal-header bg-vanguard rounded-t-2xl">                <h5 class="modal-title" id="updateModalLabel">Seleccionar Activo</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span wire:click.prevent="cancel_seleccionar()" aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover table-sm">
                        <thead style="{z-index: 1 ;}">
                            <tr>
                                <th>Opc.</th>
                                <th>Activo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($activos_precargados)
                                @foreach ($activos_precargados as $index => $row)
                                        <tr>
                                            <td width="45">
                                                <div class="btn-group">
                                                    <a wire:click="seleccionar_activo({{$row['id']}})" 
                                                        type="button" class="btn btn-sm btn-default" title="Seleccionar activo"
                                                        >
                                                        <i class="far fa-check-square fa-lg"></i>
                                                    </a>
                                                </div>
                                            </td>
                                            <td>
                                                {!! $row['descripcion'] !!}
                                            </td>
                                        </tr>
                                @endforeach
                            @endif
                        </tbody>

                    </table>
                </div>
            </div>
            {{-- <div class="modal-footer">
                <button type="button" wire:click.prevent="cancel_activo()" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" wire:click.prevent="update_activo({{$selected_activo_index}})" class="btn btn-primary close-modal">Guardar</button>
            </div> --}}
       </div>
    </div>
</div>
