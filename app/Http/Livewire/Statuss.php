<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Status;

class Statuss extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $name, $color, $estado;
    public $updateMode = false;

    public function render()
    {
		$keyWord = '%'.$this->keyWord .'%';
        return view('livewire.statuses.view', [
            'statuses' => Status::latest()
						->orWhere('name', 'LIKE', $keyWord)
						->orWhere('color', 'LIKE', $keyWord)
						->orWhere('estado', 'LIKE', $keyWord)
						->paginate(10),
        ]);
    }
	
	public function create() 
	{
		$this->estado=true;
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
		$this->estado = null;
    }

    public function store()
    {
        $this->validate([
		'name' => 'required',
        ]);

        Status::create([ 
			'name' => $this-> name,
			'color' => $this-> color,
			'estado' => $this-> estado
        ]);
        
        $this->resetInput();
		$this->emit('closeModal');
		session()->flash('message', 'Estado creado correctamente.');
    }

    public function edit($id)
    {
        $record = Status::findOrFail($id);

        $this->selected_id = $id; 
		$this->name = $record-> name;
		$this->color = $record-> color;
		$this->estado = $record-> estado;
		
        $this->updateMode = true;
    }

    public function update()
    {
        $this->validate([
		'name' => 'required',
        ]);

        if ($this->selected_id) {
			$record = Status::find($this->selected_id);
            $record->update([ 
			'name' => $this-> name,
			'color' => $this-> color,
			'estado' => $this-> estado
            ]);

            $this->resetInput();
            $this->updateMode = false;
		    $this->emit('closeModal');
			session()->flash('message', 'Estado actualizado correctamente.');
        }
    }

    public function destroy($id)
    {
        if ($id) {
            $record = Status::where('id', $id);
            $record->delete();
        }
    }
}
