<?php

namespace App\Http\Livewire;

use App\Exports\PersonalExport;
use App\Http\Controllers\ApiController;
use App\Imports\PersonalImport;
use App\Models\Area;
use App\Models\Cargo;
use App\Models\Empresa;
use App\Models\Gerencia;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Personal;
use App\Models\Sede;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Maatwebsite\Excel\Facades\Excel;
use Livewire\WithFileUploads;

class Personals extends Component
{
    use WithPagination;
    use WithFileUploads;

	protected $paginationTheme = 'bootstrap';
    public $selected_id, 
	$keyWord, 
	$dni, 
	$name, 
	$nombres, 
	$apellido_paterno, 
	$apellido_materno, 
	$empresa_id, 
	$sede_id, 
	$gerencia_id, 
	$area_id, 
	$cargo_id, 
	$correo_empresa, 
	$celular_empresa, 
	$correo_personal, 
	$telefono_personal, 
	$celular_personal, 
	$foto, 
	$estado, 
	$genero, 
	$fecha_ingreso, 
	$file,

	$empresas,
	$gerencias,
	$sedes,
	$areas,
	$cargos,
	$access_token;
	public $token = null;

    public $updateMode = true;

	protected $listeners = [
        'edit',
		'selectedUpdated' => 'updateSelected'
    ];

	public $selectedFromPersonalTable = [];

    public function updateSelected($value)
    {
        $this->selectedFromPersonalTable = $value;
    }

	public function tokenValidation() {
		$tokenController = new ApiController();

		if (isset($this->token)) {
			if ($tokenController->checkTokenExpiration($this->token)) {
                $res = $tokenController->login();
				$this->evaluarResultado($res);
            } 
		} else {
			$res = $tokenController->login();
			$this->evaluarResultado($res);
		}
	}

	public function evaluarResultado($res) {
		if($res['statusCode'] == 200) {
			$this->token = $res['token'];
		} else {
			$this->token = null;
			session()->flash('message-danger', 'No se tiene acceso al servidor del API. Error: '.$res['statusCode']);
			$this->emit('alert-danger');
		}
	}

	public function listarSelects() {
		$this->empresas 	= 	Empresa::		orderBy('name')->where('estado',1)->select('name as label', 'id as value')->get()->toArray();
		$this->gerencias 	= 	Gerencia::		orderBy('name')->where('estado',1)->select('name as label', 'id as value')->get()->toArray();
		$this->sedes 		= 	Sede::			orderBy('name')->where('estado',1)->select('name as label', 'id as value')->get()->toArray();
		$this->areas 		= 	Area::			orderBy('name')->where('estado',1)->select('name as label', 'id as value')->get()->toArray();
		$this->cargos 		=	Cargo::			orderBy('name')->where('estado',1)->select('name as label', 'id as value')->get()->toArray();

		$this->emit('listar_selects',
			$this->empresas,
			$this->gerencias,
			$this->sedes,
			$this->areas,
			$this->cargos,
		);
		$this->actualizarDatosPersonal();
	}

	public function mount() {
		
		// $data = null;
		
		$tokenController = new ApiController();

        $this->token = $tokenController->getLastToken();

		// $response = Http::post("http://10.13.10.49:81/api/login?email=john.delacruz@vanguardfresh.pe&password=Ch4p1guard$");

		// if ($response->successful()) {
		// 	$data = $response->json();
		// 	$this->access_token = $data['access_token'];
		// } else {
		// 	// La solicitud no fue exitosa, manejar el error
		// 	$statusCode = $response->status();
		// 	session()->flash('message-danger', 'No se tiene acceso al servidor del API. Error: '.$statusCode);
		// 	$this->emit('alert-danger');
		// }
	}

