<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Opcione;

class Opciones extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $pregunta_id, $opcion, $valor, $optionid;
    public $updateMode = false;

    public function render()
    {
		$keyWord = '%'.$this->keyWord .'%';
        return view('livewire.opciones.view', [
            'opciones' => Opcione::latest()
						->orWhere('pregunta_id', 'LIKE', $keyWord)
						->orWhere('opcion', 'LIKE', $keyWord)
						->orWhere('valor', 'LIKE', $keyWord)
						->orWhere('optionid', 'LIKE', $keyWord)
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
		$this->pregunta_id = null;
		$this->opcion = null;
		$this->valor = null;
		$this->optionid = null;
    }

    public function store()
    {
        $this->validate([
		'pregunta_id' => 'required',
		'opcion' => 'required',
		'valor' => 'required',
		'optionid' => 'required',
        ]);

        Opcione::create([ 
			'pregunta_id' => $this-> pregunta_id,
			'opcion' => $this-> opcion,
			'valor' => $this-> valor,
			'optionid' => $this-> optionid
        ]);
        
        $this->resetInput();
		$this->emit('closeModal');
		session()->flash('message', 'Opcione creado correctamente.');
    }

    public function edit($id)
    {
        $record = Opcione::findOrFail($id);

        $this->selected_id = $id; 
		$this->pregunta_id = $record-> pregunta_id;
		$this->opcion = $record-> opcion;
		$this->valor = $record-> valor;
		$this->optionid = $record-> optionid;
		
        $this->updateMode = true;
    }

    public function update()
    {
        $this->validate([
		'pregunta_id' => 'required',
		'opcion' => 'required',
		'valor' => 'required',
		'optionid' => 'required',
        ]);

        if ($this->selected_id) {
			$record = Opcione::find($this->selected_id);
            $record->update([ 
			'pregunta_id' => $this-> pregunta_id,
			'opcion' => $this-> opcion,
			'valor' => $this-> valor,
			'optionid' => $this-> optionid
            ]);

            $this->resetInput();
            $this->updateMode = false;
		    $this->emit('closeModal');
			session()->flash('message', 'Opcione actualizado correctamente.');
        }
    }

    public function destroy($id)
    {
        if ($id) {
            $record = Opcione::where('id', $id);
            $record->delete();
        }
    }
}
