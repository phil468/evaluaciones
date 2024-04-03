<?php

namespace App\Http\Controllers;

use App\Models\Planilla;
use Illuminate\Support\Facades\Http;

class PlanillaController extends Controller
{
    public $token = null;

    public function obtenerResponse(string $empresa,string $val) {
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
        ])->get(config('app.url_api').'api/manager/planillas/'.$empresa.'/'.$val);

    }

    public function upsert(string $empresa,string $val) 
    {        
        $message='';
        $response2 = $this->obtenerResponse($empresa,$val);

        if ($response2->successful()) {
            $data = $response2->json();
            
            if(!$data) {
                if($val == 0) {
                    //dd('Se consultó en el ERP NISIRA. Consulta general no encontrada.');
                    return $message = 'Se consultó en el ERP NISIRA. Consulta general no encontrada.';
                } else {
                    //dd('Se consultó en el ERP NISIRA. DNI '. $val.' no encontrado.');
                    return $message = 'Se consultó en el ERP NISIRA. PLANIILA '. $val.' no encontrado.';
                }
            } else {
                // $row=$data[0];
                foreach ($data as $row) 
                {
                    //Recuperando o Insertando Empresa
                    if(!empty(trim($row['DESCRIPCION']))){
                        $planilla = Planilla::updateOrCreate(
                            ['idplanilla_nisira' => trim($row['IDPLANILLA']), 'empresa_id' => trim($row['EMPRESA'])],
                            ['name' => trim($row['DESCRIPCION'])]
                        );
                    }
                }

                if($val != 0) {
                    if ($planilla) {
                        // dd('PLANILLA '. $val.' importados desde NISIRA.');
                        // $this->edit($personal->id);
                        return $message = 'PLANILLA '. $val.' importados desde NISIRA.';
                        // $this->emit('alert-success');
                        // $this->listarSelects();
                    } else {
                        //dd('PLANILLA '. $val.' no encontrado en la lista de PLANILLA. Se consultaron los datos de NISIRA pero hubo un error en la inserción/lectura de los datos');
                        return $message = 'PLANILLA '. $val.' no encontrado en la lista de PLANILLA. Se consultaron los datos de NISIRA pero hubo un error en la inserción/lectura de los datos';
                        // $this->emit('alert-danger');
                        // Manejar el código de estado de error
                    }
                }
            }
            
            return 
            [
                'res' => true,
                'message' => $message
            ];
        } else {
            // La solicitud no fue exitosa, manejar el error
            $statusCode = $response2->status();
            
            if($val == 0) {
                // dd('message-busqueda-dni', 'Consulta general devolvió error. Error: '.$statusCode);
                return $message = 'Consulta general devolvió error. Error: '.$statusCode;
            } else {
                //dd('PLANILLA '. $val.' no encontrado en la lista de PLANILLA ni en los registros de NISIRA. Error: '.$statusCode);
                return $message = 'PLANILLA '. $val.' no encontrado en la lista de PLANILLA de NISIRA. Error: '.$statusCode;
            }
            
            return 
            [
                'res' => true,
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
			return $message = 'No se tiene acceso al servidor del API. Error: '.$res['statusCode'];
			// $this->emit('alert-danger');
		}
	}

    public function actualizarNombreParaTodos() {
        $message = '';
        // Recorre todos los registros de tu modelo
        $modelo = Planilla::all(); // Reemplaza 'TuModelo' por el nombre de tu modelo
        $jsonData = ($this->obtenerResponse(0,0)->json()); // Obtiene el JSON de la API
        // json_decode($jsonData, true); // Convierte el JSON a un array asociativo
    
        // $modelo->actualizarEstadoParaTodos($jsonData);

        foreach ($modelo as $registro) {
            if($registro->name == NULL) {
                // Recorre los registros del JSON devuelto
                foreach ($jsonData as $jsonRegistro) {
                    if ($jsonRegistro['EMPRESA'] == $registro->empresa_id && $jsonRegistro['IDPLANILLA'] == $registro->idplanilla_nisira) {
                        $registro->name = $jsonRegistro['DESCRIPCION'];
                        break; 
                    }
                }

                if ($registro->isDirty('name')) {
                    $registro->save();
                    $message = $message.'Se ingresó la Planilla: '.$registro->name.'<br>';
                }
            }
            
        }

        return $message;
    }
}
