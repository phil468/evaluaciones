<div
    x-data="{ mode: 'light' }"
    x-on:dark-mode-toggled.window="mode = $event.detail"
>
    <span x-show="mode === 'light'">
        <img src="{{ asset('/img/icon/VanguardPeru-Sp-2cWeb-med.png') }}" alt="Logo" class="h-10">
    </span>
 
    <span x-show="mode === 'dark'">
        <img src="{{ asset('/img/icon/VanguardPeru-Sp-reverseWeb-med.png') }}" alt="Logo" class="h-10">

    </span>
</div>


