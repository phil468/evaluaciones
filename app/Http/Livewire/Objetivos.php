<?php

namespace App\Http\Livewire;

use App\Models\EvaluadorHasEvaluado;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Objetivo;
use App\Models\TiposDeObjetivo;

class Objetivos extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $resultado, $evaluado_id, $evaluador_id, $tipo_objetivo_id, $descripcion, $evidencia;
    public $updateMode = false;
    public $evaluador_has_evaluado_id, $evaluador, $evaluado;

    public function mount($evaluador_has_evaluado_id)
    {
		$this->evaluador_has_evaluado_id = $evaluador_has_evaluado_id;
        $this->evaluador = EvaluadorHasEvaluado::find($evaluador_has_evaluado_id)->evaluador()->get()->first();
        // dd($this->evaluador);
        $this->evaluado = EvaluadorHasEvaluado::find($evaluador_has_evaluado_id)->evaluado()->get()->first();
    }

    public function render()
    {
        // dd(Objetivo::latest()
        //                 ->where('evaluador_has_evaluado_id',$this->evaluador_has_evaluado_id)->get());
		$keyWord = '%'.$this->keyWord .'%';
        return view('livewire.objetivos.view', [
            'objetivos' => Objetivo::latest()
                        ->where('evaluador_has_evaluado_id',$this->evaluador_has_evaluado_id)->get(),
                        'tipos_objetivo' => TiposDeObjetivo::all(),
        ]);
    }
	
    public function cancel()
    {
        $this->resetInput();
        $this->updateMode = false;
    }
	
    private function resetInput()
    {		
		$this->resultado = null;
		$this->evaluado_id = null;
		$this->evaluador_id = null;
		$this->tipo_objetivo_id = null;
		$this->descripcion = null;
		$this->evidencia = null;
    }

	public function create() 
	{
	}

    public function store()
    {
        $this->validate([
            'descripcion' => 'required|string|max:200',
            'tipo_objetivo_id' => 'required',
        ]);

        Objetivo::create([ 
			'resultado' => $this-> resultado,
			'evaluado_id' => $this-> evaluado->id,
			'evaluador_id' => $this-> evaluador->id,
			'tipo_objetivo_id' => $this-> tipo_objetivo_id,
			'descripcion' => $this-> descripcion,
			'evidencia' => $this-> evidencia,
            'evaluador_has_evaluado_id' => $this->evaluador_has_evaluado_id,
        ]);
        
        EvaluadorHasEvaluado::find($this->evaluador_has_evaluado_id)->update(['realizado' => '1']);

        $this->resetInput();
		$this->emit('closeModal');
		session()->flash('message', 'Objetivo creado correctamente.');
    }

    public function edit($id)
    {
        $record = Objetivo::findOrFail($id);

        $this->selected_id = $id; 
		$this->resultado = $record-> resultado;
		$this->evaluado_id = $record-> evaluado_id;
		$this->evaluador_id = $record-> evaluador_id;
		$this->tipo_objetivo_id = $record-> tipo_objetivo_id;
		$this->descripcion = $record-> descripcion;
		$this->evidencia = $record-> evidencia;
		
        $this->updateMode = true;
    }

    public function update()
    {
        $this->validate([
            'descripcion' => 'required|string|max:200',
            'tipo_objetivo_id' => 'required',
        ]);

        if ($this->selected_id) {
			$record = Objetivo::find($this->selected_id);
            $record->update([ 
			'resultado' => $this-> resultado,
			'evaluado_id' => $this-> evaluado->id,
			'evaluador_id' => $this-> evaluador->id,
			'tipo_objetivo_id' => $this-> tipo_objetivo_id,
			'descripcion' => $this-> descripcion,
			'evidencia' => $this-> evidencia,
            'evaluador_has_evaluado_id' => $this->evaluador_has_evaluado_id,
            ]);

            $this->resetInput();
            $this->updateMode = false;
		    $this->emit('closeModal');
			session()->flash('message', 'Objetivo actualizado correctamente.');
        }
    }

    public function destroy($id)
    {
        if ($id) {
            $record = Objetivo::where('id', $id);
            $record->delete();
        }

        if (Objetivo::where('evaluador_has_evaluado_id',$this->evaluador_has_evaluado_id)->count() == 0) {
            EvaluadorHasEvaluado::find($this->evaluador_has_evaluado_id)->update(['realizado' => null]);
        }        
    }
}
