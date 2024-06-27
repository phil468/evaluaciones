<?php

namespace App\Http\Livewire;

use App\Imports\EncargadosPlanesImport;
use App\Imports\EvaluadoresImport;
use App\Imports\EvaluadoresObjetivosImport;
use App\Mail\EvaluadorNotification;
use App\Models\EncargadosPlanesDeAccion;
use App\Models\Evaluacione;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Personal;
use App\Models\PlanesConfiguracion;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;

class EncargadosPlanes extends Component
{
    use WithFileUploads; // Utiliza el trait en tu componente

    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $encargado_id, $empleado_id, $planes_de_accion_configuracion_id,
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
    
    $file,$file_objetivos,
    $tipo_de_evaluacion_objetivos_id,
    $message
    ;

    public $updateMode = false;
    public $createMode = false;
	public $cargando = false;
	public $actualizandoVista = false;

    protected $listeners = ['edit' => 'edit'];
    
    public function mount() 
    {
        $this->tipo_de_evaluacion_objetivos_id = 2;

        $this->evaluadores 	=	Personal::		        orderBy('name')->select('name as label', 'id as value')->get()->toArray();
		$this->evaluados 	= 	Personal::		        orderBy('name')->select('name as label', 'id as value')->get()->toArray();
		$this->evaluaciones = 	PlanesConfiguracion::   orderBy('title')->activa()->select('title as label', 'id as value')->get()->toArray();
    }
    public function render()
    {
		$keyWord = '%'.$this->keyWord .'%';
        return view('livewire.encargados-planes.view');
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
        session()->flash('messagePlanes', 'Se eliminaron los objetivos y los registros correctamente.');
        $this->emit('closeModal');
        $this->emit('refreshEncargadosPlanes');
    }

    public function abrirImportar() 
    {
        session()->flash('messagePlanes', null);
    }
        
    public function importarEncargadosPlanes()
    {
        $this->validate([
            'file' => 'required|file|mimes:xls,xlsx'
        ]);

        try {
            $importacion = new EncargadosPlanesImport;
            Excel::import($importacion, $this->file);
			$this->message = $importacion->getMessage();
            session()->flash('messagePlanes', $this->message);
            // dd('messagePlanes');

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
                $this->message = $this->message.$error . "<br>";
            }
            session()->flash('messagePlanes', $this->message);
        }

