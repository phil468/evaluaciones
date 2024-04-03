<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Evaluacione;

class Evaluaciones extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $eid, $title, $date, $status;
    public $updateMode = false;

    public function render()
    {
		$keyWord = '%'.$this->keyWord .'%';
        return view('livewire.evaluaciones.view', [
            'evaluaciones' => Evaluacione::latest()
						->orWhere('eid', 'LIKE', $keyWord)
						->orWhere('title', 'LIKE', $keyWord)
						->orWhere('date', 'LIKE', $keyWord)
						->orWhere('status', 'LIKE', $keyWord)
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
		$this->eid = null;
		$this->title = null;
		$this->date = null;
		$this->status = null;
    }

    public function store()
    {
        $this->validate([
            'title' => 'required',
        ]);

        Evaluacione::create([ 
			'eid' => $this-> eid,
			'title' => $this-> title,
			'date' => $this-> date,
			'status' => $this-> status
        ]);
        
        $this->resetInput();
		$this->emit('closeModal');
		session()->flash('message', 'Evaluacione creado correctamente.');
    }

    public function edit($id)
    {
        $record = Evaluacione::findOrFail($id);

        $this->selected_id = $id; 
		$this->eid = $record-> eid;
		$this->title = $record-> title;
		$this->date = $record-> date;
		$this->status = $record-> status;
		
        $this->updateMode = true;
    }

    public function update()
    {
        $this->validate([
            'title' => 'required',
        ]);

        if ($this->selected_id) {
			$record = Evaluacione::find($this->selected_id);
            $record->update([ 
			'eid' => $this-> eid,
			'title' => $this-> title,
			'date' => $this-> date,
			'status' => $this-> status
            ]);

            $this->resetInput();
            $this->updateMode = false;
		    $this->emit('closeModal');
			session()->flash('message', 'Evaluacione actualizado correctamente.');
        }
    }

    public function destroy($id)
    {
        if ($id) {
            $record = Evaluacione::where('id', $id);
            $record->delete();
        }
    }
}
