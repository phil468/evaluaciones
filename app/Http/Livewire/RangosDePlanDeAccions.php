<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\RangosDePlanDeAccion;

class RangosDePlanDeAccions extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $name, $color, $estado, $nombre_para_mostrar, $descripción, $rango_mayor;
    public $updateMode = false;

    public function render()
    {
		$keyWord = '%'.$this->keyWord .'%';
        return view('livewire.rangos-de-plan-de-accion.view', [
            'rangosDePlanDeAccions' => RangosDePlanDeAccion::latest()
						->orWhere('name', 'LIKE', $keyWord)
						->orWhere('color', 'LIKE', $keyWord)
						->orWhere('estado', 'LIKE', $keyWord)
						->orWhere('nombre_para_mostrar', 'LIKE', $keyWord)
						->orWhere('descripción', 'LIKE', $keyWord)
						->orWhere('rango_mayor', 'LIKE', $keyWord)
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
		$this->nombre_para_mostrar = null;
		$this->descripción = null;
		$this->rango_mayor = null;
    }


	public function create() 
	{
	}
    
    public function store()
    {
        $this->validate([
		'name' => 'required',
		'estado' => 'required',
		'nombre_para_mostrar' => 'required',
		'rango_mayor' => 'required',
        ]);

        RangosDePlanDeAccion::create([ 
			'name' => $this-> name,
			'color' => $this-> color,
			'estado' => $this-> estado,
			'nombre_para_mostrar' => $this-> nombre_para_mostrar,
			'descripción' => $this-> descripción,
			'rango_mayor' => $this-> rango_mayor
        ]);
        
        $this->resetInput();
		$this->emit('closeModal');
		session()->flash('message', 'Rangos De Plan De Accion creado correctamente.');
    }

    public function edit($id)
    {
        $record = RangosDePlanDeAccion::findOrFail($id);

        $this->selected_id = $id; 
		$this->name = $record-> name;
		$this->color = $record-> color;
		$this->estado = $record-> estado;
		$this->nombre_para_mostrar = $record-> nombre_para_mostrar;
		$this->descripción = $record-> descripción;
		$this->rango_mayor = $record-> rango_mayor;
		
        $this->updateMode = true;
    }

    public function update()
    {
        $this->validate([
		'name' => 'required',
		'estado' => 'required',
		'nombre_para_mostrar' => 'required',
		'rango_mayor' => 'required',
        ]);

        if ($this->selected_id) {
			$record = RangosDePlanDeAccion::find($this->selected_id);
            $record->update([ 
			'name' => $this-> name,
			'color' => $this-> color,
			'estado' => $this-> estado,
			'nombre_para_mostrar' => $this-> nombre_para_mostrar,
			'descripción' => $this-> descripción,
			'rango_mayor' => $this-> rango_mayor
            ]);

            $this->resetInput();
            $this->updateMode = false;
		    $this->emit('closeModal');
			session()->flash('message', 'Rangos De Plan De Accion actualizado correctamente.');
        }
    }

    public function destroy($id)
    {
        if ($id) {
            $record = RangosDePlanDeAccion::where('id', $id);
            $record->delete();
        }
    }
}
