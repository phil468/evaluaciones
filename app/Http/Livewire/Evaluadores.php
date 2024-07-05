<?php

namespace App\Http\Livewire;

use App\Imports\EvaluadoresImport;
use App\Imports\EvaluadoresObjetivosImport;
use App\Mail\EvaluadorNotification;
use App\Models\Evaluacione;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\EvaluadorHasEvaluado;
use App\Models\Objetivo;
use App\Models\ObjetivosPrecargado;
use App\Models\Personal;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;

class Evaluadores extends Component
{
    use WithFileUploads; // Utiliza el trait en tu componente

    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $evaluador_id, $evaluado_id, $evaluacion_id,
    $evaluadores,
    $evaluados,
    $evaluaciones,

    $cargo_de_evaluador,
    $area_de_evaluador,
    $gerencia_sub_gerencia_de_evaluador,
    $cargo_de_evaluado,
    $area_de_evaluado,
    $gerencia_sub_gerencia_de_evaluado,
    $cantidad_requerida,
    $valor_esperado,
    $jerarquia,
    $realizado,
        
    $file,$file_objetivos
    ,$tipo_de_evaluacion_objetivos_id,
    $tipo_de_evaluacion_id=null,
    $message
    ;

    public $updateMode = false;
    public $createMode = false;
	public $cargando = false;
	public $actualizandoVista = true;

    protected $listeners = [
        'edit_evaluador' => 'edit' , 
        'eliminadoEvaluadoresCompetencias' => 'eliminadoEvaluadoresCompetencias',
        'eliminadoEvaluadoresResultados' => 'eliminadoEvaluadoresResultados'
    ];

    public function eliminadoEvaluadoresCompetencias($message)
    {
        session()->flash('messageEvaluadoresCompetencias', $message);    
    }

    public function eliminadoEvaluadoresResultados()
    {
        session()->flash('messageEvaluadoresResultados', 'Evaluador y objetivos eliminados correctamente.');    
    }
    
    public function mount() 
    {
        $this->tipo_de_evaluacion_objetivos_id = 2;
        $this->evaluadores 	=	Personal::		orderBy('name')->select('name as label', 'id as value')->get()->toArray();
		$this->evaluados 	= 	Personal::		orderBy('name')->select('name as label', 'id as value')->get()->toArray();
		$this->evaluaciones = 	Evaluacione::	orderBy('title')->select('title as label', 'id as value')->get()->toArray();
    }

    public function render()
    {
		$keyWord = '%'.$this->keyWord .'%';
        return view('livewire.evaluadores.view', [
            'evaluadorHasEvaluados' => EvaluadorHasEvaluado::latest()
            ->orderBy('evaluador_has_evaluados.id', 'desc')
			->orWhere('evaluador_id', 'LIKE', $keyWord)
			->orWhere('evaluado_id', 'LIKE', $keyWord)
			->orWhere('evaluacion_id', 'LIKE', $keyWord)
			->paginate(20),
        ]);
    }
	
    public function eliminarObjetivos()
    {
        $evaluaciones_por_objetivos_no_iniciadas = Evaluacione::evaluacionPorObjetivos()->noIniciada()->get();

        foreach ($evaluaciones_por_objetivos_no_iniciadas as $e) {
            $evaluadores = $e->evaluadores();
            foreach ($evaluadores as $e) {
                $e->objetivos()->delete();
            }
            $e->evaluadores()->delete();
        }
        session()->flash('message', 'Se eliminaron los objetivos y los registros correctamente.');
        $this->emit('closeModal');
    }
        
