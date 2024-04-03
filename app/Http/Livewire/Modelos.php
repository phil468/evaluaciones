<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Modelo;

class Modelos extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $name, $codigo, $estado;
    public $updateMode = false;

    public function render()
    {
		$keyWord = '%'.$this->keyWord .'%';
        return view('livewire.modelos.view', [
            'modelos' => Modelo::latest()
						->orWhere('name', 'LIKE', $keyWord)
						->orWhere('codigo', 'LIKE', $keyWord)
						->orWhere('estado', 'LIKE', $keyWord)
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
		$this->name = null;
		$this->codigo = null;
		$this->estado = null;
    }

    public function store()
    {
        $this->validate([
            'name' => 'required'
        ]);

        Modelo::create([ 
			'name' => $this-> name,
			'codigo' => $this-> codigo,
			'estado' => $this-> estado
        ]);
        
        $this->resetInput();
		$this->emit('closeModal');
		session()->flash('message', 'Modelo creado correctamente.');
    }

    public function edit($id)
    {
        $record = Modelo::findOrFail($id);

        $this->selected_id = $id; 
		$this->name = $record-> name;
		$this->codigo = $record-> codigo;
		$this->estado = $record-> estado;
		
        $this->updateMode = true;
    }

    public function update()
    {
        $this->validate([
            'name' => 'required'
        ]);

        if ($this->selected_id) {
			$record = Modelo::find($this->selected_id);
            $record->update([ 
			'name' => $this-> name,
			'codigo' => $this-> codigo,
			'estado' => $this-> estado
            ]);

            $this->resetInput();
            $this->updateMode = false;
		    $this->emit('closeModal');
			session()->flash('message', 'Modelo actualizado correctamente.');
        }
    }

    public function destroy($id)
    {
        if ($id) {
            $record = Modelo::where('id', $id);
            $record->delete();
        }
    }
}