	public function buscar_dni() {
		if($this->selected_id == 0) {

		$personal = Personal::where('dni', '=', $this->dni)->first() ?? null;
		if ($personal) {
			$this->edit($personal->id);
			session()->flash('message-busqueda-dni', 'DNI '. $this->dni.' encontrado. Datos de usuario seleccionados.');
		} else {
			$this->tokenValidation();

			if (isset($this->token)) {

				$this->empresa_id = null;
				$this->gerencia_id = null;
				$this->sede_id = null;
				$this->area_id = null;
				$this->cargo_id = null;

				$message_error = '';

				$response2  = Http::withHeaders([
					'Authorization' => 'Bearer '.$this->token->access_token,
				])->get('http://10.13.10.49:81/api/manager/personal/'.$this->dni);


				if ($response2->successful()) {
					$data = $response2->json();
					
					if(!$data) {
						$this->empresa_id = null;
						$this->gerencia_id = null;
						$this->sede_id = null;
						$this->area_id = null;
						$this->cargo_id = null;

						session()->flash('message-busqueda-dni', 'Se consultó en el ERP NISIRA. DNI '. $this->dni.' no encontrado.');

					} else {
						$row=$data[0];
						// foreach ($data as $row) 
						// {
							//Recuperando o Insertando Empresa
							if(!empty(trim($row['IDEMPRESA']))){
								$empresa = Empresa::firstOrCreate(
									['idempresa_nisira' => trim($row['IDEMPRESA'])],
									['name' => trim($row['empresa']) , 'estado' => 1]
								);
							}
		
							//Recuperando o Insertando Cargo
							if(!empty(trim($row['IDCARGO']))){
							$cargo = Cargo::firstOrCreate(
								['idcargo_nisira' => trim($row['IDCARGO'])],
								['name' => trim($row['cargo']) , 'estado' => 1]
							);
							} else {
								$cargo =  Cargo::where('name', trim($row['cargo']))->firstOr(function () {
									return NULL;
								});
							}
		
							//Actualizando o Insertando Personal
							$personal = Personal::updateOrCreate(
								[
									'dni' => trim($row['NRODOCUMENTO']),
								],
								[
									'name' =>trim($row['nombrecompleto']),
									'nombres' => trim($row['NOMBRES']),
									'apellido_paterno' => trim($row['A_PATERNO']),
									'apellido_materno' => trim($row['A_MATERNO']),
									'empresa_id' => $empresa->id??NULL,
									'cargo_id' => $cargo->id??NULL,
									'correo_personal' => trim($row['EMAIL']),
									'celular_personal' => trim($row['CELULAR']),
									'fecha_ingreso'  => trim($row['FECHA_INGRESO']) == '' ? NULL : (Carbon::createFromFormat('Y-m-d H:i:s.u', trim($row['FECHA_INGRESO']))->toDateString())
								]
							);
						// }
		
						if ($personal) {

							$this->edit($personal->id);
							session()->flash('message-busqueda-dni', 'DNI '. $this->dni.' importados desde NISIRA. Datos de usuario seleccionados.');
							// $this->emit('alert-success');
							$this->listarSelects();
						} else {
							session()->flash('message-busqueda-dni', 'DNI '. $this->dni.' no encontrado en la lista de personal. Se consultaron los datos de NISIRA pero hubo un error en la inserción/lectura de los datos');
							// $this->emit('alert-danger');
							// Manejar el código de estado de error
						}
					}
				} else {
					// La solicitud no fue exitosa, manejar el error
					$statusCode = $response2->status();
					session()->flash('message-busqueda-dni', 'DNI '. $this->dni.' no encontrado en la lista de personal ni en los registros de NISIRA. Error: '.$statusCode);
					// $this->emit('alert-danger');
					// Manejar el código de estado de error
				}
			}
		}
	}
		// $this->actualizarDatosPersonal();
    }
		
	public function actualizarDatosPersonal () {
		$this->emit('actualizarDatosP',
			$this->empresa_id,
			$this->gerencia_id,
			$this->sede_id,
			$this->area_id,
			$this->cargo_id,
		);
	}

