<?php

namespace App\Http\Livewire;

use App\Exports\AccesoriosExport;
use App\Imports\AccesoriosImport;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Accesorio;
use Maatwebsite\Excel\Facades\Excel;
use Livewire\WithFileUploads;

class Accesorios extends Component
{
    use WithPagination;
    use WithFileUploads;

	protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $name, $estado, $stock, $file;
    public $updateMode = false;

    public function render()
    {
		$keyWord = '%'.$this->keyWord .'%';
        return view('livewire.accesorios.view', [
            'accesorios' => Accesorio::latest()
						->orWhere('name', 'LIKE', $keyWord)
						->orWhere('estado', 'LIKE', $keyWord)
						->orWhere('stock', 'LIKE', $keyWord)
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
		$this->estado = null;
		$this->stock = null;
    }

    public function store()
    {
        $this->validate([
            'name' => 'required'
        ]);

        Accesorio::create([ 
			'name' => $this-> name,
			'estado' => $this-> estado,
			'stock' => $this-> stock
        ]);
        
        $this->resetInput();
		$this->emit('closeModal');
		session()->flash('message', 'Accesorio creado correctamente.');
    }

    public function edit($id)
    {
        $record = Accesorio::findOrFail($id);

        $this->selected_id = $id; 
		$this->name = $record-> name;
		$this->estado = $record-> estado;
		$this->stock = $record-> stock;
		
        $this->updateMode = true;
    }

    public function update()
    {
        $this->validate([
            'name' => 'required'
        ]);

        if ($this->selected_id) {
			$record = Accesorio::find($this->selected_id);
            $record->update([ 
			'name' => $this-> name,
			'estado' => $this-> estado,
			'stock' => $this-> stock
            ]);

            $this->resetInput();
            $this->updateMode = false;
		    $this->emit('closeModal');
			session()->flash('message', 'Accesorio actualizado correctamente.');
        }
    }

    public function destroy($id)
    {
        if ($id) {
            $record = Accesorio::where('id', $id);
            $record->delete();
        }
    }
    public function importar()
    {
            $this->validate([
                'file' => 'required|file|mimes:xls,xlsx'
    
            ]);
     
                $cs =  Excel::import(new AccesoriosImport, $this->file);
        
                $this->resetInput();                
               
                session()->flash('message', 'Accesorio importado correctamente.');
                $this->emit('closeModal');
                $this->emit('alert');
    }

    public function exportar()
    {
        return Excel::download(new AccesoriosExport, 'accesorios.xlsx');
    }
}
