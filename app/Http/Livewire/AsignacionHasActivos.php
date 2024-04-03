<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\AsignacionHasActivo;

class AsignacionHasActivos extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $activo_id, $asignacion_id, $accesorios_entregados, $accesorios_devueltos, $performance_id, $vigencia_id, $fecha_de_vigencia, $devuelto, $fecha_de_devolucion, $observaciones;
    public $updateMode = false;

    public function render()
    {
		$keyWord = '%'.$this->keyWord .'%';
        return view('livewire.asignacionHasActivos.view', [
            'asignacionHasActivos' => AsignacionHasActivo::latest()
						->orWhere('activo_id', 'LIKE', $keyWord)
						->orWhere('asignacion_id', 'LIKE', $keyWord)
						->orWhere('accesorios_entregados', 'LIKE', $keyWord)
						->orWhere('accesorios_devueltos', 'LIKE', $keyWord)
						->orWhere('performance_id', 'LIKE', $keyWord)
						->orWhere('vigencia_id', 'LIKE', $keyWord)
						->orWhere('fecha_de_vigencia', 'LIKE', $keyWord)
						->orWhere('devuelto', 'LIKE', $keyWord)
						->orWhere('fecha_de_devolucion', 'LIKE', $keyWord)
						->orWhere('observaciones', 'LIKE', $keyWord)
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
		$this->activo_id = null;
		$this->asignacion_id = null;
		$this->accesorios_entregados = null;
		$this->accesorios_devueltos = null;
		$this->performance_id = null;
		$this->vigencia_id = null;
		$this->fecha_de_vigencia = null;
		$this->devuelto = null;
		$this->fecha_de_devolucion = null;
		$this->observaciones = null;
    }

    public function store()
    {
        $this->validate([
		'activo_id' => 'required',
		'asignacion_id' => 'required',
        ]);

        AsignacionHasActivo::create([ 
			'activo_id' => $this-> activo_id,
			'asignacion_id' => $this-> asignacion_id,
			'accesorios_entregados' => $this-> accesorios_entregados,
			'accesorios_devueltos' => $this-> accesorios_devueltos,
			'performance_id' => $this-> performance_id,
			'vigencia_id' => $this-> vigencia_id,
			'fecha_de_vigencia' => $this-> fecha_de_vigencia,
			'devuelto' => $this-> devuelto,
			'fecha_de_devolucion' => $this-> fecha_de_devolucion,
			'observaciones' => $this-> observaciones
        ]);
        
        $this->resetInput();
		$this->emit('closeModal');
		session()->flash('message', 'AsignacionHasActivo creado correctamente.');
    }

    public function edit($id)
    {
        $record = AsignacionHasActivo::findOrFail($id);

        $this->selected_id = $id; 
		$this->activo_id = $record-> activo_id;
		$this->asignacion_id = $record-> asignacion_id;
		$this->accesorios_entregados = $record-> accesorios_entregados;
		$this->accesorios_devueltos = $record-> accesorios_devueltos;
		$this->performance_id = $record-> performance_id;
		$this->vigencia_id = $record-> vigencia_id;
		$this->fecha_de_vigencia = $record-> fecha_de_vigencia;
		$this->devuelto = $record-> devuelto;
		$this->fecha_de_devolucion = $record-> fecha_de_devolucion;
		$this->observaciones = $record-> observaciones;
		
        $this->updateMode = true;
    }

    public function update()
    {
        $this->validate([
		'activo_id' => 'required',
		'asignacion_id' => 'required',
        ]);

        if ($this->selected_id) {
			$record = AsignacionHasActivo::find($this->selected_id);
            $record->update([ 
			'activo_id' => $this-> activo_id,
			'asignacion_id' => $this-> asignacion_id,
			'accesorios_entregados' => $this-> accesorios_entregados,
			'accesorios_devueltos' => $this-> accesorios_devueltos,
			'performance_id' => $this-> performance_id,
			'vigencia_id' => $this-> vigencia_id,
			'fecha_de_vigencia' => $this-> fecha_de_vigencia,
			'devuelto' => $this-> devuelto,
			'fecha_de_devolucion' => $this-> fecha_de_devolucion,
			'observaciones' => $this-> observaciones
            ]);

            $this->resetInput();
            $this->updateMode = false;
		    $this->emit('closeModal');
			session()->flash('message', 'AsignacionHasActivo actualizado correctamente.');
        }
    }

    public function destroy($id)
    {
        if ($id) {
            $record = AsignacionHasActivo::where('id', $id);
            $record->delete();
        }
    }
}
