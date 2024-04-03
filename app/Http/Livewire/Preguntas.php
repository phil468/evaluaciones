<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Pregunta;

class Preguntas extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $seccion_id, $evaluacion_id, $qid, $pregunta, $tipo, $opciones, $numero_orden;
    public $updateMode = false;

    public function render()
    {
		$keyWord = '%'.$this->keyWord .'%';
        return view('livewire.preguntas.view', [
            'preguntas' => Pregunta::latest()
						->orWhere('seccion_id', 'LIKE', $keyWord)
						->orWhere('evaluacion_id', 'LIKE', $keyWord)
						->orWhere('qid', 'LIKE', $keyWord)
						->orWhere('pregunta', 'LIKE', $keyWord)
						->orWhere('tipo', 'LIKE', $keyWord)
						->orWhere('opciones', 'LIKE', $keyWord)
						->orWhere('numero_orden', 'LIKE', $keyWord)
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
		$this->seccion_id = null;
		$this->evaluacion_id = null;
		$this->qid = null;
		$this->pregunta = null;
		$this->tipo = null;
		$this->opciones = null;
		$this->numero_orden = null;
    }

    public function store()
    {
        $this->validate([
		'seccion_id' => 'required',
        ]);

        Pregunta::create([ 
			'seccion_id' => $this-> seccion_id,
			'evaluacion_id' => $this-> evaluacion_id,
			'qid' => $this-> qid,
			'pregunta' => $this-> pregunta,
			'tipo' => $this-> tipo,
			'opciones' => $this-> opciones,
			'numero_orden' => $this-> numero_orden
        ]);
        
        $this->resetInput();
		$this->emit('closeModal');
		session()->flash('message', 'Pregunta creado correctamente.');
    }

    public function edit($id)
    {
        $record = Pregunta::findOrFail($id);

        $this->selected_id = $id; 
		$this->seccion_id = $record-> seccion_id;
		$this->evaluacion_id = $record-> evaluacion_id;
		$this->qid = $record-> qid;
		$this->pregunta = $record-> pregunta;
		$this->tipo = $record-> tipo;
		$this->opciones = $record-> opciones;
		$this->numero_orden = $record-> numero_orden;
		
        $this->updateMode = true;
    }

    public function update()
    {
        $this->validate([
		'seccion_id' => 'required',
        ]);

        if ($this->selected_id) {
			$record = Pregunta::find($this->selected_id);
            $record->update([ 
			'seccion_id' => $this-> seccion_id,
			'evaluacion_id' => $this-> evaluacion_id,
			'qid' => $this-> qid,
			'pregunta' => $this-> pregunta,
			'tipo' => $this-> tipo,
			'opciones' => $this-> opciones,
			'numero_orden' => $this-> numero_orden
            ]);

            $this->resetInput();
            $this->updateMode = false;
		    $this->emit('closeModal');
			session()->flash('message', 'Pregunta actualizado correctamente.');
        }
    }

    public function destroy($id)
    {
        if ($id) {
            $record = Pregunta::where('id', $id);
            $record->delete();
        }
    }
}