    public function render()
    {
		$keyWord = '%'.$this->keyWord .'%';
        return view('livewire.personals.view', [
            'personals' => Personal::latest('personal.id')
						->select(
							'personal.*',
							'personal.name as name',
						)
						->orWhere('personal.dni', 'LIKE', $keyWord)
						->orWhere('personal.name', 'LIKE', $keyWord)
						->orWhere('personal.nombres', 'LIKE', $keyWord)
						->orWhere('personal.apellido_paterno', 'LIKE', $keyWord)
						->orWhere('personal.apellido_materno', 'LIKE', $keyWord)
						->orWhere('personal.correo_empresa', 'LIKE', $keyWord)
						->orWhere('personal.celular_empresa', 'LIKE', $keyWord)
						->orWhere('personal.correo_personal', 'LIKE', $keyWord)
						->orWhere('personal.telefono_personal', 'LIKE', $keyWord)
						->orWhere('personal.celular_personal', 'LIKE', $keyWord)
						->orWhere('personal.foto', 'LIKE', $keyWord)
						->orWhere('personal.genero', 'LIKE', $keyWord)
						->orWhere('personal.fecha_ingreso', 'LIKE', $keyWord)
                        ->orWhere('empresas.name', 'LIKE', $keyWord)
                        ->leftJoin('empresas', function ($join) {
                            $join->on('personal.empresa_id', '=', 'empresas.id');
                        })
                        ->orWhere('sedes.name', 'LIKE', $keyWord)
                        ->leftJoin('sedes', function ($join) {
                            $join->on('personal.sede_id', '=', 'sedes.id');
                        })
                        ->orWhere('gerencias.name', 'LIKE', $keyWord)
                        ->leftJoin('gerencias', function ($join) {
                            $join->on('personal.gerencia_id', '=', 'gerencias.id');
                        })
                        ->orWhere('areas.name', 'LIKE', $keyWord)
                        ->leftJoin('areas', function ($join) {
                            $join->on('personal.area_id', '=', 'areas.id');
                        })
                        ->orWhere('cargos.name', 'LIKE', $keyWord)
                        ->leftJoin('cargos', function ($join) {
                            $join->on('personal.cargo_id', '=', 'cargos.id');
                        })
						->paginate(10),
						// 'empresas' 	=> 	Empresa::	orderBy('name')->where('estado',1)->select('name', 'id')->get()->toArray(),
						// 'gerencias' => 	Gerencia::	orderBy('name')->where('estado',1)->select('name', 'id')->get()->toArray(),
						// 'sedes' 	=> 	Sede::		orderBy('name')->where('estado',1)->select('name', 'id')->get()->toArray(),
						// 'areas' 	=> 	Area::		orderBy('name')->where('estado',1)->select('name', 'id')->get()->toArray(),
						// 'cargos' 	=>	Cargo::		orderBy('name')->where('estado',1)->select('name', 'id')->get()->toArray(),
        ]);
    }
	
    public function cancel()
    {
        $this->resetInput();
		$this->emit('limpiarDatosP');
        // $this->updateMode = false;
    }
	
    private function resetInput()
    {		
		$this->dni = null;
		$this->name = null;
		$this->nombres = null;
		$this->apellido_paterno = null;
		$this->apellido_materno = null;
		$this->empresa_id = null;
		$this->sede_id = null;
		$this->gerencia_id = null;
		$this->area_id = null;
		$this->cargo_id = null;
		$this->correo_empresa = null;
		$this->celular_empresa = null;
		$this->correo_personal = null;
		$this->telefono_personal = null;
		$this->celular_personal = null;
		$this->foto = null;
		$this->estado = null;
		$this->genero = null;
		$this->fecha_ingreso = null;
		$this->file = null;
    }

	public function create() 
	{
		$this->estado=true;
        $this->listarSelects();
        $this->updateMode = true;
	}