    public function importar()
    {
        $this->message = null;
        $this->validate([
            'file' => 'required|file|mimes:xls,xlsx'
        ]);

        try {
            $importacion = new EvaluadoresImport;
            Excel::import($importacion, $this->file);
			$this->message = $importacion->getMessage();
            session()->flash('message_importacion_evaluadores_competencias', $this->message);
            // dd('message');

        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            // dd($failures);

            foreach ($failures as $failure) {
                $fila = $failure->row();
                $atributo = $failure->attribute();
                $errores = $failure->errors();
                $valores = $failure->values();

                // Formatear el mensaje de error
                $mensajeError = "Error en la fila $fila, Atributo: $atributo, ";
                $mensajeError .= "Errores: " . implode(', ', $errores) . ", ";
                $mensajeError .= "Valores: " . implode(', ', $valores);

                // Agregar el mensaje de error al array de erroresDetallados
                $erroresDetallados[] = $mensajeError;
            }

            // Mostrar los errores
            // Aquí puedes decidir cómo quieres mostrar los errores. Por ejemplo:
            foreach ($erroresDetallados as $error) {
                $this->message = $this->message.$error . "<br>";
            }
            session()->flash('message_importacion_evaluadores_competencias_error', $this->message);
        }

        $this->resetInput();
        $this->emit('limpiarFile');      
        $this->emit('refreshEvaluadoresCompetencias');
    }

    public function importar_objetivos()
    {
        $this->message = null;
        $this->validate([
            'file_objetivos' => 'required|file|mimes:xls,xlsx'
        ]);

        try {
            $importacion = new EvaluadoresObjetivosImport;
            Excel::import($importacion, $this->file_objetivos);
			$this->message = $importacion->getMessage();
            session()->flash('message_importacion_evaluadores_resultados', $this->message);
            // dd('message');

        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            // dd($failures);

            foreach ($failures as $failure) {

                $fila = $failure->row();
                $atributo = $failure->attribute();
                $errores = $failure->errors();
                $valores = $failure->values();

                // $failure->row(); // row that went wrong
                // $failure->attribute(); // either heading key (if using heading row concern) or column index
                // $failure->errors(); // Actual error messages from Laravel validator
                // $failure->values(); // The values of the row that has failed.

                // Formatear el mensaje de error
                $mensajeError = "Error en la fila $fila, Atributo: $atributo, ";
                $mensajeError .= "Errores: " . implode(', ', $errores) . ", ";
                $mensajeError .= "Valores: " . implode(', ', $valores);

                // Agregar el mensaje de error al array de erroresDetallados
                $erroresDetallados[] = $mensajeError;
            }

            // Mostrar los errores
            // Aquí puedes decidir cómo quieres mostrar los errores. Por ejemplo:
            foreach ($erroresDetallados as $error) {
                $this->message = $this->message.'<p class="my-2">'.$error . "</p>";
            }
            session()->flash('message_importacion_evaluadores_resultados_error', $this->message);
        }

        $this->resetInput();
        $this->emit('limpiarFile');      
        $this->emit('refreshEvaluadoresResultados');
    }

    //Enviar correo de notificación
    public function enviarCorreo()
    {
        
    }

    public function listarSelects($disable = false) 
    {
        $this->evaluadores 	=	Personal::		orderBy('name')->select('name as label', 'id as value')->get()->toArray();
		$this->evaluados 	= 	Personal::		orderBy('name')->select('name as label', 'id as value')->get()->toArray();
		$this->evaluaciones = 	Evaluacione::	orderBy('title')->activa()->select('title as label', 'id as value')
                                ->when($this->tipo_de_evaluacion_id, function ($q) {
                                    return $q->where('tipo_de_evaluacion_id', $this->tipo_de_evaluacion_id);
                                })->get()->toArray();

		$this->actualizarDatosPersonal($disable);
	}
    
	public function actualizarDatosPersonal ($disable = false) {
		$this->emit('actualizarDatosEvaluadores',
			$this->evaluador_id,
			$this->evaluado_id,
			$this->evaluacion_id,

            $this->evaluadores,
            $this->evaluados,
            $this->evaluaciones,

            $disable
		);
	}

    public function cancel()
    {
		$this->emit('limpiarDatosEvaluadores');
        $this->resetInput();
		$this->resetValidation();
        $this->updateMode = false;
    }
	
