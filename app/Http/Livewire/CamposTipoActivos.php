<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\CamposTipoActivo;

class CamposTipoActivos extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $name, $estado;
    public $updateMode = false;

    public function render()
    {
		$keyWord = '%'.$this->keyWord .'%';
        return view('livewire.campos-tipo-activos.view', [
            'camposTipoActivos' => CamposTipoActivo::latest()
						->orWhere('name', 'LIKE', $keyWord)
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
		$this->estado = null;
    }

    public function store()
    {
        $this->validate([
        ]);

        CamposTipoActivo::create([ 
			'name' => $this-> name,
			'estado' => $this-> estado
        ]);
        
        $this->resetInput();
		$this->emit('closeModal');
		session()->flash('message', 'CamposTipoActivo creado correctamente.');
    }

    public function edit($id)
    {
        $record = CamposTipoActivo::findOrFail($id);

        $this->selected_id = $id; 
		$this->name = $record-> name;
		$this->estado = $record-> estado;
		
        $this->updateMode = true;
    }

    public function update()
    {
        $this->validate([
        ]);

        if ($this->selected_id) {
			$record = CamposTipoActivo::find($this->selected_id);
            $record->update([ 
			'name' => $this-> name,
			'estado' => $this-> estado
            ]);

            $this->resetInput();
            $this->updateMode = false;
		    $this->emit('closeModal');
			session()->flash('message', 'CamposTipoActivo actualizado correctamente.');
        }
    }

    public function destroy($id)
    {
        if ($id) {
            $record = CamposTipoActivo::where('id', $id);
            $record->delete();
        }
    }
}
