<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\CapacitacionHasArea;

class CapacitacionHasAreas extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $area_id, $capacitacion_id;
    public $updateMode = false;

    public function render()
    {
		$keyWord = '%'.$this->keyWord .'%';
        return view('livewire.capacitacionHasAreas.view', [
            'capacitacionHasAreas' => CapacitacionHasArea::latest()
						->orWhere('area_id', 'LIKE', $keyWord)
						->orWhere('capacitacion_id', 'LIKE', $keyWord)
						->paginate(10),
        ]);
    }
	
    public function cancel()
    {
        $this->resetInput();
        $this->updateMode = false;
    }
	
    private function resetInput()
    {		
		$this->area_id = null;
		$this->capacitacion_id = null;
    }

    public function store()
    {
        $this->validate([
		'area_id' => 'required',
		'capacitacion_id' => 'required',
        ]);

        CapacitacionHasArea::create([ 
			'area_id' => $this-> area_id,
			'capacitacion_id' => $this-> capacitacion_id
        ]);
        
        $this->resetInput();
		$this->emit('closeModal');
		session()->flash('message', 'CapacitacionHasArea creado correctamente.');
    }

    public function edit($id)
    {
        $record = CapacitacionHasArea::findOrFail($id);

        $this->selected_id = $id; 
		$this->area_id = $record-> area_id;
		$this->capacitacion_id = $record-> capacitacion_id;
		
        $this->updateMode = true;
    }

    public function update()
    {
        $this->validate([
		'area_id' => 'required',
		'capacitacion_id' => 'required',
        ]);

        if ($this->selected_id) {
			$record = CapacitacionHasArea::find($this->selected_id);
            $record->update([ 
			'area_id' => $this-> area_id,
			'capacitacion_id' => $this-> capacitacion_id
            ]);

            $this->resetInput();
            $this->updateMode = false;
		    $this->emit('closeModal');
			session()->flash('message', 'CapacitacionHasArea actualizado correctamente.');
        }
    }

    public function destroy($id)
    {
        if ($id) {
            $record = CapacitacionHasArea::where('id', $id);
            $record->delete();
        }
    }
}
