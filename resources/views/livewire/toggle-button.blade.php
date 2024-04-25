<div>
    {{-- <a href="#" wire:click="toggleUpdate({{ $rfq->id }})" id="{{ $rfq->id }}" >
        <i class="fas {{ ($this->active) ? 'fa-toggle-on' : 'fa-toggle-off' }} fa-3x"></i>
    </a> --}}
    <label class="switch">
        <input 
        {{-- onclick="confirm('¿Estás seguro?') || event.stopImmediatePropagation()"  --}}
        type="checkbox" wire:model="active">

        <span class="slider round"></span>
    </label>
    <div class="mt-1 font-italic"
    x-data="{shown: false}"
    x-show.transition.opacity.out.duration.1500ms="shown"
    x-show="shown"
    x-init="@this.on('statusUpdated', () => {
        shown = true;
        setTimeout(() => shown = false, 2000)
    })"
    style="display: none">
        Actualizado
    </div>
    <style>
        .switch {
            position: relative;
            display: inline-block;
            width: 30px;
            height: 17px;
        }
    
        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }
    
        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            -webkit-transition: .4s;
            transition: .4s,
        }
    
        .slider:before {
            position: absolute;
            content: "";
            height: 13px;
            width: 13px;
            left: 2px;
            bottom: 2px;
            background-color: white;
            -webkit-transition: .4s;
            transition: .4s,
        }
    
        input:checked+.slider {
            background-color: #6ECBC9 !important;
        }
    
        input:focus+.slider {
            box-shadow: #6ECBC9 !important;
            /* background-color: #000!important; */
        }
    
        input:checked+.slider:before {
            -webkit-transform: translateX(13px);
            -ms-transform: translateX(13px);
            transform: translateX(13px);
        }
    
        /* Rounded slider */
        .slider.round {
            border-radius: 17px;
        }
    
        .slider.round:before {
            border-radius: 50%;
        }
    
    </style>
    
</div>
