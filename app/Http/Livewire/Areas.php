<?php

namespace App\Http\Livewire;

use App\Exports\AreasExport;
use App\Imports\AreasImport;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Area;
use App\Models\Gerencia;
use App\Models\TipoArea;
use Maatwebsite\Excel\Facades\Excel;
use Livewire\WithFileUploads;

class Areas extends Component
{
    use WithPagination;
    use WithFileUploads;

	protected $paginationTheme = 'bootstrap';

    public const TIPO_AREA = [
        'AREA' => 1,
        'SUBGERENCIA' => 2,
        'GERENCIA' => 3,
        'GERENCIA CORPORATIVA' => 4,
    ];

    public $selected_id, $keyWord, $name, $estado, $idempresa_nisira, $idarea_nisira, $fechacreacion_nisira,$file, $gerencia_id;

    // NUEVOS
    public $tipo_id = 1;
    public $area_superior_id = null;

    public $updateMode = false;

    public function render()
    {
        // dd( Area::latest()
        //                 ->with(['tipo', 'superior'])
        //             ->get()->toArray()
        //             );
		$keyWord = '%'.$this->keyWord .'%';
        return view('livewire.areas.view', [
            'gerencias' 	=> Gerencia::orderBy('name')->where('estado',1)->pluck('name', 'id')->toArray(),
            'areas'         => Area::latest()
                        ->orderBy('estado', 'desc')
						->orWhere('name', 'LIKE', $keyWord)
						->orWhere('estado', 'LIKE', $keyWord)
						->orWhere('idempresa_nisira', 'LIKE', $keyWord)
						->orWhere('idarea_nisira', 'LIKE', $keyWord)
						->orWhere('fechacreacion_nisira', 'LIKE', $keyWord)
                        ->with('tipo', 'superior', 'gerencia', 'subgerencia')
						->paginate(20),
            'areasPadre' => Area::orderBy('name')->where('estado', 1)->pluck('name', 'id')->toArray(),
            'tipos' => TipoArea::orderBy('name')->pluck('name', 'id')->toArray()
        ]);
    }
	
	public function create() 
	{
		$this->estado=true;
        $this->tipo_id = 1;
		$this->area_superior_id = null;
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

        // NUEVOS
        $this->tipo_id = 1;
        $this->area_superior_id = null;
    }

    public function store()
    {
        $this->validate([
            'name' => 'required|string|max:250',
            'tipo_id' => 'required|integer',
            'area_superior_id' => 'nullable|integer|exists:areas,id',
        ]);

        Area::create([ 
			'name' => $this-> name,
			'estado' => $this-> estado,
            'tipo_id' => $this-> tipo_id,
            'area_superior_id' => $this-> area_superior_id,
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

        // NUEVOS
        $this->tipo_id = $record->tipo_id;
        $this->area_superior_id = $record->area_superior_id;
		
        $this->updateMode = true;
    }

    public function update()
    {
        $this->validate([
            'name' => 'required|string|max:250',
            'tipo_id' => 'required|integer',
            // Evita que se elija a sí mismo
            'area_superior_id' => [
                'nullable','integer','exists:areas,id',
                function ($attr, $value, $fail) {
                    if ($value && $value == $this->selected_id) {
                        $fail('El área superior no puede ser la misma área.');
                    }
                }
            ],
        ]);

        if ($this->selected_id) {
			$record = Area::find($this->selected_id);
            $record->update([ 
			'name' => $this-> name,
			'estado' => $this-> estado,
            'tipo_id' => $this->tipo_id,
            'area_superior_id' => $this->area_superior_id,
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
