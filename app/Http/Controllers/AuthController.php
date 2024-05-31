<?php

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials)) {
            return response()->json(['error' => 'Credenciales inválidas'], 401);
        }

        $user = Auth::user();
        $token = JWTAuth::fromUser($user);

        return response()->json(compact('token'));
    }

    public function refreshToken()
    {
        return $this->respondWithToken(JWTAuth::refresh());
    }


}
