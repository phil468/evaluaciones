<?php

namespace App\Http\Livewire;

use App\Http\Controllers\PersonalController;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Asistencium;
use App\Models\Capacitacione;
use App\Models\Personal;
use App\Models\Sesione;

class Asistenciums extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, 
	$sesion_id, $personal_id, $active, $observaciones, 
	$empresa_id, $gerencia_id, $area_id, $cargo_id, $planilla_id, 
	$sede_id, $tipo_de_trabajador_id, $tipo_de_personal_id, $capacitacion_id,
	$numero_sesion_id,
	$filtro_asistencia = false,
	$filtro_no_asistencia = false,
	$numero_de_sesion,$dni_search,
	$fecha,$hora_inicio,$hora_fin;
	// public $areas,
	// ;
    public $updateMode = false;

	public $capacitacion=[];

	public function mount($capacitacion_id)
    {
		$this->updateCapacitacion($capacitacion_id);
    }

	public function updateCapacitacion($capacitacion_id)
	{		
        $this->capacitacion_id = $capacitacion_id;
		$this->capacitacion = Capacitacione::where('id',$capacitacion_id)
		->with(['tipo_capacitacion','tema','sede','expositor','empresa','status','modalidad','registrador'])
		->get()
		->toArray();
	}

	public function updatedNumeroSesionId($value)
	{
		if($value)
		{
			$sesion = Capacitacione::find($this->capacitacion_id)->sesiones()->where('numero_de_sesion', $value)->first();
			if(!$sesion)
			{
				$this->crear_sesion_asistencia($value);
				$sesion = Capacitacione::find($this->capacitacion_id)->sesiones()->where('numero_de_sesion', $value)->first();				
			}
			$sesion->personal()->syncWithoutDetaching(Capacitacione::find($this->capacitacion_id)->capacitacion_has_personal()->pluck('capacitacion_has_personal.id')->toArray());
			$this->sesion_id = $sesion->id;
			$this->fecha = $sesion->fecha;
			$this->hora_inicio = $sesion->hora_inicio;
			$this->hora_fin = $sesion->hora_fin;

		} else {		
			$this->sesion_id = null;
		}
	}

	public function crear_sesion_asistencia($value)
	{
		$capacitacion = Capacitacione::find($this->capacitacion_id);
		
		if($value==1) {
			$capacitacion->sesiones()->create([
				'fecha_sesion' => $capacitacion->fecha_capacitacion,
				'hora_inicio' => $capacitacion->hora_inicio,
				'hora_fin' => $capacitacion->hora_fin,
				'capacitacion_id' => $capacitacion->id,
				'active' => 1,
				'numero_de_sesion' => $value
			]);
		} else {
			$capacitacion->sesiones()->create([
				// 'fecha_sesion' => $capacitacion->fecha_capacitacion,
				'hora_inicio' => $capacitacion->hora_inicio,
				'hora_fin' => $capacitacion->hora_fin,
				'capacitacion_id' => $capacitacion->id,
				'active' => 1,
				'numero_de_sesion' => $value
			]);
		}

	}

	public function updatedFecha($value){
		$sesion = Sesione::find($this->sesion_id);
		$sesion->fecha = $value;
		$sesion->save();
		
		session()->flash('message', 'Fecha Actualizada');
	}

	public function updatedHoraInicio($value){
		$sesion = Sesione::find($this->sesion_id);
		$sesion->hora_inicio = $value;
		$sesion->save();
		session()->flash('message', 'Hora de Inicio Actualizada');

	}

	public function updatedHoraFin($value){
		$sesion = Sesione::find($this->sesion_id);
		$sesion->hora_fin = $value;
		$sesion->save();
		session()->flash('message', 'Hora de Fin Actualizada');

	}

	public function agregarSesion(){
		$capacitacion = Capacitacione::find($this->capacitacion_id);
		$capacitacion->cantidad_de_sesiones = $capacitacion->cantidad_de_sesiones + 1;
		$capacitacion->synced=false;
		$capacitacion->save();
		$this->crear_sesion_asistencia($capacitacion->cantidad_de_sesiones);
		$this->updateCapacitacion($this->capacitacion_id);
		$this->numero_sesion_id = $capacitacion->cantidad_de_sesiones;
		$this->updatedNumeroSesionId($capacitacion->cantidad_de_sesiones);
		// $this->sesion_id = Sesione::where('capacitacion_id', $this->capacitacion_id)->where('numero_de_sesion', $capacitacion->cantidad_de_sesiones)->first()->id;

	}

    public function render()
    {
		// dd(Asistencium::
		// select('asistencia.*')
		// ->orderBy('person.name', 'asc')
		// ->leftJoin('capacitacion_has_personal as chp', 'chp.id', '=', 'asistencia.capacitacion_has_personal_id')
		// ->leftJoin('personal as person', 'person.id', '=', 'chp.personal_id')
		// ->where('asistencia.sesion_id', $this->sesion_id)
		// ->get());
		$keyWord = '%'.$this->keyWord .'%';
        return view('livewire.asistenciums.view', [
            'asistencia' => Asistencium::
			select('asistencia.*')
			->orderBy('person.name', 'asc')
			->leftJoin('capacitacion_has_personal as chp', 'chp.id', '=', 'asistencia.capacitacion_has_personal_id')
			->leftJoin('personal as person', 'person.id', '=', 'chp.personal_id')
			->where('asistencia.sesion_id', $this->sesion_id)
			->get()
        ]);
    }

	public function buscar_dni()
	{
		$this->validate([
			'dni_search' => 'required|numeric|digits:8',
        ], [
			'dni_search.required' => 'El campo DNI es obligatorio.',
			'dni_search.numeric' => 'El campo DNI debe ser numérico.',
			'dni_search.digits' => 'El campo DNI debe tener 8 dígitos.',
		],[
			'dni_search' => 'DNI'
		]);

		$personal=null;
		$personal = Personal::where('dni', $this->dni_search)->first();
		if($personal)
		{
			$this->personal_id = $personal->id;
		}
		else
		{			
			$personalController = new PersonalController();
			$res = $personalController->actualizarPersonalNisira($this->dni_search);

			if($res['res'])
			{				
				$personal = Personal::where('dni', $this->dni_search)->first();
			}
			else
			{
				$personal = null;
				//mostrar ventana para confirmar si se desea ingresar el DNI o no
				$this->emit('confirmarIngresoDNI', $this->dni_search);

				return;
			}		
		}

		if ($personal) {

			$this->personal_id = $personal->id;

			$this->ingresar_asistencia();

		}
		else {
			$this->personal_id = null;
			session()->flash('message', 'No se ha logrado ingresar la asistencia de este personal.'.$this->dni_search);
		}
		$this->dni_search = null;
	}

	public function ingresar_dni()
	{
		
		$personalController = new PersonalController();
		$res = $personalController->ingresarDNI($this->dni_search);

		// $personal = Personal::create([
		// 	'dni' => $this->dni_search,
		// 	'estado' => 1,
		// 	'importado' => 0,
		// ]);
		
		$this->personal_id = $res;

		$this->ingresar_asistencia();

		$this->dni_search = null;
		$this->emit('closeModal');
	}

	public function cancel_no_guardar_dni()
	{
		$this->dni_search = null;		$this->emit('closeModal');

	}

	public function ingresar_asistencia()
	{
		$capacitacion_has_personal = Capacitacione::find($this->capacitacion_id)
			->capacitacion_has_personal()
			->where('personal_id', $this->personal_id)
			->first();

		//traer los campos necesarios para capacitacion_has_personal

		$personal = Personal::where('id',$this->personal_id)->select(
			// 'nombres',
			'empresa_id',
			'gerencia_id',
			'area_id',
			'cargo_id',
			'planilla_id',
			'sede_id',
			'tipo_de_trabajador_id',
			'tipo_de_personal_id'
		)->first()->toArray();

		// dd($personal);

		if (!$capacitacion_has_personal) {
			$capacitacion = Capacitacione::find($this->capacitacion_id);
			$capacitacion->personal()->syncWithoutDetaching([$this->personal_id => $personal]);

			$capacitacion_has_personal = Capacitacione::find($this->capacitacion_id)
				->capacitacion_has_personal()
				->where('personal_id', $this->personal_id)
				->first();
		}

		$asistencia = Asistencium::where('sesion_id', $this->sesion_id)
			->where('capacitacion_has_personal_id', $capacitacion_has_personal->id)
			->first();

		if ($asistencia) {
			$asistencia->active = 1;
			$asistencia->save();
		} else {
			Asistencium::create([
				'sesion_id' => $this->sesion_id,
				'capacitacion_has_personal_id' => $capacitacion_has_personal->id,
				'active' => 1,
			]);
		}

		$this->personal_id = null;

		session()->flash('message', 'Asistencia ingresada correctamente.');
	}
	
    public function filtro_asistencia() {
        if ($this->filtro_asistencia) {
            $this->filtro_asistencia = false;
        } else {
            $this->filtro_asistencia = true;
        }
    }

    public function filtro_no_asistencia() {
        if ($this->filtro_no_asistencia) {
            $this->filtro_no_asistencia = false;
        } else {
            $this->filtro_no_asistencia = true;
        }
    }
	
    public function cancel()
    {
        $this->resetInput();
        $this->updateMode = false;
    }
	
    private function resetInput()
    {		
		$this->sesion_id = null;
		$this->personal_id = null;
		$this->active = null;
		$this->observaciones = null;
		$this->empresa_id = null;
		$this->gerencia_id = null;
		$this->area_id = null;
		$this->cargo_id = null;
		$this->planilla_id = null;
		$this->sede_id = null;
		$this->tipo_de_trabajador_id = null;
		$this->tipo_de_personal_id = null;
		$this->capacitacion_id = null;
    }

    public function store()
    {
        $this->validate([
		'sesion_id' => 'required',
		'personal_id' => 'required',
        ]);

        Asistencium::create([ 
			'sesion_id' => $this-> sesion_id,
			'personal_id' => $this-> personal_id,
			'active' => $this-> active,
			'observaciones' => $this-> observaciones,
			'empresa_id' => $this-> empresa_id,
			'gerencia_id' => $this-> gerencia_id,
			'area_id' => $this-> area_id,
			'cargo_id' => $this-> cargo_id,
			'planilla_id' => $this-> planilla_id,
			'sede_id' => $this-> sede_id,
			'tipo_de_trabajador_id' => $this-> tipo_de_trabajador_id,
			'tipo_de_personal_id' => $this-> tipo_de_personal_id,
			'capacitacion_id' => $this-> capacitacion_id
        ]);
        
        $this->resetInput();
		$this->emit('closeModal');
		session()->flash('message', 'Asistencium creado correctamente.');
    }

    public function edit($id)
    {
        $record = Asistencium::findOrFail($id);

        $this->selected_id = $id; 
		$this->sesion_id = $record-> sesion_id;
		$this->personal_id = $record-> personal_id;
		$this->active = $record-> active;
		$this->observaciones = $record-> observaciones;
		$this->empresa_id = $record-> empresa_id;
		$this->gerencia_id = $record-> gerencia_id;
		$this->area_id = $record-> area_id;
		$this->cargo_id = $record-> cargo_id;
		$this->planilla_id = $record-> planilla_id;
		$this->sede_id = $record-> sede_id;
		$this->tipo_de_trabajador_id = $record-> tipo_de_trabajador_id;
		$this->tipo_de_personal_id = $record-> tipo_de_personal_id;
		$this->capacitacion_id = $record-> capacitacion_id;
		
        $this->updateMode = true;
    }

    public function update()
    {
        $this->validate([
		'sesion_id' => 'required',
		'personal_id' => 'required',
        ]);

        if ($this->selected_id) {
			$record = Asistencium::find($this->selected_id);
            $record->update([ 
			'sesion_id' => $this-> sesion_id,
			'personal_id' => $this-> personal_id,
			'active' => $this-> active,
			'observaciones' => $this-> observaciones,
			'empresa_id' => $this-> empresa_id,
			'gerencia_id' => $this-> gerencia_id,
			'area_id' => $this-> area_id,
			'cargo_id' => $this-> cargo_id,
			'planilla_id' => $this-> planilla_id,
			'sede_id' => $this-> sede_id,
			'tipo_de_trabajador_id' => $this-> tipo_de_trabajador_id,
			'tipo_de_personal_id' => $this-> tipo_de_personal_id,
			'capacitacion_id' => $this-> capacitacion_id
            ]);

            $this->resetInput();
            $this->updateMode = false;
		    $this->emit('closeModal');
			session()->flash('message', 'Asistencium actualizado correctamente.');
        }
    }

    public function destroy($id)
    {
        if ($id) {
            $record = Asistencium::where('id', $id);
            $record->delete();
        }
    }
}
