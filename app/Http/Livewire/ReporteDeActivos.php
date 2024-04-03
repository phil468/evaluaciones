<?php

namespace App\Http\Livewire;

use App\Models\Activo;
use App\Models\AsignacionHasActivo;
use App\Models\Cargo;
use App\Models\Devolucion;
use App\Models\Empresa;
use App\Models\Personal;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Livewire\Component;

class ReporteDeActivos extends Component
{
	public $loading = false;

	public
    $search_personal_dni = null, 
	$personal_id = null,
    $personal = null,
    $access_token = null,
    $activos_asignados = [],
    $activos_asignados_devueltos = [];

    public function mount() {
		$data = null;
		$response = Http::post("http://10.13.10.49:81/api/login?email=john.delacruz@vanguardfresh.pe&password=Ch4p1guard$");

		if ($response->successful()) {
			$data = $response->json();
			$this->access_token = $data['access_token'];

		} else {
			// La solicitud no fue exitosa, manejar el error
			$statusCode = $response->status();
			session()->flash('message-danger', 'No se tiene acceso al servidor del API. Error: '.$statusCode);
			$this->emit('alert-danger');
		}
        //$this->personal = Personal::orderBy('name')->where('estado',1)->select('name','id')->get()->toArray();
        //$this->personal = Personal::orderBy('name')->where('estado',1)->select('name','id')->get();
		
		$this->personal	=	Personal::orderBy('name')->where('estado',1)->select('name', 'id')->get()->toArray();
		// dd($this->personal);
    }

    public function render() {
		return view('livewire.reporte-de-activos.view', []);			
    }

    public function updatedPersonalId() {
		$this->listarActivosAsignados();
	}

	public function listarActivosAsignados() {
        $this->activos_asignados = 
        Activo::where('personal_id',$this->personal_id)
        ->with(
            'asignacion_has_activo',
            'asignacion_has_activo.performance',
            'asignacion_has_activo.vigencia',
            'asignacion_has_activo.accesorios',
            )
        ->get()->toArray();

        $this->activos_asignados_devueltos = 
        Devolucion::where('personal_id',$this->personal_id)
		->with(
			'activos_devueltos',
			'activos_devueltos.activo',
			'activos_devueltos.performance',
			// 'activos_devueltos.vigencia',
			'activos_devueltos.accesorios',
			'personal',
			'area',
			'empresa',
			'sede',
			'responsable',
			'responsable_area',
			'cargo',
			'responsable_cargo'
			)->get()->toArray();
	}
	
    public function buscar_dni() {
		$personal = Personal::where('dni', '=', $this->search_personal_dni)->first() ?? null;
		if ($personal) {
			$this->personal_id = $personal->id;
			session()->flash('message-success', 'DNI '. $this->search_personal_dni.' encontrado. Datos de usuario seleccionados.');
			$this->emit('alert-success');
		} else {
			$this->personal_id = null;

			$response2  = Http::withHeaders([
				'Authorization' => 'Bearer '.$this->access_token,
			])->get('http://10.13.10.49:81/api/manager/personal/'.$this->search_personal_dni);


			if ($response2->successful()) {
				$data = $response2->json();
				
				if(!$data) {
					$this->personal_id = null;

					session()->flash('message-danger', 'DNI '. $this->search_personal_dni.' no encontrado en la lista de personal de NISIRA.');
					$this->emit('alert-danger');

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
	
						//Recuperando o Insertando Gerencia
						// if(!empty(trim($row['idgerencia']))){
						// $gerencia = Gerencia::firstOrCreate(
						// 	['idarea_nisira' => trim($row['idgerencia'])],
						// 	['name' => trim($row['gerencia']) , 'estado' => 1]
						// );
						// }
	
						//Recuperando o Insertando Area
						// if(!empty(trim($row['idarea']))){
						// $area = Area::firstOrCreate(
						// 	['idarea_nisira' => trim($row['idarea'])],
						// 	['name' => trim($row['area']) , 'estado' => 1]
						// );
						// }
	
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
	
						//Recuperando o Insertando Sede
						// if(!empty(trim($row['idsucursal']))){
						// $sede = Sede::firstOrCreate(
						// 	['idsede_nisira' => trim($row['idsucursal'])],
						// 	['name' => trim($row['sede']) , 'estado' => 1]
						// );
						// }
	
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
								// 'gerencia_id' => $gerencia->id??NULL,
								// 'area_id' => $area->id??NULL,
								// 'sede_id' => $sede->id??NULL,
								'cargo_id' => $cargo->id??NULL,
								// 'correo_empresa' => trim($row['correo_empresa']),
								// 'celular_empresa' => trim($row['celular_empresa']),
								'correo_personal' => trim($row['EMAIL']),
								// 'telefono_personal' => trim($row['telefono']),
								'celular_personal' => trim($row['CELULAR']),
								// 'estado' => trim($row['estado']) == "" ? 1 : trim($row['estado']),
								// 'genero' => trim($row['sexo']) == 'Masculino' ? 'H': (trim($row['sexo']) == 'Femenino' ? 'M': ''),
								'fecha_ingreso'  => trim($row['FECHA_INGRESO']) == '' ? NULL : (Carbon::createFromFormat('Y-m-d H:i:s.u', trim($row['FECHA_INGRESO']))->toDateString())
							]
						);
					// }
	
					if ($personal) {                        
                        $this->personal = Personal::orderBy('name')->where('estado',1)->pluck('name', 'id')->toArray();
						$this->personal_id = $personal->id;
						session()->flash('message-success', 'DNI '. $this->search_personal_dni.' importados desde NISIRA. Datos de usuario seleccionados.');
						$this->emit('alert-success');
					} else {
						session()->flash('message-danger', 'DNI '. $this->search_personal_dni.' no encontrado en la lista de personal. Se consultaron los datos de NISIRA pero hubo un error en la inserción/lectura de los datos');
						$this->emit('alert-danger');
					}

				}
			} else {
				// La solicitud no fue exitosa, manejar el error
				$statusCode = $response2->status();
				session()->flash('message-danger', 'DNI '. $this->search_personal_dni.' no encontrado en la lista de personal ni en los registros de NISIRA. Error: '.$statusCode);
				$this->emit('alert-danger');
				// Manejar el código de estado de error
			}
		}
        if ($this->personal_id) {
            $this->listarActivosAsignados();
        } else {            
			session()->flash('message-danger', 'DNI '. $this->search_personal_dni.' no encontrado en la lista de personal ni en los registros de NISIRA.');
			$this->emit('alert-danger');
        }
		$this->search_personal_dni = null;
		$this->emit('personalIdSelected', $this->personal_id);

    }
	
}
