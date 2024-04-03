<?php

namespace App\Http\Livewire;

use App\Exports\CargosExport;
use App\Imports\CargosImport;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Cargo;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;

class Cargos extends Component
{
    use WithPagination;
    use WithFileUploads;

	protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $name, $estado, $file;
    public $updateMode = false;

    public function render()
    {
		$keyWord = '%'.$this->keyWord .'%';
        return view('livewire.cargos.view', [
            'cargos' => Cargo::latest()
						->orWhere('name', 'LIKE', $keyWord)
						->orWhere('estado', 'LIKE', $keyWord)
						->orWhere('idcargo_nisira', 'LIKE', $keyWord)
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
    }

    public function store()
    {
        $this->validate([
            'name' => 'required'
        ]);

        Cargo::create([ 
			'name' => $this-> name,
			'estado' => $this-> estado
        ]);
        
        $this->resetInput();
		$this->emit('closeModal');
		session()->flash('message', 'Cargo creado correctamente.');
    }

    public function edit($id)
    {
        $record = Cargo::findOrFail($id);

        $this->selected_id = $id; 
		$this->name = $record-> name;
		$this->estado = $record-> estado;
		
        $this->updateMode = true;
    }

    public function update()
    {
        $this->validate([
            'name' => 'required'
        ]);

        if ($this->selected_id) {
			$record = Cargo::find($this->selected_id);
            $record->update([ 
			'name' => $this-> name,
			'estado' => $this-> estado
            ]);

            $this->resetInput();
            $this->updateMode = false;
		    $this->emit('closeModal');
			session()->flash('message', 'Cargo actualizado correctamente.');
        }
    }

    public function destroy($id)
    {
        if ($id) {
            $record = Cargo::where('id', $id);
            $record->delete();
        }
    }

    public function importar()
    {
            $this->validate([
                'file' => 'required|file|mimes:xls,xlsx'
    
            ]);
     
                $cs =  Excel::import(new CargosImport, $this->file);
        
                $this->resetInput();                
               
                session()->flash('message', 'Cargo importado correctamente.');
                $this->emit('closeModal');
                $this->emit('alert');
    }

    public function exportar()
    {
        return Excel::download(new CargosExport, 'cargos.xlsx');
    }
}
