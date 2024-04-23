<div x-data="{
    edit: false,
    edited: false,
    init() {
        window.livewire.on('fieldEdited', (id) => {
            if (id === '{{ $rowId }}') {
                this.edited = true
                setTimeout(() => {
                    this.edited = false
                }, 5000)
            }
        })
    }
}" x-init="init()" :key="{{ $rowId }}">
    <button class="min-h-[28px] w-full text-left hover:bg-blue-100 px-2 py-1 -mx-2 -my-1 rounded focus:outline-none" x-bind:class="{ 'text-green-600': edited }" x-show="!edit"
        x-on:click="edit = true; $nextTick(() => { $refs.input.focus() })">{!! htmlspecialchars($value) !!}</button>
    <span x-cloak x-show="edit">
        <input type="datetime-local" class="w-full px-2 py-1 -mx-2 -my-1 bg-gray-200 border-blue-400 rounded focus:outline-none focus:border-blue-400" x-ref="input" value="{!! htmlspecialchars($value) !!}"
            wire:change="edited($event.target.value, '{{ $key }}', '{{ $column }}', '{{ $rowId }}')"
            x-on:click.away="edit = false" x-on:blur="edit = false" x-on:keydown.enter="edit = false" />
    </span>
</div>
