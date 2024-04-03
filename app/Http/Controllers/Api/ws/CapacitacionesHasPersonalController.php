<?php

namespace App\Http\Controllers\Api\ws;

use App\Http\Controllers\Controller;
use App\Models\CapacitacionHasPersonal;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CapacitacionesHasPersonalController extends Controller
{
    public function getUpdatesFromServer(Request $request)
    {
        
        $tipo_user = $request['tipoUser'];
        $user_id = $request['userId'];
        $lastUpdate = $request['lastUpdate'];

        //
        $response = [];
        try {

            $datamodel = [];            
            $datamodel = DB::select("call sp_get_capacitacion_has_personal_updates ($tipo_user,$user_id,'$lastUpdate')");

            $data = [];
            foreach ($datamodel as $item) {
                $data[] = $item;
            }

            $response = $data;

        } catch (Exception $ex) {
            $response["success"] = "false";
            $response["message"] = "";
            $response["error"] = array("code" => "2", "message" => $ex->getMessage(), "errors" => []);
        }

        return response()->json(
            $response,
            200,
            [
                'Content-Type' => 'application/json;charset=UTF-8',
                'Charset' => 'utf-8'
            ],
            JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
        );

    }

    public function store(Request $request) {
          
        $response = [];
        try {
            $data = $request->all();

            $capacitacionHasPersonal = CapacitacionHasPersonal::updateOrcreate(
                [
                    'capacitacion_id' => $data['capacitacion_id'],
                    'personal_id' => $data['personal_id'],
                ],
                [
                    'active' => $data['active'],
                    'observaciones' => $data['observaciones'],
                    'empresa_id' => $data['empresa_id'],
                    'gerencia_id' =>   $data['gerencia_id'],
                    'area_id' => $data['area_id'],
                    'cargo_id' =>  $data['cargo_id'],
                    'planilla_id' => $data['planilla_id'],
                    'sede_id' => $data['sede_id'],
                    'tipo_de_trabajador_id' => $data['tipo_de_trabajador_id'],
                    'tipo_de_personal_id' => $data['tipo_de_personal_id'],
                    'synced' => true,                    
                ]
            );

            $response["message"] = "Sesión actualizada correctamente.";

            $datamodel = [];
                
            // $datamodel = DB::select("call sp_get_capacitacion_has_personal_id ($capacitacionHasPersonal->id)");
    
            $data = [];
            foreach ($datamodel as $item) {
                $data[] = $item;
            }
    
            $response["data"] = $data;
                
            $response["success"] = true;

        } catch (Exception $ex) {
            $response["success"] = false;
            $response["message"] = "";
            $response["error"] = array("code" => "2", "message" => $ex->getMessage(), "errors" => []);
        }
    
        return response()->json(
            $response,
            200,
            [
                'Content-Type' => 'application/json;charset=UTF-8',
                'Charset' => 'utf-8'
            ],
            JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
        );
    
    }
}
