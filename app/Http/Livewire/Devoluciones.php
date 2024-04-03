<?php

namespace App\Http\Livewire;

use App\Http\Controllers\ApiController;
use App\Models\Accesorio;
use App\Models\Activo;
use App\Models\Area;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Asignacione;
use App\Models\AsignacionHasActivo;
use App\Models\Cargo;
use App\Models\Devolucion;
use App\Models\DevolucionHasActivo;
use App\Models\Empresa;
use App\Models\Gerencia;
use App\Models\Performance;
use App\Models\Personal;
use App\Models\Sede;
use App\Models\Status;
use App\Models\Vigencium;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class Devoluciones extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $personal_id, $empresa_id, $gerencia_id, $sede_id, $area_id, $cargo_id, $fecha, 
	$responsable_id, $responsable_area_id, $responsable_cargo_id, $created_by, $updated_by, $deleted_by, $pdf,

	$selected_activo_id,
	$performance_id,
	$vigencia_id,
	$fecha_vigencia,
	$observaciones_activo,
    $observaciones_devolucion,
	$activo_accesorios=[],
    $accesorios_asignado_activo=[],
	$selected_activo_index,
	$activos_precargados,
	
	$personal,
	$empresas,
	$gerencias,
	$sedes,
	$areas,
	$cargos,
	$responsables,
	$vigencias,
	$condiciones,
	$accesorios,

	$createMode = false, 
	$viewMode = false,
	$updateMode = false,
	$pdfMode = false,
	$search_personal_dni = null,
	$dni = null;
	public $step = [1=>false, 2=>false, 3=>false];
	public $activos_selected=[];
	public $activo_id, $activo_precargado_id;
	public $activos_list=[];
	public $estado_stock;
	public $estado_asignado;
	public $condicion_nuevo;
	public $condicion_usado;
	public $devolucion_guardada;
	public $id_personal_firma;
	public $firma;
	public $firma_personal;
	public $firma_responsable;
	public $tipo_firma;
	public $correo_personal;
    public $messages_validate = [
        'activos_asignados.required' => 'Debe agregar por lo menos 1 activo',
        'activos_asignados.*.asignacion_has_activo.performance.id' => 'El campo es obligatorio ',
        // 'activos_asignados.*.vigencia_id.required' => 'El campo es obligatorio',
    ];
	public $access_token = null;
	public $token = null;
	
	protected $listeners = ['guardarFirma' => 'guardarFirma', 'descargarPDF' => 'descargarPDF'];

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

    public $activos_asignados = [];

    public function mount() {
        $data = null;

		$tokenController = new ApiController();

        $this->token = $tokenController->getLastToken();

        // $response = Http::post("http://10.13.10.49:81/api/login?email=john.delacruz@vanguardfresh.pe&password=Ch4p1guard$");

        // if ($response->successful()) {
        //     $data = $response->json();
        //     $this->access_token = $data['access_token'];
        //     //dd($data);
        // } else {
        //     // La solicitud no fue exitosa, manejar el error
        //     $statusCode = $response->status();
        //     session()->flash('message-danger', 'No se tiene acceso al servidor del API. Error: '.$statusCode);
        //     $this->emit('alert-danger');
        // }

        $this->estado_stock = Status::where('name', '=', 'stock')->first()->id ?? null;
        if ($this->estado_stock == null) {
            session()->flash('message-danger', 'No se encuentra definido el estado "stock". Por favor revisar el nombre de los estados');
            $this->emit('alert-danger');
        }

        $this->estado_asignado = Status::where('name', '=', 'asignado')->first()->id ?? null;
        if ($this->estado_asignado == null) {
            session()->flash('message-danger', 'No se encuentra definido el estado "asignado". Por favor revisar el nombre de los estados');
            $this->emit('alert-danger');
        }
        
        // $this->estado_asignado = Status::where('name', '=', 'asignado')->first()->id ?? null;
        // if ($this->estado_asignado == null) {
        //     session()->flash('message-danger', 'No se encuentra definido el estado "asignado". Por favor revisar el nombre de los estados');
        //     $this->emit('alert-danger');
        // }
        
        $this->condicion_nuevo = Performance::where('name', '=', 'nuevo')->first() ?? null;
        if ($this->condicion_nuevo == null) {
            session()->flash('message-danger', 'No se encuentra definida la condición "nuevo". Por favor revisar el nombre de las condiciones');
            $this->emit('alert-danger');
        }

        $this->condicion_usado = Performance::where('name', '=', 'usado')->first() ?? null;
        if ($this->condicion_usado == null) {
            session()->flash('message-danger', 'No se encuentra definida la condición "usado". Por favor revisar el nombre de las condiciones');
            $this->emit('alert-danger');
        }
        // $this->viewPdf(16);
    }

    public function render() {
		return view('livewire.devoluciones.view', []);
    }	

	public function listarSelects() {
		$this->personal 	=	Personal::		orderBy('name')->where('estado',1)->select('name as label', 'id as value')->get()->toArray();
		$this->empresas 	= 	Empresa::		orderBy('name')->where('estado',1)->select('name as label', 'id as value')->get()->toArray();
		$this->gerencias 	= 	Gerencia::		orderBy('name')->where('estado',1)->select('name as label', 'id as value')->get()->toArray();
		$this->sedes 		= 	Sede::			orderBy('name')->where('estado',1)->select('name as label', 'id as value')->get()->toArray();
		$this->areas 		= 	Area::			orderBy('name')->where('estado',1)->select('name as label', 'id as value')->get()->toArray();
		$this->cargos 		=	Cargo::			orderBy('name')->where('estado',1)->select('name as label', 'id as value')->get()->toArray();
		$this->responsables =	Personal::		orderBy('personal.name')->where('personal.estado',1)
												->join('users', 'personal.id', 'users.personal_id')
												->select('personal.name as label', 'personal.id as value')
												->get()->toArray();

		$this->vigencias 	=	Vigencium::		orderBy('name')->where('estado',1)->pluck('name', 'id')->toArray();
		$this->condiciones 	=	Performance::	orderBy('name')->where('estado',1)->pluck('name', 'id')->toArray();
		$this->accesorios 	=	Accesorio::orderBy('name')->where('estado',1)->get();

		$this->emit('listar_selects',
			$this->personal,
			$this->empresas,
			$this->gerencias,
			$this->sedes,
			$this->areas,
			$this->cargos,
			$this->responsables
		);
		$this->actualizarDatosPersonal();
		$this->actualizarDatosResponsable();
	}

    public function create() {
        $this->createMode = true;
        $this->responsable_id       = auth()->user()->personal->id;
        $this->responsable_area_id  = auth()->user()->personal->area_id;
        $this->responsable_cargo_id = auth()->user()->personal->cargo_id;
        $this->fecha                = date('Y-m-d');
        $this->listarSelects();
    }
        
    public function view($id) {
        $this->edit($id);        
        $this->createMode = false;
        $this->updateMode = false;
        $this->viewMode = true;
    }
	
    public function cancel()
    {
        $this->resetInput();
        $this->resetValidation();
        $this->updateMode = false;
        $this->createMode = false;
        $this->viewMode = false;
		$this->pdfMode = false;
    }
	
    public function cancel_seleccionar()
    {		
		$this->activos_precargados = [];
		$this->emit('closeModal');
    }

    private function resetInput()
    {
		$this->personal_id = null;
		$this->empresa_id = null;
		$this->gerencia_id = null;
		$this->sede_id = null;
		$this->area_id = null;
		$this->cargo_id = null;
		$this->dni = null;
		$this->fecha = null;
		$this->responsable_id = null;
		$this->responsable_area_id = null;
		$this->responsable_cargo_id = null;
		$this->created_by = null;
		$this->updated_by = null;
		$this->deleted_by = null;
		$this->pdf = null;
		$this->correo_personal = null;
		$this->firma = null;
		$this->activos_list = [];
		$this->activos_asignados = [];
		$this->firma_personal = null;
		$this->firma_responsable = null;
		$this->tipo_firma = null;
    }

	public function updatedPersonalId($value) {
		$personal = Personal::find($value) ?? null;
		if ($personal) {
			$this->empresa_id = $personal->empresa_id;
			$this->gerencia_id = $personal->gerencia_id;
			$this->sede_id = $personal->sede_id;
			$this->area_id = $personal->area_id;
			$this->cargo_id = $personal->cargo_id;
			$this->dni = $personal->dni;
            $this->cargar_activos_asignados_a_personal();	
		} else {
			$this->empresa_id = null;
			$this->gerencia_id = null;
			$this->sede_id = null;
			$this->area_id = null;
			$this->cargo_id = null;
			$this->dni = null;
		}
		$this->actualizarDatosPersonal();
	}

	public function actualizarDatosPersonal () {
		$this->emit('actualizarDatosP',
			$this->personal_id,
			$this->empresa_id,
			$this->gerencia_id,
			$this->sede_id,
			$this->area_id,
			$this->cargo_id,
		);
	}
	
	public function updatedResponsableId($value) {
		$personal = Personal::find($value) ?? null;
		if ($personal) {
			$this->responsable_area_id = $personal->area_id;
			$this->responsable_cargo_id = $personal->cargo_id;	
		} else {			
			$this->responsable_area_id = null;
			$this->responsable_cargo_id = null;	
		}
		$this->actualizarDatosResponsable();
	}
	
	public function actualizarDatosResponsable () {
		$this->emit('actualizarDatosR',
			$this->responsable_id,
			$this->responsable_area_id,
			$this->responsable_cargo_id,
		);
	}

    public function cargar_activos_asignados_a_personal() {           
        $this->activos_asignados = 
        Activo::where('personal_id',$this->personal_id)
		->whereNotNull('asignacion_has_activo_id')
        ->with(
            'asignacion_has_activo',
            'asignacion_has_activo.performance',
            'asignacion_has_activo.vigencia',
            'asignacion_has_activo.accesorios',
            )
        ->get()->toArray();

        foreach ($this->activos_asignados as $index=>$row) {
            $this->activos_asignados[$index]['seleccionado'] = 0;
            $this->activos_asignados[$index]['observaciones_devolucion'] = null;
        }
    }

    public function buscar_dni() {
		$personal = Personal::where('dni', '=', $this->search_personal_dni)->first() ?? null;
		if ($personal) {
			$this->personal_id = $personal->id;
			$this->empresa_id = $personal->empresa_id;
			$this->gerencia_id = $personal->gerencia_id;
			$this->sede_id = $personal->sede_id;
			$this->area_id = $personal->area_id;
			$this->cargo_id = $personal->cargo_id;
			$this->dni = $personal->dni;
			session()->flash('message-success', 'DNI '. $this->search_personal_dni.' encontrado. Datos de usuario seleccionados.');
			$this->emit('alert-success');

            $this->cargar_activos_asignados_a_personal();

		} else {
			$this->tokenValidation();

			if (isset($this->token)) {

				$this->personal_id = null;
				$this->empresa_id = null;
				$this->gerencia_id = null;
				$this->sede_id = null;
				$this->area_id = null;
				$this->cargo_id = null;
				$this->dni = null;
				// session()->flash('message-danger', 'DNI '. $this->search_personal_dni.' no encontrado en la lista de personal.');
				// $this->emit('alert-danger');

				$message_error = '';

				$response2  = Http::withHeaders([
					'Authorization' => 'Bearer '.$this->token->access_token,
				])->get('http://10.13.10.49:81/api/manager/personal/'.$this->search_personal_dni);


				if ($response2->successful()) {
					$data = $response2->json();
					
					if(!$data) {
						$this->personal_id = null;
						$this->empresa_id = null;
						$this->gerencia_id = null;
						$this->sede_id = null;
						$this->area_id = null;
						$this->cargo_id = null;
						$this->dni = null;

						session()->flash('message-danger', 'Se consultó en el ERP NISIRA. DNI '. $this->search_personal_dni.' no encontrado.');
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
							$this->personal_id = $personal->id;
							$this->empresa_id = $personal->empresa_id;
							$this->gerencia_id = $personal->gerencia_id;
							$this->sede_id = $personal->sede_id;
							$this->area_id = $personal->area_id;
							$this->cargo_id = $personal->cargo_id;
							$this->dni = $personal->dni;
							
							$this->cargar_activos_asignados_a_personal();
							session()->flash('message-success', 'DNI '. $this->search_personal_dni.' importados desde NISIRA. Datos de usuario seleccionados.');
							$this->emit('alert-success');
							$this->listarSelects();
						} else {
							session()->flash('message-danger', 'DNI '. $this->search_personal_dni.' no encontrado en la lista de personal. Se consultaron los datos de NISIRA pero hubo un error en la inserción/lectura de los datos');
							$this->emit('alert-danger');
							// Manejar el código de estado de error
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
		}
		$this->actualizarDatosPersonal();
		$this->search_personal_dni = null;
    }
	    
    public function seleccionar_activo($index)
    {
		$this->activos_asignados[$index]['seleccionado'] = 1;

        // Si la condición es nuevo, se coloca por defecto en usado
        if ($this->activos_asignados[$index]['asignacion_has_activo']['performance_id'] == $this->condicion_nuevo->id) {          
            $this->activos_asignados[$index]['asignacion_has_activo']['performance']['id']      = $this->condicion_usado->id;
            $this->activos_asignados[$index]['asignacion_has_activo']['performance']['name']    = $this->condicion_usado->name;
        }
        
        // Se coloca por defecto los accesorios en devueltos
        if (!empty($this->activos_asignados[$index]['asignacion_has_activo'])) { 
            if (!empty($this->activos_asignados[$index]['asignacion_has_activo']['accesorios'])) {                
                $this->activos_asignados[$index]['asignacion_has_activo']['accesorios_devueltos_ids'] = array_column($this->activos_asignados[$index]['asignacion_has_activo']['accesorios'], 'id');
                $this->activos_asignados[$index]['asignacion_has_activo']['accesorios_devueltos_names'] = array_column($this->activos_asignados[$index]['asignacion_has_activo']['accesorios'], 'name');
            }
        }
    }

    public function deseleccionar_activo($index)
    {
		$this->activos_asignados[$index]['seleccionado'] = 0;
        
        //Se deshacen los cambios en las ediciones que se hayan hecho, en : condición, accesorios y observaciones de devolución
        if (isset($this->activos_asignados[$index]['asignacion_has_activo']['performance_id'])) {
            $condicion = Performance::where('id', '=', $this->activos_asignados[$index]['asignacion_has_activo']['performance_id'])->first() ?? null;

            if( $condicion){
                $this->activos_asignados[$index]['asignacion_has_activo']['performance']['id']      = $condicion->id;
                $this->activos_asignados[$index]['asignacion_has_activo']['performance']['name']    = $condicion->name;
            }
        }
        
        if (!empty($this->activos_asignados[$index]['asignacion_has_activo'])) { 
            if (!empty($this->activos_asignados[$index]['asignacion_has_activo']['accesorios'])) {                
                $this->activos_asignados[$index]['asignacion_has_activo']['accesorios_devueltos_ids']   = [];
                $this->activos_asignados[$index]['asignacion_has_activo']['accesorios_devueltos_names'] = [];
            }
        }

        $this->activos_asignados[$index]['observaciones_devolucion'] = '';
    }

	public function agregar_asignados() {
		
        $this->validate([
			'activo_id' => 'required|min:4',
		], [
			'activo_id.required' => 'El campo es requerido para buscar el activo',
			'activo_id.min' => 'El campo debe tener como mínimo 4 carácteres ',
		], [
			'activo_id' => ''
		]);

		$this->activo_id= strtoupper(trim($this->activo_id));
		$activos = Activo::
			where(function($query) {
				$query->orWhere('serial_number', 'like', '%'.$this->activo_id)
				->orWhere('imei1', 'like', '%'.$this->activo_id);
			})			
			->whereNotNull('asignacion_has_activo_id')
			->where('status_id',$this->estado_asignado)
			
			->get() ?? null;

		$message = null;
		$activo = null;

		if ($activos && count($activos)) {
			if(count($activos) == 1) {
				$activo = $activos[0];
				$this->agregar_activo($activo);				
			} else {				
				$this->precargar_activos($activos);
			}
		} else {
			$message = 'Ningún activo con Serial/IMEI: '.$this->activo_id.', se encuentra en estado asignado.';
			session()->flash('message-info', $message);
			$this->emit('alert-info'); 
			$this->activo_id= null;
		}
    }

	public function agregar_activo($activo) {

		$array = 
        Activo::where('id',$activo->id)
		->whereNotNull('asignacion_has_activo_id')
        ->with(
            'asignacion_has_activo',
            'asignacion_has_activo.performance',
            'asignacion_has_activo.vigencia',
            'asignacion_has_activo.accesorios',
            )
        ->get()->first();

		if ($array) {	
			if ($array->estado <> 1) {
				$message = 'Activo inactivo.';
			} else if ($array->status_id <> $this->estado_asignado) {
				$message = 'Activo no se encuentra asignado.';
			} else {
				if (($clave = array_search($array->id, array_column($this->activos_asignados,'id'))) === false) {
					$array1 = $array->toArray();
					$array1['seleccionado'] = 1;
					$array1['observaciones_devolucion'] = null;
					array_push($this->activos_asignados, $array1);
					$message = 'Activo  se registró correctamente.';
				} else {
					$message = 'Activo se registró anteriormente.';
				}
			}
		}

		session()->flash('message-info', $message);
		$this->emit('alert-info'); 
		$this->activo_id= null;

	}

	public function precargar_activos($activos) {
		$this->activos_precargados = $activos->toArray();
		$this->emit('openSeleccionarActivoModal');
	}

	public function seleccionar_activo_asignado($activo_id) {
		$activo_agregar = Activo::find($activo_id);
		$this->agregar_activo($activo_agregar);		
		$this->emit('closeModal');
	}

    public function agregar() {

		$message = null;

        if (
            ($clave = array_search($this->activo_precargado_id, array_column($this->activos_asignados,'serial_number'))) !== false || 
        	($clave = array_search($this->activo_precargado_id, array_column($this->activos_asignados,'imei1'))) !== false
        ) {
		    $this->activos_asignados[$clave]['seleccionado'] 	=	1;
            $message .= 
            'Activo con Serial number/IMEI1 ' . $this->activo_precargado_id . ' SELECCIONADO.';
        } else {
            $message .= 
            'Activo con Serial number/IMEI1 ' . $this->activo_precargado_id . ' NO ENCONTRADO.';
        }

		session()->flash('message-info', $message);
		$this->emit('alert-info'); 
		$this->activo_precargado_id= null;
    }

	public function agregarFirma($tipo_firma,$id)
	{
		$this->tipo_firma = $tipo_firma;
	}
	
	public function guardarFirma()
	{
		if ($this->tipo_firma == "responsable") {			
			$this->firma_responsable = $this->firma;
		}
		if ($this->tipo_firma == "personal") {			
			$this->firma_personal = $this->firma;
		}		
		$this->tipo_firma = null;
		$this->id_personal_firma = null;
		$this->firma = null;
		$this->emit('closeModal');
	}

	public function pdfMode()
    {
        $this->validate([
			'personal_id' => 'required',
			'empresa_id' => 'required',
			'gerencia_id' => 'required',
			'sede_id' => 'required',
			'area_id' => 'required',
			'cargo_id' => 'required',
			'fecha' => 'required',
			'responsable_id' => 'required',
			'responsable_area_id' => 'required',
			'responsable_cargo_id' => 'required',
            'activos_asignados' => 'required',
            'activos_asignados.*.asignacion_has_activo.performance.id' => 'required'
        ], $this->messages_validate,[
			'personal_id' 			=> 'Personal',
			'empresa_id' 			=> 'Empresa',
			'gerencia_id' 			=> 'Gerencia',
			'sede_id' 				=> 'Sede',
			'area_id' 				=> 'Área',
			'cargo_id' 				=> 'Cargo',
			'fecha' 				=> 'Fecha',
			'responsable_id' 		=> 'Responsable',
			'responsable_area_id' 	=> 'Área',
			'responsable_cargo_id' 	=> 'Cargo',
            'activos_asignados' 	=> 'Activos asignados',
            'activos_asignados.*.asignacion_has_activo.performance.id' => 'Condición'			
		]);
        
        $tieneSeleccionado = $this->evaluarActivosDevueltos();
        
        if ($tieneSeleccionado) {
            $this->correo_personal = Personal::find($this->personal_id)->correo_personal ?? null;		
            $this->firma_responsable = Personal::find($this->responsable_id)->firma ?? null;
            $this->firma_personal = null;
            $this->pdfMode = true;
        } else {
            $message = 'Ningún activo seleccionado para devolución.';
            session()->flash('message-danger', $message);
            $this->emit('alert-danger');
        }
	}

	public function notPdfMode()
    {
		$this->pdfMode = false;
	}

    public function store()
    {
        // dd($this->activos_asignados);
        $this->validate([
			'personal_id' => 'required',
			'empresa_id' => 'required',
			'gerencia_id' => 'required',
			'sede_id' => 'required',
			'area_id' => 'required',
			'cargo_id' => 'required',
			'fecha' => 'required',
			'responsable_id' => 'required',
			'responsable_area_id' => 'required',
			'responsable_cargo_id' => 'required',
			'firma_responsable' => 'required',
			'firma_personal' => 'required',
			'correo_personal' => 'required|email',
            'activos_asignados' => 'required'
        ], $this->messages_validate,[
			'personal_id' 			=> 'Personal',
			'empresa_id' 			=> 'Empresa',
			'gerencia_id' 			=> 'Gerencia',
			'sede_id' 				=> 'Sede',
			'area_id' 				=> 'Área',
			'cargo_id' 				=> 'Cargo',
			'fecha' 				=> 'Fecha',
			'responsable_id' 		=> 'Responsable',
			'responsable_area_id' 	=> 'Área',
			'responsable_cargo_id' 	=> 'Cargo',
			'firma_responsable' 	=> 'Firma de responsable',
			'firma_personal' 		=> 'Firma ',
			'correo_personal' 		=> 'Correo',
            'activos_list' 			=> 'Activos'

		]);

        $tieneSeleccionado = $this->evaluarActivosDevueltos();

        if ($tieneSeleccionado) {

            // Insertar Asignación
            $record = Devolucion::create([
                'personal_id'           => $this-> personal_id,
                'empresa_id'            => $this-> empresa_id,
                'gerencia_id'           => $this-> gerencia_id,
                'sede_id'               => $this-> sede_id,
                'area_id'               => $this-> area_id,
                'cargo_id'              => $this-> cargo_id,
                'fecha'                 => $this-> fecha,
                'responsable_id'        => $this-> responsable_id,
                'responsable_area_id'   => $this-> responsable_area_id,
                'responsable_cargo_id'  => $this-> responsable_cargo_id,
                'created_by'            => auth()->user()->id,
            ]);
            
            foreach ($this->activos_asignados as $key => $value) {
                if ($value['seleccionado'] == 1) {

                    // Insertar activos en asignación
                    $activo = DevolucionHasActivo::create([
                        'activo_id'				 	=> $value['id'],
                        'devolucion_id' 			=> $record->id,
                        'performance_id' 			=> $value['asignacion_has_activo']['performance']['id'],
                        'observaciones' 			=> $value['observaciones_devolucion'],
                        'asignacion_has_activo_id'  => $value['asignacion_has_activo']['id']
                    ]);                
                       
                    // Insertar accesorios
                    if (!empty($value['asignacion_has_activo']['accesorios_devueltos_ids'])) {
                        $activo->accesorios()->attach($value['asignacion_has_activo']['accesorios_devueltos_ids']);
                    }
                    
                    // Actualizando Activo en Asignación
                    $asignacion_has_activo_actualizar = AsignacionHasActivo::find($value['asignacion_has_activo']['id']);
                    $asignacion_has_activo_actualizar->update([
                        'devuelto' 					=> 1,
                        'fecha_de_devolucion'		=> $this-> fecha,                    
                    ]);
    
                    //Actualizar personal			
                    $record_p = Personal::find($this-> personal_id);
    
                    $record_p->empresa_id 		= 	$this-> empresa_id;
                    $record_p->gerencia_id 		= 	$this-> gerencia_id;
                    $record_p->sede_id			= 	$this-> sede_id;
                    $record_p->area_id 			= 	$this-> area_id;
                    $record_p->cargo_id 		= 	$this-> cargo_id;
                    $record_p->correo_personal	= 	$this-> correo_personal ?? $record_p->correo_personal;
                    if ($record_p->isDirty()) {
                        $record_p->save();
                    }
                    
                    //Actualizar responsable			
                    $record_r = Personal::find($this-> responsable_id);
                    $record_r->area_id 	= 	$this-> responsable_area_id;
                    $record_r->cargo_id	= 	$this-> responsable_cargo_id;
                    $record_r->firma	= 	$this-> firma_responsable;
                    if ($record_r->isDirty()) {
                        $record_r->save();
                    }
    
                    // Actualizar activo
                    $activo_actualizar = Activo::find($value['id']);
                    $activo_actualizar->update([
                        'status_id' => $this->estado_stock,
                        'performance_id' => $value['asignacion_has_activo']['performance']['id'],
                        'personal_id' => null,
                        'fecha_asignacion' => null,
                        'fecha_de_vigencia' => null,
                        'vigencia_id' => null,
                        'updated_by' => auth()->user()->id,
                        'asignacion_has_activo_id' => null,
                        'fecha_devolucion' => $this->fecha,
                    ]);
    
                    //Actualizar stock de accesorios
                    
                    if (!empty($value['asignacion_has_activo']['accesorios_devueltos_ids'])) {
                    foreach ($value['asignacion_has_activo']['accesorios_devueltos_ids'] as $key => $item) {
                        $record_a = Accesorio::find($item);
                        $record_a->update([ 
                            'stock' => $record_a-> stock + 1
                        ]);
                        }
                    }
                }
            }

            $this->generarPdf($record->id);

        } else {
            $message = 'Ningún activo seleccionado para devolución.';
            session()->flash('message-danger', $message);
            $this->emit('alert-danger');
        }

    }

    public function evaluarActivosDevueltos() {
        $elementos = $this->activos_asignados;

        $tieneSeleccionado = false;

        foreach ($elementos as $elemento) {
            if ($elemento['seleccionado'] == 1) {
                $tieneSeleccionado = true;
                break;
            }
        }

        return $tieneSeleccionado;
    }

	public function viewPdf($id) 
	{
		$this->devolucion_guardada = Asignacione::where('id',$id)->first();
	}

	public function generarPdf($id)
	{
		$devolucion_guardada = Devolucion::where('id',$id)
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
			)
		->first()->toArray();

		// dd($devolucion_guardada);
		$hoy = time();
		$pdf =  PDF::loadView('livewire.devoluciones.pdf', 
			[
				'devolucion_guardada' => $devolucion_guardada,
				'firma_personal' => $this->firma_personal,
				'firma_responsable' => $this->firma_responsable
			]
		);
		
		$pdf->render();

		// Verifica si se generaron los datos del PDF correctamente
		if ($output = $pdf->output()) {
			// Guarda el archivo PDF en el sistema de archivos
			$fileName = 'devolucion_'.$hoy.'.pdf';
			
			Storage::disk('public')->put($fileName, $output);
						
			// Verifica si el archivo PDF se guardó correctamente
			if (Storage::disk('public')->exists($fileName)) {
				
				//Actualizar Asignacion con archivo PDF
				$record = Devolucion::find($id);					
				$record->update([ 
					'pdf' => $fileName
				]);

				$message = 'El PDF fue generado y guardado correctamente.';
				session()->flash('message-success', $message);
				$this->emit('alert-success');

				$correo_personal = $this->correo_personal;
				$this->resetInput();
				$this->emit('descargarPDF',$record->pdf);

				$this->correo_personal = null;
				$this->devolucion_guardada =null;
				$this->createMode = false;
				$this->pdfMode = false;

				try {

					Mail::send('livewire.devoluciones.mail', ['date'=>$hoy], function ($mail) use ($fileName,$output,$correo_personal) {
						$mail->subject('Devolucion de Activos');
						$mail->from(auth()->user()->email, auth()->user()->personal->name);
						$mail->to($correo_personal);
						$mail->attachData($output, $fileName);
					});

					// return Storage::disk('public')->download($fileName);

					$message = 'El PDF fue generado y guardado correctamente.El correo electrónico fue enviado correctamente.';
					session()->flash('message-success', $message);

				} catch (\Exception $e) {
					// Error al enviar el correo electrónico
					Log::error('Error al enviar el correo electrónico: ' . $e->getMessage());

					$message = 'Error al enviar el correo electrónico.';
					session()->flash('message-danger', $message);
					$this->emit('alert-danger'); 
				}
				// El PDF se generó y se guardó correctamente
			} else {
				// Error al guardar el archivo PDF
				$message = 'Error al guardar el archivo PDF.';
				session()->flash('message-danger', $message);
				$this->emit('alert-danger'); 
			}
			
		} else {
			// Error al generar los datos del PDF
			$message = 'Error al generar los datos del PDF.';			
			session()->flash('message-danger', $message);
			$this->emit('alert-danger'); 
		}

		// $pdf->save(storage_path('app/public/entregas/') . 'entrega_'.$hoy.'.pdf');
		

		// dd($respuesta);

		//->stream('archivo.pdf');	
		//$pdf->download('archivo_'.$hoy.'.pdf');
	}
	
    public function descargarPDF($pdf)
    {
		return Storage::disk('public')->download($pdf);
    }	

    public function edit($id)//sin utilizar la función
    {
        $record = Asignacione::findOrFail($id);

        $this->selected_id = $id; 
		$this->personal_id = $record-> personal_id;
		$this->empresa_id = $record-> empresa_id;
		$this->gerencia_id = $record-> gerencia_id;
		$this->sede_id = $record-> sede_id;
		$this->area_id = $record-> area_id;
		$this->cargo_id = $record-> cargo_id;
		$this->fecha = $record-> fecha;
		$this->responsable_id = $record-> responsable_id;
		$this->responsable_area_id = $record-> responsable_area_id;
		$this->responsable_cargo_id = $record-> responsable_cargo_id;
		$this->created_by = $record-> created_by;
		$this->updated_by = $record-> updated_by;
		$this->deleted_by = $record-> deleted_by;
		$this->pdf = $record-> pdf;
		
        $this->updateMode = true;
    }
	
    public function edit_activo($id)
    {
        $record = $this->activos_asignados[$id];
        // dd($record);
		$this->selected_activo_index = $id;
 		$this->performance_id=$record['asignacion_has_activo']['performance']['id'];
		// $this->vigencia_id=$record['asignacion_has_activo']['vigencia_id'];
		// $this->fecha_vigencia=$record['asignacion_has_activo']['fecha_vigencia']??'';
		// $this->observaciones_activo=$record['asignacion_has_activo']['observaciones'];
		$this->observaciones_devolucion=$record['observaciones_devolucion'];
		$this->activo_accesorios=$record['asignacion_has_activo']['accesorios_devueltos_ids']??[];
		$this->accesorios_asignado_activo = $record['asignacion_has_activo']['accesorios'];
        //dd($this->activo_accesorios);
    }

    public function update_activo($id)
    {
        $this->validate([
			'performance_id' => 'required',
        ],[],[
			'performance_id' => 'Condición',
		]);

		$this->activos_asignados[$id]['asignacion_has_activo']['performance']['id'] 	=	$this->performance_id;
		$this->activos_asignados[$id]['observaciones_devolucion'] 	=	$this->observaciones_devolucion;
		$this->activos_asignados[$id]['asignacion_has_activo']['accesorios_devueltos_ids'] 	=	$this->activo_accesorios;
		$this->activos_asignados[$id]['asignacion_has_activo']['accesorios_devueltos_names'] 	=	Accesorio::whereIn('id',$this->activo_accesorios)->pluck('name')->toArray() ?? null ;
		$this->activos_asignados[$id]['asignacion_has_activo']['performance']['name'] = Performance::find($this->performance_id)->name;

		$this->resetInputActivos();
		$this->emit('closeModal');
		session()->flash('message', 'Activos actualizados correctamente.');
    }
	
    public function cancel_activo()
    {
        $this->resetInputActivos();
    }
	
    public function resetInputActivos()
    {
		$this->performance_id=null;
		$this->observaciones_devolucion=null;
		$this->activo_accesorios=[];
    }

    public function update()//sin utilizar la función
    {
        $this->validate([ 
			'personal_id' => 'required'
        ],[],[
			'personal_id' => 'Personal',
		]);

        if ($this->selected_id) {
			$record = Asignacione::find($this->selected_id);
            $record->update([ 
			'personal_id' => $this-> personal_id,
			'empresa_id' => $this-> empresa_id,
			'gerencia_id' => $this-> gerencia_id,
			'sede_id' => $this-> sede_id,
			'area_id' => $this-> area_id,
			'cargo_id' => $this-> cargo_id,
			'fecha' => $this-> fecha,
			'responsable_id' => $this-> responsable_id,
			'responsable_area_id' => $this-> responsable_area_id,
			'responsable_cargo_id' => $this-> responsable_cargo_id,
			'created_by' => $this-> created_by,
			'updated_by' => $this-> updated_by,
			'deleted_by' => $this-> deleted_by,
			'pdf' => $this-> pdf
            ]);

            $this->resetInput();
            $this->updateMode = false;
		    $this->emit('closeModal');
			session()->flash('message', 'Asignacione actualizado correctamente.');
        }
    }

    public function destroy($id)//sin utilizar la función
    {
        if ($id) {
            $record = Asignacione::where('id', $id);
            $record->delete();
        }
    }
}
