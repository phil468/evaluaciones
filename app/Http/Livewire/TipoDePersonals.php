<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\TipoDePersonal;

class TipoDePersonals extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $idtipopersonal_nisira, $name, $estado, $empresa_id;
    public $updateMode = false;

    public function render()
    {
		$keyWord = '%'.$this->keyWord .'%';
        return view('livewire.tipoDePersonals.view', [
            'tipoDePersonals' => TipoDePersonal::latest()
						->orWhere('idtipopersonal_nisira', 'LIKE', $keyWord)
						->orWhere('name', 'LIKE', $keyWord)
						->orWhere('estado', 'LIKE', $keyWord)
						->orWhere('empresa_id', 'LIKE', $keyWord)
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
		$this->idtipopersonal_nisira = null;
		$this->name = null;
		$this->estado = null;
		$this->empresa_id = null;
    }

    public function store()
    {
        $this->validate([
        ]);

        TipoDePersonal::create([ 
			'idtipopersonal_nisira' => $this-> idtipopersonal_nisira,
			'name' => $this-> name,
			'estado' => $this-> estado,
			'empresa_id' => $this-> empresa_id
        ]);
        
        $this->resetInput();
		$this->emit('closeModal');
		session()->flash('message', 'TipoDePersonal creado correctamente.');
    }

    public function edit($id)
    {
        $record = TipoDePersonal::findOrFail($id);

        $this->selected_id = $id; 
		$this->idtipopersonal_nisira = $record-> idtipopersonal_nisira;
		$this->name = $record-> name;
		$this->estado = $record-> estado;
		$this->empresa_id = $record-> empresa_id;
		
        $this->updateMode = true;
    }

    public function update()
    {
        $this->validate([
        ]);

        if ($this->selected_id) {
			$record = TipoDePersonal::find($this->selected_id);
            $record->update([ 
			'idtipopersonal_nisira' => $this-> idtipopersonal_nisira,
			'name' => $this-> name,
			'estado' => $this-> estado,
			'empresa_id' => $this-> empresa_id
            ]);

            $this->resetInput();
            $this->updateMode = false;
		    $this->emit('closeModal');
			session()->flash('message', 'TipoDePersonal actualizado correctamente.');
        }
    }

    public function destroy($id)
    {
        if ($id) {
            $record = TipoDePersonal::where('id', $id);
            $record->delete();
        }
    }
}