    private function resetInput()
    {		
		$this->selected_id = null;
		$this->evaluador_id = null;
		$this->evaluado_id = null;
		$this->evaluacion_id = null;
        $this->cargo_de_evaluador = null;
        $this->area_de_evaluador = null;
        $this->gerencia_sub_gerencia_de_evaluador = null;
        $this->cargo_de_evaluado = null;
        $this->area_de_evaluado = null;
        $this->gerencia_sub_gerencia_de_evaluado = null;
        $this->cantidad_requerida = null;
        $this->valor_esperado = null;
        $this->jerarquia = null;
        $this->realizado = null;
        $this->file = null;
        $this->file_objetivos = null;
    }

    public function create() {
        $this->updateMode = true;	
        $this->listarSelects();  
    }   

    public function store()
    {
        $this->validate([
            'evaluador_id' => [
                'required',
                Rule::unique('evaluador_has_evaluados')->where(function ($query) {
                    return $query->where('evaluador_id' ,    $this-> evaluador_id)
                                 ->where('evaluado_id'  ,     $this-> evaluado_id)
                                 ->where('evaluacion_id',   $this-> evaluacion_id);
                }),
            ],
            'evaluado_id' => 'required',
            'evaluacion_id' => 'required',

            'cargo_de_evaluador' => 'required',
            'area_de_evaluador' => 'required',
            'gerencia_sub_gerencia_de_evaluador' => 'required',

            'cargo_de_evaluado' => 'required',
            'area_de_evaluado' => 'required',
            'gerencia_sub_gerencia_de_evaluado' => 'required',

            'jerarquia' => 'required_if:tipo_de_evaluacion_id,2|exclude_unless::tipo_de_evaluacion_id,2|min:1',
        ], [
            'evaluador_id.required' => 'El campo evaluador es obligatorio.',
            'evaluador_id.unique' => 'Esta combinación de Evaluador - Evaluado - Evaluacion ya ha sido registrada.',
            'evaluado_id.required' => 'El campo evaluado es obligatorio.',
            'evaluacion_id.required' => 'El campo evaluación es obligatorio.',
            'jerarquia.required_if' => 'El campo jerarquía es obligatorio cuando el tipo de evaluación es por resultados.',
        ], [
            'evaluador_id' => 'evaluador',
            'evaluado_id' => 'evaluado',
            'evaluacion_id' => 'evaluación',
            'cargo_de_evaluador' => 'cargo de evaluador',
            'area_de_evaluador' => 'área de evaluador',
            'gerencia_sub_gerencia_de_evaluador' => 'gerencia/subgerencia de evaluador',
            'cargo_de_evaluado' => 'cargo de evaluado',
            'area_de_evaluado' => 'área de evaluado',
            'gerencia_sub_gerencia_de_evaluado' => 'gerencia/subgerencia de evaluado',
            'jerarquia' => 'jerarquía',
        ]);

        $record = EvaluadorHasEvaluado::create([ 
			'evaluador_id' => $this-> evaluador_id,
			'evaluado_id' => $this-> evaluado_id,
			'evaluacion_id' => $this-> evaluacion_id,

            'cargo_de_evaluador' => $this-> cargo_de_evaluador,
            'area_de_evaluador' => $this-> area_de_evaluador,
            'gerencia_sub_gerencia_de_evaluador' => $this-> gerencia_sub_gerencia_de_evaluador,

            'cargo_de_evaluado' => $this-> cargo_de_evaluado,
            'area_de_evaluado' => $this-> area_de_evaluado,
            'gerencia_sub_gerencia_de_evaluado' => $this-> gerencia_sub_gerencia_de_evaluado,

            'tipo_jerarquia_id' => $this-> jerarquia ,
        ]);
        
        if ($this->tipo_de_evaluacion_id == 2) {
            $this->ingresarObjetivosPrecargados($this-> jerarquia,$record);
        }

		$this->emit('limpiarDatosEvaluadores');
        $this->resetInput();
        $this->resetValidation();
        $this->updateMode=false;
		$this->emit('closeModal');

        if ($this->tipo_de_evaluacion_id == 1) {
            $this->emit('refreshEvaluadoresCompetencias');        
            session()->flash('messageEvaluadoresCompetencias', 'Evaluadores creado correctamente.');
        }

        if ($this->tipo_de_evaluacion_id == 2) {
            $this->emit('refreshEvaluadoresResultados');
            session()->flash('messageEvaluadoresResultados', 'Evaluadores creado correctamente.');
        }
    }

