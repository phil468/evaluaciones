<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Respuesta;

class SeguimientoEvaluados extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $pregunta_id, $opcion_id, $valor_numerico, $valor_texto, $evaluado_id;
    public $updateMode = false;

    public function render()
    {
		$keyWord = '%'.$this->keyWord .'%';
        return view('livewire.seguimiento-evaluados.view', [
            'respuestas' => Respuesta::latest()
						->orWhere('pregunta_id', 'LIKE', $keyWord)
						->orWhere('opcion_id', 'LIKE', $keyWord)
						->orWhere('valor_numerico', 'LIKE', $keyWord)
						->orWhere('valor_texto', 'LIKE', $keyWord)
						->orWhere('evaluado_id', 'LIKE', $keyWord)
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
		$this->opcion_id = null;
		$this->valor_numerico = null;
		$this->valor_texto = null;
		$this->evaluado_id = null;
    }

    public function store()
    {
        $this->validate([
        ]);

        Respuesta::create([ 
			'pregunta_id' => $this-> pregunta_id,
			'opcion_id' => $this-> opcion_id,
			'valor_numerico' => $this-> valor_numerico,
			'valor_texto' => $this-> valor_texto,
			'evaluado_id' => $this-> evaluado_id
        ]);
        
        $this->resetInput();
		$this->emit('closeModal');
		session()->flash('message', 'Respuesta creado correctamente.');
    }

    public function edit($id)
    {
        $record = Respuesta::findOrFail($id);

        $this->selected_id = $id; 
		$this->pregunta_id = $record-> pregunta_id;
		$this->opcion_id = $record-> opcion_id;
		$this->valor_numerico = $record-> valor_numerico;
		$this->valor_texto = $record-> valor_texto;
		$this->evaluado_id = $record-> evaluado_id;
		
        $this->updateMode = true;
    }

    public function update()
    {
        $this->validate([
        ]);

        if ($this->selected_id) {
			$record = Respuesta::find($this->selected_id);
            $record->update([ 
			'pregunta_id' => $this-> pregunta_id,
			'opcion_id' => $this-> opcion_id,
			'valor_numerico' => $this-> valor_numerico,
			'valor_texto' => $this-> valor_texto,
			'evaluado_id' => $this-> evaluado_id
            ]);

            $this->resetInput();
            $this->updateMode = false;
		    $this->emit('closeModal');
			session()->flash('message', 'Respuesta actualizado correctamente.');
        }
    }

    public function destroy($id)
    {
        if ($id) {
            $record = Respuesta::where('id', $id);
            $record->delete();
        }
    }
}
