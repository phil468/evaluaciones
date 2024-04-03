<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Planilla;
use App\Models\Sede;

class Planillas extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $name, $estado, $planilla_id, $sede_id,
	$sedes;
    public $updateMode = false;

	public function listarSelects() {
		$this->sedes 		= 	Sede::			orderBy('name')->where('estado',1)->select('name as label', 'id as value')->get()->toArray();
		
		$this->emit('listar_selects',
			$this->sedes,
		);
		$this->actualizarDatosSelect();
	}

    public function actualizarDatosSelect () {
		$this->emit('actualizarDatosSelect',
			$this->sede_id,
		);
	}

    public function render()
    {
		$keyWord = '%'.$this->keyWord .'%';
        return view('livewire.planillas.view', [
            'planillas' => Planilla::latest('planillas.created_at')
            ->select('planillas.*')
                        ->where('planillas.name', 'LIKE', $keyWord)
						// ->orWhere('planillas.name', 'LIKE', $keyWord)
						->orWhere('planillas.estado', 'LIKE', $keyWord)
                        ->orWhere('planillas.idplanilla_nisira', 'LIKE', $keyWord)
                        // ->orWhere('empresa_id', 'LIKE', $keyWord)
                        // ->orWhere('sede_id', 'LIKE', $keyWord)
                        ->leftJoin('empresas','empresas.id','=','planillas.empresa_id')
                        ->leftJoin('sedes','sedes.id','=','planillas.sede_id')
                        ->orWhere('empresas.name', 'LIKE', $keyWord)
                        ->orWhere('sedes.name', 'LIKE', $keyWord)
						->paginate(10),
        ]);
    }
	
    public function create() 
	{
		$this->estado=true;
        $this->listarSelects();
        // $this->resetInput();
        $this->updateMode = true;
	}

    public function cancel()
    {
        $this->resetInput();
		$this->emit('limpiarDatosSelect');
        $this->updateMode = false;
    }
	
    private function resetInput()
    {		
		$this->name = null;
		$this->estado = null;
        // $this->empresa_id = null;
        $this->sede_id = null;
    }

    public function store()
    {
        $this->validate([
            'name' => 'required'
        ]);

        Planilla::create([ 
			'name' => $this-> name,
			'estado' => $this-> estado,
            // 'empresa_id' => $this-> empresa_id,
            'sede_id' => $this-> sede_id,
        ]);
        
        $this->resetInput();
        $this->emit('limpiarDatosSelect');
		$this->emit('closeModal');
		session()->flash('message', 'Planilla creado correctamente.');
    }

    public function edit($id)
    {
        $record = Planilla::findOrFail($id);

        $this->selected_id = $id; 
		$this->name = $record-> name;
		$this->estado = $record-> estado;
        // $this->empresa_id = $record-> empresa_id;
        $this->sede_id = $record-> sede_id;
		
        $this->updateMode = true;
        $this->listarSelects();
    }

    public function update()
    {
        $this->validate([
            'name' => 'required'
        ]);

        if ($this->selected_id) {
			$record = Planilla::find($this->selected_id);
            $record->update([ 
			'name' => $this-> name,
			'estado' => $this-> estado,
            // 'empresa_id' => $this-> empresa_id,
            'sede_id' => $this-> sede_id,
            ]);

            $this->resetInput();
			$this->emit('limpiarDatosSelect');
            $this->updateMode = false;
		    $this->emit('closeModal');
			session()->flash('message', 'Planilla actualizado correctamente.');
        }
    }

    public function destroy($id)
    {
        if ($id) {
            $record = Planilla::where('id', $id);
            $record->delete();
        }
    }
}
