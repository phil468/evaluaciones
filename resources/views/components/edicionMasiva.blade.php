<div class="mb-4">
    <button class="btn btn-outline-vanguard" wire:click="edicionMasiva"
    @if (count($selected) < 1)
        disabled
    @endif
    >
        Edición Masiva
    </button>    
</div>