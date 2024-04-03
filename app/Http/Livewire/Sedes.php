<?php

namespace App\Http\Livewire;

use App\Exports\SedesExport;
use App\Imports\SedesImport;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Sede;
use Maatwebsite\Excel\Facades\Excel;
use Livewire\WithFileUploads;

class Sedes extends Component
{
    use WithPagination;
    use WithFileUploads;

	protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $name, $estado, $idsucursal_nisira, $fechacreacion_nisira, $file;
    public $updateMode = false;

    public function render()
    {
		$keyWord = '%'.$this->keyWord .'%';
        return view('livewire.sedes.view', [
            'sedes' => Sede::latest()
						->orWhere('name', 'LIKE', $keyWord)
						->orWhere('estado', 'LIKE', $keyWord)
						->orWhere('idsucursal_nisira', 'LIKE', $keyWord)
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
		$this->idsucursal_nisira = null;
		$this->fechacreacion_nisira = null;
		$this->file = null;
    }

    public function store()
    {
        $this->validate([
		'name' => 'required'
        ]);

        Sede::create([ 
			'name' => $this-> name,
			'estado' => $this-> estado,
			'idsucursal_nisira' => $this-> idsucursal_nisira,
			'fechacreacion_nisira' => $this-> fechacreacion_nisira
        ]);
        
        $this->resetInput();
		$this->emit('closeModal');
		session()->flash('message', 'Sede creado correctamente.');
    }

    public function edit($id)
    {
        $record = Sede::findOrFail($id);

        $this->selected_id = $id; 
		$this->name = $record-> name;
		$this->estado = $record-> estado;
		$this->idsucursal_nisira = $record-> idsucursal_nisira;
		$this->fechacreacion_nisira = $record-> fechacreacion_nisira;
		
        $this->updateMode = true;
    }

    public function update()
    {
        $this->validate([
		'name' => 'required'
        ]);

        if ($this->selected_id) {
			$record = Sede::find($this->selected_id);
            $record->update([ 
			'name' => $this-> name,
			'estado' => $this-> estado,
			'idsucursal_nisira' => $this-> idsucursal_nisira,
			'fechacreacion_nisira' => $this-> fechacreacion_nisira
            ]);

            $this->resetInput();
            $this->updateMode = false;
		    $this->emit('closeModal');
			session()->flash('message', 'Sede actualizado correctamente.');
        }
    }

    public function destroy($id)
    {
        if ($id) {
            $record = Sede::where('id', $id);
            $record->delete();
        }
    }
    

	public function importar()
    {
            $this->validate([
                'file' => 'required|file|mimes:xls,xlsx'    
            ]);
     
                $cs =  Excel::import(new SedesImport, $this->file);
        
                $this->resetInput();                
               
                session()->flash('message', 'Sedes importado correctamente.');
                $this->emit('closeModal');
                $this->emit('alert');
    }

    public function exportar()
    {
        return Excel::download(new SedesExport, 'sedes.xlsx');
    }

}
