<?php

namespace App\Http\Controllers;

use App\Models\TipoDeTrabajador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TipoDeTrabajadorController extends Controller
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
        ])->get(config('app.url_api').'api/manager/tipodetrabajador/'.$empresa.'/'.$val);

    }

    public function upsert(string $empresa,string $val) 
    {
        $message='';
        $response2 = $this->obtenerResponse($empresa,$val);

        if ($response2->successful()) {
            $data = $response2->json();
            
            if(!$data) {
                if($val == 0) {
                    return $message = 'Se consultó en el ERP NISIRA. Consulta general no encontrada.';
                } else {
                    return $message = 'Se consultó en el ERP NISIRA. Valor '. $val.' no encontrado.';
                }
            } else {
                // $row=$data[0];
                foreach ($data as $row) 
                {
                    //Recuperando o Insertando Empresa
                    if(!empty(trim($row['DESCRIPCION']))){
                        $planilla = TipoDeTrabajador::updateOrCreate(
                            ['idtipotrabajador_nisira' => trim($row['IDTIPOTRABAJADOR']), 'empresa_id' => trim($row['EMPRESA'])],
                            ['name' => trim($row['DESCRIPCION'])]
                        );
                    }
                }

                if($val != 0) {
                    if ($planilla) {
                        return $message = 'TIPO DE TRABAJADOR '. $val.' importados desde NISIRA.';
                    } else {
                        return $message = 'Se consultaron los datos de NISIRA pero hubo un error en la inserción/lectura de los datos';
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
                return $message = 'Consulta general devolvió error. Error: '.$statusCode;
            } else {
                return $message = 'Consulta TIPO DE TRABAJADOR '. $val.' devolvió error. Error: '.$statusCode;
            }
            
            return 
            [
                'res' => true,
                'message' => $message
            ];
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
        $modelo = TipoDeTrabajador::all(); // Reemplaza 'TuModelo' por el nombre de tu modelo
        $jsonData = ($this->obtenerResponse(0,0)->json()); // Obtiene el JSON de la API
        // json_decode($jsonData, true); // Convierte el JSON a un array asociativo
    
        // $modelo->actualizarEstadoParaTodos($jsonData);

        foreach ($modelo as $registro) {
            if($registro->name == NULL) {
                // Recorre los registros del JSON devuelto
                foreach ($jsonData as $jsonRegistro) {
                    if ($jsonRegistro['EMPRESA'] == $registro->empresa_id && $jsonRegistro['IDTIPOTRABAJADOR'] == $registro->idtipotrabajador_nisira) {
                        $registro->name = $jsonRegistro['DESCRIPCION'];
                        break; 
                    }
                }

                if ($registro->isDirty('name')) {
                    $registro->save();
                    $message = $message.'Se ingresó el tipo de trabajador: '.$registro->name.'<br>';
                }
            }
            
        }

        return $message;
    }
}
