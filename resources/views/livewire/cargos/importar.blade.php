<!-- Modal -->
<div wire:ignore.self class="modal fade" id="importDataModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
       <div class="rounded-2xl modal-content">
            <div class="text-white modal-header bg-vanguard rounded-t-2xl">                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div class="float-left">
                        <h5 class="modal-title" id="updateModalLabel">Importar Cargos</h5>
                    </div>
                </div>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
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
                    <button wire:click="importar" wire:loading.attr="disabled" class="btn btn-primary close-modal">Importar</button>
                </form>
            </div>
       </div>
    </div>
</div>
