{{-- filepath: c:\laragon\www\evaluaciones\resources\views\components\validacion-buttons.blade.php --}}
<div class="flex space-x-2">
    <button wire:click="abrirModalValidacion({{ $planId }}, 'validado')"
            class="px-3 py-1 text-xs font-medium text-white bg-green-600 rounded hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
        <i class="fas fa-check"></i> Validar
    </button>
    <button wire:click="abrirModalValidacion({{ $planId }}, 'no_validado')"
            class="px-3 py-1 text-xs font-medium text-white bg-red-600 rounded hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
        <i class="fas fa-times"></i> Rechazar
    </button>
    
    {{-- @if($showModalValidacion)
        {{ dd("mostrar validacion") }}
    @endif --}}
</div>