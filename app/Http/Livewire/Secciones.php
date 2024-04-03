<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Seccione;

class Secciones extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $name, $color;
    public $updateMode = false;

    public function render()
    {
		$keyWord = '%'.$this->keyWord .'%';
        return view('livewire.secciones.view', [
            'secciones' => Seccione::latest()
						->orWhere('name', 'LIKE', $keyWord)
						->orWhere('color', 'LIKE', $keyWord)
						->paginate(20),
        ]);
    }
	
    public function cancel()
    {
        $this->resetInput();
        $this->updateMode = false;
    }
	
    private function resetInput()
    {		
		$this->name = null;
		$this->color = null;
    }

    public function store()
    {
        $this->validate([
		'name' => 'required',
        ]);

        Seccione::create([ 
			'name' => $this-> name,
			'color' => $this-> color
        ]);
        
        $this->resetInput();
		$this->emit('closeModal');
		session()->flash('message', 'Seccione creado correctamente.');
    }

    public function edit($id)
    {
        $record = Seccione::findOrFail($id);

        $this->selected_id = $id; 
		$this->name = $record-> name;
		$this->color = $record-> color;
		
        $this->updateMode = true;
    }

    public function update()
    {
        $this->validate([
		'name' => 'required',
        ]);

        if ($this->selected_id) {
			$record = Seccione::find($this->selected_id);
            $record->update([ 
			'name' => $this-> name,
			'color' => $this-> color
            ]);

            $this->resetInput();
            $this->updateMode = false;
		    $this->emit('closeModal');
			session()->flash('message', 'Seccione actualizado correctamente.');
        }
    }

    public function destroy($id)
    {
        if ($id) {
            $record = Seccione::where('id', $id);
            $record->delete();
        }
    }
}
