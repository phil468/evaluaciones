<?php

namespace App\Http\Livewire;

use App\Models\Area;
use App\Models\Competencia;
use App\Models\EstadosDePlanDeAccion;
use App\Models\Gerencia;
use App\Models\Personal;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\PlanesDeAccion;
use App\Models\Proceso;
use App\Models\TipoDeProceso;

class PlanesDeAccions extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $encargado_id, $empleado_id, $competencia_id, $tipo_de_proceso_id, $proceso_id, $fecha_de_revision, $estado_id, $gerencia_id, $area_id, $avance, $name, $nombre_de_proceso_id;
    public $updateMode = false;

	public $competencias ;
	public $procesos 	;
	public $estados 		;
	public $gerencias 	;
	public $areas 		;
	public $personals 	;
	
	public function mount($encargado_id=null,$empleado_id=null,$competencia_id=null,$tipo_de_proceso_id=null,$nombre_de_proceso_id=null)
	{
		$this->encargado_id = $encargado_id;
		$this->empleado_id = $empleado_id;
		$this->competencia_id = $competencia_id;
		$this->tipo_de_proceso_id = $tipo_de_proceso_id;
		$this->nombre_de_proceso_id = $nombre_de_proceso_id;

		//$this->tipos_de_procesos => TipoDeProceso::orderBy('name','asc')->where('estado',1)->pluck('name','id'),
			$this->competencias 	= Competencia::orderBy('name','asc')->where('estado',1)->pluck('name','id');
			$this->procesos 		= Proceso::orderBy('name','asc')->where('estado',1)->pluck('name','id');
			$this->estados 			= EstadosDePlanDeAccion::orderBy('name','asc')->where('estado',1)->pluck('name','id');
			$this->gerencias 		= Gerencia::orderBy('name','asc')->where('estado',1)->pluck('name','id');
			$this->areas 			= Area::orderBy('name','asc')->where('estado',1)->pluck('name','id');
			$this->personals 		= Personal::orderBy('name','asc')->where('estado',1)->pluck('name','id');
	}

    public function render()
    {
		// dd(Proceso::orderBy('name','desc')->where('estado',1)->pluck('name','id'));

		$keyWord = '%'.$this->keyWord .'%';
        return view('livewire.planes-de-accion.view', [
            'planesDeAccions' => PlanesDeAccion::latest()
						->when($this->encargado_id, function ($query, $encargado_id) {
							return $query->where('encargado_id', $encargado_id);
						})
						->when($this->empleado_id, function ($query, $empleado_id) {
							return $query->where('empleado_id', $empleado_id);
						})						
						->orWhere('encargado_id', 'LIKE', $keyWord)
						->orWhere('empleado_id', 'LIKE', $keyWord)
						->orWhere('competencia_id', 'LIKE', $keyWord)
						->orWhere('tipo_de_proceso_id', 'LIKE', $keyWord)
						->orWhere('proceso_id', 'LIKE', $keyWord)
						->orWhere('fecha_de_revision', 'LIKE', $keyWord)
						->orWhere('estado_id', 'LIKE', $keyWord)
						->orWhere('gerencia_id', 'LIKE', $keyWord)
						->orWhere('area_id', 'LIKE', $keyWord)
						->orWhere('avance', 'LIKE', $keyWord)
						->orWhere('name', 'LIKE', $keyWord)
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
		$this->encargado_id = null;
		$this->empleado_id = null;
		$this->competencia_id = null;
		$this->tipo_de_proceso_id = null;
		$this->proceso_id = null;
		$this->fecha_de_revision = null;
		$this->estado_id = null;
		$this->gerencia_id = null;
		$this->area_id = null;
		$this->avance = null;
		$this->name = null;
    }

    public function store()
    {
        $this->validate([
			'name' => 'required',
			'encargado_id' => 'required',
			'empleado_id' => 'required',
			'competencia_id' => 'required',
			// 'tipo_de_proceso_id' => 'required',
			'proceso_id' => 'required',
			'estado_id' => 'required',
			// 'gerencia_id' => 'required',
			// 'area_id' => 'required',
			'avance' => 'required',
			]);

        PlanesDeAccion::create([ 
			'encargado_id' => $this-> encargado_id,
			'empleado_id' => $this-> empleado_id,
			'competencia_id' => $this-> competencia_id,
			'tipo_de_proceso_id' => $this-> tipo_de_proceso_id,
			'proceso_id' => $this-> proceso_id,
			'fecha_de_revision' => $this-> fecha_de_revision,
			'estado_id' => $this-> estado_id,
			'gerencia_id' => $this-> gerencia_id,
			'area_id' => $this-> area_id,
			'avance' => $this-> avance,
			'name' => $this-> name
        ]);
        
        $this->resetInput();
		$this->emit('closeModal');
		session()->flash('message', 'Planes De Mejora creado correctamente.');
    }

    public function edit($id)
    {
        $record = PlanesDeAccion::findOrFail($id);

        $this->selected_id = $id; 
		$this->encargado_id = $record-> encargado_id;
		$this->empleado_id = $record-> empleado_id;
		$this->competencia_id = $record-> competencia_id;
		$this->tipo_de_proceso_id = $record-> tipo_de_proceso_id;
		$this->proceso_id = $record-> proceso_id;
		$this->fecha_de_revision = $record-> fecha_de_revision;
		$this->estado_id = $record-> estado_id;
		$this->gerencia_id = $record-> gerencia_id;
		$this->area_id = $record-> area_id;
		$this->avance = $record-> avance;
		$this->name = $record-> name;
		
        $this->updateMode = true;
    }

    public function update()
    {
        $this->validate([
			'name' => 'required',
			'encargado_id' => 'required',
			'empleado_id' => 'required',
			'competencia_id' => 'required',
			// 'tipo_de_proceso_id' => 'required',
			'proceso_id' => 'required',
			'estado_id' => 'required',
			// 'gerencia_id' => 'required',
			// 'area_id' => 'required',
			'avance' => 'required',
			]);

        if ($this->selected_id) {
			$record = PlanesDeAccion::find($this->selected_id);
            $record->update([ 
			'encargado_id' => $this-> encargado_id,
			'empleado_id' => $this-> empleado_id,
			'competencia_id' => $this-> competencia_id,
			'tipo_de_proceso_id' => $this-> tipo_de_proceso_id,
			'proceso_id' => $this-> proceso_id,
			'fecha_de_revision' => $this-> fecha_de_revision,
			'estado_id' => $this-> estado_id,
			'gerencia_id' => $this-> gerencia_id,
			'area_id' => $this-> area_id,
			'avance' => $this-> avance,
			'name' => $this-> name
            ]);

            $this->resetInput();
            $this->updateMode = false;
		    $this->emit('closeModal');
			session()->flash('message', 'Planes De Mejora actualizado correctamente.');
        }
    }

    public function destroy($id)
    {
        if ($id) {
            $record = PlanesDeAccion::where('id', $id);
            $record->delete();
        }
    }
}
