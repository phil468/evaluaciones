<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Sesione;

class Sesiones extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $capacitacion_id, $numero_de_sesion, $fecha, $hora_inicio, $hora_fin;
    public $updateMode = false;

    public function render()
    {
		$keyWord = '%'.$this->keyWord .'%';
        return view('livewire.sesiones.view', [
            'sesiones' => Sesione::latest()
						->orWhere('capacitacion_id', 'LIKE', $keyWord)
						->orWhere('numero_de_sesion', 'LIKE', $keyWord)
						->orWhere('fecha', 'LIKE', $keyWord)
						->orWhere('hora_inicio', 'LIKE', $keyWord)
						->orWhere('hora_fin', 'LIKE', $keyWord)
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
		$this->capacitacion_id = null;
		$this->numero_de_sesion = null;
		$this->fecha = null;
		$this->hora_inicio = null;
		$this->hora_fin = null;
    }

    public function store()
    {
        $this->validate([
		'capacitacion_id' => 'required',
        ]);

        Sesione::create([ 
			'capacitacion_id' => $this-> capacitacion_id,
			'numero_de_sesion' => $this-> numero_de_sesion,
			'fecha' => $this-> fecha,
			'hora_inicio' => $this-> hora_inicio,
			'hora_fin' => $this-> hora_fin
        ]);
        
        $this->resetInput();
		$this->emit('closeModal');
		session()->flash('message', 'Sesione creado correctamente.');
    }

    public function edit($id)
    {
        $record = Sesione::findOrFail($id);

        $this->selected_id = $id; 
		$this->capacitacion_id = $record-> capacitacion_id;
		$this->numero_de_sesion = $record-> numero_de_sesion;
		$this->fecha = $record-> fecha;
		$this->hora_inicio = $record-> hora_inicio;
		$this->hora_fin = $record-> hora_fin;
		
        $this->updateMode = true;
    }

    public function update()
    {
        $this->validate([
		'capacitacion_id' => 'required',
        ]);

        if ($this->selected_id) {
			$record = Sesione::find($this->selected_id);
            $record->update([ 
			'capacitacion_id' => $this-> capacitacion_id,
			'numero_de_sesion' => $this-> numero_de_sesion,
			'fecha' => $this-> fecha,
			'hora_inicio' => $this-> hora_inicio,
			'hora_fin' => $this-> hora_fin
            ]);

            $this->resetInput();
            $this->updateMode = false;
		    $this->emit('closeModal');
			session()->flash('message', 'Sesione actualizado correctamente.');
        }
    }

    public function destroy($id)
    {
        if ($id) {
            $record = Sesione::where('id', $id);
            $record->delete();
        }
    }
}