    public function ingresarObjetivosPrecargados($jerarquia,$record)
    {
        $objetivos_precargados = 
        ObjetivosPrecargado::where('tipo_de_jerarquia_id', $jerarquia)
        ->where('evaluacion_id', $record->evaluacion_id)
        ->get();

        foreach ($objetivos_precargados as $objetivo_precargado) {
            Objetivo::create([
                'evaluado_id' => $this-> evaluado_id,
                'evaluador_id' => $this-> evaluador_id,
                'objetivo_precargado_id' => $objetivo_precargado->id,
                'evaluador_has_evaluado_id' => $record->id,

                'meta' => $objetivo_precargado-> meta,
                'grupal' => $objetivo_precargado-> grupal,
                'porcentaje_de_participacion' => $objetivo_precargado-> porcentaje_de_participacion,
                'tipo_objetivo_id' => $objetivo_precargado-> tipo_objetivo_id,
                'resultado_anterior_o_esperado' => $objetivo_precargado-> resultado_anterior_o_esperado,
                'minimo' => $objetivo_precargado-> minimo,
                'maximo' => $objetivo_precargado-> maximo,
                'valor' => $objetivo_precargado-> valor,
                'porcentaje_de_logro_STI' => $objetivo_precargado-> porcentaje_de_logro_STI,
                'peso_ponderado' => $objetivo_precargado-> peso_ponderado,
                'evaluacion_id' => $objetivo_precargado->evaluacion_id, // por defecto
                'estado_id' => $objetivo_precargado-> grupal ? 1 : null,
            ]);
        }
    }

    public function edit($id, $tipo_de_evaluacion_id)
    {
        $this->tipo_de_evaluacion_id = $tipo_de_evaluacion_id;

		if ($id != 0) {
            $record = EvaluadorHasEvaluado::findOrFail($id);

            $this->selected_id = $id; 
            $this->evaluador_id = $record-> evaluador_id;
            $this->evaluado_id = $record-> evaluado_id;
            $this->evaluacion_id = $record-> evaluacion_id;
           
            $this->cargo_de_evaluador = $record-> cargo_de_evaluador;
            $this->area_de_evaluador = $record-> area_de_evaluador;
            $this->gerencia_sub_gerencia_de_evaluador = $record-> gerencia_sub_gerencia_de_evaluador;
            $this->cargo_de_evaluado = $record-> cargo_de_evaluado;
            $this->area_de_evaluado = $record-> area_de_evaluado;
            $this->gerencia_sub_gerencia_de_evaluado = $record-> gerencia_sub_gerencia_de_evaluado;

            $this->jerarquia = $record->tipo_jerarquia_id;

            $this->realizado = $record-> realizado;
		} else {
			$this->selected_id = 0;            
		}

        $this->updateMode = true;

        $disable = false;

        if ($tipo_de_evaluacion_id == 1 && $this->realizado) {
            $disable = true;
        } else {
            $disable = false;
        }
        $this->listarSelects($disable);
    }

    public function updatedJerarquia($value)
    { 
        if($this->selected_id == 0) return;
        if($this->tipo_de_evaluacion_id != 2) return;

        $record = EvaluadorHasEvaluado::find($this->selected_id);

        if($record->tipo_jerarquia_id != $value) {
            session()->flash('cambioJerarquia', 'Si cambia la jerarquía se eliminarán los objetivos actuales y se generarán los nuevos objetivos para este evaluador-evaluado-evaluación.');
        } else {
            session()->forget('cambioJerarquia', null);
        }
    }

