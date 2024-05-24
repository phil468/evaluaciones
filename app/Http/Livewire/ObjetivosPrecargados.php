<?php

namespace App\Http\Livewire;

use App\Models\Evaluacione;
use App\Models\Objetivo;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\ObjetivosPrecargado;
use App\Models\TiposDeObjetivo;

class ObjetivosPrecargados extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $meta, $grupal, $porcentaje_de_participacion, $evidencias, $resultado_anterior_o_esperado, $tipo_objetivo_id, $minimo, $maximo, $valor, $porcentaje_de_logro_STI, $peso_ponderado, $evaluacion_id, $simbolo;
    public $updateMode = false;
	public $tipos_objetivo=[];
	public $evaluaciones=[];

	protected $rules = 
	[
		'grupal' => 'required',
		'meta' => 'required_if:grupal,1|max:500',
		'tipo_objetivo_id' => 'required_if:grupal,1',
		'resultado_anterior_o_esperado' => 'required_if:grupal,1',
		'porcentaje_de_participacion' => 'required|numeric|between:0,100',
		'minimo'=>'required|numeric|lt:maximo|gt:0',
		'maximo'=>'required|numeric|gt:minimo|',
		'evaluacion_id' => 'required',
	];

	protected $validationAttributes = 
	[
		'grupal' => 'Objetivo grupal',
		'meta' => 'Meta',
		'tipo_objetivo_id' => 'Tipo de objetivo',
		'resultado_anterior_o_esperado' => 'Resultado anterior o esperado',
		'porcentaje_de_participacion' => 'Porcentaje de participación',
		'minimo'=>'Mínimo',
		'maximo'=>'Máximo',
		'evaluacion_id' => 'Evaluación',
	];

	protected $messages = [
		'meta.required_if' => 'El campo Meta es obligatorio cuando Objetivo grupal es SÍ.',
		'tipo_objetivo_id.required_if' => 'El campo Tipo de objetivo es obligatorio cuando Objetivo grupal es SÍ.',
		'resultado_anterior_o_esperado.required_if' => 'El campo Resultado anterior o esperado es obligatorio cuando Objetivo grupal es SÍ.',
	];

	// public function updated($meta)
    // {
    //     $this->validateOnly($meta);
    // }

	public function mount() {
		$this->tipos_objetivo = TiposDeObjetivo::all();
		$this->evaluaciones = Evaluacione::evaluacionPorObjetivos()->activa()->get();
	}

    public function render() {
		$keyWord = '%'.$this->keyWord .'%';
        return view('livewire.objetivos-precargados.view', [
            'objetivosPrecargados' => ObjetivosPrecargado::latest()
						->orWhere('meta', 'LIKE', $keyWord)
						->orWhere('grupal', 'LIKE', $keyWord)
						->orWhere('porcentaje_de_participacion', 'LIKE', $keyWord)
						->orWhere('evidencias', 'LIKE', $keyWord)
						->orWhere('resultado_anterior_o_esperado', 'LIKE', $keyWord)
						->orWhere('tipo_objetivo_id', 'LIKE', $keyWord)
						->orWhere('minimo', 'LIKE', $keyWord)
						->orWhere('maximo', 'LIKE', $keyWord)
						->orWhere('valor', 'LIKE', $keyWord)
						->orWhere('porcentaje_de_logro_STI', 'LIKE', $keyWord)
						->orWhere('peso_ponderado', 'LIKE', $keyWord)
						->orWhere('evaluacion_id', 'LIKE', $keyWord)
						->paginate(10),
                        // 'tipos_objetivo' => TiposDeObjetivo::all(),
        ]);
    }

	public function updatedTipoObjetivoId($value)
	{
		if ($value == null) {
			$this->simbolo = '';
			return;
		}
		$this->tipo_objetivo_id = $value;
		$tipo_objetivo = TiposDeObjetivo::find($value);
		$this->simbolo = $tipo_objetivo->simbolo;
	}

    public function cancel()
    {
        $this->resetInput();
		$this->resetValidation();
        $this->updateMode = false;
    }
	
    private function resetInput()
    {		
		$this->meta = null;
		$this->grupal = null;
		$this->porcentaje_de_participacion = null;
		$this->evidencias = null;
		$this->resultado_anterior_o_esperado = null;
		$this->tipo_objetivo_id = null;
		$this->minimo = null;
		$this->maximo = null;
		$this->valor = null;
		$this->porcentaje_de_logro_STI = null;
		$this->peso_ponderado = null;
		$this->evaluacion_id = null;
		$this->simbolo = null;
    }

	public function create_v2() 
	{
		$this->selected_id = 0; 
		$this->grupal = 1;
		$this->tipo_objetivo_id = $this->tipos_objetivo[0]->id;
		$this->simbolo = TiposDeObjetivo::find($this->tipo_objetivo_id)->simbolo;
		$this->evaluacion_id = $this->evaluaciones[0]->id;
	}
    
	public function evaluarGrupal()
	{
		if(!$this->grupal) {
			$this->meta = null;
			$this->tipo_objetivo_id = null;
			$this->resultado_anterior_o_esperado = null;
		}
	}

    public function store()
    {
        $this->validate(
        );

		$this->evaluarGrupal();

        ObjetivosPrecargado::create([ 
			'meta' => $this-> meta,
			'grupal' => $this-> grupal,
			'porcentaje_de_participacion' => $this-> porcentaje_de_participacion,
			'evidencias' => $this-> evidencias,
			'resultado_anterior_o_esperado' => $this-> resultado_anterior_o_esperado,
			'tipo_objetivo_id' => $this-> tipo_objetivo_id,
			'minimo' => $this-> minimo,
			'maximo' => $this-> maximo,
			'valor' => $this-> valor,
			'porcentaje_de_logro_STI' => $this-> porcentaje_de_logro_STI,
			'peso_ponderado' => $this-> peso_ponderado,
			'evaluacion_id' => $this-> evaluacion_id
        ]);
        
        $this->resetInput();
		$this->resetValidation();
		$this->updateMode = false;
		$this->emit('closeModal');
		session()->flash('message', 'Objetivos Precargado creado correctamente.');
    }

    public function edit($id)
    {
		$this->resetValidation();
		$this->resetInput();

		if ($id != 0) {
			$record = ObjetivosPrecargado::findOrFail($id);

			$this->selected_id = $id; 
			$this->meta = $record-> meta;
			$this->grupal = $record-> grupal;
			$this->porcentaje_de_participacion = $record-> porcentaje_de_participacion;
			$this->evidencias = $record-> evidencias;
			$this->resultado_anterior_o_esperado = $record-> resultado_anterior_o_esperado;
			$this->tipo_objetivo_id = $record-> tipo_objetivo_id;
			$this->minimo = $record-> minimo;
			$this->maximo = $record-> maximo;
			$this->valor = $record-> valor;
			$this->porcentaje_de_logro_STI = $record-> porcentaje_de_logro_STI;
			$this->peso_ponderado = $record-> peso_ponderado;
			$this->evaluacion_id = $record-> evaluacion_id;
			$this->simbolo = $record->tipo_objetivo->simbolo??null;
			
		} else {
			$this->create();
		}
		$this->updateMode = true;
    }

    public function update()
    {
        $this->validate(
        );

		$this->evaluarGrupal();
		
        if ($this->selected_id) {
			$record = ObjetivosPrecargado::find($this->selected_id);
            $record->update([ 
			'meta' => $this-> meta,
			'grupal' => $this-> grupal,
			'porcentaje_de_participacion' => $this-> porcentaje_de_participacion,
			'evidencias' => $this-> evidencias,
			'resultado_anterior_o_esperado' => $this-> resultado_anterior_o_esperado,
			'tipo_objetivo_id' => $this-> tipo_objetivo_id,
			'minimo' => $this-> minimo,
			'maximo' => $this-> maximo,
			'valor' => $this-> valor,
			'porcentaje_de_logro_STI' => $this-> porcentaje_de_logro_STI,
			'peso_ponderado' => $this-> peso_ponderado,
			'evaluacion_id' => $this-> evaluacion_id
            ]);

			$this->resetInput();
			$this->resetValidation();
            $this->updateMode = false;
		    $this->emit('closeModal');
			session()->flash('message', 'Objetivos Precargado actualizado correctamente.');
        }
    }

    public function destroy($id)
    {
        if ($id) {
            $record = ObjetivosPrecargado::where('id', $id);
            $record->delete();
        }
    }
}
