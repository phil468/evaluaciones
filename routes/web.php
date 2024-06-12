<?php

use App\Http\Controllers\RolController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GeneraReporte;
use App\Models\Asignacione;
use App\Models\EvaluadorHasEvaluado;
use App\Models\User;
use Laravel\Socialite\Facades\Socialite;
use Livewire\Livewire;
use GuzzleHttp\Client;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     // return redirect('entregas');
//     view('dash.index');
// });

//Ruta HOME:
// Route::get('/connect', [App\Http\Controllers\HomeController::class,'redirectToAzure']);

Route::get('/auth/redirect', function () {
    return Socialite::driver('azure')
    // ->scopes([
    //     'api://e5a37484-1e31-499f-94af-fd254c7422d4/Contacts.Read',
    //     'api://e5a37484-1e31-499f-94af-fd254c7422d4/User.ReadBasic.All'
    //     ]) // Solicita el ámbito específico
    ->redirect('/home');
});
 
Route::get('/auth/callback', function () {
    $user = Socialite::driver('azure')->user();

    $localUser = User::where('email', $user->email)->first();

    //he agregado el atributo employeeNumber

    if (!$localUser) {
        // El usuario no existe, crea un nuevo usuario
        $localUser = User::create([
            'name' => $user->name,
            'email' => $user->email,
            // puedes agregar más campos aquí si los necesitas
        ]);
    }

    // Inicia sesión con el usuario
    Auth::login($localUser, true);

    // $response = Http::withToken($user->token)->get('https://graph.microsoft.com/v1.0/me/contacts');
    // $response = Http::withToken($user->token)->get('https://graph.microsoft.com/v1.0/users');
    // $response = Http::withToken($user->token)->get('https://graph.microsoft.com/v1.0/users');


    // dd($response->json());

    // Redirige al usuario a la página de inicio o a donde quieras
    return redirect('/home');
    //return redirect(route('dash.index'));

});

Route::get('/logout', function () {
    Auth::guard()->logout();
    return redirect('/login');
        
    // $azureLogoutUrl = Socialite::driver('azure')->getLogoutUrl(route('login')); // reemplaza con tu URL de redirección
    // return redirect()->away($azureLogoutUrl);
    // $request->session()->flush();
    // $azureLogoutUrl = Socialite::driver('azure')->getLogoutUrl(route('login'));
    // return redirect($azureLogoutUrl);
})->name('logout');

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('dash.index');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('dash.index');
Route::get('/personal/importar/{numero}', [App\Http\Controllers\PersonalController::class,'actualizarPersonalNisira'])->name('personal.actualizar');
Route::get('/personal/actualizarEstadoParaTodos', [App\Http\Controllers\PersonalController::class,'actualizarEstadoParaTodos'])->name('personal.actualizarEstadoParaTodos');
Route::get('/planilla/importar/{empresa}/{val}', [App\Http\Controllers\PlanillaController::class,'upsert'])->name('planilla.upsert');
Route::get('/tipodepersonal/importar/{empresa}/{val}', [App\Http\Controllers\TipoDePersonalController::class,'upsert'])->name('tipodepersonal.upsert');
Route::get('/tipodetrabajador/importar/{empresa}/{val}', [App\Http\Controllers\TipoDeTrabajadorController::class,'upsert'])->name('tipodetrabajador.upsert');
//Rutas de autenticación
Auth::routes();

