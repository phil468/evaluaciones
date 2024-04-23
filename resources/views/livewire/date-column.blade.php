<button class="min-h-[28px] w-full text-left hover:bg-blue-100 px-2 py-1 -mx-2 -my-1 
rounded focus:outline-none" 
x-bind:class="{ 'text-green-600': edited }" 
x-show="!edit" 
x-on:click="edit = true; $nextTick(() => { $refs.input.focus() })">
2024-01-05 12:56:03
</button>