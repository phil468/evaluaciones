<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Cargo;
use App\Models\Empresa;
use App\Models\Personal;
use App\Models\Planilla;
use App\Models\TipoDePersonal;
use App\Models\TipoDeTrabajador;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PersonalController extends Controller
{    
    public $token = null;

    public function obtenerResponse(string $numero) {
        // si numero == 0 entonces trae toda la información actual del personal 
		$tokenController = new ApiController();

        $this->token = $tokenController->getLastToken();

        if (isset($this->token)) {
			if ($tokenController->checkTokenExpiration($this->token)) {
                $res = $tokenController->login();
				$this->evaluarResultado($res);
            } 
		} else {
			$res = $tokenController->login();
			$this->evaluarResultado($res);
		}

        return $response2  = Http::withHeaders([
            'Authorization' => 'Bearer '.$this->token->access_token,
        ])->get(config('app.url_api').'api/manager/capacitaciones/personal/'.$numero);

    }

    public function actualizarPersonalNisira(string $numero) 
    {
        $numero = trim($numero);
        $message='';
        $response2 = $this->obtenerResponse($numero);

        if ($response2->successful()) {
            $data = $response2->json();
            
            if(!$data) {
                if($numero == 0) {
                    // dd('Se consultó en el ERP NISIRA. Consulta general no encontrada.');
                    $message = 'Se consultó en el ERP NISIRA. Consulta general no encontrada.';
                    return [
                        'res' => false,
                        'message' => $message
                    ];
                } else {
                    // dd('Se consultó en el ERP NISIRA. DNI '. $numero.' no encontrado.');
                    $message = 'Se consultó en el ERP NISIRA. DNI '. $numero.' no encontrado.';
                    return [
                        'res' => false,
                        'message' => $message
                    ];
                }
            } else {
                // $row=$data[0];
                $chunks = array_chunk($data, 100);

                foreach ($chunks as $chunkedData) {
                    foreach ($chunkedData as $row) {
                    //Recuperando o Insertando Empresa
                    if(!empty(trim($row['IDEMPRESA']))){
                        $empresa = Empresa::updateOrCreate(
                            ['name' => trim($row['empresa'])],
                            ['estado' => 1]
                        );
                    }

                    //Recuperando o Insertando CARGO
                    if(!empty(trim($row['IDCARGO']))){
                        $cargo = Cargo::updateOrCreate(
                            ['idcargo_nisira' => trim($row['IDCARGO']), 'empresa_id' => $empresa->id],
                            ['name' => trim($row['cargo']) , 'estado' => 1]
                        );
                    } else {
                        $cargo =  null;
                    }

                    //Recuperando o Insertando PLANILLA
                    if(!empty(trim($row['IDPLANILLA']))){
                        $planilla = Planilla::updateOrCreate(
                            ['idplanilla_nisira' => trim($row['IDPLANILLA']), 'empresa_id' => $empresa->id],
                            ['name' => trim($row['planilla']) , 'estado' => 1]
                        );
                    } else {
                        $planilla =  null;
                    }

                    //Recuperando o Insertando tipo de trabajador
                    if(!empty(trim($row['IDTIPOTRABAJADOR']))){
                        $tipotrabajador = TipoDeTrabajador::updateOrCreate(
                            ['idtipotrabajador_nisira' => trim($row['IDTIPOTRABAJADOR']), 'empresa_id' => $empresa->id],
                            ['name' => trim($row['TIPOTRABAJADOR']) , 'estado' => 1]
                        );
                    } else {
                        $tipotrabajador =  null;
                    }

                    //Recuperando o Insertando tipo de Personal
                    if(!empty(trim($row['IDTIPOPERSONAL']))){
                        $tipopersonal = TipoDePersonal::updateOrCreate(
                            ['idtipopersonal_nisira' => trim($row['IDTIPOPERSONAL']), 'empresa_id' => $empresa->id],
                            ['name' => trim($row['tipopersonal']) , 'estado' => 1]
                        );
                    } else {
                        $tipopersonal =  null;
                    }

                    //Recuperando o Insertando IDCCOSTO
                    if(!empty(trim($row['IDCCOSTO']))){
                        $area = Area::firstOrCreate(
                            ['idccosto_nisira' => trim($row['IDCCOSTO']), 'empresa_id' => $empresa->id],
                            ['name' => trim($row['CENTRO_COSTO']),'centro_costo' => trim($row['CENTRO_COSTO']) , 'estado' => 1]
                        );
                    } else {
                        $area =  null;
                    }

                    $personal = Personal::firstOrNew(['dni' => trim($row['NRODOCUMENTO'])]);

                    $personal->name = mb_strtoupper(trim($row['nombrecompleto']));
                    $personal->nombres = mb_strtoupper(trim($row['NOMBRES']));
                    $personal->apellido_paterno = mb_strtoupper(trim($row['A_PATERNO']));
                    $personal->apellido_materno = mb_strtoupper(trim($row['A_MATERNO']));
                    if($empresa != null) {
                        $personal->empresa_id = $empresa->id;
                    }
                    if($cargo != null) {
                        $personal->cargo_id = $cargo->id;
                    }
                    if($planilla != null) {
                        $personal->planilla_id = $planilla->id;
                    }
                    if($tipotrabajador != null) {
                        $personal->tipo_de_trabajador_id = $tipotrabajador->id;
                    }
                    if($tipopersonal != null) {
                        $personal->tipo_de_personal_id = $tipopersonal->id;
                    }
                    if($area != null && $personal->area_id == null) {
                        $personal->area_id = $area->id;
                    }
                    $personal->sexo = isset($row['sexo'])?trim($row['sexo']):NULL;
                    $personal->estado = 1;
                    $personal->cesado = 0;
                    $personal->importado = 1;
                    $personal->fecha_cese = NULL;
                    $personal->fecha_ingreso  = trim($row['FECHA_INGRESO']) == '' ? NULL : (Carbon::createFromFormat('Y-m-d H:i:s.u', trim($row['FECHA_INGRESO']))->toDateString());
                    
                    if ($personal->isDirty()) {
                        $personal->save();
                        $message = $message.'Se ingresaron/actualizaron los datos del trabajador '.$personal->name.'.\n';
                    } else {
                        $message = $message.'No se ingresaron/actualizaron los datos del trabajador '.$personal->name.'.\n';
                    }

                    if($personal) {
                        $res = true;
                        // if ($numero != 0) {
                        // }

                    // $planillaController = new PlanillaController();
                    // $messageActualizarPlanilla = $planillaController->actualizarNombreParaTodos();
                    // $tipopersonalController = new TipoDePersonalController();
                    // $messageActualizarTipoDePersonal = $tipopersonalController->actualizarNombreParaTodos();
                    // $tipotrabajadorController = new TipoDeTrabajadorController();
                    // $messageActualizarTipoDeTrabajador = $tipotrabajadorController->actualizarNombreParaTodos();
                    }
                }
            }
                
                // if ($numero == 0) {
                //     $message = 'Se ingresaron/actualizaron '.count($data).' personas.<br>';
                // }

                return 
                [
                    'res' => true,
                    'message' => $message
                    // .$messageActualizarPlanilla
                    // .$messageActualizarTipoDePersonal
                    // .$messageActualizarTipoDeTrabajador
                ];
                // if($numero != 0) {
                //     if ($personal) {
                //         // dd('DNI '. $numero.' importados desde NISIRA. Datos de usuario seleccionados.');
                //         // $this->edit($personal->id);
                //         session()->flash('message-busqueda-dni', 'DNI '. $numero.' importados desde NISIRA.');
                //         // $this->emit('alert-success');
                //         // $this->listarSelects();
                //     } else {
                //         // dd('DNI '. $numero.' no encontrado en la lista de personal. Se consultaron los datos de NISIRA pero hubo un error en la inserción/lectura de los datos');
                //         session()->flash('message-busqueda-dni', 'DNI '. $numero.' no encontrado en la lista de personal. Se consultaron los datos de NISIRA pero hubo un error en la inserción/lectura de los datos');
                //         // $this->emit('alert-danger');
                //         // Manejar el código de estado de error
                //     }
                // }
            }

        } else {
            // La solicitud no fue exitosa, manejar el error
            $statusCode = $response2->status();
            
            if($numero == 0) {
                // dd('message-busqueda-dni', 'Consulta general devolvió error. Error: '.$statusCode);
                $message = 'Consulta general devolvió error. Error: '.$statusCode;
            } else {
                // dd('DNI '. $numero.' no encontrado en la lista de personal ni en los registros de NISIRA. Error: '.$statusCode);
                $message = 'DNI '. $numero.' no encontrado en la lista de personal ni en los registros de NISIRA. Error: '.$statusCode;
            }

            return [
                'res' => false,
                'message' => $message
            ];
            // $this->emit('alert-danger');
            // Manejar el código de estado de error
        }
    }

    public function evaluarResultado($res) {
		if($res['statusCode'] == 200) {
			$this->token = $res['token'];
		} else {
			$this->token = null;
            $message = 'No se tiene acceso al servidor del API. Error: '.$res['statusCode'];
            return [
                'res' => false,
                'message' => $message
            ];
        }
	}

    public function actualizarEstadoParaTodos() {
        $message = 'Se actualizaron los estados de los trabajadores.<br>';
        // Recorre todos los registros de tu modelo
        $modelo = Personal::all(); // Reemplaza 'TuModelo' por el nombre de tu modelo
        $jsonData = ($this->obtenerResponse(0)->json()); // Obtiene el JSON de la API
        // json_decode($jsonData, true); // Convierte el JSON a un array asociativo
    
        // $modelo->actualizarEstadoParaTodos($jsonData);

        // Create an array to store the DNIs from the JSON data
        $jsonDnis = [];

        // Extract the DNIs from the JSON data and store them in the array
        foreach ($jsonData as $jsonRegistro) {
            if (isset($jsonRegistro['NRODOCUMENTO'])) {
                $jsonDnis[] = $jsonRegistro['NRODOCUMENTO'];
            }
        }

        // Update the state in the model for each record
        foreach ($modelo as $registro) {
            $dni = $registro->dni;
            $registro->cesado = in_array($dni, $jsonDnis) ? 0 : 1;

            if ($registro->isDirty('cesado')) {
                // $registro->cesado = $cesado;
                $registro->save();
                $message .= 'Se actualizó el estado de cese de: ' . $registro->name . ' - DNI ' . $dni . ($registro->cesado ? 'CESADO' : 'NO CESADO') . '<br>';
            }
        }

        return [
            'res' => true,
            'message' => $message
        ];
//        return $message;
    }

    public function ingresarDNI($dni) {
        $dni = trim($dni);
        $personal = Personal::create([
			'dni' => $dni,
			'estado' => 1,
			'importado' => 0,
		]);

		return $personal->id;
    }

}
