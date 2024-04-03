<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\ApiToken;

class ApiController extends Controller
{
    public function login(
        // Request $request
    )
    {
        $token = null;

        $statusCode = null;
        
        $response = Http::post("http://10.13.10.49:81/api/login?email=john.delacruz@vanguardfresh.pe&password=Ch4p1guard$");

        if ($response->successful()) {
            // dd($response->status());
            $statusCode = $response->status();
            $data = $response->json();
            // Guardar el token en la base de datos
            $token = ApiToken::create([
                'user_id' => $data['user']['user_id'],
                'access_token' => $data['access_token'],
                'token_type' => $data['token_type'],
                'expires_at' => now()->addSeconds($data['expires_in']),
                'refresh_token' => $data['refresh_token'],
            ]);
            // Resto de la lógica después de guardar el token
            // ...
        } else {
            $statusCode = $response->status();
        }
        
        return  ['statusCode' => $statusCode, 'token' =>$token];
        // else {
        //     // La solicitud no fue exitosa, manejar el error
        //     $statusCode = $response->status();
        //     session()->flash('message-danger', 'No se tiene acceso al servidor del API. Error: '.$statusCode);
        //     return redirect()->route('ruta.de.redireccion');
        // }
    }

    public function checkTokenExpiration(ApiToken $apiToken)
    {
        // dd($apiToken->expires_at->isPast());

        return $apiToken->expires_at->isPast();
    }

    public function refreshToken(ApiToken $apiToken)
    {
        // Hacer una solicitud a la API para obtener un nuevo token con el refresh token
        // Actualizar la información en la base de datos
        // Retornar el nuevo token
    }

    public function getLastToken()
    {
        $lastToken = ApiToken::orderBy('created_at', 'desc')
            ->first();

        return $lastToken;
    }

    public function obtenerToken()
    {
        $token = null;

        $token = ApiController::getLastToken();

        if (isset($token)) {
            if (ApiController::checkTokenExpiration($token)) {
                $token = ApiController::login();
            } else {
                $token = null;
            }
        } else {
            $token = ApiController::login();
        }

        return $token;
        // Hacer una solicitud a la API para obtener un nuevo token con el refresh token
        // Actualizar la información en la base de datos
        // Retornar el nuevo token
    }
}
