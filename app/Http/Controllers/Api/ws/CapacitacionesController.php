<?php

namespace App\Http\Controllers\Api\ws;

use App\Http\Controllers\Controller;
use App\Models\Capacitacione;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class CapacitacionesController extends Controller
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
                // $datamodel = DB::select("call sp_get_capacitaciones ($tipo_user,$user_id)");
            // } else {
                $datamodel = DB::select("call sp_get_capacitaciones_updates ($tipo_user,$user_id,'$lastUpdate')");
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


    public function getCapacitaciones($tipo_user,$user_id)
    {
        //
        $response = [];
        try {

            $datamodel = [];
            
                $datamodel = DB::select("call sp_get_capacitaciones ('$tipo_user')");

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

    public function getRegistros()
    {
        //
        $response = [];
        try {

            $datamodel = [];
            
                $datamodel = DB::select("call sp_get_registros ()");

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
    
    public function getSesiones()
    {
        //
        $response = [];
        try {

            $datamodel = [];
            
                $datamodel = DB::select("call sp_get_sesiones ()");

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

    
    public function getAsistencias()
    {
        //
        $response = [];
        try {

            $datamodel = [];
            
                $datamodel = DB::select("call sp_get_registros ()");

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
    
public function login(Request $request)
{
    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {
        $user = auth()->user();
        // dd($user);
        $token = JWTAuth::fromUser($user);
        $response['token'] = $token;
        $response['user'] = $user;
    
        return response()->json($response, 200);
    }

    return response()->json(['message' => 'Unauthorized'], 401);
}

public function refreshToken($user)
{
    // dd($user);
    try {
        $user = User::find($user->id);
        $token = JWTAuth::fromUser($user);
        $response['token'] = $token;
        $response['user'] = $user;
    
        return response()->json($response, 200);
    } catch (Exception $ex) {

        return response()->json(['error' => 'No se pudo refrescar el token /n'.$ex], 401);
    }
    // return response()->json(['message' => 'Unauthorized'], 401);

    // return $this->respondWithToken(JWTAuth::refresh());

    // try {
    //     if (!$token = JWTAuth::parseToken()->refresh()) {
    //         return response()->json(['error' => 'No se pudo refrescar el token'], 401);
    //     }
    // } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
    //     return response()->json(['error' => 'Token inválido'], 401);
    // } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
    //     return response()->json(['error' => 'Token expirado'], 401);
    // } catch (Tymon\JWTAuth\Exceptions\TokenBlacklistedException $e) {
    //     return response()->json(['error' => 'Token en lista negra'], 401);
    // }

    // return response()->json(compact('token'));

}

public function store(Request $request) {
    // logica para guardar capacitación

    $response = [];
    try {
        $data = $request->all();

        if ($data['id']) {
            $capacitacion = Capacitacione::where('id',$data['id'])->first();
            $capacitacion->cantidad_de_sesiones = $data['sesiones'];
            $capacitacion->synced = true;
            $capacitacion->save();
            
            $response["message"] = "Capacitación creado correctamente.";
        } else {

        }
        $datamodel = [];
            
        $datamodel = DB::select("call sp_get_capacitaciones_id ($capacitacion->id)");

        $data = [];
        foreach ($datamodel as $item) {
            $data[] = $item;
        }

        // $response["message"] = $response["message"] . " | ". $data[0]->synced;
        $response["data"] = $data;
            
        $response["success"] = true;
        // $response["data"] = $capacitacion;
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
