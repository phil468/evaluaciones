<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Tymon\JWTAuth\Facades\JWTAuth;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
// Route::post('register', 'UserController@register');
// Route::post('login', 'UserController@authenticate');

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::group(['middleware' => ['jwt.verify']], function() {
    // retornar usuario
    Route::get('user',[App\Http\Controllers\UserController::class, 'getAuthenticatedUser']);
    Route::get('/capacitaciones/{tipo_user}/{user_id}', [App\Http\Controllers\Api\ws\CapacitacionesController::class, 'getCapacitaciones'])->name('capacitaciones.getCapacitaciones'); 
    Route::get('/capacitaciones/registros', [App\Http\Controllers\Api\ws\CapacitacionesController::class, 'getRegistros'])->name('capacitaciones.getRegistros');
    Route::get('/capacitaciones/sesiones', [App\Http\Controllers\Api\ws\CapacitacionesController::class, 'getSesiones'])->name('capacitaciones.getSesiones');
    Route::get('/capacitaciones/asistencias', [App\Http\Controllers\Api\ws\CapacitacionesController::class, 'getAsistencias'])->name('capacitaciones.getAsistencias');
    Route::post('capacitaciones', [App\Http\Controllers\Api\ws\CapacitacionesController::class, 'store']);
    Route::post('sesiones', [App\Http\Controllers\Api\ws\SesionesController::class, 'store']);
    Route::post('capacitacionHasPersonal', [App\Http\Controllers\Api\ws\CapacitacionesHasPersonalController::class, 'store']);
    Route::post('asistencias', [App\Http\Controllers\Api\ws\AsistenciasController::class, 'store']);

    // Route::get('/capacitaciones/api/capacitaciones/updates', [App\Http\Controllers\Api\ws\CapacitacionesController::class, 'updates'])->name('capacitaciones.getAsistencias');
    Route::get('capacitaciones/updates', [App\Http\Controllers\Api\ws\CapacitacionesController::class, 'getUpdatesFromServer'])->name('capacitaciones.getUpdatesFromServer');
    Route::get('sesiones/updates', [App\Http\Controllers\Api\ws\SesionesController::class, 'getUpdatesFromServer'])->name('sesiones.getUpdatesFromServer');
    Route::get('capacitacionHasPersonal/updates', [App\Http\Controllers\Api\ws\CapacitacionesHasPersonalController::class, 'getUpdatesFromServer'])->name('capacitaciones_has_personal.getUpdatesFromServer');
    Route::get('asistencias/updates', [App\Http\Controllers\Api\ws\AsistenciasController::class, 'getUpdatesFromServer'])->name('asistencias.getUpdatesFromServer');
    // Route::post('refresh-token', [App\Http\Controllers\Api\ws\CapacitacionesController::class, 'refreshToken']);

    /*AÑADE AQUI LAS RUTAS QUE QUIERAS PROTEGER CON JWT*/
    // return $request->user();
});

// Middleware para verificar si el token ha expirado
Route::post('/refresh-token', function (Request $request) {
    // $token = JWTAuth::getToken();
    $user = User::find($request->id);

        $token = JWTAuth::fromUser($user);
        // $response['token'] = $token;
    return response()->json(['token' => $token]);
});


Route::get('login', [App\Http\Controllers\Api\ws\CapacitacionesController::class, 'login'])->name('login');
//FALTA MODIFICAR

// Route::post('/login', CapacitacionesController::class->login());
// 'Api/ws/CapacitacionesController@login');    
