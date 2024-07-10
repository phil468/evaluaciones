<?php

namespace App\Http\Livewire;

use App\Models\Evaluacione;
use App\Models\EvaluadorHasEvaluado;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Objetivo;
use App\Models\ObjetivoHasEvidencia;
use App\Models\TiposDeObjetivo;
use Livewire\WithFileUploads;

class Objetivos extends Component
{
    use WithPagination;
    use WithFileUploads;

	protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $resultado, $evaluado_id, $evaluador_id, $tipo_objetivo_id, $descripcion, $evidencia;

    public $meta, $porcentaje_de_participacion, $evidencias, $resultado_anterior_o_esperado, $minimo, $maximo, $valor, $porcentaje_de_logro_STI, $peso_ponderado, $evaluacion_id, $simbolo, $estado_id;

    public $updateMode = false;
    public $evaluador_has_evaluado_id, $evaluador, $evaluado, $evaluador_has_evaluado, $cantidad_requerida;
    public $objetivos_precargados = [], $grupal, $cargo;

	public $tipos_objetivo=[];
	public $evaluaciones=[];
    
    public $primera_fase_activa, $segunda_fase_activa, $minimo_evaluacion, $maximo_evaluacion;

    public $objetivoss;
    public $objetivos;
    public $subtotal;
    public $total;

    public $valor_actualizado;

    public $readOnly;

