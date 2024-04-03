<?php

namespace App\Http\Livewire;

use App\Models\Area;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Capacitacione;
use App\Models\Cargo;
use App\Models\Empresa;
use App\Models\Modalidade;
use App\Models\Personal;
use App\Models\Sede;
use App\Models\Status;
use App\Models\Tema;
use App\Models\TipoDeCapacitacione;

class Capacitaciones extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $selected_id,
	$keyWord,
	$empresa_id,
	$capacitaciones_tipo_id,
	$tema_id,
	$sede_id,
	$fecha_capacitacion,
	$hora_inicio,
	$hora_fin,
	$expositor_id,
	$cargo_expositor_id,
	$registrador_id,
	$cargo_registrador_id,
	$fecha_registro,
	$activo,
	$status_id,
	$empresas,
	$capacitaciones_tipos, 
	$temas, 				
	$sedes, 				
	$expositors, 			
	$cargos, 	
	$registradors, 		
	$cargo_registradors, 	
	$statuss,
	$estado_realizado,
	$modalidades,
	$modalidad_id,
	$areas,
	$area_id=[],
	$cantidad_de_sesiones,
	$expositor_externo,
	$nombre_expositor_externo,
	$tema_id_add;
	
    public $updateMode = false;
	
	public $cargando = false;

    protected $listeners = [
		'edit' => 'edit',
		'selectedUpdated' => 'updateSelected'
	];
	
	// protected $listeners = [
    //     'edit',
    // ];

	public $selectedFromPersonalTable = [];

    public function updateSelected($value)
    {
        $this->selectedFromPersonalTable = $value;
    }

	public function listarSelects() {
		$this->empresas 				= 	Empresa::				orderBy('name')->where('estado',1)->whereNotNull('name')->select('name as label', 'id as value')->get()->toArray();
		$this->capacitaciones_tipos 	= 	TipoDeCapacitacione::	orderBy('name')->where('estado',1)->whereNotNull('name')->select('name as label', 'id as value')->get()->toArray();
		$this->temas 					= 	Tema::					orderBy('name')->where('estado',1)->whereNotNull('name')->select('name as label', 'id as value')->get()->toArray();
		$this->sedes 					= 	Sede::					orderBy('name')->where('estado',1)->whereNotNull('name')->select('name as label', 'id as value')->get()->toArray();
		$this->expositors 				=	Personal::				orderBy('name')->where('estado',1)->whereNotNull('name')->select('name as label', 'id as value')->get()->toArray();
		$this->cargos 					=	Cargo::					orderBy('name')->where('estado',1)->whereNotNull('name')->select('name as label', 'id as value')->get()->toArray();
		$this->registradors 			=	Personal::				orderBy('personal.name')->where('personal.estado',1)
																	->join('users', 'personal.id', 'users.personal_id')
																	->select('personal.name as label', 'personal.id as value')
																	->where('users.registrador',1)
																	->get()->toArray();
																	// dd($this->registradors);
		// $this->registradors 			=	Personal::				orderBy('name')->where('estado',1)->select('name as label', 'id as value')->get()->toArray();
		$this->cargo_registradors 		=	Cargo::					orderBy('name')->where('estado',1)->whereNotNull('name')->select('name as label', 'id as value')->get()->toArray();
		$this->statuss 					=	Status::				orderBy('name')->where('estado',1)->whereNotNull('name')->select('name as label', 'id as value')->get()->toArray();
		$this->areas 					=	Area::					orderBy('name')->where('estado',1)->whereNotNull('name')->select('name as label', 'id as value')->get()->toArray();
		$this->modalidades 				= 	Modalidade::			orderBy('name')->where('estado',1)->whereNotNull('name')->select('name as label', 'id as value')->get()->toArray();
		
		$this->emit('listarSelects',
			$this->empresas,
			$this->capacitaciones_tipos,
			$this->temas,
			$this->sedes,
			$this->expositors,
			$this->cargos,
			$this->registradors,
			$this->cargo_registradors,
			$this->statuss,
			$this->areas,
			$this->modalidades
		);
		$this->actualizarSelects();
	}
			
	public function actualizarSelects () {
		$this->emit('actualizarSelects',
			$this->empresa_id,
			$this->capacitaciones_tipo_id,
			$this->tema_id,
			$this->sede_id,
			$this->expositor_id,
			$this->cargo_expositor_id,
			$this->registrador_id,
			$this->cargo_registrador_id,
			$this->status_id,
			$this->area_id,
			$this->modalidad_id
		);
	}

	public function updatedExpositorId($value) {
		$this->cargo_expositor_id = null;
		$personal = Personal::find($value) ?? null;
		if ($personal) {
			$this->cargo_expositor_id = $personal->cargo_id;	
		}
		$this->actualizarDatosExpositor();
	}
	
	public function actualizarDatosExpositor () {
		$this->emit('actualizarDatosExpositor',
			$this->cargo_expositor_id,
			$this->expositor_id,
			$this->expositor_externo
		);
	}
	
	public function updatedRegistradorId($value) {
		$this->cargo_registrador_id = null;
		$personal = Personal::find($value) ?? null;
		if ($personal) {
			$this->cargo_registrador_id = $personal->cargo_id;	
		}
		$this->actualizarDatosRegistrador();
	}
	
	public function updatedModalidadId($value) {
		if($value == 1) {
			$this->expositor_externo = 1;
			// $this->updatedExpositorExterno(1);
		} else {
			$this->expositor_externo = null;
			// $this->updatedExpositorExterno(null);
		}
	}

	public function updatedExpositorExterno($value) {
		if($value == 1) {
			$this->expositor_id = null;
			$this->cargo_expositor_id = null;
			$this->actualizarDatosExpositor();
		} else {
			$this->nombre_expositor_externo = null;
			$this->actualizarDatosExpositor();
		}
	}

	public function actualizarDatosRegistrador () {
		$this->emit('actualizarDatosRegistrador',
			$this->cargo_registrador_id,
			$this->registrador_id
		);
	}

	public function agregar_tema() {
		$this->validate([			
			'tema_id_add' => 'required'
		],[],['tema_id_add' => 'Tema para agregar']);

		$tema = Tema::firstOrCreate(
			['name' => $this->tema_id_add],
			['estado' => 1]
			// ['serial_number' =>  $this->ct_id_add],
			// [
			// 	'activo_tipo_id' => 8,
			// 	'estado' => 1,
			// 	'brand_id' => 1,
			// 	'status_id' => 1,
			// 	'performance_id' => 1,
			// ]
		);

		// if ($tema->activo_tipo_id == 8) {
			$this->temas = 	Tema::orderBy('name')->where('estado',1)->whereNotNull('name')->select('name as label', 'id as value')->get()->toArray();
			
			$this->emit('listarTemas',
				$this->temas,$tema->id
			// $this->notebooks,
			);
			$this->tema_id = $tema->id;
			$this->tema_id_add = null;
		// } else {
			// $error['ct_id_add'] = "Serial de Activo no es un cargador de laptop";
		// }

	}

	// public function agregar_tema() {
	// 	$this->emit('agregar_tema');
	// }

	public function mount() {		
		$this->estado_realizado = Status::where('name', '=', 'realizado')->first()->id ?? null;
		if ($this->estado_realizado == null) {
			session()->flash('message-danger', 'No se encuentra definido el estado "realizado"
			. Por favor revisar el nombre de los estados');
			$this->emit('alert-danger');
		}
	}

		public function render()
    {
		$keyWord = '%'.$this->keyWord .'%';
        return view('livewire.capacitaciones.view'
		);
    }
	
    public function cancel()
    {
		$this->resetValidation();
        $this->resetInput();
		$this->emit('limpiarDatos');
        $this->cargando = false;
        // $this->actualizandoVista = true;
    }
	
    private function resetInput()
    {		
		$this->empresa_id = null;
		$this->capacitaciones_tipo_id = null;
		$this->tema_id = null;
		$this->sede_id = null;
		$this->fecha_capacitacion = null;
		$this->hora_inicio = null;
		$this->hora_fin = null;
		$this->expositor_id = null;
		$this->cargo_expositor_id = null;
		$this->registrador_id = null;
		$this->cargo_registrador_id = null;
		$this->fecha_registro = null;
		$this->activo = null;
		$this->status_id = null;
		$this->area_id = [];
		$this->modalidad_id = null;
		$this->cantidad_de_sesiones = null;
		$this->expositor_externo = null;
		$this->nombre_expositor_externo = null;
    }

	public function create() 
	{
		// $this->listar_selecciones();	
        $this->listarSelects();
		$this->activo=true;
		$this->cantidad_de_sesiones=1;
        // $this->updateMode = false;
        $this->updateMode = true;
        $this->modalidad_id = 2;
		$this->empresa_id = 1;
		
        $this->cargando = true;
        // $this->actualizandoVista = true;
	}

    public function store()
    {
		if ($this->expositor_externo != 1) {
			$this->expositor_externo = 0;
		}

        $this->validate([
			'empresa_id' => 'required',
			'capacitaciones_tipo_id' => 'required',
			'tema_id' => 'required',
			'sede_id' => 'required',
			'expositor_id' => 'required_if:expositor_externo,0',
			'cargo_expositor_id' => 'required_if:expositor_externo,0',
			// 'expositor_id' => 'required',
			// 'cargo_expositor_id' => 'required',
			'registrador_id' => 'required',
			'cargo_registrador_id' => 'required',
			// 'fecha_registro' => 'required',
			'status_id' => 'required',
			'cantidad_de_sesiones' =>  'required',
			'nombre_expositor_externo' => 'required_if:expositor_externo,1',
		],[
			'nombre_expositor_externo.required_if' => 'El campo nombre expositor externo es obligatorio cuando el expositor es externo.',
			'expositor_id.required_if' => 'El campo expositor es obligatorio cuando el expositor es interno.',
			'cargo_expositor_id.required_if' => 'El campo cargo expositor es obligatorio cuando el expositor es interno.',
		]);

		if ($this->expositor_externo == 1) {
			$this->expositor_id = null;
			$this->cargo_expositor_id = null;
		} else {
			$this->nombre_expositor_externo = null;
		}

        $record = Capacitacione::create([ 
			'empresa_id' => $this-> empresa_id,
			'capacitaciones_tipo_id' => $this-> capacitaciones_tipo_id,
			'tema_id' => $this-> tema_id,
			'sede_id' => $this-> sede_id,
			'fecha_capacitacion' => $this-> fecha_capacitacion,
			'hora_inicio' => $this-> hora_inicio,
			'hora_fin' => $this-> hora_fin,
			'expositor_id' => $this-> expositor_id,
			'cargo_expositor_id' => $this-> cargo_expositor_id,
			'registrador_id' => $this-> registrador_id,
			'cargo_registrador_id' => $this-> cargo_registrador_id,
			'fecha_registro' => $this-> fecha_registro,
			'activo' => $this-> activo,
			'status_id' => $this-> status_id,
			'modalidad_id' => $this-> modalidad_id,
			'cantidad_de_sesiones' => $this-> cantidad_de_sesiones??1,
			'expositor_externo' => $this-> expositor_externo,
			'nombre_expositor_externo' => $this-> nombre_expositor_externo,
			'synced' =>false
        ]);

		if($record) {
			$record->areas()->sync($this->area_id);
		}
        
        $this->resetInput();
        $this->cargando = true;
		$this->emit('closeModal');
		session()->flash('message', 'Capacitacion creado correctamente.');
    }

    public function edit($id)
    {
		if ($id != 0) {
			$this->resetValidation();
			$this->resetInput();	

			$record = Capacitacione::findOrFail($id);

			// dd($record);

			$this->selected_id = $id; 
			$this->empresa_id = $record-> empresa_id;
			$this->capacitaciones_tipo_id = $record-> capacitaciones_tipo_id;
			$this->tema_id = $record-> tema_id;
			$this->sede_id = $record-> sede_id;
			$this->fecha_capacitacion = $record-> fecha_capacitacion;
			$this->hora_inicio = $record-> hora_inicio;
			$this->hora_fin = $record-> hora_fin;
			$this->expositor_id = $record-> expositor_id;
			$this->cargo_expositor_id = $record-> cargo_expositor_id;
			$this->registrador_id = $record-> registrador_id;
			$this->cargo_registrador_id = $record-> cargo_registrador_id;
			$this->fecha_registro = $record-> fecha_registro;
			$this->activo = $record-> activo;
			$this->status_id = $record-> status_id;
			$this->modalidad_id = $record-> modalidad_id;
			$this->area_id = $record->areas()->pluck('area_id')->toArray();
			$this->cantidad_de_sesiones = $record-> cantidad_de_sesiones;
			$this->expositor_externo = $record-> expositor_externo;
			$this->nombre_expositor_externo = $record-> nombre_expositor_externo;
		} else {
			$this->resetValidation();
			$this->resetInput();
			$this->selected_id = 0;
			$this->activo=true;			
			$this->cantidad_de_sesiones=1;
			$this->modalidad_id = 2;
			$this->empresa_id = 1;
		}
		
		$this->listarSelects();
        $this->updateMode = true;
        $this->cargando = true;
    }

    public function update()
    {   
		if ($this->expositor_externo != 1) {
			$this->expositor_externo = 0;
		}

        $this->validate([
			'empresa_id' => 'required',
			'capacitaciones_tipo_id' => 'required',
			'tema_id' => 'required',
			'sede_id' => 'required',
			'expositor_id' => 'required_if:expositor_externo,0',
			'cargo_expositor_id' => 'required_if:expositor_externo,0',
			// 'expositor_id' => 'required',
			// 'cargo_expositor_id' => 'required',
			'registrador_id' => 'required',
			'cargo_registrador_id' => 'required',
			// 'fecha_registro' => 'required',
			'status_id' => 'required',
			'cantidad_de_sesiones' =>  'required',
			'nombre_expositor_externo' => 'required_if:expositor_externo,1',
		],[
			'nombre_expositor_externo.required_if' => 'El campo nombre expositor externo es obligatorio cuando el expositor es externo.',
			'expositor_id.required_if' => 'El campo expositor es obligatorio cuando el expositor es interno.',
			'cargo_expositor_id.required_if' => 'El campo cargo expositor es obligatorio cuando el expositor es interno.',
		]);

		if ($this->expositor_externo == 1) {
			$this->expositor_id = null;
			$this->cargo_expositor_id = null;
		} else {
			$this->nombre_expositor_externo = null;
		}
		
        if ($this->selected_id) {
			$record = Capacitacione::find($this->selected_id);
            $record->update([ 
			'empresa_id' => $this-> empresa_id,
			'capacitaciones_tipo_id' => $this-> capacitaciones_tipo_id,
			'tema_id' => $this-> tema_id,
			'sede_id' => $this-> sede_id,
			'fecha_capacitacion' => $this-> fecha_capacitacion,
			'hora_inicio' => $this-> hora_inicio,
			'hora_fin' => $this-> hora_fin,
			'expositor_id' => $this-> expositor_id,
			'cargo_expositor_id' => $this-> cargo_expositor_id,
			'registrador_id' => $this-> registrador_id,
			'cargo_registrador_id' => $this-> cargo_registrador_id,
			'fecha_registro' => $this-> fecha_registro,
			'activo' => $this-> activo,
			'status_id' => $this-> status_id,
			'modalidad_id' => $this-> modalidad_id,
			'cantidad_de_sesiones' => $this-> cantidad_de_sesiones??1,
			'expositor_externo' => $this-> expositor_externo,
			'nombre_expositor_externo' => $this-> nombre_expositor_externo,
			'synced'=>false
            ]);
			
			$record->areas()->sync($this->area_id);

            $this->resetInput();
            $this->updateMode = false;			
			$this->cargando = true;
		    $this->emit('closeModal');
			session()->flash('message', 'Capacitacion actualizado correctamente.');
        }
    }

    public function destroy($id)
    {
        if ($id) {
            $record = Capacitacione::where('id', $id);
            $record->delete();
        }
    }
}
