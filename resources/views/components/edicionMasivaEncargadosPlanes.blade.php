<div class="mb-4">
    <button class="btn btn-outline-vanguard" wire:click="edicionMasiva" data-toggle="modal" data-target="#updateEncargadosPlanesModal"
    @if (count($selected) < 1)
        disabled
    @endif
    >
        Edición Masiva
    @if (count($selected) != 0)
        <span class="badge badge-dark">{{count($selected)}}</span>
    @endif
    </button>    
</div>