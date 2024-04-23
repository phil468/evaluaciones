
<div x-data="{ value: @entangle('value') }" :key="{{ $rowId }}">
    <label class="flex items-center cursor-pointer">
        <div class="relative">
            <input type="checkbox" class="hidden" x-model="value" wire:change="edited($event.target.checked, '{{ $key }}', '{{ $column }}', '{{ $rowId }}')">
            <div class="w-10 h-4 bg-gray-400 rounded-full shadow-inner toggle__line"></div>
            <div class="absolute inset-y-0 left-0 w-6 h-6 rounded-full shadow toggle__dot {{ $value ? 'bg-green-400' : 'bg-gray-400' }}" style="
            {{ $value ? 'transform: translateX(100%);' : '' }}
            
            "></div>
        </div>
        <div class="ml-3 font-medium text-gray-700">
            {!! htmlspecialchars($value) !!}
        </div>
    </label>
</div>

<style>
    .toggle__dot {
        top: -.25rem;
        left: -.25rem;
        transition: all 0.3s ease-in-out;
    }

    input:checked ~ .toggle__dot {
        /* transform: translateX(100%); */
    }
</style>