        $this->resetInput();
        $this->emit('limpiarFile');      
        $this->emit('refreshEncargadosPlanes');
    }

    
    //Enviar correo de notificación
    public function enviarCorreo()
    {
        
    }

    public function listarSelects() 
    {
		$this->evaluadores 	=	Personal::		orderBy('name')->select('name as label', 'id as value')->get()->toArray();
		$this->evaluados 	= 	Personal::		orderBy('name')->select('name as label', 'id as value')->get()->toArray();
		$this->evaluaciones = 	PlanesConfiguracion::		orderBy('title')->activa()->select('title as label', 'id as value')->get()->toArray();

		// $this->emit('listar_selects_encargados_planes',
		// 	$this->evaluadores,
		// 	$this->evaluados,
		// 	$this->evaluaciones,
		// );
		$this->actualizarDatosPersonal();
	}
    
	public function actualizarDatosPersonal ()
    {
		$this->emit('actualizarDatosEncargadosPlanes',
			$this->encargado_id,
			$this->empleado_id,
			$this->planes_de_accion_configuracion_id
		);
	}

    public function cancel()
    {
		$this->emit('limpiarDatosEncargadosPlanes');
        $this->resetInput();
		$this->resetValidation();
        $this->updateMode = false;
    }
	
    private function resetInput()
    {		
		$this->selected_id = null;
		$this->encargado_id = null;
		$this->empleado_id = null;
		$this->planes_de_accion_configuracion_id = null;
        $this->cargo_de_evaluador = null;
        $this->area_de_evaluador = null;
        $this->gerencia_sub_gerencia_de_evaluador = null;
        $this->cargo_de_evaluado = null;
        $this->area_de_evaluado = null;
        $this->gerencia_sub_gerencia_de_evaluado = null;
        $this->cantidad_requerida = null;
        $this->valor_esperado = null;
        $this->jerarquia = null;
        $this->file = null;
        $this->file_objetivos = null;
    }

    public function create()
    {
        $this->updateMode = true;
        $this->listarSelects();
    }   

    public function store()
    {   
        $this->validate([
            'encargado_id' => [
                'required',
                Rule::unique('encargados_planes_de_accion')->where(function ($query) {
                    return $query->where('encargado_id', $this-> encargado_id)
                                 ->where('empleado_id', $this-> empleado_id)
                                 ->where('planes_de_accion_configuracion_id', $this-> planes_de_accion_configuracion_id);
                }),
            ],
            'empleado_id' => 'required',
            'planes_de_accion_configuracion_id' => 'required',

            'cargo_de_evaluador' => 'required',
            'area_de_evaluador' => 'required',
            'gerencia_sub_gerencia_de_evaluador' => 'required',
            'cargo_de_evaluado' => 'required',
            'area_de_evaluado' => 'required',
            'gerencia_sub_gerencia_de_evaluado' => 'required',

            'cantidad_requerida' => 'required|integer',
            'valor_esperado' => 'required|numeric',
            'jerarquia' => 'required',
        ]);

        EncargadosPlanesDeAccion::create([ 
            'encargado_id' => $this-> encargado_id,
            'empleado_id' => $this-> empleado_id,
            'planes_de_accion_configuracion_id' => $this-> planes_de_accion_configuracion_id,

            'cargo_de_evaluador' => $this-> cargo_de_evaluador,
            'area_de_evaluador' => $this-> area_de_evaluador,
            'gerencia_sub_gerencia_de_evaluador' => $this-> gerencia_sub_gerencia_de_evaluador,
            'cargo_de_evaluado' => $this-> cargo_de_evaluado,
            'area_de_evaluado' => $this-> area_de_evaluado,
            'gerencia_sub_gerencia_de_evaluado' => $this-> gerencia_sub_gerencia_de_evaluado,

            'cantidad_requerida' => $this-> cantidad_requerida,
            'valor_esperado' => $this-> valor_esperado,
            'jerarquia' => $this-> jerarquia,
        ]);
        
		$this->emit('limpiarDatosEncargadosPlanes');
        $this->resetInput();
        $this->resetValidation();
        $this->updateMode=false;
		$this->emit('closeModal');
        $this->emit('refreshEncargadosPlanes');
		session()->flash('messagePlanes', 'Evaluadores creado correctamente.');
    }

    public function edit($id)
    {
        if ($id != 0) {
            $record = EncargadosPlanesDeAccion::findOrFail($id);

            $this->selected_id = $id; 
            $this->encargado_id = $record-> encargado_id;
            $this->empleado_id = $record-> empleado_id;
            $this->planes_de_accion_configuracion_id = $record-> planes_de_accion_configuracion_id;

            $this->cargo_de_evaluador = $record-> cargo_de_evaluador;
            $this->area_de_evaluador = $record-> area_de_evaluador;
            $this->gerencia_sub_gerencia_de_evaluador = $record-> gerencia_sub_gerencia_de_evaluador;
            $this->cargo_de_evaluado = $record-> cargo_de_evaluado;
            $this->area_de_evaluado = $record-> area_de_evaluado;
            $this->gerencia_sub_gerencia_de_evaluado = $record-> gerencia_sub_gerencia_de_evaluado;

            $this->cantidad_requerida = $record-> cantidad_requerida;
            $this->valor_esperado = $record-> valor_esperado;
            $this->jerarquia = $record-> jerarquia;
		} else {
			$this->selected_id = 0;
		}
        $this->updateMode = true;
        $this->listarSelects();        
    }

    public function update()
    {
        $this->validate([
            'encargado_id' => [
                'required',
                Rule::unique('encargados_planes_de_accion')->where(function ($query) {
                    return $query->where('encargado_id', $this-> encargado_id)
                                 ->where('empleado_id', $this-> empleado_id)
                                 ->where('planes_de_accion_configuracion_id', $this-> planes_de_accion_configuracion_id);
                })->ignore($this->selected_id),
            ],
            'empleado_id' => 'required',
            'planes_de_accion_configuracion_id' => 'required',

            'cargo_de_evaluador' => 'required',
            'area_de_evaluador' => 'required',
            'gerencia_sub_gerencia_de_evaluador' => 'required',
            'cargo_de_evaluado' => 'required',
            'area_de_evaluado' => 'required',
            'gerencia_sub_gerencia_de_evaluado' => 'required',

            'cantidad_requerida' => 'required|integer',
            'valor_esperado' => 'required|numeric',
            'jerarquia' => 'required',
        ]);

        if ($this->selected_id) {
			$record = EncargadosPlanesDeAccion::find($this->selected_id);
            $record->update([ 
                'encargado_id' => $this-> encargado_id,
                'empleado_id' => $this-> empleado_id,
                'planes_de_accion_configuracion_id' => $this-> planes_de_accion_configuracion_id,

                'cargo_de_evaluador' => $this-> cargo_de_evaluador,
                'area_de_evaluador' => $this-> area_de_evaluador,
                'gerencia_sub_gerencia_de_evaluador' => $this-> gerencia_sub_gerencia_de_evaluador,
                'cargo_de_evaluado' => $this-> cargo_de_evaluado,
                'area_de_evaluado' => $this-> area_de_evaluado,
                'gerencia_sub_gerencia_de_evaluado' => $this-> gerencia_sub_gerencia_de_evaluado,

                'cantidad_requerida' => $this-> cantidad_requerida,
                'valor_esperado' => $this-> valor_esperado,
                'jerarquia' => $this-> jerarquia,
            ]);

            $this->emit('limpiarDatosEncargadosPlanes');
            $this->resetInput();
            $this->resetValidation();
            $this->updateMode=false;
		    $this->emit('closeModal');
            $this->emit('refreshEncargadosPlanes');
			session()->flash('messagePlanes', 'Encargado de planes actualizado correctamente.');
        }
    }

    public function crear_editar_usuarios() {
        $evaluadores = EncargadosPlanesDeAccion::all();
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

    public function destroy($id)
    {
        if ($id) {
            $record = EncargadosPlanesDeAccion::where('id', $id);
            $record->delete();
        }
    }
}
