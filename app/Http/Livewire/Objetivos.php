<?php

namespace App\Http\Livewire;

use App\Models\Evaluacione;
use App\Models\EvaluadorHasEvaluado;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Objetivo;
use App\Models\ObjetivoHasEvidencia;
use App\Models\TiposDeObjetivo;

class Objetivos extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $resultado, $evaluado_id, $evaluador_id, $tipo_objetivo_id, $descripcion, $evidencia;

    public $meta, $porcentaje_de_participacion, $evidencias, $resultado_anterior_o_esperado, $minimo, $maximo, $valor, $porcentaje_de_logro_STI, $peso_ponderado, $evaluacion_id, $simbolo;

    public $updateMode = false;
    public $evaluador_has_evaluado_id, $evaluador, $evaluado, $evaluador_has_evaluado, $cantidad_requerida;
    public $objetivos_precargados = [], $grupal, $cargo;

	public $tipos_objetivo=[];
	public $evaluaciones=[];
    
    public $primera_fase_activa, $segunda_fase_activa, $minimo_evaluacion, $maximo_evaluacion;

    public $objetivoss;

	protected $rules = 
	[
		'grupal' => 'required',
		'meta' => 'required|max:500',
		'tipo_objetivo_id' => 'required',
		'resultado_anterior_o_esperado' => 'required',
		'porcentaje_de_participacion' => 'required|numeric|between:0,100',
        'evaluado_id' => 'required',
        'evaluador_id' => 'required',
        'evaluador_has_evaluado_id' => 'required',
		// 'minimo'=>'required|numeric|lt:maximo|gt:0',
		// 'maximo'=>'required|numeric|gt:minimo|',
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
		// 'meta.required' => 'El campo Meta es obligatorio cuando Objetivo grupal es SÍ.',
		// 'tipo_objetivo_id.required_if' => 'El campo Tipo de objetivo es obligatorio cuando Objetivo grupal es SÍ.',
		// 'resultado_anterior_o_esperado.required_if' => 'El campo Resultado anterior o esperado es obligatorio cuando Objetivo grupal es SÍ.',
	];

    public function mount($evaluador_has_evaluado_id)
    {        
       
        $this->tipos_objetivo = TiposDeObjetivo::all();
        $this->evaluaciones = Evaluacione::evaluacionPorObjetivos()->activa()->get();

		$this->evaluador_has_evaluado_id = $evaluador_has_evaluado_id;
		$this->evaluador_has_evaluado = EvaluadorHasEvaluado::where('id',$evaluador_has_evaluado_id)->get()->first();
        // dd($this->evaluador_has_evaluado->evaluacion->minimo);
        $this->evaluador = EvaluadorHasEvaluado::find($evaluador_has_evaluado_id)->evaluador()->get()->first();
        $this->evaluado = EvaluadorHasEvaluado::find($evaluador_has_evaluado_id)->evaluado()->get()->first();

        $this->cantidad_requerida = EvaluadorHasEvaluado::find($evaluador_has_evaluado_id)->cantidad_requerida;
        $this->grupal = $this->evaluador_has_evaluado->grupal;

        $this->evaluar_fases();

        $this->objetivoss = Objetivo::latest()->where('evaluador_has_evaluado_id',$this->evaluador_has_evaluado_id)->get();


        // $this->cargo = EvaluadorHasEvaluado::where('evaluador_has_evaluado_id',$evaluador_has_evaluado_id);
    }

    public function evaluar_fases()
    {
        $this->primera_fase_activa = $this->evaluador_has_evaluado->evaluacion->primera_fase_activa;
        $this->segunda_fase_activa = $this->evaluador_has_evaluado->evaluacion->segunda_fase_activa;
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
        // dd($value);

        // if ($this->tipo_objetivo_id == 2) { // si es porcentaje
            
            $this->calcular_minimo();
            $this->calcular_maximo();

            // $this->resultado_anterior_o_esperado = $value/100;
        // } else {

            // $this->resultado_anterior_o_esperado = $value;
        // }
    }

    // public function updatedValor($value)
    // {
    //     $this->calcular_porcentaje_de_logro_STI();
	// }

    public $isOpen = false;

    public function openModal()
    {
        $this->isOpen = true;
    }

    public $evidencia_subir;

    public function uploadEvidencia()
    {
        $validatedData = $this->validate([
            'evidencia_subir' => 'required|file|max:1024', // 1MB Max
        ]);

        $evidenciaName = $this->evidencia_subir->store('evidencias', 'public');

        ObjetivoHasEvidencia::create([
            'objetivo_id' => $this->objetivo_id,
            'evidencia' => $evidenciaName,
        ]);

        $this->isOpen = false;
    }

    public function render()
    {
        $this->evaluar_fases();
        
        $this->objetivoss =Objetivo::latest()->where('evaluador_has_evaluado_id',$this->evaluador_has_evaluado_id)->get();

        $keyWord = '%'.$this->keyWord .'%';
        return view('livewire.objetivos.view', [
            'objetivos' => Objetivo::where('evaluador_has_evaluado_id',$this->evaluador_has_evaluado_id)
                        ->orderByDesc('grupal')
                        ->get(),
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
		$this->resultado = null;
		$this->evaluado_id = null;
		$this->evaluador_id = null;
		$this->tipo_objetivo_id = null;
		$this->descripcion = null;
		$this->evidencia = null;

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
    
	public function create() 
	{
		$this->selected_id = 0; 

        $this->grupal = 0;//no grupal

		$this->tipo_objetivo_id = $this->tipos_objetivo[0]->id;
		$this->simbolo = TiposDeObjetivo::find($this->tipo_objetivo_id)->simbolo;

        $this->minimo_evaluacion = $this->evaluador_has_evaluado->evaluacion->minimo;
        $this->maximo_evaluacion = $this->evaluador_has_evaluado->evaluacion->maximo;

        $this->valor = 0;
        $this->evaluacion_id = $this->evaluador_has_evaluado->evaluacion->id;
        
        $this->evaluado_id = $this-> evaluado->id;
        $this->evaluador_id = $this-> evaluador->id;
        $this->evaluador_has_evaluado_id = $this->evaluador_has_evaluado_id;
	}
    
	public function evaluarGrupal()
	{
		if(!$this->grupal) {
			$this->meta = null;
			$this->tipo_objetivo_id = null;
			$this->resultado_anterior_o_esperado = null;
		}
	}

    public function calcular_minimo()
    {
        // dd($this-> resultado_anterior_o_esperado);

        $this->minimo = $this-> resultado_anterior_o_esperado ? $this-> resultado_anterior_o_esperado * $this->minimo_evaluacion / 100 : 0;
        // dd($this->minimo);
    }

    public function calcular_maximo()
    {
        $this->maximo = $this-> resultado_anterior_o_esperado ? $this-> resultado_anterior_o_esperado * $this->maximo_evaluacion / 100 : 0;
    }

    public function calcular_porcentaje_de_logro_STI() {
            if ($this->valor > $this->maximo) {
                $this->porcentaje_de_logro_STI = $this->maximo_evaluacion;
            }
            else if ($this->valor >= $this->minimo) {
                $this->porcentaje_de_logro_STI = $this->resultado_anterior_o_esperado !=0 ? ($this->valor/$this->resultado_anterior_o_esperado)*100 : 0;
            } else {
                $this->porcentaje_de_logro_STI = 0;
            }
    }

    public function calcular_peso_ponderado()
    {
        $this->peso_ponderado = ($this->porcentaje_de_participacion * $this->porcentaje_de_logro_STI) / 100;
    }

    public function store()
    {
        $this->evaluar_fases();
        if($this->primera_fase_activa) {
            // dd('llegó a primera fase');
            // $this->evaluarGrupal();
            $this->calcular_minimo();
            $this->calcular_maximo();
            $this->calcular_porcentaje_de_logro_STI();
            $this->calcular_peso_ponderado();

            // dd('pasó calculos');
            $this->validate(
            );    
    
            Objetivo::create([ 
                
                'evaluado_id' => $this-> evaluado->id,
                'evaluador_id' => $this-> evaluador->id,
                'evaluador_has_evaluado_id' => $this->evaluador_has_evaluado_id,

                'meta' => $this-> meta,
                'grupal' => $this-> grupal,
                'porcentaje_de_participacion' => $this-> porcentaje_de_participacion,
                // 'evidencias' => $this-> evidencias,
                'tipo_objetivo_id' => $this-> tipo_objetivo_id,
                'resultado_anterior_o_esperado' => $this-> resultado_anterior_o_esperado,
                'minimo' => $this-> minimo,
                'maximo' => $this-> maximo,
                'valor' => $this-> valor,
                'porcentaje_de_logro_STI' => $this-> porcentaje_de_logro_STI,
                'peso_ponderado' => $this-> peso_ponderado,

                'evaluacion_id' => $this->evaluador_has_evaluado->evaluacion_id, // por defecto
            ]);
            
            // dd('pasó creación');

            $this->cantidad_requerida = EvaluadorHasEvaluado::find($this->evaluador_has_evaluado_id)->cantidad_requerida;

            if (Objetivo::where('evaluador_has_evaluado_id',$this->evaluador_has_evaluado_id)->count() == $this->cantidad_requerida) {
                EvaluadorHasEvaluado::find($this->evaluador_has_evaluado_id)->update(['realizado' => '1']);
            }

            $this->resetInput();
            $this->resetValidation();
            $this->updateMode = false;
            $this->emit('closeModal');
            session()->flash('message', 'Objetivos creado correctamente.');
        } else {
            $this->resetInput();
            $this->resetValidation();
            $this->updateMode = false;
            $this->emit('closeModal');
            session()->flash('message', 'No se registraron objetivos. Acabó la fecha de registros');
        }

        // $this->validate([
        //     'descripcion' => 'required|string|max:200',
        //     'tipo_objetivo_id' => 'required',
        // ]);

        // Objetivo::create([ 
		// 	'resultado' => $this-> resultado,
		// 	'evaluado_id' => $this-> evaluado->id,
		// 	'evaluador_id' => $this-> evaluador->id,
		// 	'tipo_objetivo_id' => $this-> tipo_objetivo_id,
		// 	'descripcion' => $this-> descripcion,
		// 	'evidencia' => $this-> evidencia,
        //     'evaluador_has_evaluado_id' => $this->evaluador_has_evaluado_id,
        // ]);
        
        // // $this->cantidad_requerida = EvaluadorHasEvaluado::find($evaluador_has_evaluado_id)->cantidad_requerida;

        // if (Objetivo::where('evaluador_has_evaluado_id',$this->evaluador_has_evaluado_id)->count() == $this->cantidad_requerida) {
        //     EvaluadorHasEvaluado::find($this->evaluador_has_evaluado_id)->update(['realizado' => '1']);
        // }

        // $this->resetInput();
		// $this->emit('closeModal');
		// session()->flash('message', 'Objetivo creado correctamente.');
    }

    public function store_valor($index) {
        $this->validate(
            [
                'objetivoss.*.valor' => 'required|numeric',
            ]
        );
        
        // $this->calcular_porcentaje_de_logro_STI();
        // $this->calcular_peso_ponderado();

        // foreach ($this->objetivoss as $objetivo) {
            //guardar si ha habido un cambio

            $objetivo = $this->objetivoss[$index];

            if($objetivo->isDirty()) {
                //calcular
                if ($objetivo['valor'] > $objetivo['maximo']) {
                    $objetivo['porcentaje_de_logro_STI'] = $objetivo['maximo_evaluacion'];
                }
                else if ($objetivo['valor'] >= $objetivo['minimo']) {
                    $objetivo['porcentaje_de_logro_STI'] = 
                        $objetivo['resultado_anterior_o_esperado'] !=0 ?   
                            ($objetivo['valor/$objetivo'] / $objetivo['resultado_anterior_o_esperado'])*100 
                        : 0;
                } else {
                    $objetivo['porcentaje_de_logro_STI'] = 0;
                }

                $objetivo->porcentaje_de_logro_STI = $objetivo['porcentaje_de_logro_STI'];

                $objetivo->peso_ponderado = ($objetivo['porcentaje_de_participacion'] * $objetivo['porcentaje_de_logro_STI']) / 100;

                // $objetivo->peso_ponderado = $objetivo['peso_ponderado'];
                //isdirty

                $objetivo->save();
            }
        // }

        // $record = Objetivo::find($id);
        // $record->update([ 
        //     'valor' => $this-> valor,
        //     'porcentaje_de_logro_STI' => $this-> porcentaje_de_logro_STI,
        //     'peso_ponderado' => $this-> peso_ponderado,
        // ]);

        // $this->resetInput();
        // $this->resetValidation();
        // $this->updateMode = false;
        // $this->emit('closeModal');
        // session()->flash('message', 'Objetivo actualizado correctamente.');
    }

    public function edit($id)
    {
		$this->resetValidation();
		$this->resetInput();

		if ($id != 0) {
			$record = Objetivo::findOrFail($id);

			$this->selected_id = $id; 
            $this->evaluado_id = $record-> evaluado_id;
            $this->evaluador_id = $record-> evaluador_id;
            $this->evaluacion_id = $record-> evaluacion_id;


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

            // $this->simbolo = TiposDeObjetivo::find($this->tipo_objetivo_id)->simbolo;

            $this->minimo_evaluacion = $this->evaluador_has_evaluado->evaluacion->minimo;
            $this->maximo_evaluacion = $this->evaluador_has_evaluado->evaluacion->maximo;
			
		} else {
			$this->create();
		}
		$this->updateMode = true;
    }
    
    public function update()
    {
        $this->evaluar_fases();
        if($this->primera_fase_activa) {
            // dd('llegó a primera fase');
            // $this->evaluarGrupal();
            $this->calcular_minimo();
            $this->calcular_maximo();
            $this->calcular_porcentaje_de_logro_STI();
            $this->calcular_peso_ponderado();

            // dd($this-> resultado_anterior_o_esperado);
            
            $this->validate(
            );   

            // $this->evaluarGrupal();
            
            if ($this->selected_id) {
                $record = Objetivo::find($this->selected_id);
                $record->update([ 
                    
                    'evaluado_id' => $this-> evaluado->id,
                    'evaluador_id' => $this-> evaluador->id,
                    'evaluador_has_evaluado_id' => $this->evaluador_has_evaluado_id,

                    'meta' => $this-> meta,
                    'grupal' => $this-> grupal,
                    'porcentaje_de_participacion' => $this-> porcentaje_de_participacion,
                    // 'evidencias' => $this-> evidencias,
                    'tipo_objetivo_id' => $this-> tipo_objetivo_id,
                    'resultado_anterior_o_esperado' => $this-> resultado_anterior_o_esperado,
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

        } else {
            $this->resetInput();
            $this->resetValidation();
            $this->updateMode = false;
            $this->emit('closeModal');
            session()->flash('message', 'No se registraron objetivos. Acabó la fecha de registros');
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