    public function update()
    {
        $this->validate([
            'evaluador_id' => [
                'required',
                Rule::unique('evaluador_has_evaluados')->where(function ($query) {
                    return $query->where('evaluador_id', $this-> evaluador_id)
                                 ->where('evaluado_id', $this-> evaluado_id)
                                 ->where('evaluacion_id', $this-> evaluacion_id);
                    })->ignore($this->selected_id),
            ],
            'evaluado_id' => 'required',
            'evaluacion_id' => 'required',

            'cargo_de_evaluador' => 'required',
            'area_de_evaluador' => 'required',
            'gerencia_sub_gerencia_de_evaluador' => 'required',

            'cargo_de_evaluado' => 'required',
            'area_de_evaluado' => 'required',
            'gerencia_sub_gerencia_de_evaluado' => 'required',

            'jerarquia' => 'required_if:tipo_de_evaluacion_id,2|exclude_unless::tipo_de_evaluacion_id,2|min:1',
        ], [
            'evaluador_id.required' => 'El campo evaluador es obligatorio.',
            'evaluador_id.unique' => 'Esta combinación de Evaluador - Evaluado - Evaluacion ya ha sido registrada.',
            'evaluado_id.required' => 'El campo evaluado es obligatorio.',
            'evaluacion_id.required' => 'El campo evaluación es obligatorio.',
            'jerarquia.required_if' => 'El campo jerarquía es obligatorio cuando el tipo de evaluación es por resultados.',

        ], [
            'evaluador_id' => 'evaluador',
            'evaluado_id' => 'evaluado',
            'evaluacion_id' => 'evaluación',
            'cargo_de_evaluador' => 'cargo de evaluador',
            'area_de_evaluador' => 'área de evaluador',
            'gerencia_sub_gerencia_de_evaluador' => 'gerencia/subgerencia de evaluador',
            'cargo_de_evaluado' => 'cargo de evaluado',
            'area_de_evaluado' => 'área de evaluado',
            'gerencia_sub_gerencia_de_evaluado' => 'gerencia/subgerencia de evaluado',
            'jerarquia' => 'jerarquía',
        ]);

        if ($this->selected_id) {

			$record = EvaluadorHasEvaluado::find($this->selected_id);

            $borrarObjetivos = false;

            $borrarObjetivos = ($record->tipo_jerarquia_id != $this-> jerarquia);

            $record->update([
                'evaluador_id' => $this-> evaluador_id,
                'evaluado_id' => $this-> evaluado_id,
                'evaluacion_id' => $this-> evaluacion_id,

                'cargo_de_evaluador' => $this-> cargo_de_evaluador,
                'area_de_evaluador' => $this-> area_de_evaluador,
                'gerencia_sub_gerencia_de_evaluador' => $this-> gerencia_sub_gerencia_de_evaluador,
                
                'cargo_de_evaluado' => $this-> cargo_de_evaluado,
                'area_de_evaluado' => $this-> area_de_evaluado,
                'gerencia_sub_gerencia_de_evaluado' => $this-> gerencia_sub_gerencia_de_evaluado,

                'tipo_jerarquia_id' => $this-> jerarquia ,
            ]);

            if ($this->tipo_de_evaluacion_id == 1) {
                $this->emit('refreshEvaluadoresCompetencias');        
                session()->flash('messageEvaluadoresCompetencias', 'Evaluador actualizado correctamente.');
            }
    
            if ($this->tipo_de_evaluacion_id == 2) {
                $objetivos = $record->objetivos;

                if(!count($objetivos)) {
                    $this->ingresarObjetivosPrecargados($this-> jerarquia,$record);
                } else {
                    if ($borrarObjetivos) {
                        $objetivos->each(function($objetivo) {
                            $objetivo->delete();
                        });
                        $this->ingresarObjetivosPrecargados($this-> jerarquia,$record);
                    } else {
                        foreach ($objetivos as $o) {
                            $o->evaluador_id = $this->evaluador_id;
                            $o->evaluado_id = $this->evaluado_id;
                            if ($o->isDirty()) {
                                $o->save();
                            }
                        }
                    }
                }

                $this->emit('refreshEvaluadoresResultados');
                session()->flash('messageEvaluadoresResultados', 'Evaluadores creado correctamente.');
                session()->forget('cambioJerarquia', null);
            }

			$this->emit('limpiarDatosEvaluadores');
            $this->resetInput();
            $this->resetValidation();
            $this->updateMode = false;
		    $this->emit('closeModal');
        }
    }

