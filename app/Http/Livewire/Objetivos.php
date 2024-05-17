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
    public $evaluador_has_evaluado_id, $evaluador, $evaluado, $evaluador_has_evaluado, $cantidad_requerida;
    public $objetivos_precargados = [];

    public function mount($evaluador_has_evaluado_id)
    {        
        $objetivos_precargados = array(
            array(
                'id' => 1,
                'nombre' => 'a-predefinidos',
                'tipo' => '1',
                'participacion' => 0.4,
                'evidencia' => NULL,
                'resultadoEsperado' => 0.3,
                'minimo' => 2,
                'maximo' => 0.8,
                'valor' => 1.2,
                'logroSTI' => NULL,
                'pesoPond' => NULL,
                'otrosCampos' => NULL,
                'otrosCampos2' => 4,
                'otrosCampos3' => NULL,
                'otrosCampos4' => NULL,
                'otrosCampos5' => NULL,
            ),
            array(
                'id' => 2,
                'nombre' => 'b-predefinidos',
                'tipo' => '1',
                'participacion' => 0.2,
                'evidencia' => NULL,
                'resultadoEsperado' => 0.9,
                'minimo' => 2,
                'maximo' => 0.8,
                'valor' => 1.2,
                'logroSTI' => NULL,
                'pesoPond' => NULL,
                'otrosCampos' => NULL,
                'otrosCampos2' => 4,
                'otrosCampos3' => NULL,
                'otrosCampos4' => NULL,
                'otrosCampos5' => NULL,
            ),
            array(
                'id' => 3,
                'nombre' => 'c-predefinidos',
                'tipo' => '1',
                'participacion' => 0.2,
                'evidencia' => NULL,
                'resultadoEsperado' => 4228420,
                'minimo' => 1,
                'maximo' => 0.8,
                'valor' => 1.2,
                'logroSTI' => NULL,
                'pesoPond' => NULL,
                'otrosCampos' => NULL,
                'otrosCampos2' => 4,
                'otrosCampos3' => NULL,
                'otrosCampos4' => NULL,
                'otrosCampos5' => NULL,
            ),
            array(
                'id' => 4,
                'nombre' => NULL,
                'tipo' => '0',
                'participacion' => 0.1,
                'evidencia' => NULL,
                'resultadoEsperado' => NULL,
                'minimo' => NULL,
                'maximo' => 0.8,
                'valor' => 1.2,
                'logroSTI' => NULL,
                'pesoPond' => NULL,
                'otrosCampos' => NULL,
                'otrosCampos2' => 4,
                'otrosCampos3' => NULL,
                'otrosCampos4' => NULL,
                'otrosCampos5' => NULL,
            ),
            array(
                'id' => 5,
                'nombre' => NULL,
                'tipo' => '0',
                'participacion' => 0.1,
                'evidencia' => NULL,
                'resultadoEsperado' => NULL,
                'minimo' => NULL,
                'maximo' => 0.8,
                'valor' => 1.2,
                'logroSTI' => NULL,
                'pesoPond' => NULL,
                'otrosCampos' => NULL,
                'otrosCampos2' => 4,
                'otrosCampos3' => NULL,
                'otrosCampos4' => NULL,
                'otrosCampos5' => NULL,
            ),
        );
        
		$this->evaluador_has_evaluado_id = $evaluador_has_evaluado_id;
		$this->evaluador_has_evaluado = EvaluadorHasEvaluado::find($evaluador_has_evaluado_id)->id;//get()->first();
        $this->evaluador = EvaluadorHasEvaluado::find($evaluador_has_evaluado_id)->evaluador()->get()->first();
        $this->evaluado = EvaluadorHasEvaluado::find($evaluador_has_evaluado_id)->evaluado()->get()->first();

        $this->cantidad_requerida = EvaluadorHasEvaluado::find($evaluador_has_evaluado_id)->cantidad_requerida;
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
        
        // $this->cantidad_requerida = EvaluadorHasEvaluado::find($evaluador_has_evaluado_id)->cantidad_requerida;

        if (Objetivo::where('evaluador_has_evaluado_id',$this->evaluador_has_evaluado_id)->count() == $this->cantidad_requerida) {
            EvaluadorHasEvaluado::find($this->evaluador_has_evaluado_id)->update(['realizado' => '1']);
        }

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

        if (Objetivo::where('evaluador_has_evaluado_id',$this->evaluador_has_evaluado_id)->count() < $this->cantidad_requerida) {
            EvaluadorHasEvaluado::find($this->evaluador_has_evaluado_id)->update(['realizado' => null]);
        }        
    }
}
