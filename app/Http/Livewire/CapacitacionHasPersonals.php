<?php

namespace App\Http\Livewire;

use App\Models\Area;
use App\Models\Capacitacione;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\CapacitacionHasPersonal;
use App\Models\Empresa;
use App\Models\Gerencia;
use App\Models\Personal;
use App\Models\Sede;

class CapacitacionHasPersonals extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $personal_id, $active, $observaciones, $empresa_id, $gerencia_id, $area_id, $cargo_id, $planilla_id, $sede_id, $tipo_de_trabajador_id, $tipo_de_personal_id;
    public $areas, 
	$gerencias,
	$sedes,
	$name_personal; 	
	public $updateMode = false;

	public $capacitacion_id;

	public $capacitacion;

	protected $listeners = [
        'edit',
		'selectedUpdated' => 'updateSelected'
    ];
	
	public function listarSelects() {
		// $this->empresas 	= 	Empresa::		orderBy('name')->where('estado',1)->select('name as label', 'id as value')->get()->toArray();
		$this->gerencias 	= 	Gerencia::		orderBy('name')->where('estado',1)->select('name as label', 'id as value')->get()->toArray();
		$this->sedes 		= 	Sede::			orderBy('name')->where('estado',1)->select('name as label', 'id as value')->get()->toArray();
		$this->areas 		= 	Area::			orderBy('name')->where('estado',1)->select('name as label', 'id as value')->get()->toArray();

	$this->emit('listar_selects',
			$this->gerencias,
			$this->sedes,
			$this->areas,
		);
		$this->actualizarDatosPersonal();
	}

	public function actualizarDatosPersonal () {
		$this->emit('actualizarDatosP',
			$this->gerencia_id,
			$this->sede_id,
			$this->area_id,
		);
	}

	public $selectedFromPersonalTable = [];

	public function updateSelected($value)
	{
		$this->selectedFromPersonalTable = $value;
	}

    public function mount($capacitacion_id)
    {
        $this->capacitacion_id = $capacitacion_id;
		$this->capacitacion = Capacitacione::where('id',$capacitacion_id)
		->with(['tipo_capacitacion','tema','sede','expositor','empresa','status','modalidad','registrador'])
		->get()
		->toArray();

		// dd(Capacitacione::find($capacitacion_id));
    }

    public function render()
    {
		$keyWord = '%'.$this->keyWord .'%';
        return view('livewire.capacitacion-has-personals.view', 
		[ 'capacitacion' => $this->capacitacion,
		//$this->capacitacion
            'capacitacionHasPersonals' => CapacitacionHasPersonal::orderBy('personal_name', 'asc')
			->select('capacitacion_has_personal.*','personal.name as personal_name')
						->where('capacitacion_id', $this->capacitacion_id)
						->with(['personal'
						])
						->leftJoin('personal', 'personal.id', 'capacitacion_has_personal.personal_id')
						->get(),
        ]);
    }

	public function agregarSeleccionados()
	{
		$capacitacion = Capacitacione::find($this->capacitacion_id);

		// Supongamos que $this->selectedFromPersonalTable es un array de ids
		$personalIds = $this->selectedFromPersonalTable;

		// Realiza una consulta en el modelo Personal para obtener los datos correspondientes a esos ids
		$personalData = Personal::whereIn('id', $personalIds)->get();

		// Inicializa un array para almacenar los datos reorganizados
		$resultArray = [];

		// Itera sobre los resultados y organiza la información
		foreach ($personalData as $personal) {
			$resultArray[$personal->id] = [
				'gerencia_id' => $personal->gerencia_id,
				'area_id' => $personal->area_id,
				'sede_id' => $personal->sede_id,
				'cargo_id' => $personal->cargo_id,
				'planilla_id' => $personal->planilla_id,
				'tipo_de_trabajador_id' => $personal->tipo_de_trabajador_id,
				'tipo_de_personal_id' => $personal->tipo_de_personal_id,
				'empresa_id' => $personal->empresa_id,
			];
		}

		// dd($resultArray);
		$capacitacion->personal()->syncWithoutDetaching($resultArray);

		$capacitacion->personal()->syncWithoutDetaching($this->selectedFromPersonalTable);
		$this->selectedFromPersonalTable = [];
		$this->emit('limpiarSeleccionPersonalTable');
		$this->emit('refrescarRegistroTable');
		session()->flash('message', 'Personal creado correctamente.');
	}

	public function cancelarSeleccionados()
	{
		$this->selectedFromPersonalTable = [];
		// $this->emit('closeModal');
		session()->flash('message', 'Personal creado correctamente.');

	}

	public function quitarAsistente($value)
	{
		// dd($this->selectedFromPersonalTable);
		// foreach ($this->selectedFromPersonalTable as $key => $value) {
			// dd($value);
		CapacitacionHasPersonal::find($value)
			->delete();
		// }
		$this->selectedFromPersonalTable = [];
		// $this->emit('closeModal');
		session()->flash('message', 'Personal quitado correctamente.');

	}

    public function cancel()
    {
        $this->resetInput();
		$this->emit('limpiarDatosP');
        // $this->updateMode = false;
    }
	
    private function resetInput()
    {		
		$this->name_personal = null;
		$this->personal_id = null;
		$this->capacitacion_id = null;
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
    }

    public function store()
    {
        $this->validate([
		'personal_id' => 'required',
		'capacitacion_id' => 'required',
        ]);

        CapacitacionHasPersonal::create([ 
			'personal_id' => $this-> personal_id,
			'capacitacion_id' => $this-> capacitacion_id,
			'active' => $this-> active,
			'observaciones' => $this-> observaciones,
			'empresa_id' => $this-> empresa_id,
			'gerencia_id' => $this-> gerencia_id,
			'area_id' => $this-> area_id,
			'cargo_id' => $this-> cargo_id,
			'planilla_id' => $this-> planilla_id,
			'sede_id' => $this-> sede_id,
			'tipo_de_trabajador_id' => $this-> tipo_de_trabajador_id,
			'tipo_de_personal_id' => $this-> tipo_de_personal_id
        ]);
        
        $this->resetInput();
		$this->emit('limpiarDatosP');
		$this->emit('closeModal');
		session()->flash('message', 'CapacitacionHasPersonal creado correctamente.');
    }

    public function edit($id)
    {
		if ($id != 0) {
        $record = CapacitacionHasPersonal::findOrFail($id);

		$this->name_personal = $record->personal->name;
        $this->selected_id = $id; 
		$this->personal_id = $record-> personal_id;
		// $this->capacitacion_id = $record-> capacitacion_id;
		// $this->active = $record-> active;
		// $this->observaciones = $record-> observaciones;
		$this->empresa_id = $record-> empresa_id;
		$this->gerencia_id = $record-> gerencia_id;
		$this->area_id = $record-> area_id;
		// $this->cargo_id = $record-> cargo_id;
		// $this->planilla_id = $record-> planilla_id;
		$this->sede_id = $record-> sede_id;
		// $this->tipo_de_trabajador_id = $record-> tipo_de_trabajador_id;
		// $this->tipo_de_personal_id = $record-> tipo_de_personal_id;
		
		} else {
			$this->resetValidation();
			$this->resetInput();
			$this->selected_id = 0; 
			$this->active=true;
		}

		$this->updateMode = true;
		$this->listarSelects();
    }

    public function update()
    {
        $this->validate([
		'personal_id' => 'required',
		'capacitacion_id' => 'required',
        ]);

        if ($this->selected_id) {
			$record = CapacitacionHasPersonal::find($this->selected_id);
            $record->update([ 
			// 'personal_id' => $this-> personal_id,
			// 'capacitacion_id' => $this-> capacitacion_id,
			// 'active' => $this-> active,
			// 'observaciones' => $this-> observaciones,
			// 'empresa_id' => $this-> empresa_id,
			'gerencia_id' => $this-> gerencia_id,
			'area_id' => $this-> area_id,
			// 'cargo_id' => $this-> cargo_id,
			// 'planilla_id' => $this-> planilla_id,
			'sede_id' => $this-> sede_id,
			// 'tipo_de_trabajador_id' => $this-> tipo_de_trabajador_id,
			// 'tipo_de_personal_id' => $this-> tipo_de_personal_id
            ]);

			$personal = Personal::find($this->personal_id);
			$personal->update([
				'gerencia_id' => $this-> gerencia_id,
				'area_id' => $this-> area_id,
				'sede_id' => $this-> sede_id,
			]);

            $this->resetInput();
            $this->updateMode = false;
		    $this->emit('closeModal');
			session()->flash('message', 'CapacitacionHasPersonal actualizado correctamente.');
        }
    }

    public function destroy($id)
    {
        if ($id) {
            $record = CapacitacionHasPersonal::where('id', $id);
            $record->delete();
        }
    }


}
