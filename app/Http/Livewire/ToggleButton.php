<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Database\Eloquent\Model;

class ToggleButton extends Component
{
    public Model $model;
    public string $field;
    public bool $active;

    public function mount()
    {
        $this->active = (bool) $this->model->getAttribute($this->field);
    }

    public function updating($field, $value)
    {
        // dd($value, $this->field);
        if(!empty($field)){
            $this->model->setAttribute($this->field, $value)->save();
            $this->emit('statusUpdated'); // this emit is used for show status changed message for this import or use cdn of Alpine Js also
        }
    }

    /* This below commented function is not working so don't use it*/
    // public function toggleUpdate($id)
    // {
    //     // dd($this->active);
    //     $rowValue = $this->model::findOrFail($id);
    //     $value = ($rowValue === true) ? false : true;
    //     $rowValue->update([
    //         $this->field = $value,
    //     ]);
    //     dd($value);
    // }

    public function render()
    {
        return view('livewire.toggle-button');
    }
}