    public function store()
    {
        $this->validate([
			'dni' => 'required'
        ]);

        Personal::create([ 
			'dni' => $this-> dni,
			'name' => $this-> name,
			'nombres' => $this-> nombres,
			'apellido_paterno' => $this-> apellido_paterno,
			'apellido_materno' => $this-> apellido_materno,
			'empresa_id' => $this-> empresa_id,
			'sede_id' => $this-> sede_id,
			'gerencia_id' => $this-> gerencia_id,
			'area_id' => $this-> area_id,
			'cargo_id' => $this-> cargo_id,
			'correo_empresa' => $this-> correo_empresa,
			'celular_empresa' => $this-> celular_empresa,
			'correo_personal' => $this-> correo_personal,
			'telefono_personal' => $this-> telefono_personal,
			'celular_personal' => $this-> celular_personal,
			'foto' => $this-> foto,
			'estado' => $this-> estado,
			'genero' => $this-> genero,
			'fecha_ingreso' => !empty($value['fecha_ingreso']) ? $value['fecha_ingreso'] : null,

			// 'fecha_ingreso' => $this-> fecha_ingreso,
        ]);
        
        $this->resetInput();
		$this->emit('limpiarDatosP');
		$this->emit('closeModal');
		session()->flash('message', 'Personal creado correctamente.');
    }

    public function edit($id)
    {
		if ($id != 0) {
			$this->resetValidation();
			$this->resetInput();
			$record = Personal::findOrFail($id);
	
			$this->selected_id = $id; 
			$this->dni = $record-> dni;
			$this->name = $record-> name;
			$this->nombres = $record-> nombres;
			$this->apellido_paterno = $record-> apellido_paterno;
			$this->apellido_materno = $record-> apellido_materno;
			$this->empresa_id = $record-> empresa_id;
			$this->sede_id = $record-> sede_id;
			$this->gerencia_id = $record-> gerencia_id;
			$this->area_id = $record-> area_id;
			$this->cargo_id = $record-> cargo_id;
			$this->correo_empresa = $record-> correo_empresa;
			$this->celular_empresa = $record-> celular_empresa;
			$this->correo_personal = $record-> correo_personal;
			$this->telefono_personal = $record-> telefono_personal;
			$this->celular_personal = $record-> celular_personal;
			$this->foto = $record-> foto;
			$this->estado = $record-> estado;
			$this->genero = $record-> genero;
			$this->fecha_ingreso = $record-> fecha_ingreso;
		} else {
			$this->resetValidation();
			$this->resetInput();
			$this->selected_id = 0; 
			$this->estado=true;
		}

        $this->updateMode = true;
        $this->listarSelects();
    }

    public function update()
    {
		// dd($this-> empresa_id);
        $this->validate([
			'dni' => 'required'
        ]);

        if ($this->selected_id) {
			$record = Personal::find($this->selected_id);
            $record->update([ 
			'dni' => $this-> dni,
			'name' => $this-> name,
			'nombres' => $this-> nombres,
			'apellido_paterno' => $this-> apellido_paterno,
			'apellido_materno' => $this-> apellido_materno,
			'empresa_id' => $this-> empresa_id,
			'sede_id' => $this-> sede_id,
			'gerencia_id' => $this-> gerencia_id,
			'area_id' => $this-> area_id,
			'cargo_id' => $this-> cargo_id,
			'correo_empresa' => $this-> correo_empresa,
			'celular_empresa' => $this-> celular_empresa,
			'correo_personal' => $this-> correo_personal,
			'telefono_personal' => $this-> telefono_personal,
			'celular_personal' => $this-> celular_personal,
			'foto' => $this-> foto,
			'estado' => $this-> estado,
			'genero' => $this-> genero,
			'fecha_ingreso' => $this-> fecha_ingreso
            ]);

            $this->resetInput();
			$this->emit('limpiarDatosP');
		    $this->emit('closeModal');
			session()->flash('message', 'Personal actualizado correctamente.');
        }
    }

    public function destroy($id)
    {
        if ($id) {
            $record = Personal::where('id', $id);
            $record->first()->delete();
        }
    }

	public function importar()
    {
            $this->validate([
                'file' => 'required|file|mimes:xls,xlsx'    
            ]);
     
                $cs =  Excel::import(new PersonalImport, $this->file);
        
                $this->resetInput();                
               
                session()->flash('message', 'Personal importado correctamente.');
                $this->emit('closeModal');
                $this->emit('alert');
    }

    public function exportar()
    {
        return Excel::download(new PersonalExport, 'personal.xlsx');
    }
}