Route::group(['middleware'  =>  ['auth']],function(){

    Route::get('/download/{id}', [App\Http\Controllers\EvidenciaController::class,'download'])->name('download');

    Route::resource('roles',RolController::class);

    //Prueba domPDF
    // Route::get('/users/{user_id}/dompdf',[UserController::class,'dompdf'])->name('users.dompdf');
    Route::view('/dashboard','livewire.dashboard.index')->name('dashboard')->middleware(['can:ver-dashboard']);
    Route::view('/personal','livewire.personals.index')->name('personal')->middleware(['can:ver-personal']);
    Route::view('/areas','livewire.areas.index')->name('areas')->middleware(['can:ver-area']);
    Route::view('/capacitaciones','livewire.capacitaciones.index')->name('capacitaciones')->middleware(['can:ver-capacitacion']);
    Route::get('/capacitaciones/{capacitacion_id}', function ($capacitacion_id) {
        return view('livewire.capacitacion-has-personals.index')->with('capacitacion_id', $capacitacion_id);
    })->name('capacitaciones.personal')->middleware(['can:ver-capacitacion']);
    Route::get('/capacitaciones/{capacitacion_id}/asistencia', function ($capacitacion_id) {
        return view('livewire.asistenciums.index')->with('capacitacion_id', $capacitacion_id);
    })->name('capacitaciones.asistencia')->middleware(['can:ver-capacitacion']);
    
    Route::view('/cargos','livewire.cargos.index')->name('cargos')->middleware(['can:ver-cargo']);
    Route::view('/tipos_de_capacitaciones','livewire.tipo-de-capacitaciones.index')->name('tipo-de-capacitacion')->middleware(['can:ver-tipo-de-capacitacion']);//tipo_de_activos
    Route::view('/empresas','livewire.empresas.index')->name('empresas')->middleware(['can:ver-empresa']);
    
    Route::view('/evaluaciones','livewire.evaluaciones.index')->name('evaluaciones')->middleware(['can:ver-empresa']);
    Route::view('/preguntas','livewire.preguntas.index')->name('preguntas')->middleware(['can:ver-empresa']);
    Route::view('/opciones','livewire.opciones.index')->name('opciones')->middleware(['can:ver-empresa']);
    Route::get('/evaluaciones-de-desempeno/{id}', function ($tipo_de_evaluacion_id) {
        return view('livewire.evaluador-has-evaluados.index')->with('tipo_de_evaluacion_id', $tipo_de_evaluacion_id);
    })->name('evaluacion_de_desempeno')
    ->middleware(['can:ver-evaluaciones-de-desempeno']);

    Route::view('/evaluadores','livewire.evaluadores.index')->name('evaluadores')->middleware(['can:ver-empresa']);
    Route::view('/secciones','livewire.secciones.index')->name('secciones')->middleware(['can:ver-empresa']);
    Route::view('/estados-de-plan-de-accion','livewire.estados-de-plan-de-accion.index')->name('estados-de-plan-de-accion')->middleware(['can:ver-estados-de-plan-de-accion']);
    Route::view('/planes-de-accion','livewire.planes-de-accion.index')->name('planes-de-accion')->middleware(['can:ver-planes-de-accion']);
    Route::view('/planes-de-accion','livewire.planes-de-accion.index')->name('planes-de-accion')->middleware(['can:ver-planes-de-accion']);
    
    Route::get('/evaluacion/{tipo_de_evaluacion_id}/{id}', function ($tipo_de_evaluacion_id,$evaluacion_id) {
        $this->evaluadorHasEvaluado = EvaluadorHasEvaluado::where('evaluador_has_evaluados.id',$evaluacion_id)
            ->where('evaluador_has_evaluados.evaluador_id', auth()->user()->personal_id)
            ->when($tipo_de_evaluacion_id == 1, function ($query) {
                return $query->where('evaluador_has_evaluados.realizado', null);
            })
            ->leftJoin('evaluaciones','evaluador_has_evaluados.evaluacion_id','=','evaluaciones.id')
            ->where('evaluaciones.tipo_de_evaluacion_id',$tipo_de_evaluacion_id)
            ->first();
        if ($this->evaluadorHasEvaluado) {            
            if ($tipo_de_evaluacion_id == 1) {
                return view('livewire.evaluacion.index')->with('evaluacion_id', $evaluacion_id);
            } elseif ($tipo_de_evaluacion_id == 2) {
                return view('livewire.objetivos.index')->with('evaluacion_id', $evaluacion_id);
            }
        } else {
            $this->evaluadorHasEvaluado = EvaluadorHasEvaluado::where('evaluador_has_evaluados.id',$evaluacion_id)
            ->leftJoin('evaluaciones','evaluador_has_evaluados.evaluacion_id','=','evaluaciones.id')
            ->where('evaluaciones.tipo_de_evaluacion_id',$tipo_de_evaluacion_id)
            ->first();            
            if ($this->evaluadorHasEvaluado) {
                if ($this->evaluadorHasEvaluado->realizado == 1 && $this->evaluadorHasEvaluado->tipo_de_evaluacion_id == 1) {
                    return redirect()->route('evaluacion_de_desempeno', $tipo_de_evaluacion_id)->with('error', 'Ya evaluó a este empleado');
                } else if ($this->evaluadorHasEvaluado->evaluador_id != auth()->user()->personal_id) {
                    return redirect()->route('evaluacion_de_desempeno', $tipo_de_evaluacion_id)->with('error', 'No tiene permisos para evaluar este personal');
                }
            } else {
            return redirect()->route('evaluacion_de_desempeno', $tipo_de_evaluacion_id)->with('error', 'No se encuentra registrada esta evaluación');
            }
        }
    })->name('evaluacion.show')->middleware(['can:ver-evaluaciones-de-desempeno']);

    Route::view('/respuestas','livewire.respuestas.index')->name('respuestas')->middleware(['can:ver-empresa']);

    Route::view('/objetivos-precargados','livewire.objetivos-precargados.index')->name('objetivos-precargados')->middleware(['can:ver-objetivos-precargados']);

    Route::view('/objetivos','livewire.objetivos-lista.index')->name('objetivos')->middleware(['can:ver-empresa']);
    Route::get('/planes-de-mejora/{ingreso}', function ($ingreso) {
        return view('livewire.planes-de-mejora.index')->with('ingreso', $ingreso);
    })->name('planes-de-mejora.ingreso')->middleware(['can:ver-evaluaciones-de-desempeno']);

    Route::get('/planes-de-mejora/{dashboard}/{empleado_id}', function ($dashboard, $empleado_id) {
        return view('livewire.planes-de-mejora.index')->with('dashboard', $dashboard)->with('empleado_id', $empleado_id);
    })->name('planes-de-mejora')->middleware(['can:ver-evaluaciones-de-desempeno']);

    Route::view('/seguimiento_evaluadores','livewire.seguimiento-evaluadores.index')->name('seguimiento_evaluadores')->middleware(['can:ver-empresa']);
    Route::view('/seguimiento_evaluados','livewire.seguimiento-evaluados.index')->name('seguimiento_evaluados')->middleware(['can:ver-empresa']);

    Route::view('/sedes','livewire.sedes.index')->name('sedes')->middleware(['can:ver-sede']);
    Route::view('/gerencias','livewire.gerencias.index')->name('gerencias')->middleware(['can:ver-gerencia']);
    Route::view('/temas','livewire.temas.index')->name('temas')->middleware(['can:ver-tema']); //marca
    Route::view('/modalidades','livewire.modalidades.index')->name('modalidades')->middleware(['can:ver-modalidad']);//modelo
    Route::view('/estados','livewire.statuses.index')->name('estados')->middleware(['can:ver-estado']);
    Route::view('/planillas','livewire.planillas.index')->name('planillas')->middleware(['can:ver-planilla']);
    // Route::view('/vigencia','livewire.vigenciums.index')->name('vigencia')->middleware(['can:ver-vigencia']);
    // Route::view('/motivo_baja','livewire.baja-motivos.index')->name('motivo-bajas')->middleware(['can:ver-motivo-baja']);
    // Route::view('/tipo_asignacion','livewire.asignacion-tipos.index')->name('tipo_asignaciones')->middleware(['can:ver-tipo-asignacion']);
    Route::view('/asistencias','livewire.asistenciums.reporte')->name('asistencias')->middleware(['can:ver-asistencia']);
    // Route::view('/devoluciones','livewire.devoluciones.index')->name('devoluciones')->middleware(['can:ver-devolucion']);
    Route::view('/emails/templates/send-invoice','emails.templates.send-invoice')->name('mail-template');

    Route::view('/reporte-de-activos','livewire.reporte-de-activos.index')->name('reporte-de-activos')->middleware(['can:ver-reporte-de-activos']);
    Route::view('/reporte-por-tipo-de-activos','livewire.reporte-por-tipo-de-activos.index')->name('reporte-por-tipo-de-activos')->middleware(['can:ver-reporte-por-tipo-de-activos']);
    Route::view('/reporte-por-estado-de-activos','livewire.reporte-por-estado-de-activos.index')->name('reporte-por-estado-de-activos')->middleware(['can:ver-reporte-por-estado-de-activos']);
    
    Route::get('/pdfprueba', function () {
        $asignacion_guardada = Asignacione::where('id',16)
        ->with(
            'activos_asignados',
            'activos_asignados.activo',
            'activos_asignados.performance',
            'activos_asignados.vigencia',
            'activos_asignados.accesorios',
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
    
        return view('livewire.asignaciones.pdf', ['asignacion_guardada' => $asignacion_guardada]);
    });

    Route::resource('users',UserController::class);
    Route::resource('roles',RolController::class);
    
});
// Auth::routes();
Route::get('/web/capacitaciones/{tipo_user}/{user_id}', [App\Http\Controllers\Api\ws\CapacitacionesController::class, 'getCapacitaciones'])->name('capacitaciones.getCapacitaciones'); //FALTA MODIFICAR

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/validar-codigo', [App\Http\Controllers\VerificationController::class, 'verifyCode'])->name('verification.verify');

Route::get('/enviar-correo', [App\Http\Controllers\VerificationController::class, 'enviarCorreo']);

