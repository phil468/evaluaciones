<!-- Modal -->
<div wire:ignore.self class="modal fade" id="importDataModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="rounded-2xl modal-content">
            <div class="text-white modal-header bg-vanguard rounded-t-2xl">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div class="float-left">
                        <h5 class="modal-title h6" id="updateModalLabel">Importar Evaluadores de Evaluación de Desempeño por Competencias</h5>
                    </div>
                </div>
                <button type="button" class="text-white close" data-dismiss="modal" aria-label="Close">
                    <span wire:click.prevent="cancel()" aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" enctype="multipart/form-data" wire:submit.prevent="importar"> 
                    @csrf
                    <div class="form-group">
                        <label for="file">Archivo</label>
                        <input type="file" wire:model.defer="file" class="form-control-file" id="file" >
                        @error('file') <span class="error text-danger">{{ $message }}</span> @enderror
                    </div>                    
                    <div>
                        <button wire:click="importar" wire:loading.attr="disabled" class="mb-2 btn btn-vanguard close-modal rounded-xl">Importar</button>
                    </div>
                   
                    <div class="row">
                        <div class="bg-light">
                            @if (session()->has('message_importacion_evaluadores_competencias'))
                                <div class="alert alert-success">
                                    <h4 class="alert-heading h5">Respuesta de importación</h4>
                                    {!! session('message_importacion_evaluadores_competencias') !!}
                                </div>
                            @endif
                            @if (session()->has('message_importacion_evaluadores_competencias_error'))
                                <div class="alert alert-warning">
                                    <h4 class="alert-heading h5">Respuesta de importación</h4>
                                    {!! session('message_importacion_evaluadores_competencias_error') !!}
                                </div>
                            @endif
                        </div>                     
                    </div>
                </form>
            </div>
       </div>
    </div>
</div>
