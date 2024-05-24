<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\ObjetivoHasEvidencia;

class ObjetivoHasEvidencias extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $objetivo_id, $name, $estado;
    public $updateMode = false;

    public function render()
    {
		$keyWord = '%'.$this->keyWord .'%';
        return view('livewire.objetivo-has-evidencias.view', [
            'objetivoHasEvidencias' => ObjetivoHasEvidencia::latest()
						->orWhere('objetivo_id', 'LIKE', $keyWord)
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
		$this->objetivo_id = null;
		$this->name = null;
		$this->estado = null;
    }


	public function create() 
	{
	}
    
    public function store()
    {
        $this->validate([
		'objetivo_id' => 'required',
        ]);

        ObjetivoHasEvidencia::create([ 
			'objetivo_id' => $this-> objetivo_id,
			'name' => $this-> name,
			'estado' => $this-> estado
        ]);
        
        $this->resetInput();
		$this->emit('closeModal');
		session()->flash('message', 'Objetivo Has Evidencia creado correctamente.');
    }

    public function edit($id)
    {
        $record = ObjetivoHasEvidencia::findOrFail($id);

        $this->selected_id = $id; 
		$this->objetivo_id = $record-> objetivo_id;
		$this->name = $record-> name;
		$this->estado = $record-> estado;
		
        $this->updateMode = true;
    }

    public function update()
    {
        $this->validate([
		'objetivo_id' => 'required',
        ]);

        if ($this->selected_id) {
			$record = ObjetivoHasEvidencia::find($this->selected_id);
            $record->update([ 
			'objetivo_id' => $this-> objetivo_id,
			'name' => $this-> name,
			'estado' => $this-> estado
            ]);

            $this->resetInput();
            $this->updateMode = false;
		    $this->emit('closeModal');
			session()->flash('message', 'Objetivo Has Evidencia actualizado correctamente.');
        }
    }

    public function destroy($id)
    {
        if ($id) {
            $record = ObjetivoHasEvidencia::where('id', $id);
            $record->delete();
        }
    }
}
