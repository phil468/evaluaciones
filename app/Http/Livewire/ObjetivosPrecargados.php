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
	public $minimo_evaluacion, $maximo_evaluacion;

	public $tipo_de_jerarquia_id;

	protected $rules = 
	[
		'grupal' => 'required',
		'meta' => 'required_if:grupal,1|max:500',
		'tipo_objetivo_id' => 'required_if:grupal,1',
		'resultado_anterior_o_esperado' => 'required_if:grupal,1',
		'porcentaje_de_participacion' => 'required|numeric|between:0,100',
		'minimo'=>'required_if:grupal,1|exclude_if:grupal,0|exclude_if:tipo_objetivo_id,'.TiposDeObjetivo::CONDICIONAL.'|numeric|lt:maximo|gt:0',
		'maximo'=>'required_if:grupal,1|exclude_if:grupal,0|exclude_if:tipo_objetivo_id,'.TiposDeObjetivo::CONDICIONAL.'|numeric|gt:minimo|',
		'evaluacion_id' => 'required',
		'tipo_de_jerarquia_id' => 'required'
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
		'tipo_de_jerarquia_id' => 'Tipo de Jerarquía',
	];

	protected $messages = [
		'meta.required_if' => 'El campo Meta es obligatorio cuando Objetivo grupal es SÍ.',
		'tipo_objetivo_id.required_if' => 'El campo Tipo de objetivo es obligatorio cuando Objetivo grupal es SÍ.',
		'resultado_anterior_o_esperado.required_if' => 'El campo Resultado anterior o esperado es obligatorio cuando Objetivo grupal es SÍ.',
		'minimo.required_if' => 'El campo Resultado mínimo es obligatorio cuando Objetivo grupal es SÍ / Ingrese valor a Resultado Anterior/Esperado',
		'maximo.required_if' => 'El campo Resultado máximo es obligatorio cuando Objetivo grupal es SÍ / Ingrese valor a Resultado Anterior/Esperado',
	];

	// public function updated($meta)
    // {
    //     $this->validateOnly($meta);
    // }

	public function mount() {
		$this->tipos_objetivo = TiposDeObjetivo::all();
		$this->evaluaciones = Evaluacione::evaluacionPorObjetivos()->activa()->get();
	}

	public function calcular_maximo() {
		$this->maximo = number_format($this-> resultado_anterior_o_esperado ? $this-> resultado_anterior_o_esperado * $this->maximo_evaluacion / 100.00 : 0.00, 2, '.', '');
	}

	public function calcular_minimo() {
		$this->minimo = number_format($this-> resultado_anterior_o_esperado ? $this-> resultado_anterior_o_esperado * $this->minimo_evaluacion / 100.00 : 0.00, 2, '.', '');
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

	public function updatedResultadoAnteriorOEsperado($value)
	{
        $this->calcular_minimo();
        $this->calcular_maximo();
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
		$this->tipo_de_jerarquia_id = null;
    }

	public function create() 
	{
		$this->selected_id = 0; 
		$this->grupal = 1;
		$this->tipo_objetivo_id = $this->tipos_objetivo[0]->id;
		$this->simbolo = TiposDeObjetivo::find($this->tipo_objetivo_id)->simbolo;
		$this->evaluacion_id = $this->evaluaciones[0]->id;
		$this->minimo_evaluacion = $this->evaluaciones[0]->mínimo;
		$this->maximo_evaluacion = $this->evaluaciones[0]->maximo;
		$this->tipo_de_jerarquia_id = 1;
	}
    
	public function evaluarGrupal()
	{
		if(!$this->grupal) {
			$this->meta = null;
			$this->tipo_objetivo_id = null;
			$this->resultado_anterior_o_esperado = null;
			$this->minimo = NULL;
			$this->maximo = NULL;
		} else {	
			$this->calcular_minimo();
			$this->calcular_maximo();
		}
	}

    public function store()
    {
        $this->validate(
        );

		$this->evaluarGrupal();

        ObjetivosPrecargado::create([ 
			'tipo_objetivo_id' => $this-> tipo_objetivo_id,
			'meta' => $this-> meta,
			'grupal' => $this-> grupal,
			'porcentaje_de_participacion' => $this-> porcentaje_de_participacion,
			'evidencias' => $this-> evidencias,
			'resultado_anterior_o_esperado' => $this-> resultado_anterior_o_esperado,
			'minimo' => $this-> minimo,
			'maximo' => $this-> maximo,
			'valor' => $this-> valor,
			'porcentaje_de_logro_STI' => $this-> porcentaje_de_logro_STI,
			'peso_ponderado' => $this-> peso_ponderado,
			'evaluacion_id' => $this-> evaluacion_id,
			'tipo_de_jerarquia_id' => $this-> tipo_de_jerarquia_id
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
			$this->minimo_evaluacion = Evaluacione::find($record-> evaluacion_id)->minimo;
			$this->maximo_evaluacion = Evaluacione::find($record-> evaluacion_id)->maximo;
			$this->simbolo = $record->tipo_objetivo->simbolo??null;
			$this->tipo_de_jerarquia_id = $record->tipo_de_jerarquia_id;
			
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

		// dd($this->tipo_objetivo_id, $this->resultado_anterior_o_esperado, $this->minimo, $this->maximo);
		
        if ($this->selected_id) {
			$record = ObjetivosPrecargado::find($this->selected_id);
            $record->update([ 
				'tipo_objetivo_id' => $this-> tipo_objetivo_id,
				'meta' => $this-> meta,
				'grupal' => $this-> grupal,
				'porcentaje_de_participacion' => $this-> porcentaje_de_participacion,
				'evidencias' => $this-> evidencias,
				'resultado_anterior_o_esperado' => $this-> resultado_anterior_o_esperado,
				'minimo' => $this-> minimo,
				'maximo' => $this-> maximo,
				'valor' => $this-> valor,
				'porcentaje_de_logro_STI' => $this-> porcentaje_de_logro_STI,
				'peso_ponderado' => $this-> peso_ponderado,
				'evaluacion_id' => $this-> evaluacion_id,
				'tipo_de_jerarquia_id' => $this-> tipo_de_jerarquia_id
            ]);

			// cambiamos en todos los objetivos asociados a este objetivo precargado
			if ($record->grupal && $record->tipo_de_jerarquia_id == 2) {
				$objetivos = Objetivo::where('objetivo_precargado_id', $this->selected_id)->get();

				$count = 0;

				foreach ($objetivos as $objetivo) {
					if ($objetivo->estado_id == 1)
					{
						$objetivo->tipo_objetivo_id = $record-> tipo_objetivo_id;
						$objetivo->meta = $record-> meta;
						$objetivo->porcentaje_de_participacion = $record-> porcentaje_de_participacion;
						$objetivo->resultado_anterior_o_esperado = $record-> resultado_anterior_o_esperado;
						$objetivo->minimo = $record-> minimo;
						$objetivo->maximo = $record-> maximo;

						$objetivo->save();

						// $objetivo->update([
						// 	'meta' => $record-> meta,
						// 	// 'grupal' => $record-> grupal,
						// 	'porcentaje_de_participacion' => $record-> porcentaje_de_participacion,
						// 	'resultado_anterior_o_esperado' => $record-> resultado_anterior_o_esperado,
						// 	'tipo_objetivo_id' => $record-> tipo_objetivo_id,
						// 	'minimo' => $record-> minimo,
						// 	'maximo' => $record-> maximo,
						// 	// 'valor' => $this-> valor,
						// 	// 'porcentaje_de_logro_STI' => $this-> porcentaje_de_logro_STI,
						// 	// 'peso_ponderado' => $this-> peso_ponderado,
						// 	// 'evaluacion_id' => $this-> evaluacion_id,
						// 	// 'estado_id' => $this-> grupal ? 1 : null,
						// ]);
						$count++;
					}
				}
				
				$this->resetInput();
				$this->resetValidation();
				$this->updateMode = false;
				$this->emit('closeModal');
				session()->flash('message', 'Objetivos Precargado actualizado correctamente. Se actualizaron '.$count.' objetivos asociados.');

			} else {
				$this->resetInput();
				$this->resetValidation();
				$this->updateMode = false;
				$this->emit('closeModal');
				session()->flash('message', 'Objetivos Precargado actualizado correctamente.');
			}
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
