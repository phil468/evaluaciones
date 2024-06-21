<!-- Modal -->
<div wire:ignore.self class="modal fade" id="importObjetivosDataModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="rounded-2xl modal-content">
            <div class="text-white modal-header bg-vanguard rounded-t-2xl">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div class="float-left">
                        <h5 class="modal-title" id="updateModalLabel">Importar Evaluadores de Evaluación de Desempeño por Objetivos</h5>
                    </div>
                </div>
                <button type="button" class="text-white close" data-dismiss="modal" aria-label="Close">
                    <span wire:click.prevent="cancel()" aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" enctype="multipart/form-data" wire:submit.prevent="importar_objetivos"> 
                    @csrf
                    <div class="form-group">
                        <label for="file_objetivos">Archivo</label>
                        <input type="file" wire:model.defer="file_objetivos" class="form-control-file" id="file_objetivos" >
                        @error('file_objetivos') <span class="error text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="float-right">
                        <button wire:click="importar_objetivos" wire:loading.attr="disabled" class="btn btn-vanguard close-modal rounded-xl">Importar</button>
                    </div>
                </form>
            </div>
       </div>
    </div>
</div>
