<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\EstadosDePlanDeAccion;

class EstadosDePlanDeAccions extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $name, $color, $estado;
    public $updateMode = false;

    public function render()
    {
		$keyWord = '%'.$this->keyWord .'%';
        return view('livewire.estados-de-plan-de-accion.view', [
            'estadosDePlanDeAccions' => EstadosDePlanDeAccion::latest()
						->orWhere('name', 'LIKE', $keyWord)
						->orWhere('color', 'LIKE', $keyWord)
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
		$this->color = null;
		$this->estado = null;
    }

    public function store()
    {
        $this->validate([
		'name' => 'required',
		'estado' => 'required',
        ]);

        EstadosDePlanDeAccion::create([ 
			'name' => $this-> name,
			'color' => $this-> color,
			'estado' => $this-> estado
        ]);
        
        $this->resetInput();
		$this->emit('closeModal');
		session()->flash('message', 'Estados De Plan De Accion creado correctamente.');
    }

    public function edit($id)
    {
        $record = EstadosDePlanDeAccion::findOrFail($id);

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
		'estado' => 'required',
        ]);

        if ($this->selected_id) {
			$record = EstadosDePlanDeAccion::find($this->selected_id);
            $record->update([ 
			'name' => $this-> name,
			'color' => $this-> color,
			'estado' => $this-> estado
            ]);

            $this->resetInput();
            $this->updateMode = false;
		    $this->emit('closeModal');
			session()->flash('message', 'Estados De Plan De Accion actualizado correctamente.');
        }
    }

    public function destroy($id)
    {
        if ($id) {
            $record = EstadosDePlanDeAccion::where('id', $id);
            $record->delete();
        }
    }
}
