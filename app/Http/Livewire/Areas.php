<?php

namespace App\Http\Livewire;

use App\Exports\AreasExport;
use App\Imports\AreasImport;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Area;
use App\Models\Gerencia;
use Maatwebsite\Excel\Facades\Excel;
use Livewire\WithFileUploads;

class Areas extends Component
{
    use WithPagination;
    use WithFileUploads;

	protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $name, $estado, $idempresa_nisira, $idarea_nisira, $fechacreacion_nisira,$file, $gerencia_id;
    public $updateMode = false;

    public function render()
    {
		$keyWord = '%'.$this->keyWord .'%';
        return view('livewire.areas.view', [
            'gerencias' 	=> Gerencia::orderBy('name')->where('estado',1)->pluck('name', 'id')->toArray(),
            'areas'         => Area::latest()
						->orWhere('name', 'LIKE', $keyWord)
						->orWhere('estado', 'LIKE', $keyWord)
						->orWhere('idempresa_nisira', 'LIKE', $keyWord)
						->orWhere('idarea_nisira', 'LIKE', $keyWord)
						->orWhere('fechacreacion_nisira', 'LIKE', $keyWord)
						->paginate(10),
        ]);
    }
	
	public function create() 
	{
		$this->estado=true;
	}
    
    public function cancel()
    {
        $this->resetInput();
        $this->updateMode = false;
    }
	
    private function resetInput()
    {		
		$this->name = null;
		$this->estado = null;
		$this->gerencia_id = null;
		$this->idempresa_nisira = null;
		$this->idarea_nisira = null;
		$this->fechacreacion_nisira = null;
		$this->file = null;
    }

    public function store()
    {
        $this->validate([
            'name' => 'required'
        ]);

        Area::create([ 
			'name' => $this-> name,
			'estado' => $this-> estado,
			'gerencia_id' => $this-> gerencia_id,
			'idempresa_nisira' => $this-> idempresa_nisira,
			'idarea_nisira' => $this-> idarea_nisira,
			'fechacreacion_nisira' => $this-> fechacreacion_nisira
        ]);
        
        $this->resetInput();
		$this->emit('closeModal');
		session()->flash('message', 'Area creado correctamente.');
    }

    public function edit($id)
    {
        $record = Area::findOrFail($id);

        $this->selected_id = $id; 
		$this->name = $record-> name;
		$this->estado = $record-> estado;
		$this->gerencia_id = $record-> gerencia_id;
		$this->idempresa_nisira = $record-> idempresa_nisira;
		$this->idarea_nisira = $record-> idarea_nisira;
		$this->fechacreacion_nisira = $record-> fechacreacion_nisira;
		
        $this->updateMode = true;
    }

    public function update()
    {
        $this->validate([
            'name' => 'required'
        ]);

        if ($this->selected_id) {
			$record = Area::find($this->selected_id);
            $record->update([ 
			'name' => $this-> name,
			'estado' => $this-> estado,
			'gerencia_id' => $this-> gerencia_id,
			'idempresa_nisira' => $this-> idempresa_nisira,
			'idarea_nisira' => $this-> idarea_nisira,
			'fechacreacion_nisira' => $this-> fechacreacion_nisira
            ]);

            $this->resetInput();
            $this->updateMode = false;
		    $this->emit('closeModal');
			session()->flash('message', 'Area actualizado correctamente.');
        }
    }

    public function destroy($id)
    {
        if ($id) {
            $record = Area::where('id', $id);
            $record->delete();
        }
    }
        
    public function importar()
    {
            $this->validate([
                'file' => 'required|file|mimes:xls,xlsx'
    
            ]);
     
                $cs =  Excel::import(new AreasImport, $this->file);
        
                $this->resetInput();                
               
                session()->flash('message', 'Área importado correctamente.');
                $this->emit('closeModal');
                $this->emit('alert');
    }

    public function exportar()
    {
        return Excel::download(new AreasExport, 'area.xlsx');
    }
}