	protected $rules = 
	[
		'grupal' => 'required',
		'meta' => 'required|max:500',
		'tipo_objetivo_id' => 'required|min:1',
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

    public function mount($evaluador_has_evaluado_id, $readOnly = false)
    {        
       
        $this->tipos_objetivo = TiposDeObjetivo::all();
        $this->evaluaciones = Evaluacione::evaluacionPorObjetivos()->activa()->get();

		$this->evaluador_has_evaluado_id = $evaluador_has_evaluado_id;
		$this->evaluador_has_evaluado = EvaluadorHasEvaluado::where('id',$evaluador_has_evaluado_id)->get()->first();
        // dd($this->evaluador_has_evaluado->evaluacion->minimo);
        $this->evaluador = EvaluadorHasEvaluado::find($evaluador_has_evaluado_id)->evaluador()->get()->first();
        $this->evaluado = EvaluadorHasEvaluado::find($evaluador_has_evaluado_id)->evaluado()->get()->first();
        // dd($evaluador_has_evaluado_id, $this->evaluado);

        $this->cantidad_requerida = EvaluadorHasEvaluado::find($evaluador_has_evaluado_id)->cantidad_requerida;
        $this->grupal = $this->evaluador_has_evaluado->grupal;

        $this->evaluar_fases();

        $this->objetivoss = Objetivo::latest()->where('evaluador_has_evaluado_id',$this->evaluador_has_evaluado_id)->get();

        $this->readOnly = $readOnly;

        // $this->cargo = EvaluadorHasEvaluado::where('evaluador_has_evaluado_id',$evaluador_has_evaluado_id);
    }

    public function actualizarValor($id)
    {
        $this->validate(
            [
                'valor_actualizado' => 'required|numeric',
            ], 
            [
                'valor_actualizado.required' => 'El campo Valor es obligatorio.',
                'valor_actualizado.numeric' => 'El campo Valor debe ser numérico.',
            ]
        );

        $record = Objetivo::find($this->selected_id);

        $this->valor = $this->valor_actualizado;

        if ($record->tipo_objetivo_id == TiposDeObjetivo::CONDICIONAL) {
            $this->porcentaje_de_logro_STI = $this->valor_actualizado == $record->resultado_anterior_o_esperado ? 100 : 0;
        } else {
            $this->calcular_porcentaje_de_logro_STI();
        }
        $this->calcular_peso_ponderado();
        
        $record->update([ 
            'valor' => $this->valor_actualizado*1.00,
            'porcentaje_de_logro_STI' => $this-> porcentaje_de_logro_STI,
            'peso_ponderado' => $this-> peso_ponderado,
        ]);

        $this->valor_actualizado = null;
        $this->selected_id = null;

        $this->evaluarActualizarObjetivo($id);
        
        $this->resetInput();
        $this->resetValidation();
        $this->emit('closeModal');
        session()->flash('message', 'Actualizado correctamente.');
    }

    public function evaluarActualizarObjetivo($id) {
        // evaluar si objetivo tiene valor y evidencias y cambiar estado a 2
        $objetivo = Objetivo::find($id);
        // dd($objetivo->valor, ($objetivo->evidencias()->get()->count() ));
        if ($objetivo->valor && $objetivo->evidencias()->get()->count() > 0) {
            $objetivo->update(['estado_id' => 2]);
        } elseif (!($objetivo->valor) || $objetivo->evidencias()->get()->count() <= 0)
        {
            $objetivo->update(['estado_id' => 1]);
        }
    }

    public function openModadActualizarValor($id)
    {
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
        $this->valor_actualizado = $record-> valor;
        $this->porcentaje_de_logro_STI = $record-> porcentaje_de_logro_STI;
        $this->peso_ponderado = $record-> peso_ponderado;
        $this->evaluacion_id = $record-> evaluacion_id;
        $this->simbolo = $record->tipo_objetivo->simbolo??null;

        $this->minimo_evaluacion = $this->evaluador_has_evaluado->evaluacion->minimo;
        $this->maximo_evaluacion = $this->evaluador_has_evaluado->evaluacion->maximo;

        $this->emit('actualizarValorModal');
    }

    public function openModalEvidencias($id) {
        $this->selected_id = $id;
        $this->emit('openModalEvidencias');
    }

    public function cancel_actualizar_valor()
    {
        $this->resetInput();
        $this->resetValidation();
        $this->selected_id = null;
        $this->valor_actualizado = null;
        $this->emit('closeModal');
    }

    public function cancel_evidencias()
    {
        $this->resetInput();
        $this->resetValidation();
        $this->selected_id = null;
        $this->evidencia_subir = null;
        $this->emit('closeModal');
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
        $this->calcular_minimo();
        $this->calcular_maximo();
        $this->calcular_porcentaje_de_logro_STI();
        $this->calcular_peso_ponderado();
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

    public function uploadEvidencia($id)
    {
        $this->validate([
            'evidencia_subir' => 'required|file|max:1024', // 1MB Max
        ]);

        $name = pathinfo($this->evidencia_subir->getClientOriginalName(), PATHINFO_FILENAME).'_' . time() . '.' . $this->evidencia_subir->getClientOriginalExtension();

        $evidenciaName = $this->evidencia_subir->store('evidencias', 'public');

        ObjetivoHasEvidencia::create([
            'objetivo_id' => $id,
            'ruta' => $evidenciaName,
            'name' => $name,
        ]);

        $this->evidencia_subir = null;
        $this->evaluarActualizarObjetivo($id);
        $this->emit('closeModal');
        session()->flash('message', 'Evidencia subida correctamente.');


        $this->isOpen = false;
    }

    public function deleteEvidencia($id)
    {
        $evidencia = ObjetivoHasEvidencia::find($id);
        $evidencia->delete();
        $this->evaluarActualizarObjetivo($evidencia->objetivo_id);
        $this->emit('closeModal');
        session()->flash('message', 'Evidencia eliminada correctamente.');
    }

    public function render()
    {
        $this->evaluar_fases();
        
        $this->objetivoss =Objetivo::latest()->where('evaluador_has_evaluado_id',$this->evaluador_has_evaluado_id)->get();

        $this->objetivos = Objetivo::where('evaluador_has_evaluado_id',$this->evaluador_has_evaluado_id)
        ->orderByDesc('grupal')
        ->get();

        $this->subtotal = Objetivo::where('evaluador_has_evaluado_id',$this->evaluador_has_evaluado_id)->sum('peso_ponderado')*100;

        // dd($this->subtotal,$this->evaluador_has_evaluado->evaluacion->maximo);
        if ($this->subtotal >= $this->evaluador_has_evaluado->evaluacion->maximo) {
            $this->total = $this->evaluador_has_evaluado->evaluacion->maximo;
        } elseif ($this->subtotal >= $this->evaluador_has_evaluado->evaluacion->minimo) {
            $this->total = $this->subtotal;
        } else {
            $this->total = 0.00;
        }

        $keyWord = '%'.$this->keyWord .'%';
        return view('livewire.objetivos.view', [

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
        // number_format($value*100.00, 2, '.', '');
        $this->minimo = $this-> resultado_anterior_o_esperado ? 
        number_format(($this-> resultado_anterior_o_esperado * $this->minimo_evaluacion / 100), 2, '.', '')  
        : 0;
    }

    public function calcular_maximo()
    {
        $this->maximo = $this-> resultado_anterior_o_esperado ? 
        number_format(($this-> resultado_anterior_o_esperado * $this->maximo_evaluacion / 100), 2, '.', '')
        : 0;
    }

    public function calcular_porcentaje_de_logro_STI() {
            if ($this->valor > $this->maximo) {
                $this->porcentaje_de_logro_STI = $this->maximo_evaluacion;
            }
            else if ($this->valor >= $this->minimo) {
                $this->porcentaje_de_logro_STI = $this->resultado_anterior_o_esperado !=0 ? 
                number_format((($this->valor/$this->resultado_anterior_o_esperado)*100), 2, '.', '')
                : 0;
            } else {
                $this->porcentaje_de_logro_STI = 0;
            }
    }

    public function calcular_peso_ponderado()
    {
        $this->peso_ponderado = number_format((($this->porcentaje_de_participacion * $this->porcentaje_de_logro_STI) / 100), 2, '.', '');
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
                // 'porcentaje_de_participacion' => $this-> porcentaje_de_participacion,
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
			$this->tipo_objetivo_id = $record-> tipo_objetivo_id??null;     
			$this->minimo = $record-> minimo;
			$this->maximo = $record-> maximo;
			$this->valor = $record-> valor;
			$this->porcentaje_de_logro_STI = $record-> porcentaje_de_logro_STI;
			$this->peso_ponderado = $record-> peso_ponderado;
			$this->evaluacion_id = $record-> evaluacion_id;
			$this->simbolo = $record->tipo_objetivo->simbolo??null;
            $this->estado_id = $record->estado_id;

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

            // dd($this-> resultado_anterior_o_esperado*1.00);
            // $this->evaluarGrupal();
            
            if ($this->selected_id) {
                $record = Objetivo::find($this->selected_id);
                $record->update([ 
                    
                    'evaluado_id' => $this-> evaluado->id,
                    'evaluador_id' => $this-> evaluador->id,
                    'evaluador_has_evaluado_id' => $this->evaluador_has_evaluado_id,

                    'meta' => $this-> meta,
                    'grupal' => $this-> grupal,
                    // 'porcentaje_de_participacion' => $this-> porcentaje_de_participacion,
                    // 'evidencias' => $this-> evidencias,
                    'tipo_objetivo_id' => $this-> tipo_objetivo_id,
                    'resultado_anterior_o_esperado' => $this-> resultado_anterior_o_esperado*1.00,
                    
                    'minimo' => $this-> minimo,
                    'maximo' => $this-> maximo,
                    'valor' => $this-> valor,
                    'porcentaje_de_logro_STI' => $this-> porcentaje_de_logro_STI,
                    'peso_ponderado' => $this-> peso_ponderado,

                    'evaluacion_id' => $this-> evaluacion_id,
                    'estado_id' => $this->estado_id ?? 1,
                ]);

                // dd( $record);

                $this->resetInput();
                $this->resetValidation();
                $this->updateMode = false;
                $this->emit('closeModal');
                session()->flash('message', 'Objetivos actualizado correctamente.');
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