    public function crear_editar_usuarios() {
        $evaluadores = EvaluadorHasEvaluado::all();
        foreach ($evaluadores as $e) {
            $personal_evaluador = $e->evaluador;
            $evaluado = $e->evaluado;
            // $evaluacion = $evaluador->evaluacion;
            $user_evaluador = $personal_evaluador->user??null;
            $user_evaluado = $evaluado->user??null;
            if ($user_evaluador == null) {
                $user_evaluador = new User();

                $correo = '@vanguardfresh.pe';

                $personal = $personal_evaluador;
            
                if(!empty($personal)) {
                    if (strpos(strtolower($personal->correo_personal), '@vanguardfresh.pe') !== false && strtolower($personal->correo_personal) != 'colaboradores@vanguardfresh.pe') {
                        $correo = strtolower($personal->correo_personal);
                    } else {
                        $correo = strtolower(explode(" ", trim( str_replace('ñ','n',(str_replace('Ñ','n',$personal->nombres))) ))[0]).'.'
                                .strtolower(str_replace(' ','',str_replace('ñ','n',(str_replace('Ñ','n',$personal->apellido_paterno))) ))
                                .'@vanguardfresh.pe';
                    }
                }
                $user_evaluador->name = explode(" ", $personal->nombres)[0].' '.$personal->apellido_paterno;
                $user_evaluador->email = $correo;
                $user_evaluador->password = Hash::make('123456');
                $user_evaluador->personal_id = $personal_evaluador->id;
                $user_evaluador->estado = $personal_evaluador->estado;
                $user_evaluador->save();

                $user_evaluador->assignRole('Personal');

            }
            if ($user_evaluado == null) {
                $user_evaluado = new User();

                $correo = '@vanguardfresh.pe';

                $personal = $evaluado;
            
                if(!empty($personal)) {
                    if (strpos(strtolower($personal->correo_personal), '@vanguardfresh.pe') !== false && strtolower($personal->correo_personal) != 'colaboradores@vanguardfresh.pe') {
                        $correo = strtolower($personal->correo_personal);
                    } else {
                        $correo = strtolower(explode(" ", trim( str_replace('ñ','n',(str_replace('Ñ','n',$personal->nombres))) ))[0]).'.'
                                .strtolower(str_replace(' ','',str_replace('ñ','n',(str_replace('Ñ','n',$personal->apellido_paterno))) ))
                                .'@vanguardfresh.pe';
                    }
                }
                $user_evaluado->name = explode(" ", $personal->nombres)[0].' '.$personal->apellido_paterno;

                $user_evaluado->email = $correo;
                $user_evaluado->password = Hash::make('123456');
                $user_evaluado->personal_id = $evaluado->id;
                $user_evaluado->estado = $evaluado->estado;
                $user_evaluado->save();

                $user_evaluado->assignRole('Personal');
            }
        }
    
    }

    // public function destroy($id)
    // {
    //     if ($id) {
    //         $record = EvaluadorHasEvaluado::where('id', $id);
    //         dd('delete table');
    //         $record->delete();
    //     }
    // }
}
