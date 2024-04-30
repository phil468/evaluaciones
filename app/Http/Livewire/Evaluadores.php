<?php

namespace App\Http\Livewire;

use App\Imports\EvaluadoresImport;
use App\Imports\EvaluadoresObjetivosImport;
use App\Mail\EvaluadorNotification;
use App\Models\Evaluacione;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\EvaluadorHasEvaluado;
use App\Models\Personal;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
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
    $evaluaciones,$file,$file_objetivos;

    public $updateMode = false;
    public $createMode = false;
	public $cargando = false;
	public $actualizandoVista = true;

    protected $listeners = ['edit' => 'edit'];
    
    public function render()
    {
		$keyWord = '%'.$this->keyWord .'%';
        return view('livewire.evaluadores.view', [
            'evaluadorHasEvaluados' => EvaluadorHasEvaluado::latest()
            ->orderBy('evaluador_Has_Evaluados.id', 'desc')
						->orWhere('evaluador_id', 'LIKE', $keyWord)
						->orWhere('evaluado_id', 'LIKE', $keyWord)
						->orWhere('evaluacion_id', 'LIKE', $keyWord)
						->paginate(20),
        ]);
    }
	
        
    public function importar()
    {
            $this->validate([
                'file' => 'required|file|mimes:xls,xlsx'
    
            ]);
     
                $cs =  Excel::import(new EvaluadoresImport, $this->file);
        
                $this->resetInput();                
               
                session()->flash('message', 'Evaluadores importado correctamente.');
                $this->emit('closeModal');
                $this->emit('alert');
    }

    
    public function importar_objetivos()
    {
            $this->validate([
                'file_objetivos' => 'required|file|mimes:xls,xlsx'    
            ]);
     
                $cs =  Excel::import(new EvaluadoresObjetivosImport, $this->file_objetivos);
                //mostrar mensaje que retorna de $cs
                // dd($cs);
                $this->crear_editar_usuarios();
        
                $this->resetInput();                
               
                session()->flash('message', 'Evaluadores de objetivos importado correctamente.');
                $this->emit('closeModal');
                $this->emit('alert');
    }

    //Enviar correo de notificación
    public function enviarCorreo()
    {
        // // traer todos los evaluadores en la table EvaluadorHasEvaluado
        // $evaluadores = EvaluadorHasEvaluado::all();

        // /*hacer un foreach y enviar su usuario y contraseña si no tiene usuario entonces crear su usuario y contraseña y enviarla
        // se llega al usuario así: $evaluadores->evaluador->user->email
        // se llega a la contraseña así: $evaluadores->evaluador->user->password*/
        // foreach ($evaluadores as $evaluador) {
        //     $mail = $evaluador->evaluador->user->email;
        //     $password = $evaluador->evaluador->user->password;
        //     Mail::to('recipient@example.com')->send(new EvaluadorNotification($mail, $password));



        //     # code...
        // }
        
    }

    public function listarSelects() {
		$this->evaluadores 	=	Personal::		orderBy('name')->select('name as label', 'id as value')->get()->toArray();
		$this->evaluados 	= 	Personal::		orderBy('name')->select('name as label', 'id as value')->get()->toArray();
		$this->evaluaciones = 	Evaluacione::		orderBy('title')->select('title as label', 'id as value')->get()->toArray();

		$this->emit('listar_selects',
			$this->evaluadores,
			$this->evaluados,
			$this->evaluaciones,
		);
		$this->actualizarDatosPersonal();
	}
    
	public function actualizarDatosPersonal () {
		$this->emit('actualizarDatosP',
			$this->evaluador_id,
			$this->evaluado_id,
			$this->evaluacion_id
		);
	}

    public function cancel()
    {
        $this->resetInput();
		$this->emit('limpiarDatos');
        $this->cargando = false;
        $this->actualizandoVista = true;
    }
	
    private function resetInput()
    {		
		$this->selected_id = null;
		$this->evaluador_id = null;
		$this->evaluado_id = null;
		$this->evaluacion_id = null;
    }

    public function create() {
        $this->listarSelects();
        $this->updateMode = true;		
        $this->cargando = true;
        // $this->createMode = true;
        // $this->listarSelects();
    }   

    public function store()
    {
        $this->validate([
            'evaluador_id' => 'required',
            'evaluado_id' => 'required',
            'evaluacion' => 'required',
        ]);

        EvaluadorHasEvaluado::create([ 
			'evaluador_id' => $this-> evaluador_id,
			'evaluado_id' => $this-> evaluado_id,
			'evaluacion' => $this-> evaluacion_id
        ]);
        
        $this->resetInput();
		$this->emit('limpiarDatos');
        $this->cargando = false;
		$this->actualizandoVista = true;
		$this->emit('closeModal');
		session()->flash('message', 'Evaluadores creado correctamente.');
    }

    public function edit($id)
    {
		if ($id != 0) {
			$this->resetValidation();
			$this->resetInput();
            
            $record = EvaluadorHasEvaluado::findOrFail($id);

            $this->selected_id = $id; 
            $this->evaluador_id = $record-> evaluador_id;
            $this->evaluado_id = $record-> evaluado_id;
            $this->evaluacion_id = $record-> evaluacion_id;
            
            $this->updateMode = true;
		} else {
			$this->resetValidation();
			$this->resetInput();
			$this->selected_id = 0; 
		}

        $this->listarSelects();
        $this->updateMode = true;
        $this->cargando = true;
    }

    public function update()
    {
        $this->validate([
            'evaluador_id' => 'required',
            'evaluado_id' => 'required',
            'evaluacion' => 'required',
        ]);

        if ($this->selected_id) {
			$record = EvaluadorHasEvaluado::find($this->selected_id);
            $record->update([ 
                'evaluador_id' => $this-> evaluador_id,
                'evaluado_id' => $this-> evaluado_id,
                'evaluacion' => $this-> evaluacion
            ]);

            $this->resetInput();
            // $this->updateMode = false;
			$this->emit('limpiarDatos');
        	$this->cargando = false;
        	$this->actualizandoVista = true;
		    $this->emit('closeModal');
			session()->flash('message', 'Evaluadores actualizado correctamente.');
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

    public function destroy($id)
    {
        if ($id) {
            $record = EvaluadorHasEvaluado::where('id', $id);
            $record->delete();
        }
    }
}
