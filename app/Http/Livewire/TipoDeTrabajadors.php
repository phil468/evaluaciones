<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\TipoDeTrabajador;

class TipoDeTrabajadors extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $idtipotrabajador_nisira, $name, $estado, $empresa_id;
    public $updateMode = false;

    public function render()
    {
		$keyWord = '%'.$this->keyWord .'%';
        return view('livewire.tipoDeTrabajadors.view', [
            'tipoDeTrabajadors' => TipoDeTrabajador::latest()
						->orWhere('idtipotrabajador_nisira', 'LIKE', $keyWord)
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
		$this->idtipotrabajador_nisira = null;
		$this->name = null;
		$this->estado = null;
		$this->empresa_id = null;
    }

    public function store()
    {
        $this->validate([
        ]);

        TipoDeTrabajador::create([ 
			'idtipotrabajador_nisira' => $this-> idtipotrabajador_nisira,
			'name' => $this-> name,
			'estado' => $this-> estado,
			'empresa_id' => $this-> empresa_id
        ]);
        
        $this->resetInput();
		$this->emit('closeModal');
		session()->flash('message', 'TipoDeTrabajador creado correctamente.');
    }

    public function edit($id)
    {
        $record = TipoDeTrabajador::findOrFail($id);

        $this->selected_id = $id; 
		$this->idtipotrabajador_nisira = $record-> idtipotrabajador_nisira;
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
			$record = TipoDeTrabajador::find($this->selected_id);
            $record->update([ 
			'idtipotrabajador_nisira' => $this-> idtipotrabajador_nisira,
			'name' => $this-> name,
			'estado' => $this-> estado,
			'empresa_id' => $this-> empresa_id
            ]);

            $this->resetInput();
            $this->updateMode = false;
		    $this->emit('closeModal');
			session()->flash('message', 'TipoDeTrabajador actualizado correctamente.');
        }
    }

    public function destroy($id)
    {
        if ($id) {
            $record = TipoDeTrabajador::where('id', $id);
            $record->delete();
        }
    }
}
