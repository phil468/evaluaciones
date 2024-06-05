<!-- Modal -->
<div wire:ignore.self class="modal fade" id="confirmarIngresoDNIModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="confirmarIngresoDNILabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
       <div class="rounded-2xl modal-content">
            <div class="text-white modal-header bg-vanguard rounded-t-2xl">                <h5 class="modal-title" id="confirmarIngresoDNILabel">Confirmar Ingreso DNI</h5>
                <button type="button" class="text-white close" data-dismiss="modal" aria-label="Close">
                    <span wire:click.prevent="cancel_no_guardar_dni()" aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <h5 class="modal-title" id="confirmarIngresoDNILabel">DNI no encontrado en la base de datos de NISIRA, ¿desea ingresarlo de todas maneras?</h5>
                <form>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" wire:click.prevent="cancel_no_guardar_dni()" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" wire:click.prevent="ingresar_dni()" class="btn btn-primary close-modal">Guardar</button>
            </div>
       </div>
    </div>
</div>
