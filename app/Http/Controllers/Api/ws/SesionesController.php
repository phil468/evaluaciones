<?php

namespace App\Http\Controllers\Api\ws;

use App\Http\Controllers\Controller;
use App\Models\Sesione;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SesionesController extends Controller
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
            // if ($lastUpdate == 0) {
                // $datamodel = DB::select("call sp_get_sesiones ()");
            // } else {
                $datamodel = DB::select("call sp_get_sesiones_updates ($tipo_user,$user_id,'$lastUpdate')");
            // }

            $data = [];
            foreach ($datamodel as $item) {
                $data[] = $item;
            }

            $response = $data;
            // $response["privilegies"] = $this->showprivilegies($id);
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

            $sesion = Sesione::updateOrcreate(
                [
                    'capacitacion_id' => $data['capacitacion_id'],
                    'numero_de_sesion' => $data['numero_de_sesion'],
                ],
                [
                    'fecha' => $data['fecha'],
                    'hora_inicio' => $data['hora_inicio'],
                    'hora_fin' => $data['hora_fin'],
                    'active' => $data['active'],
                    'synced' => true,
                ]
            );

            $response["message"] = "Sesión actualizada correctamente.";

            $datamodel = [];
                
            // $datamodel = DB::select("call sp_get_sesion_id ($sesion->id)");
    
            $data = [];
            foreach ($datamodel as $item) {
                $data[] = $item;
            }
    
            $response["data"] = $data;
            $response["id"] = $sesion->id;
                
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
